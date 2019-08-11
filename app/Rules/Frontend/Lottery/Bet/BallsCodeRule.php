<?php

namespace App\Rules\Frontend\Lottery\Bet;

use App\Models\Game\Lottery\LotteryList;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class BallsCodeRule implements Rule
{
    protected $message = '注单号不符合';
    protected $lottery;
    protected $balls;

    /**
     * Create a new rule instance.
     *
     * @param $lotterySign
     * @param $balls
     */
    public function __construct($lotterySign, $balls)
    {
        $this->lottery = LotteryList::where('en_name', $lotterySign)->first();
        $this->balls = $balls;
    }


    /**
     * Determine if the validation rule passes.
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $methodId = $this->checkMethodId($attribute);
        switch ($this->lottery->series_id) {
            case 'ssc':
                switch ($methodId) {
                    case 'QZXHZ'://和值
                        $pattern = '//';
                        break;
                    default:
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?){1,5}$/';
                        break;
                }
                $result = $this->checkValid($pattern, $value);
                break;
            case 'lotto':
                switch ($methodId) {
                    case 'LTDDS'://趣味 定单双
                        $pattern = '/^((?! )(?!.*  $)(?!.* $)(([0-5]) ?){1,6})*$/';
                        break;
                    case 'LTCZW'://趣味 猜中位
                        $pattern = '/^(?! )(?!.* $)(((0[3-9]))|((0[3-9]) ?)){1,7}$/';
                        break;
                    default:
                        $pattern = '/^(((?!\&)(?!.*\&$)(?!\|)(?!.*\|$)(?! )(?!.* $)(((0[1-9]|1[0-1])\&?)|((0[1-9]|1[0-1]) ?)){1,11})\|?)*$/';
                        break;
                }
                $result = $this->checkValid($pattern, $value);
                break;
            case 'k3'://1-18
                $pattern = '/^(?!\|)(?!.*\|\|$)(?!.*\|$)(([0-1]?[\d])\|?)*$/';
                $result = $this->checkValid($pattern, $value);
                break;
            default:
                $result = true;
                break;
        }
        return $result;
    }

    private function checkValid($pattern, $value)
    {
        if (!preg_match($pattern, $value)) {
            $this->message = $this->lottery->series_id.'注单号不符合';
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param $attribute
     * @return string
     */
    private function checkMethodId($attribute): string
    {
        $methodId = '';
        preg_match('/\d+/', $attribute, $matches);
        try {
            $methodId = $this->balls[$matches[0]]['method_id'];
        } catch (\Exception $e) {
            if (!empty($this->balls)) {
                $arrMethod = json_decode($this->balls, true);
                $methodId = $arrMethod[$matches[0]]['method_id'];
            } else {
                Log::error($e->getMessage().$e->getTraceAsString().$attribute);
            }
        }
        return $methodId;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
