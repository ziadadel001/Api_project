<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class LogRegistration implements ShouldQueue
{
    use Queueable;

    public string $name;
    /**
     * Create a new job instance.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('A  user ' . $this->name . ' has registered.');
    }
}
