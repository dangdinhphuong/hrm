<?php

namespace App\Services\System;

use App\Models\System\Role;
use App\Repositories\System\RoleRepository;
use App\Repositories\System\PermissionRepository;
use DB;
use Log;
use Throwable;

class RoleService
{
    private $roleRepository;
    private $permissionRepository;

    public function __construct(
        RoleRepository       $roleRepository,
        PermissionRepository $permissionRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function all($columns = ['*'])
    {
        {
            return $this->roleRepository->all($columns);
        }
    }

    public function list(array $params = [])
    {
        return $this->roleRepository->list($params);
    }

    public function create(array $params)
    {
        try {
            DB::beginTransaction();
            /** @var Role $role */
            $role = $this->roleRepository->create([
                'name' => $params['name'],
                'description' => $params['description'] ?? null
            ]);
            $permissionIds = $this->getPermissionsByCodes($params);

            $role->permissions()->attach($permissionIds ?? []);
            DB::commit();
            return true;
        } catch (Throwable $exception) {
            Log::error($exception);
        }
        DB::rollBack();
        return false;
    }

    public function getPermissionsByCodes($params)
    {
        $permissions = $this->permissionRepository->getPermissionsByCodes($params, ['id'])->toArray();
        return array_column($permissions, 'id');
    }

    public function detail($id)
    {
        $permissions = [];
        $role = $this->roleRepository->findWhereFirst(['id' => $id]);
        if (!empty($role->permissions)) {
            foreach ($role->permissions as $permission) {
                $permissions[] = $permission['code'];
            }
            unset($role->permissions);
        }
        $role->permissions = $permissions;

        return $role;
    }

    public function update($id, array $params)
    {
        try {
            DB::beginTransaction();
            $role = $this->roleRepository->update($params, $id);
            $permissionIds = $this->getPermissionsByCodes($params);
            $role->permissions()->sync($permissionIds);
            DB::commit();
            return true;
        } catch (Throwable $exception) {
            Log::error($exception);
        }
        DB::rollBack();
        return false;
    }

    public function active($id)
    {
        return $this->roleRepository->update(['is_active' => Role::IS_ACTIVE], $id);
    }

    public function deactivate($id)
    {
        return $this->roleRepository->update(['is_active' => Role::NOT_ACTIVE], $id);
    }
}
