<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GiftCardCategoryRequest extends FormRequest
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
            'name' => 'required|unique:gift_card_category,product_title',
            "image" => 'required|image|mimes:jpeg,png,jpg|max:2048',
            "use_different_email_image" => 'nullable|in:0,1',
            "email_image" => 'nullable|image|mimes:jpeg,png,jpg|max:2048|required_if:use_different_email_image,1'
        ];
    }
}
