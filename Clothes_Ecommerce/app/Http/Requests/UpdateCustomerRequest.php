<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $method = $this->method();
        if($method == "PUT"){
            return [    
                'name' => ['required'],
                'address' => ['required'],
                'city' => ['required'],
                'email' => ['required','email'=> 'email:rfc,dns'],
        ];
        }
        else{
            return [    
                'name' => ['sometimes','required'],
                'address' => ['sometimes','required'],
                'city' => ['sometimes','required'],
                'email' => ['sometimes','required','email'=> 'email:rfc,dns'],
        ];
        }
    }
}
