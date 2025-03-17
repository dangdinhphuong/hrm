<?php

namespace App\Repositories\Work;

use App\Models\Work\Request;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class RequestRepository extends BaseRepository
{

    public function model()
    {
        return Request::class;
    }

    public function list(array $params = [], bool $paginate = true, $columns)
    {
        $conditions = [];


        if (!empty($params['leave_type'])) {
            $conditions['leave_type'] = ['leave_type', '=', $params['leave_type']];
        }
        if (!empty($params['year-month'])) {
            list($params['year'], $params['month']) = explode('-', $params['year-month']);
        }

        $conditions['date'] = ['date', 'BETWEEN', getMonthStartAndEnd($params['year'] ?? null, $params['month'] ?? null)];
        $query = $this->with(['employee']);

        $paginate = !empty($params['paginate']) ? filter_var($params['paginate'], FILTER_VALIDATE_BOOLEAN) : $paginate;

        return $paginate ?
            $query->findWherePaginate($conditions, $params['limit'] ?? null, columns: $columns) :
            $query->findWhere($conditions, columns: $columns)->take($params['limit'] ?? null);
    }
}
