<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-19 14:18:04
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-19 14:18:33
 */
namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseFormRequest;

class FrontendAuthDeletePartnerAdminRequest extends BaseFormRequest
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
            'id' => 'required|numeric',
            'name' => 'required',
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
