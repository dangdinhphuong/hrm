<?php

namespace App\Models\Employee;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use App\Models\Employee\Employee;

class Vector extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'vector'; // Định nghĩa collection trong MongoDB

    protected $fillable = ['username', 'employee_id', 'face_vector']; // Các field có thể insert

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
