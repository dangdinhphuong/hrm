<?php


namespace App\Models\Department;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends BaseModel
{
    use HasFactory;

    protected $connection = 'mysql'; // Chỉ định kết nối cơ sở dữ liệu
    protected $table = 'departments';
    protected $fillable = [
        'name',
        'business_unit_type',
        'created_by',
        'updated_by',
        'parent_id'
    ];
}
