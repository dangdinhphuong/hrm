<?php

namespace App\Services\System;

use App\Repositories\System\ConfigRepository;
use Illuminate\Support\Facades\DB;
use App\Services\Attachment\FileService;
use Illuminate\Support\Facades\Cache;
use Log;
class ConfigService
{
    private $configRepository;

    public function __construct(ConfigRepository $configRepository,
                                FileService $fileService)
    {
        $this->configRepository = $configRepository;
        $this->fileService = $fileService;
    }

    public function list(array $params = [], $paginate = true, $columns = ['*'])
    {
        return $this->configRepository->list($params, $paginate, $columns);
    }

    public function create($data)
    {
        DB::beginTransaction();
        $status = false;

        try {
            foreach ($data as $key => $value) {
                Cache::forget($key); // Xóa cache trước khi cập nhật

                if (is_array($value)) {
                    foreach ($value as $index => $item) {
                        if ($this->fileService->isValidFile($item)) {
                            $oldPath = get_setting($key);
                            if ($oldPath) {
                                $this->fileService->delete($oldPath);
                            }
                            $value = $this->fileService->upload($item, 'setting');
                        }
                    }
                }

                // Cập nhật hoặc tạo mới setting
                $this->configRepository->updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }

            DB::commit();
            $status = true;
            $message = __('messages.hrm.setting.update_success');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('[' . __METHOD__ . '] :' . $e->getMessage() . ' -- line: ' . $e->getLine());
            $message = __('messages.hrm.setting.update_error');
        }

        return ['status' => $status, 'message' => $message];
    }

}
