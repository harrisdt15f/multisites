<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-13 20:35:46
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-13 20:36:13
 */
namespace App\Http\Requests\Backend\Admin\Article;

use App\Http\Requests\BaseFormRequest;

class ArticlesEditArticlesRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:backend_admin_message_articles,id',
            'category_id' => 'required|numeric|exists:frontend_info_categories,id',
            'title' => 'required|string',
            'summary' => 'required|string',
            'content' => 'required|string',
            'search_text' => 'required|string',
            'is_for_agent' => 'required|in:0,1',
            'apply_note' => 'required|string',
            'pic_name' => 'required|array',
            'pic_path' => 'required|array',
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
