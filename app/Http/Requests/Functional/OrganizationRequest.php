<?php

namespace App\Http\Requests\Functional;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrganizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'description' => 'required'
        ];
    }


    /**
     * @param Validator $validator
     * Возвращает информацию в json виде при валидационной ошибке
     */
    protected function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(response()->json([
                'result' => $validator->errors(),
                'status' => 'fail']
            , 422));
    }


}
