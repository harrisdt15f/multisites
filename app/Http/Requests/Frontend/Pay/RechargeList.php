<?php

namespace App\Http\Requests\Frontend\Pay;

use App\Http\Requests\BaseFormRequest;

class RechargeList extends BaseFormRequest
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
            'date_to'=>'filled|date',
            'date_from'=>'filled|date',
            'type'  =>'filled|string',
        ];
    }
}
