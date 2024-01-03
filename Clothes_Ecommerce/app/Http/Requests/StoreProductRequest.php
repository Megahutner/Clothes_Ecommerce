<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
                'name' => ['required','unique:products'],
                'description' => ['sometimes'],
                'price' => ['required'],
                'available'=> ['sometimes'],
                'image'=> ['sometimes','image','mimes:jpg,png,jpeg,gif,svg|max:2048'],
                 'category_id'=> ['required'],
            ];
    }
}
