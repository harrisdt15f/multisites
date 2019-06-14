<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 18:04:40
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 18:07:01
 */
namespace App\Http\Requests\Backend\Game\Lottery;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Support\Facades\Config;

class LotteriesMethodRowSwitchRequest extends BaseFormRequest
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
            'lottery_id' => 'required|string|exists:lottery_lists,id',
            'method_group' => 'required|string|exists:lottery_methods,method_group',
            'method_row' => 'required|string|exists:lottery_methods,method_row',
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
