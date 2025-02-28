<?php

namespace App\Traits\Permissions;

use App\Models\System\Permission;
use App\Models\System\Role;

trait HasPermissionsTrait
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')->where('is_active', Role::IS_ACTIVE);
    }

    public function hasRole($role)
    {
        $roles = $this->getRoles();
        return array_search($role, $roles) === false ? false : true;
    }

    public function hasPermission($permission)
    {
        $permissions = $this->getPermissions();
        return array_search($permission, $permissions) === false ? false : true;
    }

    public function getRoles()
    {
        $roles = [];
        $this->loadMissing('roles');
        foreach ($this->roles as $role) {
            $roles[] = $role['name'];
        }
        return $roles;
    }

    public function getPermissions()
    {
        $permissions = [];
        $this->loadMissing('roles.permissions');
        foreach ($this->roles as $role) {
            foreach ($role->permissions as $permission) {
                if ($permission['is_active'] === Permission::IS_ACTIVE && !empty($permission['parent_id'])) {
                    $permissions[$permission['code']] = $permission['code'];
                }
            }
        }

        return array_values($permissions);
    }
}
