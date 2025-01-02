<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueInDatabase implements Rule
{
    protected $table;
    protected $column;
    protected $connection;
    protected $ignoreId;

    public function __construct($table, $column, $connection = null, $ignoreId = null)
    {
        $this->table = $table;
        $this->column = $column;
        $this->connection = $connection;
        $this->ignoreId = $ignoreId;
    }

    public function passes($attribute, $value)
    {
        // Thực hiện query để kiểm tra unique

        $query = DB::connection($this->connection)
            ->table($this->table)
            ->where($this->column, $value);

        // Bỏ qua kiểm tra đối với ID hiện tại
        if ($this->ignoreId) {

            $query->where('id', '!=', $this->ignoreId);
        }
        return !$query->exists();
    }
    public function message()
    {
        return ':attribute đã có người dùng khác.';
    }
}
