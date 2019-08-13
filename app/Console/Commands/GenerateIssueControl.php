<?php

namespace App\Console\Commands;

use App\Events\IssueGenerateEvent;
use App\Models\Admin\SystemConfiguration;
use App\Models\Game\Lottery\LotteryList;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
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
    protected $description = '定时生成第二天的彩票奖期';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $generateIssueTime = SystemConfiguration::getGenerateIssueTime();
        $timeNow = date('H:i');
        if ($generateIssueTime == $timeNow) {
            $lotteries = LotteryList::where('status', 1)->where('en_name', '!=', 'hklhc')->pluck('en_name');
            $data = [
                'start_time' => Carbon::tomorrow(), //生成第二天的奖期
                'end_time' => Carbon::tomorrow(),
                'start_issue' => '',
            ];
            foreach ($lotteries as $lotterie) {
                $data['lottery_id'] = $lotterie;
                event(new IssueGenerateEvent($data));
            }
        }
    }
}
