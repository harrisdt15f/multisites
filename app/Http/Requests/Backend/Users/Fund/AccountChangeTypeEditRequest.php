<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 16:31:39
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-24 20:19:11
 */
namespace App\Http\Requests\Backend\Users\Fund;

use App\Http\Requests\BaseFormRequest;

class AccountChangeTypeEditRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:account_change_types,id',
            'name' => 'required|string',
            'sign' => 'required|string',
            'in_out' => 'required|numeric|in:0,1',
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
