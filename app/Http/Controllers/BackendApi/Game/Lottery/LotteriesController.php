<?php

namespace App\Http\Controllers\BackendApi\Game\Lottery;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Game\Lottery\LotteriesAddRequest;
use App\Http\Requests\Backend\Game\Lottery\LotteriesEditMethodRequest;
use App\Http\Requests\Backend\Game\Lottery\LotteriesGenerateIssueRequest;
use App\Http\Requests\Backend\Game\Lottery\LotteriesInputCodeRequest;
use App\Http\Requests\Backend\Game\Lottery\LotteriesListsRequest;
use App\Http\Requests\Backend\Game\Lottery\LotteriesLotteriesSwitchRequest;
use App\Http\Requests\Backend\Game\Lottery\LotteriesMethodGroupSwitchRequest;
use App\Http\Requests\Backend\Game\Lottery\LotteriesMethodRowSwitchRequest;
use App\Http\Requests\Backend\Game\Lottery\LotteriesMethodSwitchRequest;
use App\Http\SingleActions\Backend\Game\Lottery\LotteriesAddAction;
use App\Http\SingleActions\Backend\Game\Lottery\LotteriesEditMethodAction;
use App\Http\SingleActions\Backend\Game\Lottery\LotteriesGenerateIssueAction;
use App\Http\SingleActions\Backend\Game\Lottery\LotteriesInputCodeAction;
use App\Http\SingleActions\Backend\Game\Lottery\LotteriesIssueListsAction;
use App\Http\SingleActions\Backend\Game\Lottery\LotteriesListsAction;
use App\Http\SingleActions\Backend\Game\Lottery\LotteriesLotteriesCodeLengthAction;
use App\Http\SingleActions\Backend\Game\Lottery\LotteriesLotteriesSwitchAction;
use App\Http\SingleActions\Backend\Game\Lottery\LotteriesMethodGroupSwitchAction;
use App\Http\SingleActions\Backend\Game\Lottery\LotteriesMethodListsAction;
use App\Http\SingleActions\Backend\Game\Lottery\LotteriesMethodRowSwitchAction;
use App\Http\SingleActions\Backend\Game\Lottery\LotteriesMethodSwitchAction;
use App\Http\SingleActions\Backend\Game\Lottery\LotteriesSeriesListsAction;
use App\Lib\Common\CacheRelated;
use Illuminate\Http\JsonResponse;

class LotteriesController extends BackEndApiMainController
{
    public $lotteryIssueEloq = 'Game\Lottery\LotteryIssue'; //issueLists

    /**
     * 获取系列接口
     * @param  LotteriesSeriesListsAction $action
     * @return JsonResponse
     */
    public function seriesLists(LotteriesSeriesListsAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 获取彩种接口
     * @param  LotteriesListsRequest $request
     * @param  LotteriesListsAction  $action
     * @return JsonResponse
     */
    public function lists(LotteriesListsRequest $request, LotteriesListsAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 获取玩法结果。
     * @param  LotteriesMethodListsAction  $action
     * @return JsonResponse
     */
    public function methodLists(LotteriesMethodListsAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 获取奖期列表接口。
     * @param  LotteriesIssueListsAction  $action
     * @return JsonResponse
     */
    public function issueLists(LotteriesIssueListsAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 生成奖期
     * @param  LotteriesGenerateIssueRequest $request
     * @param  LotteriesGenerateIssueAction  $action
     * @return JsonResponse
     */
    public function generateIssue(LotteriesGenerateIssueRequest $request, LotteriesGenerateIssueAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 彩种开关
     * @param  LotteriesLotteriesSwitchRequest $request
     * @param  LotteriesLotteriesSwitchAction  $action
     * @return JsonResponse
     */
    public function lotteriesSwitch(LotteriesLotteriesSwitchRequest $request, LotteriesLotteriesSwitchAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 玩法组开关
     * @param  LotteriesMethodGroupSwitchRequest $request
     * @param  LotteriesMethodGroupSwitchAction  $action
     * @return JsonResponse
     */
    public function methodGroupSwitch(LotteriesMethodGroupSwitchRequest $request, LotteriesMethodGroupSwitchAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 玩法行开关
     * @param  LotteriesMethodRowSwitchRequest $request
     * @param  LotteriesMethodRowSwitchAction  $action
     * @return JsonResponse
     */
    public function methodRowSwitch(LotteriesMethodRowSwitchRequest $request, LotteriesMethodRowSwitchAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 玩法开关
     * @param  LotteriesMethodSwitchRequest $request
     * @param  LotteriesMethodSwitchAction  $action
     * @return JsonResponse
     */
    public function methodSwitch(LotteriesMethodSwitchRequest $request, LotteriesMethodSwitchAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 清理玩法缓存
     * @return void
     */
    public function clearMethodCache(): void
    {
        $cacheRelated = new CacheRelated();
        $cacheRelated->delete('play_method_list');
    }

    /**
     * 编辑玩法
     * @param  LotteriesEditMethodRequest $request
     * @param  LotteriesEditMethodAction  $action
     * @return JsonResponse
     */
    public function editMethod(LotteriesEditMethodRequest $request, LotteriesEditMethodAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 奖期录号
     * @param  LotteriesInputCodeRequest $request
     * @param  LotteriesInputCodeAction  $action
     * @return JsonResponse
     */
    public function inputCode(LotteriesInputCodeRequest $request, LotteriesInputCodeAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 奖期录号规则
     * @param  LotteriesLotteriesCodeLengthAction  $action
     * @return JsonResponse
     */
    public function lotteriesCodeLength(LotteriesLotteriesCodeLengthAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    //添加彩种
    public function add(LotteriesAddRequest $request, LotteriesAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
