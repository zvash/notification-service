<?php

namespace App\Jobs;

use App\Services\Sendable;

class NotifyJob extends Job
{

    /**
     * @var Sendable $sendable
     */
    protected $sendable;

    /**
     * Create a new job instance.
     *
     * @param Sendable $sendable
     */
    public function __construct(Sendable $sendable)
    {
        $this->sendable = $sendable;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sendable->send();
    }
}
