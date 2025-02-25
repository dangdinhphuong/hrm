<?php

namespace App\Repositories\Work;

use App\Models\Work\TimeSheet;
use App\Repositories\BaseRepository;

class TimeSheetRepository extends BaseRepository
{
    public function model()
    {
        return TimeSheet::class;
    }
    public function getDetailByEmployeeId(int $employeeId, $workDate)
    {
       // return $this->where('employee_id' , $employeeId)->where('work_date' , $workDate)->get();
        return $this->findWhereFirst([
            'employee_id' => $employeeId,
            'work_date'   => $workDate,
        ]);
    }
}
