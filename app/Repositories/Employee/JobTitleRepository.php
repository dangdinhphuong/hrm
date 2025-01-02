<?php

namespace App\Repositories\Employee;

use App\Models\Employee\JobTitle;
use App\Repositories\BaseRepository;

class JobTitleRepository extends BaseRepository
{
    public function model()
    {
        return JobTitle::class;
    }

    public function list(array $params = [])
    {
        return $this->select('id', 'name')->get();
    }
}
