<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCustomerRequest extends FormRequest
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
            'name' => ['required',"unique:customers"],
            'password' => ['required'],
            'address' => ['required'],
            'phone'=> ['required'],
            'email' => ['required',"unique:customers",'email'=> 'email:rfc,dns'],
        ];
    }

    public function messages(){
        return [
            'name.unique'=> "Name already exists",
            'password.required'=> "Password is required",
            'name.required'=> "Name is required",
            'address'=> "Address is required",
            'phone'=> "Phone is required",
            'email.unique'=> "Email already exists",
            'email.required'=> "Email is required",
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
