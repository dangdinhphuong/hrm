<?php

namespace App\Services\System;

use App\Repositories\System\BankRepository;

class BankService
{
    protected $bankRepository;

    public function __construct(BankRepository $bankRepository)
    {
        $this->bankRepository = $bankRepository;
    }

    public function list(array $params = [])
    {
        return $this->bankRepository->list($params);
    }
}
