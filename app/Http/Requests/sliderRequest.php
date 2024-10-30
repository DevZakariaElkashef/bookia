<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class sliderRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'image' => $isRequired . '|mimes:png,jpg,jpeg',
            'is_active' => 'required|boolean',
            'book_id' => 'nullable|exists:books,id'
        ];
    }
}
