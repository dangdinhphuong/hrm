<?php

namespace App\Services\Employee;

use App\Repositories\Employee\ContractRepository;
use App\Services\Attachment\AttachmentService;
use App\Services\Attachment\FileService;
use App\Services\Hrm\Exception;
use Illuminate\Support\Facades\DB;
use Log;
use function __;

class ContractService
{
    private $entityType = 'employee_contract';
    protected $contractRepository;
    protected $attachmentService;

    public function __construct(
        ContractRepository $contractRepository,
        AttachmentService  $attachmentService,
        FileService        $fileService
    )
    {
        $this->contractRepository = $contractRepository;
        $this->attachmentService = $attachmentService;
        $this->fileService = $fileService;
    }

    public function getByEmployeesId($employeesId)
    {
        return $this->contractRepository->getByEmployeesId($employeesId);
    }

    public function getDetailById($id)
    {
        $contract = $this->contractRepository->getDetailById($id);
        if ($contract && $contract->eavAttachments->isNotEmpty()) {
            $contract->file = $contract->eavAttachments->map(function ($eavAttachment) {
                if (!empty($eavAttachment->attachment)) {
                    return [
                        'id' => $eavAttachment->attachment->id,
                        'file_path' => $eavAttachment->attachment->file_path,
                        'descriptions' => $eavAttachment->attachment->descriptions,
                    ];
                }
                return null;
            })->filter()->values()->all(); // Loại bỏ phần tử null và lấy lại chỉ số mảng
        } else {
            $contract->file = [];
        }

        // Xóa eavAttachments sau khi xử lý
        unset($contract->eavAttachments);

        return $contract;
    }

    public function create($data)
    {
        DB::beginTransaction();
        $status = false;
        try {
            $contract = $this->save($data);
            $this->createFiles($data['file'], $contract);
            $status = true;
            DB::commit();
            $message = __('messages.hrm.contract.create_success');
        } catch (Exception $e) {
            DB::rollBack(); // Rollback nếu có lỗi xảy ra
            Log::error('[' . __METHOD__ . '] :' . $e->getMessage() . '-- line: ' . $e->getLine());
            $message = __('messages.hrm.contract.create_error');
        }
        return ['status' => $status, 'message' => $message];
    }

    public function update($data, $id)
    {
        DB::beginTransaction();
        $status = false;
        try {
            $contract = $this->save($data, 'update', $id);
            $this->updateFiles($data['file'] ?? [], $contract);
            $status = true;
            DB::commit();
            $message = __('messages.hrm.contract.create_success');
        } catch (Exception $e) {
            DB::rollBack(); // Rollback nếu có lỗi xảy ra
            Log::error('[' . __METHOD__ . '] :' . $e->getMessage() . '-- line: ' . $e->getLine());
            $message = __('messages.hrm.contract.create_error');
        }
        return ['status' => $status, 'message' => $message];
    }

    public function save($data, $action = 'create', $id = 0)
    {
        unset($data['file']);
        if ($action == 'create') {
            $contractData = $this->contractRepository->create($data);
        }
        if ($action == 'update') {
            $contractData = $this->contractRepository->update($data, $id);
        }

        return $contractData;
    }

    public function createFiles($files, $contract)
    {
        if(!empty($files) && is_array($files)){
            foreach ($files as $file) {
                $pathImage = $this->fileService->upload($file, 'contract');
                $this->attachmentService->create(
                    [
                        'descriptions' => $file->getClientOriginalName(),
                        'file' => $pathImage,
                        'entityId' => $contract->id,
                        'entityType' => $this->entityType
                    ]);
            }
        }
    }

    public function updateFiles($files, $contract)
    {
        $fileImageId = array_column($files, 'uid');
        $newFiles = array_filter($files, function($file) {
            return !is_array($file);
        });
        $newFiles = array_values($newFiles);
        foreach ($contract->eavAttachments as $eavAttachment) {
            if(empty($fileImageId)){
                $this->deleteFile($eavAttachment->attachment);
            }
            else if (!in_array($eavAttachment->attachment->id, $fileImageId)) {
                $this->deleteFile($eavAttachment->attachment);
            }
        }
        $this->createFiles($newFiles, $contract);
    }

    public function deleteFile($attachment)
    {
        $this->fileService->delete($attachment->file_path);
        return $attachment->delete();
    }

}

