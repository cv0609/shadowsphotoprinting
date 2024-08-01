<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddUserAddressRequest extends FormRequest
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
            "fname" => "required",
            "lname" => "required",
            "street1" => "required",
            "street2" => "required",
            "state" => "required",
            "postcode" => "required",
            "suburb" => "required",    
            "email" => "required|email",    
        ];
    }
}
