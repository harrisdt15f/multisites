<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-13 20:09:49
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-13 20:12:01
 */
namespace App\Http\Requests\Backend\Admin\Activity;

use App\Http\Requests\BaseFormRequest;

class ActivityDeleteRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:frontend_activity_contents,id',
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
