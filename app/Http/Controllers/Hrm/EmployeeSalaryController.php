<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Employee\SalaryService;
use App\Http\Requests\Hrm\UpdateSalaryRequest;

class EmployeeSalaryController extends Controller
{
    public function __construct(SalaryService $salaryService)
    {
        $this->salaryService = $salaryService;
    }

    public function find(Request $request)
    {
        $data = $request->all() ?? [];

        $employeeSalary = $this->salaryService->list($data, false)->first() ?? [];

        if (!empty($employeeSalary)) {
            $employeeSalaryArray = $employeeSalary->toArray(); // Chuyển toàn bộ thành array

            // Đảm bảo salaries là array trước khi merge
            $mergedSalary = array_merge(json_decode($employeeSalaryArray['salaries'], true), $employeeSalaryArray);
            unset($employeeSalaryArray['salaries']);
        }
        return responder()->success($mergedSalary ?? []);
    }
    public function me(Request $request)
    {
        $data = $request->all() ?? [];
        $employee = auth()->user()->employee;
        $data['employee_id'] = $employee->id;
        $employeeSalary = $this->salaryService->list($data, false)->first() ?? [];

        if (!empty($employeeSalary)) {
            $employeeSalaryArray = $employeeSalary->toArray(); // Chuyển toàn bộ thành array

            // Đảm bảo salaries là array trước khi merge
            $mergedSalary = array_merge(json_decode($employeeSalaryArray['salaries'], true), $employeeSalaryArray);
            unset($employeeSalaryArray['salaries']);
        }
        return responder()->success($mergedSalary ?? []);
    }

    public function update(UpdateSalaryRequest $request, int $id)
    {
        $data = $request->all();
        $employeeSalary = $this->salaryService->update($id, $data);
        return responseByStatus($employeeSalary["status"], $employeeSalary["message"]);
    }
}
