<?php

namespace App\Repositories\System;

use App\Models\System\Role;
use App\Repositories\BaseRepository;

class RoleRepository extends BaseRepository
{
    public function model()
    {
        return Role::class;
    }

    public function list(array $params = [])
    {
        $conditions = [];

        if (!empty($params['keyword'])) {
            $conditions['orWhere'] = [
                [
                    ['name', 'like', "%{$params['keyword']}%"],
                ]
            ];
        }
        if (!empty($params['name'])) $conditions['name'] = $params['name'];

        return $this->findWherePaginate(where: $conditions, limit: $params['limit'] ?? null, orderBy: ['id' => 'desc']);
    }
}
