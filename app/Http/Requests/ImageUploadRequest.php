<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageUploadRequest extends FormRequest
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
    public function rules()
    {
        return [
            'image.*' => 'required|file|max:1048576', // 1 GB in KB
        ];
    }

    public function messages()
    {
        return [
            'image.*.required' => 'Please upload an image.',
            'image.*.file' => 'The uploaded file must be a valid file.',
            'image.*.max' => 'The image size must not exceed 1 GB to upload.',
        ];
    }
}
