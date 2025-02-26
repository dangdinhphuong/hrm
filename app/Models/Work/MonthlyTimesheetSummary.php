<?php

namespace App\Models\Work;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee\Employee;

class MonthlyTimesheetSummary extends Model
{
    use HasFactory;

    protected $table = 'monthly_timesheet_summary'; // Tên bảng

    protected $fillable = [
        'employee_id',
        'year',
        'month',
        'leave_days',
        'business_trip_days',
        'holiday_days',
        'overtime_hours',
        'total_office_minutes',
        'total_late_early_minutes',
    ];

    public $timestamps = true;

    /**
     * Relationship: Một bản tổng hợp tháng thuộc về một nhân viên.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class, 'employee_id', 'employee_id')
            ->whereYear('work_date', request('year', now()->year))
            ->whereMonth('work_date', request('month', now()->month));
    }
}
