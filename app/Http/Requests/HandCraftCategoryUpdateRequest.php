<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HandCraftCategoryUpdateRequest extends FormRequest
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
        $categoryId = $this->input('category_id');

        return [
            'name' => [
                'required',
                Rule::unique('hand_craft_category', 'name')->ignore($categoryId),
            ],
            'image' => 'image|mimes:jpeg,png,jpg'
        ];
    }
}
