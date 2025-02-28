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
        Schema::connection('mysql')->create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('code', 11)->unique()->comment('Mã ngân hàng'); // varchar(11)
            $table->string('name', 100)->comment('Tên ngân hàng');// varchar(100)
            $table->bigInteger('created_by')->nullable()->comment('ID của người đã tạo bản ghi');
            $table->bigInteger('updated_by')->nullable()->comment('ID của người đã cập nhật bản ghi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql')->dropIfExists('bank');
    }
};
