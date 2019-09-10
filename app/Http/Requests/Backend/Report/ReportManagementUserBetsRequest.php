<?php

namespace App\Http\Requests\Backend\Report;

use App\Http\Requests\BaseFormRequest;

class ReportManagementUserBetsRequest extends BaseFormRequest
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
            'id' => 'integer',
            'serial_number' => 'alpha_num',
            'username' => 'alpha_num|exists:frontend_users',
            'series_id' => 'alpha_num',
            'lottery_sign' => 'alpha_num',
            'method_sign' => 'alpha_num',
            'is_tester' => 'in:0,1',
            'issue' => 'alpha_dash',
            'status' => 'integer',
            'get_sub' => 'required|in:0,1',
            'time_condtions' => 'string',
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
