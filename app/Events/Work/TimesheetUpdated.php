<?php

namespace App\Events\Work;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TimesheetUpdated
{
    use Dispatchable, SerializesModels;

    public array $data;

    /**
     * Create a new event instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }
}
