<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Services\Employee\PositionService;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    private $positionService;

    public function __construct(PositionService $positionService)
    {
        $this->positionService = $positionService;
    }

    public function  all(Request $request){
        return responder()->success($this->positionService->list($request->all()));
    }
}
