<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 15:31:17
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-24 14:08:29
 */
namespace App\Http\Requests\Backend\DeveloperUsage\Backend\Routes;

use App\Http\Requests\BaseFormRequest;

class RouteDeleteRequest extends BaseFormRequest
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
            'id' => 'required|numeric|exists:backend_admin_routes,id',
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
