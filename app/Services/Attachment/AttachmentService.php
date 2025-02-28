<?php

namespace App\Services\Attachment;

use App\Repositories\Attachment\AttachmentRepository;
use Log;

class AttachmentService
{
    protected $attachmentRepository;
    protected $eavAttachmentService;

    public function __construct(
        EavAttachmentService          $eavAttachmentService,
        attachmentRepository          $attachmentRepository,
    )
    {
        $this->eavAttachmentService = $eavAttachmentService;
        $this->attachmentRepository = $attachmentRepository;
    }

    public function create($data)
    {
        $attachment = $this->attachmentRepository->create([
            'file_path' => $data['file'],
            'descriptions' => $data['descriptions']
        ]);
        if(!empty($attachment)){
            $data['attachmentId'] = $attachment->id;
            $this->eavAttachmentService->create($data);
        }
    }

}
