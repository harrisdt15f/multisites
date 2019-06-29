<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 6/5/2019
 * Time: 8:06 PM
 */

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Waavi\Sanitizer\Laravel\SanitizesInput;

abstract class BaseFormRequest extends FormRequest
{
    use SanitizesInput;

    public function validateResolved():void
    {
        {
            $this->sanitize();
            parent::validateResolved();
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules(): array ;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    abstract public function authorize(): bool ;

    /**
     * @param  Validator  $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $datas = [
            'success' => false,
            'code' => 400,
            'data' => [],
            'message' => $validator->errors()->first(),
        ];
        throw new HttpResponseException(
            response()->json($datas)
        );
    }

}
