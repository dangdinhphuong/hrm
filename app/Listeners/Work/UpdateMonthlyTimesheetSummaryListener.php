<?php

namespace App\Listeners\Work;

use App\Events\Work\TimesheetUpdated;
use App\Jobs\Work\UpdateMonthlyTimesheetSummaryJob;
use function dispatch;

class UpdateMonthlyTimesheetSummaryListener
{
    /**
     * Xử lý sự kiện khi nó được kích hoạt.
     */
    public function handle(TimesheetUpdated $event)
    {
        dispatch(new UpdateMonthlyTimesheetSummaryJob($event->data));
    }
}

