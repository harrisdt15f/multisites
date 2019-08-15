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
                    case 'QZXHZ'://前三直选和值
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?){1,28}$/';
                        break;
                    case 'QZUHZ'://前三组选和值
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?){1,26}$/';
                        break;
                    case 'ZZXHZ'://中三直选和值
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?){1,28}$/';
                        break;
                    case 'ZZUHZ'://中三组选和值
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?){1,26}$/';
                        break;
                    case 'HZXHZ'://后三直选和值
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?){1,28}$/';
                        break;
                    case 'HZUHZ'://后三组选和值
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?){1,26}$/';
                        break;
                    case 'ZX5_S'://五星直选单式
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?)+$/';
                        break;
                    case 'ZX4_S'://四星直选单式
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?)+$/';
                        break;
                    case 'QZX3_S'://前三直选单式
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?)+$/';
                        break;
                    case 'QZU3_S'://前三组三单式
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?)+$/';
                        break;
                    case 'QZU6_S'://前三组六单式
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?)+$/';
                        break;
                    case 'QHHZX'://前三混合组选
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?)+$/';
                        break;
                    case 'ZZX3_S'://中三直选单式
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?)+$/';
                        break;
                    case 'ZZU3_S'://中三组三单式
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?)+$/';
                        break;
                    case 'ZZU6_S'://中三组六单式
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?)+$/';
                        break;
                    case 'ZHHZX'://中三混合组选
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?)+$/';
                        break;
                    case 'HZX3_S'://后三直选单式
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?)+$/';
                        break;
                    case 'HZU3_S'://后三组三单式
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?)+$/';
                        break;
                    case 'HZU6_S'://后三组六单式
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?)+$/';
                        break;
                    case 'HHHZX'://后三混合组选
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?)+$/';
                        break;
                    case 'HZX2_S'://后二直选单式
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?)+$/';
                        break;
                    case 'QZX2_S'://前二直选单式
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?)+$/';
                        break;
                    case 'HZU2_S'://后二组选单式
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?)+$/';
                        break;
                    case 'QZU2_S'://前二组选单式
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?)+$/';
                        break;
                    default:
                        $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?){1,5}$/';
                        break;
                }
                // dd($pattern);
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
