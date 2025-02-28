<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Services\Employee\JobTitleService;
use Illuminate\Http\Request;

class JobTitleController extends Controller
{
    private $jobTitleService;

    public function __construct(JobTitleService $jobTitleService)
    {
        $this->jobTitleService = $jobTitleService;
    }

    public function  all(Request $request){
        return responder()->success($this->jobTitleService->list($request->all()));
    }
}
