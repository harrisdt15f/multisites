<?php

/**
 * @Author: LingPh
 * @Date:   2019-05-31 13:46:32
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-06 11:52:02
 */

namespace App\Console\Commands;

use App\Events\IssueGenerateEvent;
use App\Models\Game\Lottery\LotteryList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateIssueControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GenerateIssue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时生成彩票奖期';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info('开始定时生成彩票奖期');
        $lotteries = LotteryList::where('status', 1)->pluck('en_name');
        $data = [
            'start_time' => date('Y-m-d'),
            'end_time' => date('Y-m-d'),
            'start_issue' => '',
        ];
        foreach ($lotteries as $lotterie) {
            $data['lottery_id'] = $lotterie;
            event(new IssueGenerateEvent($data));
        }
    }
}
