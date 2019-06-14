<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-13 20:50:55
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-13 20:51:37
 */
namespace App\Http\Requests\Backend\Admin\Article;

use App\Http\Requests\BaseFormRequest;

class ArticlesUploadPicRequest extends BaseFormRequest
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
            'pic' => 'required|image',
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
