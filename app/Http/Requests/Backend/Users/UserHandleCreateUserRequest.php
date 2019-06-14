<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-14 17:05:40
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-14 18:16:12
 */
namespace App\Http\Requests\Backend\Users;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\BaseFormRequest;

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
        // ############################################
        // $inputDatas = $request->validated();
        // validated $min $max 私有属性无法访问   需要处理
        $backEndApiMain = new BackEndApiMainController();
        $min = $backEndApiMain->currentPlatformEloq->prize_group_min;
        $max = $backEndApiMain->currentPlatformEloq->prize_group_max;
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
