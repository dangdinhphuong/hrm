<?php

namespace App\Repositories\Attachment;

use App\Models\Attachment\Attachment;
use App\Repositories\BaseRepository;

class AttachmentRepository extends BaseRepository
{
    public function model()
    {
        return Attachment::class;
    }

}
