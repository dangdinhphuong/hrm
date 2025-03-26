<?php

namespace App\Services\Work;

use App\Events\Work\TimesheetUpdated;
use App\Repositories\Work\TimeSheetRepository;
use App\Services\Work\MonthlyTimesheetSummaryService;
use App\Repositories\Employee\EmployeesRepository;
use App\Repositories\Work\RequestRepository;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

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
    const OT = 5;         // Lý do khác
    const STATUS_EMPLOYEE_ACTIVE = 1;
    const STATUS_EMPLOYEE_DEACTIVATE = 2;

    public function __construct(
        RequestRepository              $requestRepository,
        EmployeesRepository            $employeesRepository,
        TimeSheetRepository            $timeSheetRepository,
        MonthlyTimesheetSummaryService $monthlyTimesheetSummaryService
    )
    {
        $this->requestRepository = $requestRepository;
        $this->employeesRepository = $employeesRepository;
        $this->timeSheetRepository = $timeSheetRepository;
        $this->monthlyTimesheetSummaryService = $monthlyTimesheetSummaryService;
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
                if (!empty($params['employee_id'])) {
                    if (in_array($item->id, [$params['employee_id']])) {
                        $employeeId[] = $item->id;
                    }
                } else {
                    $employeeId[] = $item->id;
                }
            }

            $params['employee_id'] = $employeeId;
        } else if ($employee->position_id == self::POSITION_MANAGER) {
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

        if (!$this->validateMissedPunchesLimit($useRequest, $data)) {
            return ['status' => false, 'message' => 'Số lần quên chấm công đã vượt giới hạn'];
        }

        if (!$this->validateUpdatePermission($employee, $useRequest)) {
            return ['status' => false, 'message' => 'Bạn không có quyền cập nhật yêu cầu này'];
        }

        if (!$this->validateLeaveApprovalLimit($useRequest, $data)) {
            return ['status' => false, 'message' => 'Đã quá lượt quên chấm công'];
        }

        $fieldToUpdate = $employee->position_id == self::POSITION_OTHER ? 'hr_status' : 'manager_status';

        if ($employee->position_id == self::POSITION_MANAGER) {

            $monthlyTimesheet = $this->monthlyTimesheetSummaryService->findFirst([
                'year' => now()->year,
                'month' => now()->month,
                'employee_id' => $useRequest->employee_id,
            ]);

            // Cập nhật thông tin nghỉ phép dựa trên request và bảng chấm công tháng
            $this->updateLeaveRequest($useRequest, $monthlyTimesheet);

            // Cập nhật dữ liệu chấm công khi quên check-in/check-out
            $this->updateMissingCheckinRequest($useRequest, $data, $monthlyTimesheet);

            // Xử lý dữ liệu công tác nếu loại yêu cầu là Business Trip và đã được phê duyệt
            $this->handleBusinessTrip($useRequest, $data, $employee);

            // Xử lý cập nhật thời gian OT nếu loại yêu cầu là OT và đã được phê duyệt
            $this->handleOvertime($useRequest, $data, $monthlyTimesheet);

        }

        return $this->updateRequestStatus($useRequest, $fieldToUpdate, $data);
    }

    /**
     * Xử lý luồng đi công tác
     */
    private function handleBusinessTrip($useRequest, $data, $employee)
    {
        if ($useRequest->leave_type == self::BUSINESS_TRIP && $data['approvalStatus'] == self::APPROVED) {
            $otDates = $this->getFormattedDates($useRequest->content);
            $dataBusiessTrip = [];
            foreach ($otDates as $otDate) {
                $dataBusiessTrip[] = [
                    'employee_id' => $employee->id,
                    'work_date' => $otDate,
                    "check_in" => get_setting('setting_checkin') . ":00",
                    "check_out" => get_setting('setting_checkout') . ":00",
                    'late_early_minutes' => 0,
                ];
            }
            $this->timeSheetRepository->insert($dataBusiessTrip);
        }
    }

    /**
     * Xử lý thời gian OT
     */
    private function handleOvertime($useRequest, $data, $monthlyTimesheet)
    {
        if ($useRequest->leave_type == self::OT && $data['approvalStatus'] == self::APPROVED) {
            $otMinute = $this->extractNumber($useRequest->content) ?? 0;
            $monthlyTimesheet->update(['overtime_hours' => $monthlyTimesheet->overtime_hours + $otMinute]);
        }
    }

    /**
     * Kiểm tra số lần quên chấm công đã duyệt
     */
    private function validateMissedPunchesLimit($useRequest, $data)
    {
        if ($useRequest->leave_type == self::MISSING_CHECKIN && $data['approvalStatus'] == self::APPROVED) {
            $useRequests = $this->requestRepository->list([
                'hr_status' => self::APPROVED,
                'manager_status' => self::APPROVED,
                'employee_id' => [$useRequest->employee_id]
            ], false, ["*"]);

            return count($useRequests) < (int)get_setting('setting_missed_punches');
        }
        return true;
    }

    /**
     * Kiểm tra quyền cập nhật
     */
    private function validateUpdatePermission($employee, $useRequest)
    {
        if (!in_array($employee->position_id, [self::POSITION_MANAGER, self::POSITION_OTHER])) {
            return false;
        }

        if (
            $employee->position_id == self::POSITION_MANAGER &&
            $useRequest->employee->departments[0]->id != $employee->departments[0]->id
        ) {
            return false;
        }

        if (
            ($employee->position_id == self::POSITION_MANAGER && $useRequest->hr_status == self::PENDING_APPROVAL) ||
            ($employee->position_id == self::POSITION_OTHER && $useRequest->hr_status != self::PENDING_APPROVAL)
        ) {
            return false;
        }

        return true;
    }

    /**
     * Kiểm tra số lần nghỉ phép hoặc quên chấm công
     */
    private function validateLeaveApprovalLimit($useRequest, $data)
    {
        if ($useRequest->leave_type == self::MISSING_CHECKIN && $data['approvalStatus'] == self::APPROVED) {
            $useRequestMissingCheckin = $this->requestRepository->list([
                'manager_status' => 1,
                'leave_type' => self::MISSING_CHECKIN,
                'year-month' => now()->year . '-' . now()->month,
                'employee_id' => [$useRequest->employee_id],
            ], false, columns: ['id']);

            return count($useRequestMissingCheckin) <= get_setting('setting_leave_day');
        }
        return true;
    }

    /**
     * Xử lý logic cập nhật số ngày nghỉ phép
     */
    private function updateLeaveRequest($useRequest, $monthlyTimesheet)
    {
        if ($useRequest->leave_type == self::ANNUAL_LEAVE && !empty($monthlyTimesheet) && $monthlyTimesheet->remaining_leave_days > 0) {
            event(new TimesheetUpdated([
                'leave_days' => 1,
                'remaining_leave_days' => 1,
                'year' => now()->year,
                'month' => now()->month,
                'employee_id' => $useRequest->employee_id
            ]));
        }
    }


    /**
     * Xử lý logic cập nhật dữ liệu chấm công khi quên checkin
     */
    private function updateMissingCheckinRequest($useRequest, $data, $monthlyTimesheet)
    {
        if ($useRequest->leave_type == self::MISSING_CHECKIN && $data['approvalStatus'] == self::APPROVED) {
            $employeeWork = $this->timeSheetRepository->getDetailByEmployeeId($useRequest->employee_id, $useRequest->date);

            if ($employeeWork && $monthlyTimesheet) {
                $monthlyTimesheet->update([
                    "late_early_minutes" => (int)$monthlyTimesheet->total_late_early_minutes - (int)$employeeWork->late_early_minutes
                ]);

                $employeeWork->update([
                    "check_in" => get_setting('setting_checkin') . ":00",
                    "check_out" => get_setting('setting_checkout') . ":00",
                    "late_early_minutes" => 0
                ]);
            }
        }
    }


    /**
     * Cập nhật trạng thái yêu cầu
     */
    private function updateRequestStatus($useRequest, $fieldToUpdate, $data)
    {
        $updated = $useRequest->update([$fieldToUpdate => $data['approvalStatus']]);
        return ['status' => (bool)$updated, 'message' => $updated ? 'Cập nhật thành công' : 'Cập nhật thất bại'];
    }


    /**
     * Lấy danh sách các ngày trong khoảng thời gian được định dạng từ d/m/Y thành Y-m-d
     *
     * @param string $content Chuỗi ngày theo định dạng "dd/mm/yyyy - dd/mm/yyyy"
     * @return array Mảng chứa danh sách ngày đã format theo Y-m-d
     */

    private function getFormattedDates(string $content): array
    {
        [$startDate, $endDate] = explode(' - ', $content);

        // Chuyển đổi ngày sang Carbon
        $start = Carbon::createFromFormat('d/m/Y', $startDate);
        $end = Carbon::createFromFormat('d/m/Y', $endDate);

        // Nếu start lớn hơn end, hoán đổi lại
        if ($start->gt($end)) {
            [$start, $end] = [$end, $start];
        }

        // Tạo khoảng thời gian và trả về danh sách ngày đã format
        return array_map(fn($date) => $date->format('Y-m-d'), iterator_to_array(CarbonPeriod::create($start, $end)));
    }

    /**
     * Trích xuất số từ chuỗi
     *
     * @param string $content Chuỗi chứa số cần trích xuất (ví dụ: "40 phút")
     * @return int Số được trích xuất (ví dụ: 40)
     */
    private function extractNumber(string $content): int
    {
        preg_match('/\d+/', $content, $matches);
        return $matches[0] ?? 0;
    }
}
