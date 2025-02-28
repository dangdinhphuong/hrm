<?php

namespace App\Constants;

class DeliveryNoteStatus
{
    const NEW = 1;
    const PROCESSING = 2;
    const OLD = 3;

    const STATUS = [
        self::NEW => 'Mới',
        self::PROCESSING => 'Đang xử lý',
        self::OLD => 'Cũ'
    ];
}
