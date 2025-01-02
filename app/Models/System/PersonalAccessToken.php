<?php

namespace App\Models\System;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    // Sử dụng kết nối 'crm_mysql' cho model PersonalAccessToken
    protected $connection = 'mysql';
}
