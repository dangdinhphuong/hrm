<?php

namespace App\Services\Work;

use App\Repositories\Work\MonthlyTimesheetSummaryRepository;
use App\Repositories\Work\TimeSheetRepository;
use Illuminate\Support\Facades\Log;
use App\Services\Work\RequestService;
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
        $employee = auth()->user()->employee;
        $params['employee_id'] = $employee->position_id == RequestService::POSITION_STAFF
            ? [$employee->id]
            : null;

        return $this->monthlyTimesheetSummaryRepository->list($params, $paginate, $columns);
    }

    public function findFirst(array $params = [])
    {
        return $this->monthlyTimesheetSummaryRepository->findFirst($params);
    }

    public function updateOrCreate(array $data = [])
    {
        // Lấy dữ liệu timesheet của nhân viên trong tháng
        $timeSheetUser = $this->timeSheetRepository->list($data, false);
        $totalLateEarlyMinutes = $timeSheetUser->sum('late_early_minutes') ?? 0;
        // Lấy tháng và năm trước đó
        [$lastMonth, $lastYear] = $this->getPreviousMonthAndYear($data['month'], $data['year']);

        try {
            $monthlyTimesheet = $this->findFirst($data);
            $lastMonthlyTimesheet = $this->findFirst(array_merge($data,[
                'year' => $lastYear,
                'month' => $lastMonth
            ]));
            // Lấy số ngày nghỉ từ bản ghi trước
            $remainingLeaveDays = $lastMonthlyTimesheet->remaining_leave_days ?? 0;

            if ($monthlyTimesheet) {
                $remainingLeaveDays = !empty($data['remaining_leave_days']) && (int)$data['remaining_leave_days'] > 0
                    ? $monthlyTimesheet->remaining_leave_days - 1
                    : $monthlyTimesheet->remaining_leave_days;

                $this->monthlyTimesheetSummaryRepository->update([
                    'leave_days' => $monthlyTimesheet->leave_days += $data['leave_days'] ?? 0,
                    'total_late_early_minutes' => $totalLateEarlyMinutes,
                    'remaining_leave_days' => $remainingLeaveDays
                ], $monthlyTimesheet->id);
            } else {

                $this->monthlyTimesheetSummaryRepository->create([
                    'year' => $data['year'],
                    'month' => $data['month'],
                    'employee_id' => $data['employee_id'],
                    'remaining_leave_days' => $remainingLeaveDays + 1,
                    'business_trip_days' => 0,
                    'holiday_days' => 0,
                    'overtime_hours' => 0,
                    'total_office_minutes' => 0,
                    'total_late_early_minutes' => $totalLateEarlyMinutes
                ]);
            }

            return true;
        }
        catch (Throwable $e) {
            Log::error('Error in MonthlyTimesheetSummaryService@updateOrCreate', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'data' => $data,
            ]);
            return false;
        }
    }

    /**
     * Lấy tháng và năm trước đó
     */
    private function getPreviousMonthAndYear($month, $year)
    {
        if ($month == 1) {
            return [12, $year - 1];
        }
        return [$month - 1, $year];
    }

}
