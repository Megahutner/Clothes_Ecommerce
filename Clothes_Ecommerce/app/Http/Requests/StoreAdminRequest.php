<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAdminRequest extends FormRequest
{
  /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required',"unique:users"],
            'password' => ['required'],

            'email' => ['required',"unique:users",'email'=> 'email:rfc,dns'],
            'type' => ['required','min:0','max:3'],

        ];
    }

    public function messages(){
        return [
            'name.unique'=> "Name already exists",
            'name.required'=> "Name is required",
            'password.required'=> "Password is required",
            'email.unique'=> "Email already exists",
            'email.required'=> "Email is required",
            'email.email'=> "Email format is wrong",
            'type.required'=> "Type is required",
            'type.min'=> "Unauthorized role",
            'type.max'=> "Unauthorized role",

        ];
    }

    protected function failedValidation(Validator $validator){
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(
            [
                'message'=> $errors,
                'code'=> 422,
            ]
        ));

    } 
}
