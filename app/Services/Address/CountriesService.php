<?php

namespace App\Services\Address;


use App\Repositories\Address\CountriesRepository;

class CountriesService
{
    protected $countriesRepository;

    public function __construct(CountriesRepository $countriesRepository)
    {
        $this->countriesRepository = $countriesRepository;
    }

    public function list(array $params = [])
    {
        return $this->countriesRepository->list($params);
    }
}
