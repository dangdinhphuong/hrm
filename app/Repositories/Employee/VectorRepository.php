<?php

namespace App\Repositories\Employee;

use App\Models\Employee\Vector;
use App\Repositories\BaseRepository;

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
        $this->with(
            [
                'employee' => function ($q) use ($columns) {
                    $columns = splitTableColumns($columns, 'employee');
                    $q->select(empty($columns) ? ['*'] : array_merge(['id'], $columns));
                }
            ]);

        return $this->findWhereFirst(['username' => $username], columns:$columns);
    }
}
