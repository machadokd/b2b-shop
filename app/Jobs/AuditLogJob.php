<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AuditLogJob implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $event,
        public readonly array $context,
    ) {}

    public function handle(): void
    {
        Log::channel('audit')->info($this->event, $this->context);
    }
}
