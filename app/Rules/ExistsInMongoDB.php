<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ExistsInMongoDB implements Rule
{
    protected string $collection;
    protected string $field;

    public function __construct(string $collection, string $field)
    {
        $this->collection = $collection;
        $this->field = $field;
    }

    public function passes($attribute, $value)
    {
        return DB::connection('mongodb')->collection($this->collection)->where($this->field, $value)->exists();
    }

    public function message()
    {
        return 'The selected :attribute is invalid.';
    }
}
