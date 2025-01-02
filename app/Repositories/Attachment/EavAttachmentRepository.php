<?php

namespace App\Repositories\Attachment;

use App\Models\Attachment\EavAttachment;
use App\Repositories\BaseRepository;

class EavAttachmentRepository extends BaseRepository
{
    protected $filterFields = [
        'created_by' => 'created_by',
        'updated_by' => 'updated_by',
        'entity_type' => 'entity_type',
        'entity_id' => 'entity_id',
        'attachment_id' => 'attachment_id'
    ];

    public function model()
    {
        return EavAttachment::class;
    }
    public function list(array $params = [], $paginate = true)
    {
        $conditions = [];

        $query = $this->filter($params)->with('attachment');

        return $paginate ?
            $query->findWherePaginate($conditions, $params['limit'] ?? null) :
            $query->findWhere($conditions);

    }

    public function getSingleEavAttachment(array $params = [])
    {
        $conditions = [];

        $query = $this->filter($params)->with('attachment');

        return $query->findWhereFirst($conditions);

    }
}
