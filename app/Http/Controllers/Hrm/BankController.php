<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Services\System\BankService;
use Illuminate\Http\Request;

class BankController extends Controller
{
    private $bankService;

    public function __construct(BankService $bankService)
    {
        $this->bankService = $bankService;
    }

    public function  all(Request $request){
        return responder()->success($this->bankService->list($request->all()));
    }
}
