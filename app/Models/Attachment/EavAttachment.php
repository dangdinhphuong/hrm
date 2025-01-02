<?php

namespace App\Models\Attachment;
use App\Models\BaseModel;
use DateTimeInterface;

class EavAttachment extends BaseModel
{
    protected $connection = 'mysql';
    // Tên bảng
    protected $table = 'eav_attachments';

    // Các trường có thể gán giá trị (mass assignable)
    protected $fillable = [
        'created_by',
        'updated_by',
        'entity_type',
        'entity_id',
        'attachment_id',
    ];

    public function attachment()
    {
        return $this->hasOne(Attachment::class, 'id', 'attachment_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
