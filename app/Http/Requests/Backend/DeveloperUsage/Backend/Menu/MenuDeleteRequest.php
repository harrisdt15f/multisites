<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 14:50:28
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 17:17:11
 */
namespace App\Http\Requests\Backend\DeveloperUsage\Backend\Menu;

use App\Http\Requests\BaseFormRequest;

class MenuDeleteRequest extends BaseFormRequest
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
            'toDelete' => 'required|array',
            'toDelete.*' => 'int',
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
