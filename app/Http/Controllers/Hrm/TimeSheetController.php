<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hrm\TimeSheetRequest;
use App\Services\Work\TimeSheetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class TimeSheetController extends Controller
{
    protected $timeSheetService;

    public function __construct(TimeSheetService $timeSheetService)
    {
        $this->timeSheetService = $timeSheetService;
    }

    public function store(TimeSheetRequest $request): JsonResponse
    {
        try {
            $data = $this->timeSheetService->store($request->all());
            return responder()->success($data);
        } catch (Exception $e) {
            // Ghi log lỗi vào file riêng
            Log::channel('timesheet')->error('TimeSheet Store Error', [
                'message' => $e->getMessage(),
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return responder()->fail($e->getMessage(), 500);
        }
    }
}
