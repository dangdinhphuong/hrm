<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use DateTimeInterface;

class BaseModel extends Model
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function boot()
    {
        parent::boot();

        // Common function for assigning updated_by or created_by when applicable
        $assignUserColumns = function ($model, $isCreating = false) {
            if (Auth::check()) {
                $table = $model->getTable();
                $userId = Auth::id();

                if (Schema::connection('mysql')->hasColumn($table, 'updated_by')) {
                    $model->updated_by = $userId;
                }
                if ($isCreating && Schema::connection('mysql')->hasColumn($table, 'created_by')) {
                    $model->created_by = $userId;
                }
            }
        };

        static::creating(fn($model) => $assignUserColumns($model, true));
        static::updating(fn($model) => $assignUserColumns($model));
        static::saving(fn($model) => $assignUserColumns($model, !$model->exists));
    }
}
