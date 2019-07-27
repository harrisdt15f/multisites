<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 11:19:01
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 11:20:19
 */
namespace App\Http\Requests\Backend\Admin\Homepage;

use App\Http\Requests\BaseFormRequest;

class PopularLotteriesAddRequest extends BaseFormRequest
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
            'lotteries_id' => 'required|numeric|unique:frontend_lottery_redirect_bet_lists,lotteries_id|exists:lottery_lists,id', //彩种id
            'lotteries_sign' => 'required|string|unique:frontend_lottery_redirect_bet_lists|exists:lottery_lists,en_name', //彩种标识
            'pic' => 'required|image',
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
}
