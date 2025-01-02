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
        Schema::connection('mysql')->create('eav_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable(); // created_by, int(11), có thể null
            $table->unsignedBigInteger('updated_by')->nullable(); // updated_by, int(11), có thể null

            $table->string('entity_type',225); // entity_type, int(11), Ghi thẳng giá trị tên bảng chứa entity tương ứng
            $table->unsignedBigInteger('entity_id'); // entity_id, int(11), FOREIGN_KEY
            $table->unsignedBigInteger('attachment_id'); // attachment_id, int(11), FOREIGN_KEY

            // Thiết lập khóa ngoại cho entity_id và attachment_id
            $table->foreign('attachment_id')->references('id')->on('attachments')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eav_attachment');
    }
};
