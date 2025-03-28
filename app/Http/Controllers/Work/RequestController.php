<?php

namespace App\Http\Controllers\Work;

use App\Http\Controllers\Controller;
use App\Services\Work\RequestService;
use App\Http\Requests\Hrm\CreateLeaveRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    protected $requestService;

    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $params = $request->except(['extra_param']); // Nếu cần loại bỏ một số params không cần thiết
        $paginate = $request->has('page'); // Kiểm tra có tham số 'page' hay không

        return responder()->success($this->requestService->list($params, paginate: $paginate));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateLeaveRequest $request)
    {
        $data = $request->all();
        $requestData = $this->requestService->createRequest($data);
        return responseByStatus($requestData["status"], $requestData["message"], $requestData["data"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $requestData = $this->requestService->updateRequest($id, $data);
        return responseByStatus($requestData["status"], $requestData["message"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
