<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
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
            "email" => ["required", "email:rfc"],
            "password" => ["required", "min:8", "regex:/[A-Z]/", "regex:/[*, +, -, ., @, #, %, &, _, ~, ^, \/ ,<, >]/"]
        ];
    }

    public function messages(): array
    {
        return [
            "email.regex" => "The email format is not valid.",
            "password.min" => "The password must have at least 8 characters.",
            "password.regex" => "The password must have at least 1 uppercase letter and 1 special character."
        ];
    }
}
