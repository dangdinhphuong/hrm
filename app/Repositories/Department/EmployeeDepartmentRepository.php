<?php

namespace App\Repositories\Department;

use App\Models\Department\EmployeeDepartment;
use App\Repositories\BaseRepository;

class EmployeeDepartmentRepository extends BaseRepository
{
    public function model()
    {

        return EmployeeDepartment::class;
    }
    public function getByEmployeesId(int $employeesId)
    {
        return $this->findWhereFirst(['employees_id' => $employeesId]);
    }
}
