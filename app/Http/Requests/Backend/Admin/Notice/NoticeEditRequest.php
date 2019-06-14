<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 11:52:15
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 11:52:36
 */
namespace App\Http\Requests\Backend\Admin\Notice;

use App\Http\Requests\BaseFormRequest;

class NoticeEditRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:frontend_message_notices,id',
            'type' => 'required|numeric',
            'title' => 'required|string',
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
