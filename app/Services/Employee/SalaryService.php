<?php

namespace App\Services\Employee;

use App\Repositories\Employee\SalaryRepository;
use Illuminate\Support\Facades\DB;
use Log;

class SalaryService
{
    protected $salaryRepository;

    public function __construct(SalaryRepository $salaryRepository)
    {
        $this->salaryRepository = $salaryRepository;
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
}
