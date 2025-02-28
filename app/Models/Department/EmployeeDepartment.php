<?php

namespace App\Models\Department;

use App\Models\BaseModel;
use App\Models\Employee\Employee;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeDepartment extends BaseModel
{
    use HasFactory;

    protected $connection = 'mysql'; // Chỉ định kết nối cơ sở dữ liệu
    protected $table = 'employee_departments';


    protected $fillable = [
        'employees_id',
        'departments_id',
        'type'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);

    }
}
