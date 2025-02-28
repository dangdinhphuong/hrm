<?php

namespace App\Services\Attachment;

use App\Repositories\Attachment\EavAttachmentRepository;
use Log;

class EavAttachmentService
{
    protected $eavAttachmentRepository;

    public function __construct(
        EavAttachmentRepository $eavAttachmentRepository,
    )
    {
        $this->eavAttachmentRepository = $eavAttachmentRepository;
    }

    public function getSingle(array $params = [])
    {
        return $this->eavAttachmentRepository->getSingleEavAttachment($params);
    }

    public function create($data)
    {
        $this->eavAttachmentRepository->create([
            'entity_type' => $data['entityType'],
            'entity_id' => $data['entityId'],
            'attachment_id' => $data['attachmentId'],
        ]);
    }

}
