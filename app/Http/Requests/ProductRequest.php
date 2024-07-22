<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'category_id' => 'required',
            'product_title' => 'required|string|max:255',
            'product_description' => 'required|string',
            'product_price' => 'required|numeric',
            'type_of_paper_use' => 'required|string|max:255',
            'product_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sale_price' => 'required_if:manage_sale,1',
            'sale_start_date' => 'required_if:manage_sale,1',
            'sale_end_date' => 'required_if:manage_sale,1',
        ];
    }

    public function messages(): array
    {
        return [
            'sale_price.required_if' => 'The sale price  is required.',
            'sale_start_date.required_if' => 'The sale start date is required.',
            'sale_end_date.required_if' => 'The sale end date is required.',
        ];
    }
}
