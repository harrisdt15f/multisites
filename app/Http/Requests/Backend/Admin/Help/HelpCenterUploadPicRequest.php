<?php
/**
 * @Author: Fish
 * @Date:   2019/7/8 11:45
 */

namespace App\Http\Requests\Backend\Admin\Help;


use App\Http\Requests\BaseFormRequest;

class HelpCenterUploadPicRequest extends BaseFormRequest
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
            'pic'   => 'required|image',
        ];
    }
}