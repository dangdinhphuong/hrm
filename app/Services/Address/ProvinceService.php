<?php

namespace App\Services\Address;


use App\Repositories\Address\ProvinceRepository;

class ProvinceService
{
    public function __construct(ProvinceRepository $provinceRepository)
    {
        $this->provinceRepository = $provinceRepository;
    }

    public function list(array $params = [])
    {
        return $this->provinceRepository->list($params);
    }

    public function getIdProvinceByRegion($region)
    {
        $provinces = $this->provinceRepository->getProvinceByRegion($region);
        if(!empty($provinces)){
            return $provinces->pluck('id')->toArray();
        }
        return [];

    }
}
