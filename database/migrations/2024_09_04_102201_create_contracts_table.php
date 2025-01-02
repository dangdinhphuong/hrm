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
        Schema::connection('mysql')->create('contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable(); // created_by, int(11), có thể null
            $table->unsignedBigInteger('updated_by')->nullable(); // updated_by, int(11), có thể null

            $table->integer('contract_type')->comment('|1|Khác|,|2|Hợp đồng thử việc|,|3|Hợp đồng lao động xác định thời hạn|,|4|Hợp đồng lao động không xác định thời hạn|'); // contract_type, int(11), Lưu loại hợp đồng, dạng key 1: abc; 2: xyz
            $table->string('contract_number', 100)->unique(); // contract_number, varchar(50), NOT NULL, UNIQUE

            $table->unsignedBigInteger('employees_id'); // hrm_employees_id, int(11), FOREIGN_KEY
            $table->integer('status'); // status, int(11), NOT NULL, Lưu trạng thái hợp đồng

            $table->datetime('start_time')->nullable(); // start_time, datetime, có thể null
            $table->datetime('end_time')->nullable(); // end_time, datetime, có thể null
            $table->text('note')->nullable(); // note, text, có thể null

            // Thiết lập khóa ngoại cho hrm_employees_id
            $table->foreign('employees_id')->references('id')->on('employees')->onDelete('cascade');

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
