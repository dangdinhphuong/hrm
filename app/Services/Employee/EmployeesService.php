<?php

namespace App\Services\Employee;

use App\Repositories\Department\EmployeeDepartmentRepository;
use App\Repositories\Employee\EmployeesRepository;
use App\Services\System\UserService;
use App\Services\Attachment\AttachmentService;
use App\Services\Attachment\EavAttachmentService;
use App\Services\Attachment\FileService;
use App\Services\Hrm\Exception;
use Illuminate\Support\Facades\DB;
use Log;
use function __;

class EmployeesService
{
    protected $employeesRepository;
    protected $eavAttachmentService;
    protected $employeeDepartmentRepository;
    protected $fileService;
    private $entityType = 'employee_avatar';

    public function __construct(
        EmployeesRepository          $employeesRepository,
        AttachmentService            $attachmentService,
        EavAttachmentService         $eavAttachmentService,
        EmployeeDepartmentRepository $employeeDepartmentRepository,
        FileService                  $fileService,
        UserService                  $userService

    )
    {
        $this->employeesRepository = $employeesRepository;
        $this->employeeDepartmentRepository = $employeeDepartmentRepository;
        $this->attachmentService = $attachmentService;
        $this->eavAttachmentService = $eavAttachmentService;
        $this->fileService = $fileService;
        $this->userService = $userService;
    }

    public function list(array $params = [], $paginate = true, $columns = ['*'])
    {
        return $this->employeesRepository->list($params, $paginate, $columns);
    }
    public function create($data)
    {
        DB::beginTransaction();
        $status = false;
        try {

            $user = $this->userService->create([
                'name' => $data['last_name'],
                'username' => $data['code'],
                'email' => $data['personal_email'],
                'password' => $data['code'] . '@' . $data['phone'],
                'status' => $data['status'],
                'role' => [$data['role_id']],
                'department' => $data['department_id']
            ]);

            $data['user_id'] = $user->id;

            $employeeData = $this->save($data);

            $this->employeeDepartmentRepository->create(
                [
                    'employees_id' => $employeeData->id,
                    'departments_id' => $data["department_id"],
                    'type' => 1,
                ]
            );
            // Chỉ commit sau khi dd() hoặc kiểm tra xong
            $status = true;
            DB::commit();
            $message = __('messages.hrm.employees.create_success');
        } catch (Exception $e) {
            DB::rollBack(); // Rollback nếu có lỗi xảy ra
            Log::error('[' . __METHOD__ . '] :' . $e->getMessage() . '-- line: ' . $e->getLine());
            $message = __('messages.hrm.employees.create_error');
        }
        return ['status' => $status, 'message' => $message];
    }

    public function update($id, $data)
    {
        DB::beginTransaction();
        $status = false;
        try {


            $employeeData = $this->save($data, 'update', $id);

                $user = $this->userService->update($employeeData->user_id, [
                    'role' => [$data['role_id']],
                    'department' => $data['department_id']
                ]);
            $this->employeeDepartmentRepository->getByEmployeesId($employeeData->id)->update(
                [
                    'employees_id' => $employeeData->id,
                    'departments_id' => $data["department_id"],
                    'type' => 1,
                ]
            );;
            $status = true;
            DB::commit();
            $message = __('messages.hrm.employees.create_success');
        } catch (Exception $e) {
            DB::rollBack(); // Rollback nếu có lỗi xảy ra
            Log::error('[' . __METHOD__ . '] :' . $e->getMessage() . '-- line: ' . $e->getLine());
            $message = __('messages.hrm.employees.create_error');
        }
        return ['status' => $status, 'message' => $message];
    }

    public function save($data, $action = 'create', $id = 0)
    {
        unset($data['department_id']);
        if ($action == 'create') {
            $employeeData = $this->employeesRepository->create($data);
        }
        if ($action == 'update') {
            $employeeData = $this->employeesRepository->update($data, $id);
        }

        return $employeeData;
    }

    public function getDetailById($id)
    {
        $result = $this->employeesRepository->getDetailById($id);

        if (!empty($result)) {
            $result->currentNationalityName = $result->currentNationality->name ?? '';
            $result->currentCountryName = $result->currentCountry->name ?? '';
            $result->currentProvinceName = $result->currentProvince->name ?? '';
            $result->currentDistrictName = $result->currentDistrict->name ?? '';
            $result->currentUserName = $result->user->username ?? '';
            $result->currentBankName = $result->bank->name ?? '';
            $result->currentPositionName = $result->position->name ?? '';
            $result->currentDepartmentName = $result->departments[0]->name ?? '';
            $result->department_id = $result->departments[0]->id ?? '';
            $result->jobTitle = $result->jobTitle->name ?? '';
            $result->role_id = $result->user->roles[0]->id ?? '';
        }

        return $result ?? [];
    }

    public function getDetailByUsername($username, $columns = ["*"])
    {
        return $this->employeesRepository->getDetailByUsername($username, $columns);
    }

    public function uploadAvatar($file, $employeeId)
    {
        $status = false;
        $message = '';
        $data = [];

        try {
            // Upload file mới
            $pathImage = $this->fileService->upload($file, 'avatars');

            // Lấy EAV Attachment hiện tại của nhân viên và cập nhật hoặc tạo mới
            $eavAttachment = $this->eavAttachmentService->getSingle([
                'entity_type' => $this->entityType,
                'entity_id' => $employeeId
            ]);

            if ($eavAttachment && $eavAttachment->attachment) {
                // Xóa tệp cũ nếu có và cập nhật tệp mới
                $this->fileService->delete($eavAttachment->attachment->file_path);
                $eavAttachment->attachment->update(['file_path' => $pathImage]);
            } else {
                // Tạo mới nếu chưa có
                $this->attachmentService->create([
                    'descriptions' => 'Avatar',
                    'file' => $pathImage,
                    'entityId' => $employeeId,
                    'entityType' => $this->entityType
                ]);
            }

            // Trả về kết quả thành công
            $data = ['file' => $pathImage];
            $status = true;
            $message = __('messages.hrm.employees.avatar.create_success');

        } catch (Exception $e) {
            // Ghi log lỗi
            Log::error('[' . __METHOD__ . '] : ' . $e->getMessage() . ' -- line: ' . $e->getLine());
            $message = __('messages.hrm.employees.avatar.create_error');
        }

        return ['status' => $status, 'message' => $message, 'data' => $data];
    }

    public function getAvatar($employeeId)
    {
        $message = '';
        $data = [];

        $eavAttachment = $this->eavAttachmentService->getSingle([
            'entity_type' => $this->entityType,
            'entity_id' => $employeeId
        ]);

        if ($eavAttachment && $eavAttachment->attachment) {
            $data = ['file' => $eavAttachment->attachment->file_path];

        }
        return ['status' => true, 'message' => $message, 'data' => $data];
    }

    public function getTimesheets(array $params = [])
    {
        return $this->employeesRepository->getTimesheets($params);
    }
}
