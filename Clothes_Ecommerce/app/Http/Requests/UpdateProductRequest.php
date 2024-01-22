<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
                'name' => ['required',Rule::unique('products')->ignore($this->product->id)],
                'description' => ['sometimes'],
                'price' => ['required'],
                'available'=> ['sometimes'],
                'image'=> ['sometimes'],
                'category_id'=> ['required'],
        ];
    }
}
