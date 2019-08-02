<?php

namespace App\Console\Commands;

use App\Events\IssueGenerateEvent;
use App\Models\Admin\SystemConfiguration;
use App\Models\Game\Lottery\LotteryList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

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
        if (Cache::has('generateIssueTime')) {
            $generateIssueTime = Cache::get('generateIssueTime');
        } else {
            $systemConfiguration = new SystemConfiguration();
            $generateIssueTime = $systemConfiguration->getConfigValue('generate_issue_time');
            Cache::forever('generateIssueTime', $generateIssueTime);
        }
        $timeNow = date('H:i');
        if ($generateIssueTime == $timeNow) {
            $lotteries = LotteryList::where('status', 1)->where('en_name', '!=', 'hklhc')->pluck('en_name');
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
}
