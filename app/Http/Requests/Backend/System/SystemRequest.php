<?php
/**
 * @Author: Fish
 * @Date:   2019/7/8 17:51
 */

namespace App\Http\Requests\Backend\System;


use App\Http\Requests\BaseFormRequest;

class SystemRequest extends BaseFormRequest
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
            'pic'          => 'required|image',
            'folder_name'  => 'string',
        ];
    }
}