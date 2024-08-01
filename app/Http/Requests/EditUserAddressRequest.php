<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditUserAddressRequest extends FormRequest
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
        $slug = $this->input('slug');

        switch ($slug) {
            case 'billing':
                return [
                    "fname" => "required",
                    "lname" => "required",
                    "street1" => "required",
                    "street2" => "required",
                    "state" => "required",
                    "postcode" => "required",
                    "suburb" => "required",
                ];
            case 'shipping':
                return [
                    "ship_fname" => "required",
                    "ship_lname" => "required",
                    "ship_street1" => "required",
                    "ship_street2" => "required",
                    "ship_state" => "required",
                    "ship_postcode" => "required",
                    "ship_suburb" => "required",
                ];
            default:
                return [
                    // Optionally, handle unexpected slug values
                    "slug" => "required|in:billing,shipping",
                ];
        }
    }
}
