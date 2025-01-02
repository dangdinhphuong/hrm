<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hrm\CreateContractRequest;
use App\Http\Requests\Hrm\UpdateContractRequest;
use App\Services\Employee\ContractService;

class ContractController extends Controller
{
    protected $contractService;

    public function __construct(ContractService $contractService)
    {
        $this->contractService = $contractService;
    }

    public function  getByEmployeesId ($employeesId){
        return responder()->success($this->contractService->getByEmployeesId($employeesId));
    }
    public function  getMyContract (){
        $user = auth()->user()->load(['employee']);
        return responder()->success($this->contractService->getByEmployeesId($user->employee->id ?? 0));
    }

    public function store(CreateContractRequest $request)
    {
        $data = $request->all();
        $contract = $this->contractService->create($data);
        return responseByStatus($contract["status"], $contract["message"]);
    }

    public function update(UpdateContractRequest $request, $id)
    {
        $data = $request->all();
        $contract = $this->contractService->update($data, $id);
        return responseByStatus($contract["status"], $contract["message"]);
    }


    public function getDetailById($id)
    {
        return responder()->success($this->contractService->getDetailById($id));
    }
}
