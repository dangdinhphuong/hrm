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

    public function list(array $params = [], $paginate = true, $columns = ['*'])
    {

        $conditions = [];
        if (!empty($params['updated_by'])) {
            $conditions['orWhereIn'] = ['updated_by', 'IN', convertToArray($params['updated_by'])];
        }


        $this->filter($params)->with(
            [
                'timesheets' => function ($q) use ($columns) {
                    $columns = splitTableColumns($columns, 'timesheets');
                    $q->select(array_merge(['id'], $columns));
                    $q->select(empty($columns) ? ['*'] : array_merge(['id'], $columns));
                },
                'employee' => function ($q) use ($columns) {
                    $columns = splitTableColumns($columns, 'employee');
                    $q->select(empty($columns) ? ['*'] : array_merge(['id'], $columns));
                }
            ]);
        return $paginate ?
            $this->findWherePaginate(where: $conditions, limit: $params['limit'] ?? null) :
            $this->findWhere(where: $conditions);
    }
}
