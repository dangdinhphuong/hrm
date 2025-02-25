<?php

namespace App\Services\Work;

use App\Repositories\Work\MonthlyTimesheetSummaryRepository;

use Carbon\Carbon;

class MonthlyTimesheetSummaryService
{
    protected $monthlyTimesheetSummaryRepository;

    public function __construct(MonthlyTimesheetSummaryRepository $monthlyTimesheetSummaryRepository)
    {
        $this->monthlyTimesheetSummaryRepository = $monthlyTimesheetSummaryRepository;
    }

    public function store(array $request = [])
    {
        $year = now()->year;
        $month = now()->month;

        $this->monthlyTimesheetSummaryRepository->create(
            [
                'employee_id' => $request['employeeId'],
                'year' => $year,
                'month' => $month,
                'leave_days' => 0,
                'business_trip_days' => 0,
                'holiday_days' => 0,
                'overtime_hours' => 0,
                'total_office_minutes' => 0,
                'total_late_early_minutes' => $request['totalLateEarlyMinutes'],
            ]
        );

    }

    public function update(array $request = [])
    {
        $year = now()->year;
        $month = now()->month;
        $monthlyTimesheet = $this->monthlyTimesheetSummaryRepository->findFirst([
            'employee_id' => $request['employeeId'],
            'year' => $year,
            'month' => $month
        ]);
        $this->monthlyTimesheetSummaryRepository->update(
            [
                'total_late_early_minutes' => (int)$monthlyTimesheet->total_late_early_minutes + (int)$request['totalLateEarlyMinutes'],
            ]
        , $monthlyTimesheet->id);


    }

}
