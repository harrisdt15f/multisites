<?php

namespace App\Http\Requests\Backend\Users;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Support\Facades\Cache;

class UserHandleCreateUserRequest extends BaseFormRequest
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
        $currentPlatformEloq = Cache::get('currentPlatformEloq');
        $min = $currentPlatformEloq->prize_group_min;
        $max = $currentPlatformEloq->prize_group_max;
        return [
            'username' => 'required|unique:frontend_users',
            'password' => 'required',
            'fund_password' => 'required',
            'is_tester' => 'required|numeric',
            'prize_group' => 'required|numeric|between:' . $min . ',' . $max,
            'type' => 'required|numeric',
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
