<?php

namespace App\Models\Attachment;

use App\Models\BaseModel;
use DateTimeInterface;

class Attachment extends BaseModel
{
    protected $connection = 'mysql';
    // Tên bảng
    protected $table = 'attachments';

    // Các trường có thể gán giá trị (mass assignable)
    protected $fillable = [
        'created_by',
        'updated_by',
        'file_path',
        'descriptions',
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    // Quan hệ 1-1 ngược với model EavAttachment
    public function eavAttachment()
    {
        return $this->belongsTo(EavAttachment::class, 'attachment_id', 'id');
    }
}
