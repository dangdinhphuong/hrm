<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hrm\CreateEmployeeRequest;
use App\Http\Requests\Hrm\UpdateEmployeeRequest;
use App\Rules\ExistsInMongoDB;
use App\Services\Employee\EmployeesService;
use App\Services\Employee\VectorService;
use Illuminate\Http\Request;
use App\Services\Attachment\FileService;

class VectorController extends Controller
{
    protected $employeesService;

    public function __construct(
        VectorService    $vectorService,
        EmployeesService $employeesService,
        FileService      $fileService)
    {
        $this->employeesService = $employeesService;
        $this->fileService = $fileService;
        $this->vectorService = $vectorService;
    }

    public function getDetailByUsername(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', new ExistsInMongoDB('vector', 'username')]
        ]);

        $vector = $this->vectorService->getDetailByUsername($request->username ?? '', ['username',
            'employee_id',
            'face_vector',
            'employee.first_name',
            'employee.last_name',
            'employee.personal_email',
            'employee.phone',
            'employee.current_address',
            ]);
        return responder()->success($vector);
    }
}
