<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Services\Department\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    private $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function  all(Request $request){
        return responder()->success($this->departmentService->list($request->all()));
    }
}
