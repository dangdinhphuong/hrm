<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Tự động tạo khóa chính
            $table->string('name', 255)->nullable();
            $table->string('username', 255)->unique();
            $table->integer('group_id')->nullable()->default(3);
            $table->integer('room_id')->nullable()->comment('Id phòng ban');
            $table->integer('parent_id')->nullable();
            $table->integer('partner_id')->nullable();
            $table->integer('type')->nullable()->comment('Loại user: 1=tiểu học, 2=Kid');
            $table->string('avatar', 255)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('code_ship', 20)->nullable();
            $table->integer('gtc_extension')->nullable();
            $table->integer('job')->nullable()->comment('1: parttime,2: full time');
            $table->string('priority_source', 100)->nullable()->comment('Nguồn ưu tiên');
            $table->float('quota')->nullable()->comment('Định mức');
            $table->string('remember_token', 100)->nullable();
            $table->text('goal')->nullable()->comment('Mục tiêu');
            $table->string('ex_name', 255)->nullable()->comment('Tên extension');
            $table->string('ex_password', 255)->nullable()->comment('Password extension');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->date('date_in')->nullable()->comment('Ngày vào');
            $table->date('date_out')->nullable()->comment('Ngày ra');
            $table->string('password', 255);
            $table->string('api_token', 80)->unique()->nullable();
            $table->string('device_token', 255)->nullable();
            $table->string('skype', 200)->nullable();
            $table->integer('limit')->default(0);
            $table->string('automatic_oursource', 255)->nullable();
            $table->tinyInteger('is_allow_telesales')->default(1);
            $table->string('bitrix_user_id', 255)->nullable();
            $table->text('gtc_extension_old')->nullable();
            $table->string('api_key', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->integer('call_center')->nullable();
            $table->dateTime('password_changed_at')->nullable();

            // Indexes
            $table->index('status', 'status_1');
            $table->index('username', 'username_1');
            $table->index('group_id', 'group_id_1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
