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
        if (!empty($params['hr_status'])) {
            $conditions['hr_status'] = ['hr_status', '=', $params['hr_status']];
        }
        if (!empty($params['manager_status'])) {
            $conditions['manager_status'] = ['manager_status', '=', $params['manager_status']];
        }
        if (!empty($params['employee_id'])) {
            $conditions['employee_id'] = ['employee_id', 'IN', $params['employee_id']];
        }

        $conditions['date'] = ['date', 'BETWEEN', getMonthStartAndEnd($params['year'] ?? null, $params['month'] ?? null)];

        $query = $this->with(['employee']);
        if (!empty($params['employee_name']) || !empty($params['keyword'])) {
            $this->whereHas('employee', function ($query) use ($params) {
                $keyword = $params['keyword'] ?? $params['employee_name'];
                if ($keyword) {
                    $query->where(function ($subQuery) use ($keyword) {
                        $subQuery->where('first_name', 'like', '%' . $keyword . '%')
                            ->orWhere('last_name', 'like', '%' . $keyword . '%')
                            ->orWhere('code', 'like', '%' . $keyword . '%');

                        // Trường hợp tìm theo full name (họ + tên)
                        $subQuery->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $keyword . '%']);
                    });
                }
            });
        }


        $paginate = !empty($params['paginate']) ? filter_var($params['paginate'], FILTER_VALIDATE_BOOLEAN) : $paginate;

        return $paginate ?
            $query->findWherePaginate($conditions, $params['limit'] ?? null, columns: $columns) :
            $query->findWhere($conditions, columns: $columns)->take($params['limit'] ?? null);
    }
}
