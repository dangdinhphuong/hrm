<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Services\External\Request\RequestService;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    private $requestService;

    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
    }

    public function create(Request $request, $type)
    {
        $result = $this->requestService->create($type, $request->all());
        if ($result) {
            return responder()->success(message: "Create request $type success");
        }
        return responder()->fail("Create request $type fail");
    }
}
