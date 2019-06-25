<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-25 16:30:35
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-25 17:44:50
 */
namespace App\Http\Requests\Frontend\User\Fund;

use App\Http\Requests\BaseFormRequest;

class UserBankCardAddRequest extends BaseFormRequest
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
            'owner_name' => 'required|string',
            'bank_sign' => 'required|alpha',
            'bank_name' => 'required|string',
            'card_number' => 'required|integer',
            'province_id' => 'required|exists:users_regions,id',
            'city_id' => 'required|exists:users_regions,id',
            'branch' => 'required|string',
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
