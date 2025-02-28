<?php

namespace App\Repositories\System;

use App\Models\System\Bank;
use App\Repositories\BaseRepository;

class BankRepository extends BaseRepository
{
    public function model()
    {
        return Bank::class;
    }

    public function list(array $params = [])
    {
        return $this->select('id', 'name')->get();
    }
}
