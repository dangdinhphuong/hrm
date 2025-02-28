<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mysql')->create('employees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable()->unique()->comment('Mã định danh duy nhất của mỗi người dùng trong hệ thống CRM');
            // Employee's first and last name
            $table->string('first_name', 100)->comment('Lưu họ của nhân viên');
            $table->string('last_name', 100)->comment('Lưu tên của nhân viên');

            // Employee's email and phone
            $table->string('personal_email', 255)->nullable()->comment('Lưu email cá nhân');
            $table->string('phone', 20)->comment('Số điện thoại của nhân viên');

            // Personal details
            $table->date('birthday')->nullable()->comment('Ngày tháng năm sinh của nhân viên');
            $table->integer('gender')->nullable()->comment('1:Male, 2:famale, 3: other');
            $table->integer('marital_status')->nullable()->comment('Tình trạng hôn nhân của nhân viên');
            $table->integer('status')->comment('Trạng thái hoạt động của nhân viên');

            // Employee code and business email
            $table->string('code', 11)->nullable()->comment('Mã nhân viên');
            $table->string('business_email', 255)->nullable()->comment('Email trên công ty');

            // Additional identification
            $table->string('fingerprint_code', 100)->nullable()->comment('Mã vân tay');
            $table->string('skype_id', 100)->nullable()->comment('Link tài khoản skype');

            // Employment details
            $table->date('start_date')->nullable()->comment('Ngày vào làm');
            $table->date('end_date')->nullable()->comment('Ngày nghỉ việc');

            // Identity card details
            $table->string('old_identity_card_number', 20)->nullable()->unique()->comment('Số CMT cũ');
            $table->date('old_identity_card_issue_date')->nullable()->comment('Ngày cấp CMT cũ');
            $table->string('old_identity_card_place', 255)->nullable()->comment('Nơi cấp CMT cũ');
            $table->string('identity_card_number', 20)->nullable()->unique()->comment('Số CCCD');
            $table->date('identity_card_issue_date')->nullable()->comment('Ngày cấp CCCD');
            $table->string('identity_card_place', 255)->nullable()->comment('Nơi cấp CCCD');

            // Place of origin and nationality
            $table->text('place_origin')->nullable() ->comment('Nguyên quán');
            $table->bigInteger('nationality')->nullable() ->comment('Quốc tịch');

            // Residential and current address
            $table->text('residential_address')->nullable()->comment('Địa chỉ thường trú');
            $table->text('current_address')->nullable()->comment('Địa chỉ hiện tại');

            // Tax and social insurance
            $table->string('tax_code', 20)->nullable()->unique()->comment('Mã số thuế');
            $table->string('social_insurance_number', 20)->nullable()->unique()->comment('Số bảo hiểm xã hội');

            // Current location and bank details
            $table->bigInteger('job_title_id')->nullable()->unsigned()->comment('ID của chức danh');  // Adding job_title_id

            $table->bigInteger('position_id')->nullable()->unsigned()->comment('ID của chức vụ');

            $table->bigInteger('current_country_id')->nullable()->unsigned()->comment('Đất nước đang ở');

            $table->bigInteger('current_province_id')->nullable()->unsigned()->comment('Thành phố đang ở');

            $table->bigInteger('current_district_id')->nullable()->unsigned()->comment('Quận/Huyện đang ở');

            $table->bigInteger('bank_id')->nullable()->unsigned()->comment('Ngân hàng');

            $table->string('bank_account_number', 20)->nullable()->unique()->comment('Số tài khoản ngân hàng');
            $table->string('bank_account_name', 100)->nullable()->comment('Tên tài khoản ngân hàng');

            // Education level
            $table->integer('level')->nullable()->comment('Trình độ hiện tại');

            // Thêm các trường timestamp và ID của người tạo và cập nhật
            $table->timestamps();
            $table->bigInteger('created_by')->nullable()->comment('ID của người đã tạo bản ghi');
            $table->bigInteger('updated_by')->nullable()->comment('ID của người đã cập nhật bản ghi');

            // Thêm các khóa ngoại
            $table->foreign('current_country_id')->references('id')->on('countries')->onDelete('set null');
            $table->foreign('current_province_id')->references('id')->on('provinces')->onDelete('set null');
            $table->foreign('current_district_id')->references('id')->on('districts')->onDelete('set null');
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('set null');
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('set null');
            $table->foreign('job_title_id')->references('id')->on('job_titles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql')->dropIfExists('employee');
    }
};
