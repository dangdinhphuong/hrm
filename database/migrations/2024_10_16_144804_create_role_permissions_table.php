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
        Schema::connection('mysql')->create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->bigInteger('role_id')->nullable()->unsigned();
            $table->bigInteger('permission_id')->nullable()->unsigned();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql')->dropIfExists('role_permissions');
    }
};
