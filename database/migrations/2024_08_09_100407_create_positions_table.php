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
        Schema::connection('mysql')->create('positions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('created_by')->nullable(); // created_by, int(11), có thể null
            $table->unsignedBigInteger('updated_by')->nullable(); // updated_by, int(11), có thể null
            $table->string('name', 100); // position_name, varchar(100), NOT_NULL
            $table->string('code', 11)->unique(); // position_code, varchar(10), UNIQUE
            $table->text('description')->nullable(); // description, text, có thể null
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position');
    }
};
