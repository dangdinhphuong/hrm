<?php

namespace App\Services\Employee;

use App\Repositories\Employee\JobTitleRepository;

class JobTitleService
{
    protected $jobTitleRepository;

    public function __construct(JobTitleRepository $jobTitleRepository)
    {
        $this->jobTitleRepository = $jobTitleRepository;
    }

    public function list(array $params = [])
    {
        return $this->jobTitleRepository->list($params);
    }
}
