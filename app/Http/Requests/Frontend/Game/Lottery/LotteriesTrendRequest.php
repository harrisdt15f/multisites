<?php

namespace App\Http\Requests\Frontend\Game\Lottery;

use App\Http\Requests\BaseFormRequest;

class LotteriesTrendRequest extends BaseFormRequest
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
            'lottery_id' => 'required|string', //lottery_lists
            'range' => 'required|int', //lottery_lists
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
