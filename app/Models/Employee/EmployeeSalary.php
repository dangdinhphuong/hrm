<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
    // Nếu cần tự động encode khi lưu
    protected function salaries(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true), // Giải mã khi lấy
            set: fn ($value) => json_encode($value) // Mã hóa khi lưu
        );
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
