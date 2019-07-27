<?php

namespace App\Rules\Frontend\Lottery\Bet;

use App\Models\Game\Lottery\LotteryList;
use Illuminate\Contracts\Validation\Rule;

class BallsCodeRule implements Rule
{
    protected $message = '注单号不符合';
    protected $lottery;

    /**
     * Create a new rule instance.
     *
     * @param $lotterySign
     */
    public function __construct($lotterySign)
    {
        $this->lottery = LotteryList::where('en_name', $lotterySign)->first();
    }


    /**
     * Determine if the validation rule passes.
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        switch ($this->lottery->series_id) {
            case 'ssc':
                $pattern = '/^((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{0,19}\|?){1,5}$/';
                $result = $this->checkValid($pattern, $value);
                break;
            case 'lotto':
                $pattern = '/^(((?!\&)(?!.*\&$)(?!\|)(?!.*\|$)(?! )(?!.* $)(((0[1-9]|1[0-1])\&?)|((0[1-9]|1[0-1]) ?)){1,11})\|?)*$/';
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
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
