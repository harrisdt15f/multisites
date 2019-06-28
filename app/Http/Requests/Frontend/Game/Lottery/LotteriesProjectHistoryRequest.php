<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-19 11:20:30
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-28 15:56:55
 */
namespace App\Http\Requests\Frontend\Game\Lottery;

use App\Http\Requests\BaseFormRequest;

class LotteriesProjectHistoryRequest extends BaseFormRequest
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
            'count' => 'required|integer|min:10|max:100',
            'lottery_sign' => 'string|min:4|max:10|exists:lottery_lists,en_name',
            'begin_time' => 'date',
            'end_time' => 'date',
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
