<?php

namespace App\Services\System;

use App\Repositories\System\PermissionRepository;

class PermissionService
{
    private $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function list(array $params = [])
    {
        $rootPermissions = $this->permissionRepository->getAll();
        $result = [];

        foreach ($rootPermissions as $parent) {
            // Initialize an array for the parent's children
            $result[$parent->code] = [];

            // Loop through children and push 'code' as key and 'name' as value to the parent's array
            foreach ($parent->children as $child) {
                $result[$parent->code][$child->code] = $child->name;
            }
        }

        return $result;
    }
}
