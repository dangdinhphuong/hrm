<?php

namespace App\Models\System;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bank  extends BaseModel
{
    use HasFactory;
    protected $connection = 'mysql'; // Chỉ định kết nối cơ sở dữ liệu
    protected $table = 'banks';
    protected $fillable = [
        'code',
        'name',
        'created_by',
        'updated_by',
    ];
}
