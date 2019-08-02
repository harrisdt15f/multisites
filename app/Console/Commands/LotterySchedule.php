<?php

namespace App\Console\Commands;

use App\Jobs\Lottery\Encode\IssueEncoder;
use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotterySerie;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class LotterySchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'LotterySchedule {lottery_sign}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自开彩种自动开奖 argument --> lottery_sign';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lotterySign = $this->argument('lottery_sign');
        if ($lotterySign) {
            $lotteryIssueEloq = LotteryIssue::where([
                ['lottery_id', $lotterySign],
                ['end_time', '<', time()],
            ])->with('lottery')->orderBy('end_time', 'desc')->first();
            $seriesList = LotterySerie::getList();
            $serieArr = $seriesList[$lotteryIssueEloq->lottery->series_id]; //当前彩种的系列Arr
            $splitter = $serieArr['encode_splitter']; //该彩种分割开奖号码的方式
            //#######################
            if ($lotteryIssueEloq !== null) {
                $openCodeArr = []; //开奖号码
                $codeLength = $lotteryIssueEloq->lottery->code_length; //开奖号码的长度
                $validCodeArr = explode(',', $lotteryIssueEloq->lottery->valid_code); //合法开奖号码arr
                $isCanRepeat = $lotteryIssueEloq->lottery->lottery_type; //开奖号码是否可以重复 ？ 1可重复 2不可重复
                if ($isCanRepeat === 1) {
                    for ($length = 0; $length < $codeLength; $length++) {
                        $openCodeArr[] = Arr::random($validCodeArr);
                    }
                } elseif ($isCanRepeat === 2) {
                    $openCodeArr = Arr::random($validCodeArr, $codeLength);
                } else {
                    return;
                }
                $openCodeStr = implode($splitter, $openCodeArr); //开奖号码string
                $lotteryIssueEloq->recordEncodeNumber($openCodeStr); //开始录号
            }
        }
    }
}
