<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade'); // Tên nhân viên
            $table->date('date')->comment('Ngày bắt đầu');
            $table->unsignedTinyInteger('leave_type')->comment('Loại đơn (1: Nghỉ phép, 2: Nghỉ không lương, 3: Quên chấm công,...)');
            $table->text('content')->nullable()->comment('Nội dung đơn');
            $table->unsignedTinyInteger('hr_status')->default(0)->comment('HR duyệt (0: Chờ duyệt, 1: Đã duyệt, 2: Từ chối)');
            $table->unsignedTinyInteger('manager_status')->default(0)->comment('Quản lý duyệt (0: Chờ duyệt, 1: Đã duyệt, 2: Từ chối)');
            $table->text('reject_reason')->nullable()->comment('Lý do từ chối nếu có');
            $table->foreignId('created_by')->nullable()->comment('Người tạo đơn');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
