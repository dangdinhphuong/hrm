<?php

namespace App\Jobs\Work;

use App\Services\Work\MonthlyTimesheetSummaryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateMonthlyTimesheetSummaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }


    /**
     * Execute the job.
     */
    public function handle(MonthlyTimesheetSummaryService $monthlyTimesheetSummaryService): void
    {
        $monthlyTimesheetSummaryService->updateOrCreate($this->data);
    }
}
