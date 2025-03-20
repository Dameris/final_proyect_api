<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            "password" => ["required", "min:8", "regex:/[A-Z]/", "regex:/[*, +, -, ., @, #, %, &, _, ~, ^, \/ ,<, >]/"],
            "first_name" => ["required", "min:1", "regex:/^[A-Za-zÀ-ÿ\s'-]+$/"],
            "last_name" => ["required", "min:1", "regex:/^[A-Za-zÀ-ÿ\s'-]+$/"],
            "gender" => ["required", "in:Male, Female"],
            "profile_photo_path" => ["nullable", "mimes:jpg,jpeg,png,gif"]
        ];
    }
}
