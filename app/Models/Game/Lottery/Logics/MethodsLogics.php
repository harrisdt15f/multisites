<?php

namespace App\Models\Game\Lottery\Logics;

use App\Models\Game\Lottery\LotteryMethodsNumberButtonRule;

/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 5/24/2019
 * Time: 5:40 PM
 */
trait MethodsLogics
{
    public static function getMethodConfig($lotterySign)
    {
        return self::where('lottery_id', $lotterySign)->where('status', 1)->where('show', 1)->get();
    }

    /**
     * 获取玩法验证规则数据
     * @param $methodId
     * @return array
     */
    public static function getMethodValidationData($methodId)
    {
        $validationEloq = self::where('method_id',$methodId)->with(['methodLayout:validation_id,rule_id,display_code'])->get();
        // 获取所有的投注按钮
        $rule = LotteryMethodsNumberButtonRule::select('id','type','value')->get()->toArray();
        $rules = array_column($rule,null,'id');
        // 获取所有投注下标名称（display_name）
        $name = LotteryMethodsLayoutDisplay::select('display_name','display_code')->get()->toArray();
        $names = array_column($name, null, 'display_code');
        // 循环取出对应值
        foreach ($validationEloq as $item) {
            if (!empty($item->button_id)) {
                $item->button_id = $rules[$item->button_id]['value'];
            }
            foreach ($item->methodLayout as $_item) {
                $_item->rule_id = $rules[$_item->rule_id]['value'];
                $_item->display_code = $names[$_item->display_code]['display_name'];
            }
        }
        return $validationEloq;
    }
}
