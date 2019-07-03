<?php

/**
 * @Author: LingPh
 * @Date:   2019-05-31 13:46:32
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-07 16:09:22
 */

namespace App\Console\Commands;

use App\Jobs\Lottery\Encode\IssueEncoder;
use App\Models\Game\Lottery\LotteryIssue;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ZxyfcInputCodeControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ZxyfcInputCode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '中兴1分彩自动开奖';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info('中兴一分彩开奖');
        $lotteryIssue = LotteryIssue::where([
            ['lottery_id', 'zx1fc'],
            ['end_time', '<', time()],
        ])->orderBy('end_time', 'desc')->first();
        if ($lotteryIssue !== null) {
            $code = mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9);
            $lotteryIssue->status_encode = LotteryIssue::ENCODED;
            $lotteryIssue->encode_time = time();
            $lotteryIssue->official_code = $code;
            if ($lotteryIssue->save()) {
                dispatch(new IssueEncoder($lotteryIssue->toArray()))->onQueue('issues');
            }
        }

    }
}
