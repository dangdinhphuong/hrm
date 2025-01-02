<?php

namespace App\Repositories\Employee;

use App\Models\Employee\Position;
use App\Repositories\BaseRepository;

class PositionRepository extends BaseRepository
{
    public function model()
    {
        return Position::class;
    }

    public function list(array $params = [])
    {
        return $this->select('id', 'name')->get();
    }
}
