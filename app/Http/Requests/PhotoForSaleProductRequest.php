<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhotoForSaleProductRequest extends FormRequest
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
            // 'category_id' => 'required',
            // 'product_title' => 'required|string|max:255',
            // 'min_price' => 'required|numeric|min:0',
            // 'max_price' => 'required|numeric|min:0|gte:min_price',
            // 'product_images' => 'required|array',
            // 'product_images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
