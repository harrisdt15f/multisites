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
        $lotteryNoticeEloq = FrontendAllocatedModel::select('status', 'show_num')->where('en_name', 'mobile.lottery.notice')->first();
        if ($lotteryNoticeEloq === null) {
            $lotteryNoticeEloq = FrontendAllocatedModel::createMobileLotteryNotice();
        }
        if ($lotteryNoticeEloq->status !== 1) {
            return $contll->msgOut(false, [], '100400');
        }
        $lotterysEloq = FrontendLotteryNoticeList::select('lotteries_id', 'icon_path', 'status', 'sort', 'cn_name')->with('oneIssues')->where('status', 1)->orderBy('sort', 'asc')->limit($lotteryNoticeEloq->show_num)->get();
        $data = [];
        foreach ($lotterysEloq as $lotteryEloq) {
            $issue = $lotteryEloq->oneIssues->issue ?? null;
            $officialCode = $lotteryEloq->oneIssues->official_code ?? null;
            $encodeTime = $lotteryEloq->oneIssues->encode_time ?? null;
            $data[] = [
                'cn_name' => $lotteryEloq->cn_name,
                'lotteries_id' => $lotteryEloq->lotteries_id,
                'icon' => $lotteryEloq->icon_path,
                'issue' => $issue,
                'official_code' => $officialCode,
                'encode_time' => $encodeTime,
            ];
        }
        return $contll->msgOut(true, $data);
    }
}
