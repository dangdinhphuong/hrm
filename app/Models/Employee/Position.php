<?php

namespace App\Models\Employee;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position  extends BaseModel
{
    use HasFactory;
    protected $connection = 'mysql'; // Chỉ định kết nối cơ sở dữ liệu
    protected $table = 'positions';
    protected $fillable = [
        'position_name',
        'position_code',
        'description',
        'created_by',
        'updated_by',
    ];
}
