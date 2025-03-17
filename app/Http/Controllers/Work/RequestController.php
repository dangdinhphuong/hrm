<?php

namespace App\Http\Controllers\Work;

use App\Http\Controllers\Controller;
use App\Services\Work\RequestService;
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
        return responder()->success($this->requestService->list($request->all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
