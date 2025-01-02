<?php

namespace App\Repositories\Address;

use App\Models\Address\Country;
use App\Repositories\BaseRepository;

class CountriesRepository extends BaseRepository
{
    public function model()
    {
        return Country::class;
    }
    public function list(array $params = [])
    {
        return $this->select('id', 'name', 'code')->with(['provinces','provinces.districts'])->get();
    }
}

