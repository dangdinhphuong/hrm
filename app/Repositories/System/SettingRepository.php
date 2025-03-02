<?php

namespace App\Repositories\System;

use App\Models\System\Setting;
use App\Repositories\BaseRepository;

class SettingRepository extends BaseRepository
{
    public function model()
    {
        return Setting::class;
    }
    public function getDetailByKey(string $key)
    {
        return $this->findWhereFirst([ 'key' => $key]);
    }
}
