<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('monthly_timesheet_summary', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id', 50); // Mã nhân viên
            $table->integer('year'); // Năm
            $table->integer('month'); // Tháng
            $table->float('leave_days')->default(0); // Tổng số ngày nghỉ phép trong tháng
            $table->float('business_trip_days')->default(0); // Tổng số ngày công tác trong tháng
            $table->float('holiday_days')->default(0); // Tổng số ngày nghỉ lễ trong tháng
            $table->float('overtime_hours')->default(0); // Tổng số giờ làm thêm trong tháng
            $table->integer('total_office_minutes')->default(0); // Tổng số phút làm việc tại văn phòng trong tháng
            $table->integer('total_late_early_minutes')->default(0); // Tổng số phút đi muộn về sớm trong tháng
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_timesheet_summary');
    }
};
