<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed name
 * @property mixed price
 */
class Group extends Model
{
    use SoftDeletes;
    protected $table = 'group';

    protected $fillable = [
        'id',
        'name',
        'group_type',
        'price',
        'business_unit_type',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];
}
