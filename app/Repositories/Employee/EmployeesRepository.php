<?php

namespace App\Repositories\Employee;

use App\Models\Employee\Employee;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class EmployeesRepository extends BaseRepository
{
    public function model()
    {
        return Employee::class;
    }

    public function list(array $params = [], $paginate = true)
    {
        $conditions = [];

        if (!empty($params['keyword'])) {
            $conditions['orWhere'] = [
                [
                    ['first_name', 'like', "%{$params['keyword']}%"],
                    ['last_name', 'like', "%{$params['keyword']}%"],
                    ['code', 'like', "%{$params['keyword']}%"],
                    [DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$params['keyword']}%"]
                ]
            ];
        }


        $query = $this->filter($params)->with(['position','departments']);

        $paginate =  !empty($params['paginate']) ? filter_var($params['paginate'], FILTER_VALIDATE_BOOLEAN) : $paginate;

        return $paginate ?
            $query->findWherePaginate($conditions, $params['limit'] ?? null) :
            $query->findWhere($conditions)->take($params['limit'] ?? '');
    }

    public function getDetailById(int$id)
    {
       $this->with([
            'currentCountry:id,name',
            'currentProvince:id,name',
            'currentDistrict:id,name',
            'currentNationality:id,name',
            'user:id,name,username',
            'bank:id,name',
            'position:id,name,code',
           'jobTitle:id,name',
            'departments',

        ]);
        return $this->findWhereFirst(['id' => $id]);
    }
}
