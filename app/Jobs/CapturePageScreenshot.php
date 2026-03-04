<?php

namespace App\Jobs;

use App\Services\ScreenshotService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CapturePageScreenshot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pageId;

    /**
     * Create a new job instance.
     *
     * @param int $pageId
     * @return void
     */
    public function __construct($pageId)
    {
        $this->pageId = $pageId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ScreenshotService $screenshotService)
    {
        $screenshotService->capturePageScreenshot($this->pageId);
    }

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 30;
}
