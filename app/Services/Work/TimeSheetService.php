<?php

namespace App\Services\Work;

use App\Repositories\Work\TimeSheetRepository;
use App\Services\Work\MonthlyTimesheetSummaryService;
use App\Events\Work\TimesheetUpdated;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

class TimeSheetService
{
    protected $bankRepository;

    public function __construct(
        TimeSheetRepository            $timeSheetRepository,
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

        $employeeWork = $this->timeSheetRepository->getDetailByEmployeeId($employeeId, $workDate);

        // Nếu đã có dữ liệu chấm công trong ngày → Cập nhật check-out
        if ($employeeWork) {
            $lateEarlyMinutes = $this->calculateLateEarlyMinutes($employeeWork->check_in, $workTime);

            $timeSheet = $this->timeSheetRepository->update([
                'check_out' => $workTime,
                'late_early_minutes' => $lateEarlyMinutes,
            ], $employeeWork->id);
        } // Nếu chưa có dữ liệu → Tạo mới
        else {
            $lateEarlyMinutes = $this->checkLateMinutes($workTime);

            $timeSheet = $this->timeSheetRepository->create([
                'employee_id' => $employeeId,
                'work_date' => $workDate,
                'check_in' => $workTime,
                'late_early_minutes' => $lateEarlyMinutes,
            ]);
        }

        // Phát event cập nhật tổng hợp chấm công
        event(new TimesheetUpdated([
            'year' => now()->year,
            'month' => now()->month,
            'employee_id' => $employeeId
        ]));

        return $timeSheet;
    }

    /**
     * Tính lại tổng số phút đi muộn và về sớm theo checkout mới nhất
     */
    private function calculateLateEarlyMinutes(string $checkIn, string $checkOut): int
    {
        $officialCheckIn = '08:00:00';
        $officialCheckOut = '17:30:00'; // Nếu checkout sau giờ này, về sớm = 0

        $lateMinutes = max(0, (strtotime($checkIn) - strtotime($officialCheckIn)) / 60);
        $earlyMinutes = max(0, (strtotime($officialCheckOut) - strtotime($checkOut)) / 60);

        return (int)($lateMinutes + $earlyMinutes);
    }

    private function checkLateMinutes(string $checkInTime, string $workStartTime = '08:00'): int
    {
        $checkInTime = substr($checkInTime, 0, 5); // Chỉ lấy 'HH:mm'
        $checkIn = Carbon::createFromFormat('H:i', $checkInTime);
        $workTime = Carbon::createFromFormat('H:i', $workStartTime);

        return $checkIn->greaterThan($workTime) ? $workTime->diffInMinutes($checkIn) : 0;
    }

}
