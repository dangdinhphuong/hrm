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
        Schema::connection('mysql')->create('attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable(); // created_by, int(11), có thể null
            $table->unsignedBigInteger('updated_by')->nullable(); // updated_by, int(11), có thể null

            $table->text('file_path'); // file_path, text, NOT_NULL
            $table->text('descriptions'); // district_code, text, NOT_NULL
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachment');
    }
};
