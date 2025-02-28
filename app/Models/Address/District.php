<?php

namespace App\Models\Address;

use App\Models\BaseModel;
use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class District  extends BaseModel
{
    use HasFactory;
    protected $connection = 'mysql'; // Chỉ định kết nối cơ sở dữ liệu
    protected $table = 'districts';
    protected $fillable = [
        'name',
        'code',
        'province_id',
        'created_by',
        'updated_by',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function provinces()
    {
        return $this->hasOne(Province::class);
    }
}
