<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 10:11:19
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 10:12:28
 */
namespace App\Http\Requests\Backend\Admin\FundOperate;

use App\Http\Requests\BaseFormRequest;

class FundOperationAddFundRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:backend_admin_users,id',
            'fund' => 'required|numeric|gt:0',
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
