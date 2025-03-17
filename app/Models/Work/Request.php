<?php

namespace App\Models\Work;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $fillable = [
        'employee_id',
        'date',
        'leave_type',
        'content',
        'hr_status',
        'manager_status',
        'reject_reason',
        'created_by',
    ];

    // Quan hệ với Employee
    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee\Employee::class, 'employee_id');
    }
}
