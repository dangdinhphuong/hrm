<?php

namespace App\Http\Controllers\System;

use App\Constants\ModuleGroupPermissions;
use App\Http\Controllers\Controller;
use App\Services\System\RoleService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function all()
    {
        return responder()->success($this->roleService->all(['id', 'name']));
    }

    public function index(Request $request)
    {
        return responder()->success($this->roleService->list($request->all()));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles',
            'permissions' => 'array'
        ]);
        $role = $this->roleService->create($request->only(['name','description', 'permissions']));
        if ($role) {
            return responder()->success();
        }
        return responder()->fail();
    }

    public function show($id)
    {
        return responder()->success($this->roleService->detail($id));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('roles')->ignore($id),
            ],
            'permissions' => 'array'
        ]);

        $role = $this->roleService->update($id, $request->only(['name','description', 'permissions']));
        if ($role) {
            return responder()->success();
        }
        return responder()->fail();
    }

    public function getModuleGroupPermission()
    {
        return responder()->success(ModuleGroupPermissions::MODULES);
    }

    public function active($id)
    {
        $this->roleService->active($id);
        return responder()->success();
    }

    public function deactivate($id)
    {
        $this->roleService->deactivate($id);
        return responder()->success();
    }
}
