<?php

namespace App\Constants;

class Shipping
{
    const OTHER = 1;
    const VIETTEL_POST = 2;
    const EMS = 3;
    const DHL = 4;
    const SUPER_SHIP = 5;

    const SHIPPING_CARRIER = [
        self::OTHER => 'Khác',
        self::VIETTEL_POST => 'Viettel Post',
        self::EMS => 'EMS',
        self::DHL => 'DHL',
        self::SUPER_SHIP => 'Supership',
    ];
}
