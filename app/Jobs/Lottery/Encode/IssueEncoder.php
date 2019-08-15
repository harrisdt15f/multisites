<?php

namespace App\Jobs\Lottery\Encode;

use App\Models\Game\Lottery\LotteryIssue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class IssueEncoder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $datas;
    /**
     * Create a new job instance.
     *
     * @param $datas
     */
    public function __construct($datas)
    {
        $this->datas = $datas;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $lotteryId = $this->datas['lottery_id'];
        $issueNo = $this->datas['issue'];
        LotteryIssue::calculateEncodedNumber($lotteryId,$issueNo);
    }
}
