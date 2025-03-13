<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
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
            "first_name" => ["required", "min:1", "regex:/^[A-Za-zÀ-ÿ\s'-]+$/"],
            "last_name" => ["required", "min:1", "regex:/^[A-Za-zÀ-ÿ\s'-]+$/"],
            "message" => ["required", "min:20"]
        ];
    }

    public function messages(): array
    {
        return [
            "email.email" => "The email format is not valid.",
            "first_name.regex" => "The first name must have at least 1 characters and only letters",
            "last_name.regex" => "The last name must have at least 1 characters and only letters",
            "message.min" => "The message must at least 20 characters."
        ];
    }
}
