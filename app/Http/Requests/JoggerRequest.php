<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JoggerRequest extends FormRequest
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
            "jogger_name" => ["required", "string", "max:50", Rule::unique(table: "joggers", column: "jogger_name")->ignore(id: request("jogger"), idColumn: "id")],
            "jogger_composition" => ["required", "string", "max:50", Rule::in("Cotton", "Polyester", "Rayon", "Linen")],
            "jogger_fit" => ["required", "string", "max:50", Rule::in("Oversize", "Regular", "Slim", "Superslim", "Boxy")],
            "jogger_price" => ["required", "numeric", "min:0"],
            "jogger_img1" => ["nullable", "image", "mimes:jpg,jpeg,png,gif", "max:2048"],
            "jogger_img2" => ["nullable", "image", "mimes:jpg,jpeg,png,gif", "max:2048"]
        ];
    }

    public function messages(): array
    {
        return [
            "jogger_name.unique" => "The jogger already exists.",
            "jogger_img1.mimes",
            "jogger_img2.mimes" => "The image/s must be a JPG, JPEG, PNG, or GIF."
        ];
    }
}
