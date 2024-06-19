<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
            'object_type' => 'required|in:0,1',
           'code' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'minimum_spend' => 'required|numeric|min:0',
            'maximum_spend' => 'required|numeric|min:0|gte:minimum_spend',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'use_limit' => 'required|integer|min:0',
            'same_ip_limit' => 'required|integer|min:0',
            'use_limit_per_user' => 'required|integer|min:0',
            'use_device' => 'required|string|max:255',
            'multiple_use' => 'required|in:0,1',
            'total_use' => 'required|integer|min:0',
        ];
    }
}
