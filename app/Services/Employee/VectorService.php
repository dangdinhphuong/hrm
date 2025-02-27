<?php

namespace App\Services\Employee;

use App\Repositories\Employee\VectorRepository;
use App\Repositories\Employee\EmployeesRepository;
use App\Services\Attachment\FileService;
use App\Services\Hrm\Exception;
use Illuminate\Support\Facades\DB;
use Log;
use function __;

class VectorService
{
    protected $vectorRepository;
    protected $employeesRepository;

    public function __construct(
        VectorRepository    $vectorRepository,
        EmployeesRepository $employeesRepository,
        FileService         $fileService
    )
    {
        $this->vectorRepository = $vectorRepository;
        $this->employeesRepository = $employeesRepository;
        $this->fileService = $fileService;
    }

    public function getDetailByUsername($username, $columns = ['*'])
    {
        $userVector = $this->vectorRepository->getDetailByUsername($username, $columns);

        $userVector->avartar = $filePath = $userVector?->employee?->avatarAttachment?->attachment?->file_path;
        $userVector->avartarBase64 = $filePath
            ? $this->fileService->convertImageToBase64(str_replace(url('/storage') . '/', '', $filePath))
            : null;
        $userVector = collect($userVector)->except(['employee.avatar_attachment'])->toArray();
        return $userVector;
    }

    public function createOrUpdateVector($data, $employeeId)
    {
        DB::beginTransaction();
        $status = false;
        try {

            $employee = $this->employeesRepository->getDetailById($employeeId);

            $data = ['username' => $employee->code, 'employee_id' => (int)$employeeId, 'face_vector' => $data['vector']];

            $vector = $this->vectorRepository->createOrUpdateVector($data, $employeeId);
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
}
