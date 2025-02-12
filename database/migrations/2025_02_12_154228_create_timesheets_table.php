<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('timesheets', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id', 50); // Mã nhân viên
            $table->date('work_date'); // Ngày làm việc
            $table->time('check_in')->nullable(); // Giờ vào làm
            $table->time('check_out')->nullable(); // Giờ tan làm
            $table->integer('office_minutes')->default(0); // Số phút làm việc tại văn phòng
            $table->integer('late_early_minutes')->default(0); // Số phút đi muộn về sớm
            $table->float('overtime_hours', 8, 2)->default(0); // Số giờ làm thêm
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timesheets');
    }
};

