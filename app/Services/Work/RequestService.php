<?php

namespace App\Services\Work;

use App\Repositories\Employee\EmployeesRepository;
use App\Repositories\Work\RequestRepository;
use Illuminate\Support\Facades\DB;

class RequestService
{
    protected $requestRepository;

    const POSITION_MANAGER = 1; // Trưởng phòng
    const POSITION_STAFF = 2; // Nhân viên
    const POSITION_OTHER = 3; // Khác

    const PENDING_APPROVAL = 0; // Chờ duyệt
    const APPROVED = 1;         // Đã duyệt
    const REJECTED = 2;         // Từ chối

    const ANNUAL_LEAVE = 1;         // Nghỉ phép
    const UNPAID_LEAVE = 2;         // Nghỉ không lương
    const MISSING_CHECKIN = 3;      // Quên chấm công
    const BUSINESS_TRIP = 4;        // Đi công tác/Làm việc ngoài văn phòng Công ty
    const OTHER_REASON = 5;         // Lý do khác
    const STATUS_EMPLOYEE_ACTIVE = 1;
    const STATUS_EMPLOYEE_DEACTIVATE = 2;

    public function __construct(RequestRepository $requestRepository, EmployeesRepository $employeesRepository,)
    {
        $this->requestRepository = $requestRepository;
        $this->employeesRepository = $employeesRepository;
    }

    public function list(array $params = [], $paginate = true, $columns = ['*'])
    {
        $employee = auth()->user()->employee;

        if ($employee->position_id == self::POSITION_OTHER) {
            $employees = $this->employeesRepository->list([
                'departmentId' => $employee->departments[0]->id,
                'status' => self::STATUS_EMPLOYEE_ACTIVE,
            ], false, ["*"]);

            $employeeId = [];
            foreach ($employees as $item) {
                if ($item->departments[0]->id == $employee->departments[0]->id) {
                    if (!empty($params['employee_id'])) {
                        if (in_array($item->id, [$params['employee_id']])) {
                            $employeeId[] = $item->id;
                        }
                    } else {
                        $employeeId[] = $item->id;
                    }
                }
            }

            $params['employee_id'] = $employeeId;
        } else if ($employee->position_id == self::POSITION_STAFF) {
            $params['employee_id'] = [$employee->id];
        }
        return $this->requestRepository->list($params, $paginate, $columns);
    }

    public function createRequest($data)
    {
        $employee = auth()->user()->employee;
        $data['employee_id'] = $employee->id;
        $data['created_by'] = $employee->id;
        try {
            DB::beginTransaction();

            $requestData = $this->requestRepository->create($data);

            DB::commit();
            return ['status' => true, 'message' => '', 'data' => $requestData->toArray()];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateRequest($id, $data)
    {
        $employee = auth()->user()->employee;
        $useRequest = $this->requestRepository->find($id);

        if (!$useRequest) {
            return ['status' => false, 'message' => 'Request không tồn tại'];
        }

        // Lấy số lần quên chấm công đã được duyệt
        $useRequests = $this->requestRepository->list([
            'hr_status' => self::APPROVED,
            'manager_status' => self::APPROVED,
            'employee_id' => [$useRequest->employee_id]
        ], false, ["*"]);

        $maxMissedPunches = (int)get_setting('setting_missed_punches');

        if (count($useRequests) >= $maxMissedPunches) {
            return ['status' => false, 'message' => 'Số lần quên chấm công đã vượt giới hạn'];
        }

        // Kiểm tra quyền cập nhật
        if (!in_array($employee->position_id, [self::POSITION_MANAGER, self::POSITION_OTHER])) {
            return ['status' => false, 'message' => 'Bạn không có quyền cập nhật yêu cầu này'];
        }

        // Nếu là trưởng phòng, chỉ có thể duyệt đơn của nhân viên trong phòng mình
        if (
            $employee->position_id == self::POSITION_MANAGER &&
            $useRequest->employee->departments[0]->id != $employee->departments[0]->id
        ) {
            return ['status' => false, 'message' => 'Trưởng phòng chỉ có thể duyệt yêu cầu của nhân viên trong phòng ban của mình'];
        }

        // Kiểm tra trạng thái hợp lệ trước khi cập nhật
        if (
            ($employee->position_id == self::POSITION_MANAGER && $useRequest->hr_status == self::PENDING_APPROVAL) ||
            ($employee->position_id == self::POSITION_OTHER && $useRequest->hr_status != self::PENDING_APPROVAL)
        ) {
            return ['status' => false, 'message' => 'Không thể cập nhật yêu cầu trong trạng thái hiện tại'];
        }

        // Xác định trường cần cập nhật dựa trên vị trí nhân viên
        $fieldToUpdate = $employee->position_id == self::POSITION_OTHER ? 'hr_status' : 'manager_status';
        $updated = $useRequest->update([$fieldToUpdate => $data['approvalStatus']]);

        return ['status' => (bool)$updated, 'message' => $updated ? 'Cập nhật thành công' : 'Cập nhật thất bại'];
    }

}
