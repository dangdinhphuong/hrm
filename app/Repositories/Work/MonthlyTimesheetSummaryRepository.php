<?php

namespace App\Repositories\Work;

use App\Models\Work\MonthlyTimesheetSummary;
use App\Repositories\BaseRepository;

class MonthlyTimesheetSummaryRepository extends BaseRepository
{
    protected $filterFields = [
        'employee_id' => 'employee_id',
        'year' => 'year',
        'month' => 'month'
    ];

    public function model()
    {
        return MonthlyTimesheetSummary::class;
    }

    public function findFirst(array $params = [], $columns = ['*'])
    {
        $conditions = [];
        return $this->findWhereFirst($params, ['id' => 'desc'], $columns);
    }
}
