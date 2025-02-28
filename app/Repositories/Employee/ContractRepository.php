<?php

namespace App\Repositories\Employee;

use App\Models\Employee\Contract;
use App\Repositories\BaseRepository;

class ContractRepository extends BaseRepository
{
    public function model()
    {
        return Contract::class;
    }

    public function getByEmployeesId(int $employeesId)
    {
        $conditions = ['employees_id' => $employeesId ];
        return $this->findWherePaginate($conditions, $params['limit'] ?? null);
    }

    public function getDetailById(int $id)
    {
       $this->with([
            'eavAttachments.attachment'
        ]);
        return $this->findWhereFirst(['id' => $id]);
    }
}
