<?php

namespace App\Repositories\System;

use App\Models\System\Setting;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class ConfigRepository extends BaseRepository
{
    protected $filterFields = [
        'code' => 'code',
    ];

    public function model()
    {
        return Setting::class;
    }

    public function list(array $params = [], bool $paginate = true, $columns)
    {
        $conditions = [];

        return $this->findWhere($conditions, columns: $columns);
    }

    public function getDetailByKey($key)
    {
        return $this->findWhereFirst(['key' => $key]);
    }
}
