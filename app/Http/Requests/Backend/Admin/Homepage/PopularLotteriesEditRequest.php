<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 11:21:48
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 11:22:11
 */
namespace App\Http\Requests\Backend\Admin\Homepage;

use App\Http\Requests\BaseFormRequest;

class PopularLotteriesEditRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:frontend_lottery_redirect_bet_lists,id',
            'pic' => 'image',
            'lotteries_id' => 'required|numeric|exists:lottery_lists,id',
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
