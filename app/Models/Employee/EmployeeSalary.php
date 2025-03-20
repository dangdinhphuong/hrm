<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'salaries',
        'effective_date',
    ];

    protected $casts = [
        'salaries' => 'array', // Laravel tự động chuyển JSON thành array khi lấy dữ liệu
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
