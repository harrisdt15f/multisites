<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/5/2019
 * Time: 8:06 PM
 */

namespace App\Http\Requests\Frontend\Game\Lottery;

use App\Http\Requests\BaseFormRequest;

class LotteriesBetRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'lottery_sign' => 'required|string|min:4|max:10|exists:lottery_lists,en_name',
            'trace_issues.*' => 'required|integer|between:1,1000',
//            'trace_issues' => ['required', 'regex:/^\{(\d{9,15}\:(true|false)\,?)+\}$/'],
            //{20180405001:true,20180405001:false,20180405001:true}

            'balls.*.method_id' => 'required|exists:lottery_methods,method_id',
            'balls.*.method_name' => 'required',//中文
            'balls.*.codes' => ['required', 'regex:/^(?!\|)(?!.*\|$)((?!\&)(?!.*\&$)(?!.*?\&\&)[0-9&]{1,19}\|?){1,5}$/'],
            //0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9|0&1&2&3&4&5&6&7&8&9
            'balls.*.count' => 'required|integer',
            'balls.*.times' => 'required|integer',
            'balls.*.cost' => 'required|regex:/^\d+(\.\d{1,3})?$/',//float
            'balls.*.mode' => 'required|regex:/^\d+(\.\d{1,3})?$/',//float
            'balls.*.prize_group' => 'required|integer',
            'balls.*.price' => 'required|integer|in:1,2',

            'trace_win_stop' => 'required|integer',
            'total_cost' => 'required|regex:/^\d+(\.\d{1,3})?$/',//float
            'from' => 'integer',
            'is_trace' => 'required|integer|in:0,1',
        ];
    }

    /*public function messages()
    {
        return [
            'lottery_sign.required' => 'lottery_sign is required!',
            'trace_issues.required' => 'trace_issues is required!',
            'balls.required' => 'balls is required!'
        ];
    }*/
    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters(): array
    {
        return [
            'balls' => 'cast:array',
            'trace_issues' => 'cast:array',
        ];
    }
}
