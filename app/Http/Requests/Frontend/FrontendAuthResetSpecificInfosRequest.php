<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-25 21:48:02
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-26 10:54:44
 */
namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseFormRequest;

class FrontendAuthResetSpecificInfosRequest extends BaseFormRequest
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
            'nickname' => 'required|string',
            'realname' => 'required|string',
            'mobile' => ['required', 'regex:/^0?(13[0-9]|15[012356789]|18[0-9]|14[57])[0-9]{8}$/'],
            'email' => 'required|email',
            'zip_code' => ['required', 'regex:/^\d{6}$/'],
            'address' => 'required|string',
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
