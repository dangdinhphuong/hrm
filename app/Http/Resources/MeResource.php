<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'roles' => $this->getRoles(),
            'permissions' => $this->getPermissions(),
            'employeeId' => $this->employee->id ?? 0,
            'avatar' => $this->employee->avatarAttachments[0]->attachment->file_path ?? "",
        ];
    }
}
