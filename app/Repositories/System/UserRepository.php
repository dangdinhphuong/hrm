<?php

namespace App\Repositories\System;

use App\Models\System\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    protected $filterFields = [
        'name' => 'name',
        'username' => 'username',
        'gtc_extension' => 'gtc_extension',
        'email' => 'email',
        'status' => [self::CONDITION_FILTER_FIELD_ISSET, '=', 'status'],
        'call_center' => [self::CONDITION_FILTER_FIELD_NOT_EMPTY, 'IN', 'call_center'],
        'ids' => [self::CONDITION_FILTER_FIELD_NOT_EMPTY, 'IN', 'id'],
    ];

    public function model()
    {
        return User::class;
    }

    public function list(array $params = [], $paginate = true, $columns)
    {
        $conditions = [];

        if (!empty($params['keyword'])) {
            $conditions['orWhere'] = [
                [
                    ['name', 'like', "%{$params['keyword']}%"],
                    ['username', 'like', "%{$params['keyword']}%"]
                ]
            ];
        }

        if (!empty($params['department'])) {
            $this->whereHas('groups', function ($query) use ($params) {
                $query->whereIn('group_id', $params['department']);
            });
        }
        if (!empty($params['id'])) {
            $params['ids'] = is_array($params['id']) ? $params['id'] : [$params['id']];
        }

        if (!empty($params['role'])) {
            $this->whereHas('roles', function ($query) use ($params) {
                $query->whereIn('role_id', $params['role']);
            });
        }

        $query = $this->filter($params)->with('groups');

        $paginate =  !empty($params['paginate']) ? filter_var($params['paginate'], FILTER_VALIDATE_BOOLEAN) : $paginate;

        return $paginate ?
            $query->findWherePaginate($conditions, $params['limit'] ?? null, ['id' => 'desc'], $columns) :
            $query->findWhere($conditions, ['id' => 'desc'], $columns)->take($params['limit'] ?? '');
    }
}
