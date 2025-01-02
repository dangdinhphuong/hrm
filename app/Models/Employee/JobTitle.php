<?php

namespace App\Models\Employee;

use App\Models\BaseModel;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobTitle extends BaseModel
{
    use HasFactory;

    protected $connection = 'mysql'; // Chỉ định kết nối cơ sở dữ liệu

    protected $table = 'job_titles';  // Table name


    public $timestamps = true;  // Enable timestamps (created_at, updated_at)

    protected $fillable = [
        'created_by',
        'updated_by',
        'name',
        'description',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
