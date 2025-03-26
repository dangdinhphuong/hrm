<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Auth\Access\AuthorizationException;
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
    public function find(Request $request, $id)
    {
        $payslipData = [
            "employee_code" => "NV001",
            "employee_name" => "Nguyễn Văn A",
            "department" => "Kinh doanh",
            "position" => "Trưởng phòng",
            "work_days_standard" => "26",
            "work_days_total" => "24",
            "work_days_normal" => "22",
            "work_days_holiday" => "2",
            "salary_basic" => 13000000,
            "salary_kpi" => 2000000,
            "allowance_lunch" => 500000,
            "allowance_other" => 300000,
            "total_salary" => 15800000,
            "income_basic" => 13000000,
            "income_overtime" => 1000000,
            "income_travel" => 300000,
            "income_bonus" => 500000,
            "income_total" => 15000000,
            "deduction_insurance" => -500000,
            "deduction_tax" => -300000,
            "deduction_leave" => -300000,
            "deduction_dependents" => 2,
            "deduction_total" => -800000,
            "net_income" => 14200000,
            "bank_account" => "0976594507",
            "bank_holder" => "Nguyễn Văn A",
            "bank_name" => "Vietcombank"
        ];
       $DATA = $this->salaryService->paySlip($id);
        return responder()->success($DATA);
    }

}
