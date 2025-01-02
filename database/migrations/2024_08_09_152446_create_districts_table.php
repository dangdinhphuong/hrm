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
        Schema::connection('mysql')->create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('code',11)->nullable();
            $table->unsignedBigInteger('province_id');
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
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
        Schema::connection('mysql')->dropIfExists('district');
    }
};
