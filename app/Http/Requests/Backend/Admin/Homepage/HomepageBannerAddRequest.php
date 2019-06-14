<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 11:00:28
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 11:01:03
 */
namespace App\Http\Requests\Backend\Admin\Homepage;

use App\Http\Requests\BaseFormRequest;

class HomepageBannerAddRequest extends BaseFormRequest
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
            'title' => 'required|string|unique:frontend_page_banners,title',
            'content' => 'required|string',
            'pic' => 'required|image',
            'type' => 'required|numeric|in:1,2',
            'redirect_url' => 'string|required_if:type,1',
            'activity_id' => 'numeric|required_if:type,2|exists:frontend_activity_contents,id',
            'status' => 'required|numeric|in:0,1',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
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
