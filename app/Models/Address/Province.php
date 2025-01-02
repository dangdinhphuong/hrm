<?php

namespace App\Models\Address;

use App\Models\BaseModel;
use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Province  extends BaseModel
{
    use HasFactory;
    protected $connection = 'mysql'; // Chỉ định kết nối cơ sở dữ liệu
    protected $table = 'provinces';
    protected $fillable = [
        'name',
        'code',
        'country_id',
        'created_by',
        'updated_by',
    ];

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function countries()
    {
        return $this->hasOne(Country::class);
    }
}
