<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Services\System\ConfigService;
use Illuminate\Http\Request;


class ConfigController extends Controller
{
    protected $configService;

    public function __construct(
        ConfigService $configService,
    )
    {
        $this->configService = $configService;
    }
    public function index(Request $request)
    {
        return responder()->success($this->configService->list($request->all()));
    }
    public function getDetailByKey($key)
    {
        return responder()->success($this->configService->getDetailByKey($key));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $employees = $this->configService->create($data);
        return responseByStatus($employees["status"], $employees["message"]);

    }
}
