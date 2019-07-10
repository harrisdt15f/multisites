<?php

namespace App\Models\Game\Lottery\Logics;


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
        $result = [
            'method_name' => $this->method_name,
            'method_id' => $this->method_id,
            'method_group' => $this->method_group,
            'method_describe' => $methodDescribe,
            'method_example' => $methodExample,
            'method_helper' => $methodHelper,
            'method_button' => $method_button,
            'method_layout' => $this->retrieveMethodLayout($methoudLayoutEloq)
        ];
        return $result;
    }

    public function retrieveMethodLayout($methoudLayoutEloq): array
    {
        $result = [];
        foreach ($methoudLayoutEloq as $item) {
            $result [] = [
                'validation_id' => $item->id,
                'rule_id' => $item->formattedNumberRule,
                'display_code' => $item->formattedDisplayCode,
            ];
        }
        return $result;
    }
}
