<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Services\Work\MonthlyTimesheetSummaryService;
use Illuminate\Http\Request;

class MonthlyTimesheetSummaryController extends Controller
{
    public function __construct(MonthlyTimesheetSummaryService $monthlyTimesheetSummaryService)
    {
        $this->monthlyTimesheetSummaryService = $monthlyTimesheetSummaryService;
    }

    /**
     * Lấy danh sách chấm công theo tháng.
     */
    public function index(Request $request)
    {
        $columns = ['employee.first_name', 'employee.last_name', 'employee.code'];
        $monthlyTimesheetSummary = $this->monthlyTimesheetSummaryService->list($request->all(), columns: $columns);
        return responder()->success($monthlyTimesheetSummary);
    }
}
