<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-17 18:02:56
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-18 15:37:12
 */
namespace App\Http\Requests\Backend\Game\Lottery;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Support\Facades\Config;

class LotteriesInputNumberRequest extends BaseFormRequest
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
            'code' => 'required|array|size:5',
            'code.*' => 'required|numeric',
            'series_id' => 'required|string|exists:lottery_series,series_name',
            'lottery_id' => 'required|string|exists:lottery_lists,en_name',
            'issue' => 'required|integer',
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
