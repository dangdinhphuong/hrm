<?php

namespace App\Repositories\Employee;

use App\Models\Employee\Employee;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class EmployeesRepository extends BaseRepository
{
    protected $filterFields = [
        'code' => 'code',
    ];

    public function model()
    {
        return Employee::class;
    }

    public function list(array $params = [], bool $paginate = true, $columns)
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
        if(!empty($params['status'])){
            $conditions['status'] = ['status', '=', $params['status']];
        }

        $departmentId = $params['departmentId'] ?? null;

        $query = $this->with([
            'position',
            'departments',
            'avatarAttachment.attachment'
        ]);

        $paginate = !empty($params['paginate']) ? filter_var($params['paginate'], FILTER_VALIDATE_BOOLEAN) : $paginate;

        return $paginate ?
            $query->findWherePaginate($conditions, $params['limit'] ?? null, columns: $columns) :
            $query->findWhere($conditions, columns: $columns)->take($params['limit'] ?? null);
    }


    public function getDetailById(int $id)
    {
        $this->with([
            'currentCountry:id,name',
            'currentProvince:id,name',
            'currentDistrict:id,name',
            'currentNationality:id,name',
            'user:id,name,username',
            'user.roles',
            'bank:id,name',
            'position:id,name,code',
            'jobTitle:id,name',
            'departments',

        ]);
        return $this->findWhereFirst(['id' => $id]);
    }

    public function getDetailByUsername($username, $columns)
    {
        return $this->findWhereFirst(['code' => $username]);
    }

    public function getTimesheets(array $params = [], $paginate = true)
    {
        $conditions = [];
        $query = $this->filter($params)
            ->with([
                'timesheets',
                'monthlyTimesheets' => function ($query) {
                    $query->where('year', now()->year)
                        ->where('month', now()->month);
                }
            ]);


        $paginate = !empty($params['paginate']) ? filter_var($params['paginate'], FILTER_VALIDATE_BOOLEAN) : $paginate;

        return $paginate ?
            $query->findWherePaginate($conditions, $params['limit'] ?? null, orderBy: ['status' => 'asc'], columns: ["id", "first_name", "last_name", "code"]) :
            $query->findWhere($conditions, orderBy: ['status' => 'asc'], columns: ["id", "first_name", "last_name", "code"])->take($params['limit'] ?? '');
    }
}
