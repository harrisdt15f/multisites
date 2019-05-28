<?php

namespace App\Jobs;

use App\Models\Game\Lottery\IssueModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class IssueInserter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $datas;

    /**
     * Create a new job instance.
     *
     * @param array $datas
     */
    public function __construct(array $datas)
    {
        $this->datas = $datas;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            IssueModel::insert($this->datas);
            $message = 'Finished >>>>' . json_encode($this->datas, JSON_UNESCAPED_UNICODE) . "\n";
            Log::channel('issues')->info($message);
        } catch (\Exception $e) {
            Log::channel('issues')->error($e->getMessage());
        }
    }
}
