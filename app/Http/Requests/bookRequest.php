<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class bookRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isRequired = $this->id ? 'required' : 'nullable';

        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'image' => $isRequired . '|mimes:png,jpg,jpeg',
            'price' => "required|numeric|gt:0",
            'offer' => 'nullable|numeric',
            'description' => 'required|string',
            'is_active' => 'required|boolean'
        ];
    }
}
