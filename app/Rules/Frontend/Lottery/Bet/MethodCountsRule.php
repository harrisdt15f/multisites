<?php

namespace App\Rules\Frontend\Lottery\Bet;

use App\Models\Game\Lottery\LotteryMethod;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class MethodCountsRule implements Rule
{
    protected $message = '注数不符合';

    /**
     * Create a new rule instance.
     *
     * @param $method_id
     */
    public function __construct($method_id)
    {
        $this->method_id = $method_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        preg_match('/\d+/', $attribute, $matches);
        try {
            $methodId = $this->method_id[$matches[0]]['method_id'];
            $count = $this->method_id[$matches[0]]['count'];
            $oMethodRaws = LotteryMethod::where('method_id', $methodId)->first();
            if ($count > $oMethodRaws->total) {
                $this->message = '注数大于全包注数';
                return false;
            }
        } catch (\Exception $e) {
            if (!empty($this->method_id)) {
                $arrMethod = json_decode($this->method_id, true);
                $methodId = $arrMethod[$matches[0]]['method_id'];
                $count = $arrMethod[$matches[0]]['count'];
                $oMethodRaws = LotteryMethod::where('method_id', $methodId)->first();
                if ($count > $oMethodRaws->total) {
                    $this->message = '注数大于全包注数';
                    return false;
                } else {
                    return true;
                }
            }
            Log::error($e->getMessage().$e->getTraceAsString().$attribute);
            return false;
        }
        return true;
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
