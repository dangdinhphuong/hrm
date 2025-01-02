<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Services\Address\CountriesService;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    public $countriesService;

    public function __construct(CountriesService $countriesService)
    {
        $this->countriesService = $countriesService;
    }

    public function  index(Request $request){
        return responder()->success($this->countriesService->list($request->all()));
    }

    public function  all(Request $request){
        return responder()->success($this->countriesService->list($request->all()));
    }
}
