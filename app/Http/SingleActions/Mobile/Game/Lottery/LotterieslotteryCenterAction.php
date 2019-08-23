<?php

namespace App\Http\SingleActions\Mobile\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Admin\Homepage\FrontendLotteryNoticeList;
use App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel;
use Illuminate\Http\JsonResponse;

class LotterieslotteryCenterAction
{
    /**
     * 游戏-开奖中心
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll): JsonResponse
    {
        $lotteryNoticeEloq = FrontendAllocatedModel::select('status','show_num')
            ->where('en_name', 'mobile.lottery.notice')
            ->first();
        if ($lotteryNoticeEloq === null) {
            $lotteryNoticeEloq = FrontendAllocatedModel::createMobileLotteryNotice();
        }
        if ($lotteryNoticeEloq->status !== 1) {
            return $contll->msgOut(false, [], '100400');
        }
        $lotterysEloq = FrontendLotteryNoticeList::select('lotteries_id', 'cn_name')
            ->with('specificNewestOpenedIssue:lottery_id,issue,official_code,encode_time', 'lottery:en_name,icon_path')
            ->where('status', 1)
            ->orderBy('sort', 'asc')
            ->limit($lotteryNoticeEloq->show_num)
            ->get();
        $data = [];
        if ($lotterysEloq->count() !==0) {
            $data = $lotterysEloq->toArray();
        }
        return $contll->msgOut(true, $data);
    }
}
