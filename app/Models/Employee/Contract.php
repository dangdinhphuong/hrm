<?php

namespace App\Models\Employee;

use App\Models\Address\Country;
use App\Models\Address\District;
use App\Models\BaseModel;
use App\Models\Attachment\EavAttachment;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contract  extends BaseModel
{
    use HasFactory;
    protected $connection = 'mysql'; // Chỉ định kết nối cơ sở dữ liệu

    protected $table = 'contracts';
    protected $fillable = [
        'contract_type',
        'contract_number',
        'employees_id',
        'start_time',
        'end_time',
        'status',
        'note',
        'created_by',
        'updated_by',
    ];

    protected $dates = [
        'start_time',
        'end_time',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function countries()
    {
        return $this->hasOne(Country::class);
    }

    public function eavAttachments()
    {
        return $this->hasMany(EavAttachment::class, 'entity_id', 'id');
    }
}
