<?php

namespace App\Models\Game\Lottery\Logics;

use App\Models\Game\Lottery\LotteryMethodsStandard;

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

    public function getMethodRuleDatas(): array
    {
        $methodDescribe = $methodExample = $methodHelper = $method_button = null;
        if ($this->numberButtonRule()->exists()) {
            $numberButtonRule = $this->numberButtonRule;
            $method_button = $numberButtonRule->value;
        }
        if ($this->methoudValidationRule()->exists()) {
            $methodValidationRule = $this->methoudValidationRule;
            $methodDescribe = $methodValidationRule->describe;
            $methodExample = $methodValidationRule->example;
            $methodHelper = $methodValidationRule->helper;
        }
        $methoudLayoutEloq = $this->methodLayout()->get();
        $result[] = [
            'method_name' => $this->method_name,
            'method_id' => $this->method_id,
            'method_group' => $this->method_group,
            'method_describe' => $methodDescribe,
            'method_example' => $methodExample,
            'method_helper' => $methodHelper,
            'method_button' => $method_button,
            'method_layout' => $this->retrieveMethodLayout($methoudLayoutEloq),
        ];
        return $result;
    }

    public function retrieveMethodLayout($methoudLayoutEloq): array
    {
        $result = [];
        foreach ($methoudLayoutEloq as $item) {
            $result[] = [
                'validation_id' => $item->id,
                'rule_id' => $item->formattedNumberRule,
                'display_code' => $item->formattedDisplayCode,
            ];
        }
        return $result;
    }

    /**
     * 克隆彩种的玩法
     * @param  $lotteryEloq
     * @return array
     */
    public function cloneLotteryMethods($lotteryEloq): array
    {
        $examplesEloq = LotteryMethodsStandard::where('series_id', $lotteryEloq->series_id)->get();
        foreach ($examplesEloq as $exampleEloq) {
            $data = [
                'series_id' => $lotteryEloq->series_id,
                'lottery_name' => $lotteryEloq->cn_name,
                'lottery_id' => $lotteryEloq->en_name,
                'method_id' => $exampleEloq->method_id,
                'method_name' => $exampleEloq->method_name,
                'method_group' => $exampleEloq->method_group,
                'method_row' => $exampleEloq->method_row,
                'group_sort' => $exampleEloq->group_sort,
                'row_sort' => $exampleEloq->row_sort,
                'method_sort' => $exampleEloq->method_sort,
                'show' => $exampleEloq->show,
                'status' => $exampleEloq->status,
                'total' => $exampleEloq->total,
            ];
            $lotteryMethodEloq = new self();
            $lotteryMethodEloq->fill($data);
            $lotteryMethodEloq->save();
            if ($lotteryMethodEloq->errors()->messages()) {
                return ['success' => false, 'message' => $lotteryMethodEloq->errors()->messages()];
            }
        }
        return ['success' => true];
    }
}
