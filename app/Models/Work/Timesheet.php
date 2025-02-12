<?php

namespace App\Models\Work;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    use HasFactory;

    protected $table = 'timesheets'; // Tên bảng

    protected $fillable = [
        'employee_id',
        'work_date',
        'check_in',
        'check_out',
        'office_minutes',
        'late_early_minutes',
        'overtime_hours',
    ];

    public $timestamps = true;

    /**
     * Relationship: Một bảng chấm công thuộc về một nhân viên.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
