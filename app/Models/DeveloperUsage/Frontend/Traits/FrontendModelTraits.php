<?php

namespace App\Models\DeveloperUsage\Frontend\Traits;

/**
 * @Author: LingPh
 * @Date:   2019-05-29 17:38:37
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-18 20:23:04
 */
trait FrontendModelTraits
{

    /**
     * @param  int    $type
     * @return array
     */
    public function allFrontendModel($type): array
    {
        if ($type == 2) {
            $typeArr = [1, 2];
        } elseif ($type == 3) {
            $typeArr = [1, 3];
        }
        $parentFrontendModel = self::ParentModel($typeArr);
        $frontendModelList = [];
        foreach ($parentFrontendModel as $id => $frontendModel) {
            $frontendModelList[$id] = $frontendModel;
            $frontendModelList[$id]['childs'] = $frontendModel->childs;
            foreach ($frontendModelList[$id]['childs'] as $grandsonId => $grandsonFrontendModel) {
                $frontendModelList[$id]['childs'][$grandsonId] = $grandsonFrontendModel;
                $frontendModelList[$id]['childs'][$grandsonId]['childs'] = $grandsonFrontendModel->childs;
            }
        }
        return $frontendModelList;
    }

    /**
     * @param  array    $typeArr
     * @return mixed
     */
    public function ParentModel($typeArr)
    {
        return self::where('level', 1)->whereIn('type', $typeArr)->get();
    }

    /**
     * 获取一个模块信息
     * @param  string $en_name 模块英文名
     * @return mixed
     */
    public function getModelEloq($en_name)
    {
        return self::where('en_name', $en_name)->first();
    }

    //生成 开奖公告 前台模块
    public static function createLotteryNotice()
    {
        $parentEloq = self::where('en_name', 'page.model')->first();
        if ($parentEloq === null) {
            $parentEloq = self::createPageModel();
        }
        $frontendModelEloq = new self;
        $addData = [
            'label' => '开奖公告',
            'en_name' => 'lottery.notice',
            'pid' => $parentEloq->id,
            'type' => 1,
            'show_num' => 4,
            'status' => 1,
            'level' => ++$parentEloq->level,
            'is_homepage_display' => 1,
        ];
        $frontendModelEloq->fill($addData);
        $frontendModelEloq->save();
        return $frontendModelEloq;
    }

    //生成 手机端开奖公告 前台模块
    public static function createMobileLotteryNotice()
    {
        $parentEloq = self::where('en_name', 'page.model')->first();
        if ($parentEloq === null) {
            $parentEloq = self::createPageModel();
        }
        $frontendModelEloq = new self;
        $addData = [
            'label' => '手机端开奖公告',
            'en_name' => 'mobile.lottery.notice',
            'pid' => $parentEloq->id,
            'type' => 1,
            'show_num' => 4,
            'status' => 1,
            'level' => ++$parentEloq->level,
            'is_homepage_display' => 1,
        ];
        $frontendModelEloq->fill($addData);
        $frontendModelEloq->save();
        return $frontendModelEloq;
    }

    //生成 主题板块 前台模块
    public static function createPageModel()
    {
        $parentEloq = self::where('en_name', 'homepage')->first();
        if ($parentEloq === null) {
            $parentEloq = self::createHomepage();
        }
        $frontendModelEloq = new self;
        $addData = [
            'label' => '主题板块',
            'en_name' => 'page.model',
            'pid' => $parentEloq->id,
            'type' => 1,
            'status' => 1,
            'level' => ++$parentEloq->level,
        ];
        $frontendModelEloq->fill($addData);
        $frontendModelEloq->save();
        return $frontendModelEloq;
    }

    //生成 首页 前台模块
    public static function createHomepage()
    {
        $frontendModelEloq = new self;
        $addData = [
            'label' => '首页',
            'en_name' => 'homepage',
            'pid' => 0,
            'type' => 1,
            'status' => 1,
            'level' => 1,
        ];
        $frontendModelEloq->fill($addData);
        $frontendModelEloq->save();
        return $frontendModelEloq;
    }

    //生成 app端热门彩票 前台模块
    public static function createMobilePopularLotteries()
    {
        $parentEloq = self::where('en_name', 'page.model')->first();
        if ($parentEloq === null) {
            $parentEloq = self::createPageModel();
        }
        $frontendModelEloq = new self;
        $addData = [
            'label' => 'app端热门彩种一',
            'en_name' => 'mobile.popular.lotteries.one',
            'pid' => $parentEloq->id,
            'type' => 1,
            'show_num' => 10,
            'status' => 1,
            'level' => ++$parentEloq->level,
            'is_homepage_display' => 1,
        ];
        $frontendModelEloq->fill($addData);
        $frontendModelEloq->save();
        return $frontendModelEloq;
    }

    //生成 web端热门彩票 前台模块
    public static function createPopularLotteries()
    {
        $parentEloq = self::where('en_name', 'page.model')->first();
        if ($parentEloq === null) {
            $parentEloq = self::createPageModel();
        }
        $frontendModelEloq = new self;
        $addData = [
            'label' => 'web端热门彩种一',
            'en_name' => 'popularLotteries.one',
            'pid' => $parentEloq->id,
            'type' => 1,
            'show_num' => 10,
            'status' => 1,
            'level' => ++$parentEloq->level,
            'is_homepage_display' => 1,
        ];
        $frontendModelEloq->fill($addData);
        $frontendModelEloq->save();
        return $frontendModelEloq;
    }
}
