<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SizetypeRequest extends FormRequest
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
             'name' => 'required|unique:size_types,name'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'A title is required',
            'name.unique' => 'This name is alredy used',
        ];
    }
}
