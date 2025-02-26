<?php

namespace App\Services\Work;

use App\Repositories\Work\MonthlyTimesheetSummaryRepository;
use App\Repositories\Work\TimeSheetRepository;
use Illuminate\Support\Facades\Log;
use Throwable;

class MonthlyTimesheetSummaryService
{
    protected $timeSheetRepository;
    protected $monthlyTimesheetSummaryRepository;

    public function __construct(
        TimeSheetRepository $timeSheetRepository,
        MonthlyTimesheetSummaryRepository $monthlyTimesheetSummaryRepository
    ) {
        $this->timeSheetRepository = $timeSheetRepository;
        $this->monthlyTimesheetSummaryRepository = $monthlyTimesheetSummaryRepository;
    }
    public function list(array $params = [], $paginate = true, $columns = ['*'])
    {
        $params += ['year' => now()->year, 'month' => now()->month];
        return $this->monthlyTimesheetSummaryRepository->list($params, $paginate, $columns);
    }
    public function updateOrCreate(array $data = [])
    {
        $timeSheetUser = $this->timeSheetRepository->list($data,  false);
        $totalLateEarlyMinutes = $timeSheetUser->sum('late_early_minutes') ?? 0;

        try {
            $monthlyTimesheet = $this->monthlyTimesheetSummaryRepository->findFirst($data);

            if ($monthlyTimesheet) {
                $this->monthlyTimesheetSummaryRepository->update(array_merge($data, ['total_late_early_minutes' => $totalLateEarlyMinutes]), $monthlyTimesheet->id);
            } else {
                $this->monthlyTimesheetSummaryRepository->create(array_merge($data, [
                    'leave_days' => 0,
                    'business_trip_days' => 0,
                    'holiday_days' => 0,
                    'overtime_hours' => 0,
                    'total_office_minutes' => 0,
                    'total_late_early_minutes' => $totalLateEarlyMinutes
                ]));
            }

            return true;
        } catch (Throwable $e) {
            Log::error('Error in MonthlyTimesheetSummaryService@updateOrCreate', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'data' => $data,
            ]);
            return false; // hoặc có thể trả về response cụ thể
        }
    }
}
