<?php

namespace App\Repositories\Employee;

use App\Models\System\Vector;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class VectorRepository extends BaseRepository
{
    protected $filterFields = [
        'username' => 'username',
    ];

    public function model()
    {
        return Vector::class;
    }

    public function createOrUpdateVector(array $data, string $employeeId): Vector
    {
        return $this->updateOrCreate(
            ['employee_id' => (int)$employeeId], // Tìm theo employee_id
            $data
        );
    }
    public function getDetailByUsername($username , $columns = ['*'])
    {
        $conditions = [];
        return $this->findWhereFirst(['username' => $username], columns:$columns);
    }
}
