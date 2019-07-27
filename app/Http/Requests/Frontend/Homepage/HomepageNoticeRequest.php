<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-19 14:11:46
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-19 14:12:21
 */
namespace App\Http\Requests\Frontend\Homepage;

use App\Http\Requests\BaseFormRequest;

class HomepageNoticeRequest extends BaseFormRequest
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
            'type' => 'required|numeric',
        ];
    }
}
