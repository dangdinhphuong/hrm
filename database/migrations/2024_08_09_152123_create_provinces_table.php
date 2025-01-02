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
        Schema::connection('mysql')->create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('code', 11); // varchar(11)
            $table->string('name', 100);// varchar(100)
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
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
        Schema::connection('mysql')->dropIfExists('province');
    }
};
