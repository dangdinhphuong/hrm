<?php

namespace App\Services\Employee;

use App\Repositories\Employee\SalaryRepository;
use Illuminate\Support\Facades\DB;
use App\Services\Work\MonthlyTimesheetSummaryService;
use Carbon\Carbon;
use Log;

class SalaryService
{
    protected $salaryRepository;

    public function __construct(SalaryRepository $salaryRepository , MonthlyTimesheetSummaryService $monthlyTimesheetSummaryService)
    {

        $this->salaryRepository = $salaryRepository;
        $this->monthlyTimesheetSummaryService = $monthlyTimesheetSummaryService;
    }

    public function list(array $params = [], $paginate = true, $columns = ['*'])
    {
        return $this->salaryRepository->list($params, $paginate, $columns);
    }

    public function update($id, $data)
    {
        DB::beginTransaction();
        $status = false;

        try {
            $salary = $this->salaryRepository->updateOrCreate(
                ['employee_id' => (int)$id],  // Điều kiện update
                ['salaries' => json_encode($data)] // Dữ liệu update
            );

            $status = true;
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack(); // Rollback nếu có lỗi xảy ra
            Log::error('[' . __METHOD__ . '] :' . $e->getMessage() . '-- line: ' . $e->getLine());
        }
        return ['status' => $status, 'message' => ''];
    }

    public function calculateWorkdays(int $month, int $year): float
    {
        $totalWorkdays = 0;
        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day);
            $weekday = $date->dayOfWeek;

            if ($weekday >= 1 && $weekday <= 5) {
                $totalWorkdays += 1; // Thứ 2 - Thứ 6
            } elseif ($weekday === 6) {
                $totalWorkdays += 0.5; // Thứ 7
            }
        }

        return $totalWorkdays;
    }
    public function paySlip($id)
    {
        $mergedSalary = [];
        $employeeSalary =  $this->list(['employee_id' => [$id]], false)->first()??[];

        $employeeSalary['departments'] = $employeeSalary->employee->departments->first()->toArray();
        $employeeSalary['position'] = $employeeSalary->employee->position->first()->toArray();
        $employeeSalary['banks'] = $employeeSalary->employee->bank;

        if (!empty($employeeSalary)) {
            $employeeSalaryArray = $employeeSalary->toArray(); // Chuyển toàn bộ thành array

            // Đảm bảo salaries là array trước khi merge
            $mergedSalary = array_merge(json_decode($employeeSalaryArray['salaries'], true), $employeeSalaryArray);

            unset($employeeSalaryArray['salaries']);
        }
        [$lastMonth, $lastYear] = $this->getPreviousMonthAndYear(now()->month, now()->year);
        $lastMonthlyTimesheet =  $this->monthlyTimesheetSummaryService->findFirst(array_merge([
            'year' => $lastYear,
            'month' => $lastMonth,
            'employee_id' => $id
        ]));


        $payslipData = [
            "employee_code" => $mergedSalary['employee']["code"],
            "employee_name" =>$mergedSalary['employee']["first_name"] .' '.$mergedSalary['employee']["last_name"],
            "department" => $mergedSalary['departments']["name"],
            "position" => $mergedSalary['position']["name"],

            "work_days_standard" => $work_days_standard = $this->calculateWorkdays($lastMonth, $lastYear), // Số ngày công chuẩn trong tháng
            "work_days_total" => $work_days_standard, // Tổng số ngày làm việc thực tế
            "work_days_holiday" => $work_days_holiday = $lastMonthlyTimesheet->holiday_days ?? 0, // Số ngày làm việc vào ngày lễ
            "work_days_normal" => $work_days_standard - $work_days_holiday, // Số ngày làm việc bình thường

            "salary_basic" => (float)$mergedSalary['basic_salary'], // Lương cơ bản
            "salary_kpi" => (float)$mergedSalary['kpi_salary'], // Lương KPI (theo hiệu suất)
            "allowance_lunch" => (float)$mergedSalary['allowance_lunch'], // Phụ cấp ăn trưa
            "allowance_other" => (float)$mergedSalary['allowance_salary'], // Các khoản phụ cấp khác
            "total_salary" => $total_salary = (float)$mergedSalary['basic_salary'] + $mergedSalary['kpi_salary'] +$mergedSalary['allowance_lunch']+$mergedSalary['allowance_salary'] , // Tổng lương (chưa bao gồm thu nhập khác)

            "income_basic" => $total_salary, // Thu nhập từ lương cơ bản
            "income_overtime" => $income_overtime = ($lastMonthlyTimesheet->overtime_hours * 5000), // Thu nhập từ làm thêm giờ
            "income_travel" => $income_travel =(float)$mergedSalary['income_travel'], // Thu nhập từ công tác phí
            "income_bonus" => $bonus  = (float)$mergedSalary['bonus'], // Thưởng thêm
            "income_total" => $income_total = $total_salary + $income_overtime + $income_travel + $bonus , // Tổng thu nhập


            "deduction_insurance" => -(float)$mergedSalary['deduction_insurance'], // Khoản trừ bảo hiểm
            "deduction_tax" => -(float)$mergedSalary['deduction_tax'], // Khoản trừ thuế thu nhập cá nhân
            "deduction_leave" => -(float)$mergedSalary['deduction_dependents'], // Khoản trừ do nghỉ không lương
            "deduction_dependents" => (int)$mergedSalary['deduction_dependents'] ?? 0, // Số người phụ thuộc được giảm trừ thuế
            "deduction_total" => $deductionTotal = ((float)$mergedSalary['deduction_insurance']+(float)$mergedSalary['deduction_tax']+(float)$mergedSalary['deduction_dependents']), // Tổng các khoản khấu trừ

            "net_income" => $income_total + $deductionTotal, // Thu nhập thực lĩnh sau khi trừ các khoản khấu trừ

            "bank_account" => $mergedSalary['employee']["bank_account_number"], // Số tài khoản ngân hàng
            "bank_holder" => $mergedSalary['employee']["bank_account_name"], // Tên chủ tài khoản
            "bank_name" => $mergedSalary['banks']["name"] // Tên ngân hàng

        ];
       return $payslipData;
        //dd($mergedSalary,$payslipData,$lastMonthlyTimesheet, $lastMonthlyTimesheet->overtime_hours);
       // return $this->salaryRepository->list($params, $paginate, $columns);
    }
    /**
     * Lấy tháng và năm trước đó
     */
    private function getPreviousMonthAndYear($month, $year)
    {
        if ($month == 1) {
            return [12, $year - 1];
        }
        return [$month - 1, $year];
    }
}
