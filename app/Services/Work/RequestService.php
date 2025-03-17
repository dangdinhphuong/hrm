<?php

namespace App\Services\Work;

use App\Repositories\Work\RequestRepository;
use Illuminate\Support\Facades\DB;

class RequestService
{
    protected $requestRepository;

    public function __construct(RequestRepository $requestRepository)
    {
        $this->requestRepository = $requestRepository;
    }

    public function list(array $params = [], $paginate = true, $columns = ['*'])
    {
        return $this->requestRepository->list($params, $paginate, $columns);
    }

    public function createRequest($data)
    {
        $employee = auth()->user()->employee;
        $data['employee_id'] = $employee->id;
        $data['created_by'] = $employee->id;
        try {
            DB::beginTransaction();

            $requestData = $this->requestRepository->create($data);

            DB::commit();
            return ['status' => true, 'message' => '', 'data' => $requestData->toArray()];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateRequest($id, $data)
    {
        return $this->requestRepository->update($id, $data);
    }
}
