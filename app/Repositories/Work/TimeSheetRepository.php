<?php

namespace App\Repositories\Work;

use App\Models\Work\TimeSheet;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class TimeSheetRepository extends BaseRepository
{
    protected $filterFields = [
        'employee_id' => 'employee_id',
        'work_date' => 'work_date',
    ];

    public function model()
    {
        return TimeSheet::class;
    }

    public function getDetailByEmployeeId(int $employeeId, $workDate)
    {
        return $this->findWhereFirst([
            'employee_id' => $employeeId,
            'work_date'   => $workDate,
        ]);
    }
    public function list(array $params = [], $paginate = true, $columns = ["*"])
    {
        $conditions = [];
        if (!empty($params['year']) && !empty($params['month'])) {
            $conditions['work_date'] = ['work_date', 'BETWEEN', getMonthStartAndEnd($params['year'],$params['month'])];
        }
        $this->filter($params);
        return $paginate ?
            $this->findWherePaginate($conditions, $params['limit'] ?? null, columns:$columns) :
            $this->findWhere($conditions,orderBy : ['work_date' => 'desc'], columns:$columns);
    }
}
