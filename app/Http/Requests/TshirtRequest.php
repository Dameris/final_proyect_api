<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TshirtRequest extends FormRequest
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
            "tshirt_name" => ["required", "string", "max:50", Rule::unique(table: "tshirts", column: "tshirt_name")->ignore(id: request("tshirt"), idColumn: "id")],
            "tshirt_composition" => ["required", "string", "max:50", Rule::in("Cotton", "Polyester", "Rayon", "Linen")],
            "tshirt_fit" => ["required", "string", "max:50", Rule::in("Oversize", "Regular", "Slim", "Superslim", "Boxy")],
            "tshirt_price" => ["required", "numeric", "min:0"],
            // "tshirt_img1" => ["required", "file", "mimes:jpg, jpeg, png, gif"],
            // "tshirt_img2" => ["required", "file", "mimes:jpg, jpeg, png, gif"]
        ];
    }

    public function messages(): array
    {
        return [
            "tshirt_name.unique" => "The tshirt already exists.",
            // "tshirt_img1.mimes",
            // "tshirt_img2.mimes" => "The image/s must be a JPG, JPEG, PNG, or GIF."
        ];
    }
}
