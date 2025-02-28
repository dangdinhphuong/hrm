<?php

namespace App\Models\Address;

use App\Models\BaseModel;
use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country  extends BaseModel
{
    use HasFactory;
    protected $connection = 'mysql'; // Chỉ định kết nối cơ sở dữ liệu
    protected $table = 'countries';
    protected $fillable = [
        'name',
        'code',
        'created_by',
        'updated_by',
    ];

    public function provinces()
    {
        return $this->hasMany(Province::class);
    }

    public function employee()
    {
        return $this->hasMany(Employee::class);
    }
}
