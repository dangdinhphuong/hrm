<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Employee\SalaryService;
use App\Http\Requests\Hrm\UpdateSalaryRequest;

class EmployeePaySlipController extends Controller
{
    public function __construct(SalaryService $salaryService)
    {
        $this->salaryService = $salaryService;
    }

    public function index(Request $request)
    {
        $params = $request->except(['extra_param']);
        $paginate = $request->has('page');

        $employeeSalaries = $this->salaryService->list($params, paginate: $paginate)->toArray();

        if ($paginate) {
            foreach ($employeeSalaries['data'] as &$employeeSalary) {
                $salaries = json_decode($employeeSalary['salaries'], true);

                // Loại bỏ các khoản khấu trừ trước khi tính tổng
                $filteredSalaries = array_diff_key($salaries, array_flip(['deduction_insurance', 'deduction_tax', 'deduction_dependents', 'employees_id']));

                // Tính tổng các khoản còn lại
                $salaries['total_salary'] = array_sum(array_filter($filteredSalaries, 'is_numeric'));

                $employeeSalary['salaries'] = $salaries;
            }
        } else {
            foreach ($employeeSalaries as &$employeeSalary) {
                $salaries = json_decode($employeeSalary['salaries'], true);

                // Loại bỏ các khoản khấu trừ trước khi tính tổng
                $filteredSalaries = array_diff_key($salaries, array_flip(['deduction_insurance', 'deduction_tax', 'deduction_dependents']));

                // Tính tổng các khoản còn lại
                $salaries['total_salary'] = array_sum(array_filter($filteredSalaries, 'is_numeric'));

                $employeeSalary['salaries'] = $salaries;
            }
        }

        return responder()->success($employeeSalaries);
    }


    public function find(Request $request, $id)
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
