<?php

namespace App\Constants;

class ModuleGroupPermissions
{
    const MODULE_A = 'MODULE_A';
    const MODULE_B = 'MODULE_B';
    const MODULE_OTHER = 'MODULE_OTHER';

    const GROUP_A = 'GROUP_A';
    const GROUP_B = 'GROUP_B';
    const GROUP_C = 'GROUP_C';

    const MODULES = [
        self::MODULE_A => [
            self::GROUP_A,
            self::GROUP_B
        ],
        self::MODULE_B => [
            self::GROUP_C
        ]
    ];
}
