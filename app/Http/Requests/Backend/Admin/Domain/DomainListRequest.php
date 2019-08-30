<?php

namespace App\Http\Requests\Backend\Admin\Domain;

use App\Http\Requests\BaseFormRequest;

class DomainListRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.git
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
            'user_id' => 'int',
        ];
    }
}
