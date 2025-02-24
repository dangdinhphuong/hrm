<?php

namespace App\Models\System;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class Vector extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'vector'; // Định nghĩa collection trong MongoDB

    protected $fillable = ['employee_id', 'face_vector']; // Các field có thể insert
}
