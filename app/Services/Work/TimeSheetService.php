<?php

namespace App\Services\Work;

use App\Repositories\Work\TimeSheetRepository;
use Carbon\Carbon;

class TimeSheetService
{
    protected $bankRepository;

    public function __construct(TimeSheetRepository $timeSheetRepository)
    {
        $this->timeSheetRepository = $timeSheetRepository;
    }

    public function store(array $params = [])
    {
        $workDate = Carbon::today()->toDateString();
        $workTime = Carbon::now()->toTimeString();
        $employeeWork = $this->timeSheetRepository->getDetailByEmployeeId($params['employeeId'], $workDate);
        if($employeeWork){

        }

        $this->timeSheetRepository->create([
            'employee_id'=>$params['employeeId'],
            'work_date' => $workDate,
            'check_in' => $workTime,
            'late_early_minutes'=> $this->checkLateMinutes($workTime)
        ]);

    }
    public function checkLateMinutes(string $checkInTime, string $workStartTime = '08:00'): int
    {
        $checkInTime = substr($checkInTime, 0, 5); // Chỉ lấy 'HH:mm'
        $checkIn  = Carbon::createFromFormat('H:i', $checkInTime);
        $workTime = Carbon::createFromFormat('H:i', $workStartTime);

        return $checkIn->greaterThan($workTime) ? $workTime->diffInMinutes($checkIn) : 0;
    }
}
