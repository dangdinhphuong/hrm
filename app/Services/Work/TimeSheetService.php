<?php

namespace App\Services\Work;

use App\Repositories\Work\TimeSheetRepository;
use App\Services\Work\MonthlyTimesheetSummaryService;
use Carbon\Carbon;

class TimeSheetService
{
    protected $bankRepository;

    public function __construct(
        TimeSheetRepository $timeSheetRepository,
        MonthlyTimesheetSummaryService $monthlyTimesheetSummaryService
    )
    {
        $this->timeSheetRepository = $timeSheetRepository;
        $this->monthlyTimesheetSummaryService = $monthlyTimesheetSummaryService;
    }

    public function store(array $request = [])
    {
        $workDate = Carbon::today()->toDateString();
        $workTime = $request['time'] ?? Carbon::now()->toTimeString();
        $employeeId = $request['employeeId'];

        // Kiểm tra nhân viên đã có chấm công trong ngày chưa
        $employeeWork = $this->timeSheetRepository->getDetailByEmployeeId($employeeId, $workDate);

        $lateEarlyMinutes = $employeeWork
            ? $this->checkEarlyMinutes($workTime)
            : $this->checkLateMinutes($workTime);

        $request['totalLateEarlyMinutes'] = $lateEarlyMinutes;
        $employeeWork
            ? $this->monthlyTimesheetSummaryService->update($request)
            : $this->monthlyTimesheetSummaryService->store($request);
// todo fix checkout muộn nhiều lần bị công số late_early_minutes
        return $employeeWork
            ? $this->timeSheetRepository->update([
                'check_out' => $workTime,
                'late_early_minutes' => (int)$employeeWork->late_early_minutes + (int)$lateEarlyMinutes,
            ], $employeeWork->id)
            : $this->timeSheetRepository->create([
                'employee_id' => $employeeId,
                'work_date' => $workDate,
                'check_in' => $workTime,
                'late_early_minutes' => $lateEarlyMinutes,
            ]);
    }



    public function checkLateMinutes(string $checkInTime, string $workStartTime = '08:00'): int
    {
        $checkInTime = substr($checkInTime, 0, 5); // Chỉ lấy 'HH:mm'
        $checkIn = Carbon::createFromFormat('H:i', $checkInTime);
        $workTime = Carbon::createFromFormat('H:i', $workStartTime);

        return $checkIn->greaterThan($workTime) ? $workTime->diffInMinutes($checkIn) : 0;
    }

    public function checkEarlyMinutes(string $checkOutTime, string $workEndTime = '17:30'): int
    {
        $checkOutTime = substr($checkOutTime, 0, 5); // Chỉ lấy 'HH:mm'
        $checkOut = Carbon::createFromFormat('H:i', $checkOutTime);
        $workTime = Carbon::createFromFormat('H:i', $workEndTime);

        return $checkOut->lessThan($workTime) ? $workTime->diffInMinutes($checkOut) : 0;
    }

}
