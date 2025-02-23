<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hrm\TimeSheetRequest;
use App\Services\Work\TimeSheetService;
use Illuminate\Http\Request;

class TimeSheetController extends Controller
{
    protected $timeSheetService;

    public function __construct(

        TimeSheetService $timeSheetService)
    {
        $this->timeSheetService = $timeSheetService;
    }


    public function store(TimeSheetRequest $request)
    {
        return responder()->success($this->timeSheetService->store($request->all()));
    }
}
