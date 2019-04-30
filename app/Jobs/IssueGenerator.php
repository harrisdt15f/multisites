<?php

namespace App\Jobs;

use App\models\LotteriesModel;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class IssueGenerator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $datas;
    /**
     * Create a new job instance.
     *
     * @return void
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
    public function handle()
    {
        $lotteryId = $this->datas['lottery_id'];
        $lottery = LotteriesModel::where('en_name', $lotteryId)->first();
        if (!$lottery) {
            Log::channel('issues')->error('游戏不存在');
        }
        // 生成
        $res = $lottery->genIssue($this->datas['start_time'], $this->datas['end_time'], $this->datas['start_issue']);
        if (!is_array($res) || count($res) === 0) {
            Log::channel('issues')->error('奖期数据无法生成');
        } else {
            // 成功一部分
            $genRes = true;
            foreach ($res as $day => $_r) {
                if ($_r !== true) {
                    $genRes = false;
                    $message = $_r;
                }
            }
            if (!$genRes) {
                Log::channel('issues')->error($message);
            } else {
                Log::channel('issues')->info($res);
            }
        }
    }
}
