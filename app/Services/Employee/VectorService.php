<?php

namespace App\Services\Employee;

use App\Repositories\Employee\VectorRepository;
use App\Repositories\Employee\EmployeesRepository;
use App\Services\Hrm\Exception;
use Illuminate\Support\Facades\DB;
use Log;
use function __;

class VectorService
{
    protected $vectorRepository;
    protected $employeesRepository;

    public function __construct(
        VectorRepository    $vectorRepository,
        EmployeesRepository $employeesRepository,
    )
    {
        $this->vectorRepository = $vectorRepository;
        $this->employeesRepository = $employeesRepository;
    }

    public function getDetailByUsername($username , $columns = ['*'])
    {
        return $this->vectorRepository->getDetailByUsername($username, $columns);
    }

}
