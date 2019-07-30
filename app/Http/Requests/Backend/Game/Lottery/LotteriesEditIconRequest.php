<?php

namespace App\Http\Requests\Backend\Game\Lottery;

use App\Http\Requests\BaseFormRequest;

class LotteriesEditIconRequest extends BaseFormRequest
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
            'id' => 'required|exists:lottery_lists',
            'icon_name' => 'required|string', //彩种图标名称
            'icon_path' => 'required|string', //彩种图标路径
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
