<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 11:49:20
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 11:50:55
 */
namespace App\Http\Requests\Backend\Admin\Notice;

use App\Http\Requests\BaseFormRequest;

class NoticeAddRequest extends BaseFormRequest
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
            'type' => 'required|numeric',
            'title' => 'required|string|unique:frontend_message_notices,title',
            'content' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'status' => 'required|numeric|in:0,1',
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
