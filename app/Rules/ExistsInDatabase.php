<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ExistsInDatabase implements Rule
{
    protected $table;
    protected $column;
    protected $connection;

    public function __construct($table, $column, $connection = null)
    {
        $this->table = $table;
        $this->column = $column;
        $this->connection = $connection;
    }

    public function passes($attribute, $value)
    {
        $query = DB::connection($this->connection)->table($this->table)
            ->where($this->column, $value);

        return $query->exists();
    }

    public function message()
    {
        return 'The :attribute does not exist.';
    }
}
