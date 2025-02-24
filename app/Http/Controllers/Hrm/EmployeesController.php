<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hrm\CreateEmployeeRequest;
use App\Http\Requests\Hrm\UpdateEmployeeRequest;
use App\Services\Employee\EmployeesService;
use Illuminate\Http\Request;
use App\Services\Attachment\FileService;

class EmployeesController extends Controller
{
    protected $employeesService;

    public function __construct(
        EmployeesService $employeesService,
        FileService      $fileService)
    {
        $this->employeesService = $employeesService;
        $this->fileService = $fileService;
    }

    public function index(Request $request)
    {

        return responder()->success($this->employeesService->list($request->all()));
    }

    public function findEmployee(Request $request)
    {
        $employees = $this->employeesService->list($request->all(), columns: ['id', 'code', 'personal_email']);

        foreach ($employees as $employee){
            $employee->setRelation('vector', $employee->vector);
        }

        return responder()->success($employees);
    }


    public function getTimesheets(Request $request)
    {
        return responder()->success($this->employeesService->getTimesheets($request->all()));
    }

    public function store(CreateEmployeeRequest $request)
    {
        $data = $request->all();
        $employees = $this->employeesService->create($data);

        return responseByStatus($employees["status"], $employees["message"]);
    }

    public function update(UpdateEmployeeRequest $request, $id)
    {
        $data = $request->all();
        $employees = $this->employeesService->update($id, $data);

        return responseByStatus($employees["status"], $employees["message"]);
    }

    public function getDetailById($id)
    {
        return responder()->success($this->employeesService->getDetailById($id));
    }

    public function getMyDetail()
    {
        $user = auth()->user()->load(['employee']);

        return responder()->success($this->employeesService->getDetailById($user->employee->id ?? 0));
    }

    public function uploadAvatar(Request $request, $employee)
    {
        // Validate the request
        $request->validate([
            'avatar' => 'required|string', // Ensures image data is provided and is a string
        ]);

        $employeeAvatar = $this->employeesService->uploadAvatar($request->avatar, $employee);

        return responseByStatus($employeeAvatar["status"], $employeeAvatar["message"], $employeeAvatar['data']);
    }

    public function avatar(Request $request, $employee)
    {
        $employeeAvatar = $this->employeesService->getAvatar($employee);

        return responseByStatus($employeeAvatar["status"], $employeeAvatar["message"], $employeeAvatar['data']);
    }
}
