<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mysql')->create('employee_departments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable(); // created_by, int(11), có thể null
            $table->unsignedBigInteger('updated_by')->nullable(); // updated_by, int(11), có thể null

            $table->unsignedBigInteger('employees_id'); // hrm_employees_id, int(11), FOREIGN_KEY
            $table->unsignedBigInteger('departments_id'); // hrm_departments_id, int(11), FOREIGN_KEY

            $table->integer('type'); // type, int(11), Lưu loại nhân sự

            // Thiết lập khóa ngoại cho hrm_employees_id và hrm_departments_id
            $table->foreign('employees_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('departments_id')->references('id')->on('departments')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_department');
    }
};
