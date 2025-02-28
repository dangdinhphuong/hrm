<?php

namespace App\Repositories\System;

use App\Models\System\Permission;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class PermissionRepository extends BaseRepository
{
    protected $filterFields = [
        'permissions' => [self::CONDITION_FILTER_FIELD_NOT_EMPTY, 'IN', 'code'],
    ];
    public function model()
    {
        return Permission::class;
    }
    public function getAll()
    {
        return $this->with('children')->roots()->get();
    }

    public function getPermissionsByCodes(array $params = [], $columns = ['*'])
    {
        $conditions = [];
        $query = $this->filter($params);
        return $query->findWhere($conditions,['id' => 'desc'], $columns);
    }
}
