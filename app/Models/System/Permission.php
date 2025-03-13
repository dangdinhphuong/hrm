<?php

namespace App\Models\System;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends BaseModel
{
    use HasFactory;
    protected $connection = 'mysql'; // Chỉ định kết nối cơ sở dữ liệu
    protected $table = 'permissions';
    const NOT_ACTIVE = 0;
    const IS_ACTIVE = 1;

    protected $fillable = [
        'name',
        'code',
        'description',
        'parent_id',
        'created_by',
        'updated_by',
    ];

    public function children()
    {
        return $this->hasMany(Permission::class, 'parent_id');
    }

    // Define the relationship to get the parent permission
    public function parent()
    {
        return $this->belongsTo(Permission::class, 'parent_id');
    }

    // Scope to query only root permissions (where parent_id is null)
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }
}
