<?php

namespace App\Repositories\Department;

use App\Models\Department\Department;
use App\Repositories\BaseRepository;

class DepartmentRepository extends BaseRepository
{
    public function model()
    {
        return Department::class;
    }

    public function list(array $params = [])
    {
        return $this->select('id', 'name')->get();
    }
}
