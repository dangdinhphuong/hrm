<?php

namespace App\Models\System;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends BaseModel
{
    use HasFactory;
    protected $connection = 'mysql'; // Chỉ định kết nối cơ sở dữ liệu
    protected $table = 'roles';
    const NOT_ACTIVE = 0;
    const IS_ACTIVE = 1;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_active'
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id')
            ->withTimestamps(); // Nếu bạn sử dụng timestamps trong bảng trung gian
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }
}
