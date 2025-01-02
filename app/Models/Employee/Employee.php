<?php

namespace App\Models\Employee;

use App\Models\Address\Country;
use App\Models\Address\District;
use App\Models\Address\Province;
use App\Models\BaseModel;
use App\Models\Department\Department;
use App\Models\Attachment\EavAttachment;
use App\Models\System\Bank;
use App\Models\System\User;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends BaseModel
{
    use HasFactory;

    protected $connection = 'mysql'; // Chỉ định kết nối cơ sở dữ liệu

    protected $table = 'employees';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'personal_email',
        'phone',
        'birthday',
        'gender',
        'marital_status',
        'status',
        'code',
        'business_email',
        'fingerprint_code',
        'skype_id',
        'start_date',
        'end_date',
        'old_identity_card_number',
        'old_identity_card_issue_date',
        'old_identity_card_place',
        'identity_card_number',
        'identity_card_issue_date',
        'identity_card_place',
        'place_origin',
        'nationality',
        'residential_address',
        'current_address',
        'tax_code',
        'social_insurance_number',
        'position_id',
        'current_country_id',
        'current_province_id',
        'current_district_id',
        'bank_id',
        'bank_account_number',
        'bank_account_name',
        'level',
        'job_title_id',
        'created_by',
        'updated_by',
    ];

    protected $dates = [
        'birthday',
        'identify_release_date',
        'identify_release_date_old'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function currentCountry()
    {
        return $this->belongsTo(Country::class, 'current_country_id');
    }

    public function currentNationality()
    {
        return $this->belongsTo(Country::class, 'nationality');
    }

    // Relationship với bảng provinces
    public function currentProvince()
    {
        return $this->belongsTo(Province::class, 'current_province_id');
    }

    // Relationship với bảng districts
    public function currentDistrict()
    {
        return $this->belongsTo(District::class, 'current_district_id');
    }

    // Relationship với bảng banks
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    // Relationship với bảng positions
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function jobTitle()
    {
        return $this->belongsTo(JobTitle::class, 'job_title_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function departments()
    {
        // Thiết lập mối quan hệ nhiều-nhiều với model Department thông qua bảng employee_departments
        return $this->belongsToMany(Department::class, 'employee_departments', 'employees_id', 'departments_id')
            ->withTimestamps(); // Nếu bạn sử dụng timestamps trong bảng trung gian
    }

    public function avatarAttachments()
    {
        return $this->hasMany(EavAttachment::class, 'entity_id')
            ->where('entity_type', 'employee_avatar');
    }
}
