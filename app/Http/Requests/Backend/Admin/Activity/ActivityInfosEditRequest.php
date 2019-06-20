<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-13 20:05:26
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-20 13:57:56
 */
namespace App\Http\Requests\Backend\Admin\Activity;

use App\Http\Requests\BaseFormRequest;

class ActivityInfosEditRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:frontend_activity_contents',
            'title' => 'required',
            'content' => 'required',
            'pic' => 'image|mimes:jpeg,png,jpg',
            'start_time' => 'date_format:Y-m-d H:i:s|required_if:is_time_interval,1',
            'end_time' => 'date_format:Y-m-d H:i:s|required_if:is_time_interval,1',
            'status' => 'required',
            'redirect_url' => 'required',
            'is_time_interval' => 'required|numeric',
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
