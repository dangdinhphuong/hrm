<?php

namespace App\Services\Employee;

use App\Repositories\Employee\PositionRepository;

class PositionService
{
    protected $positionRepository;

    public function __construct(PositionRepository $positionRepository)
    {
        $this->positionRepository = $positionRepository;
    }

    public function list(array $params = [])
    {
        return $this->positionRepository->list($params);
    }
}
