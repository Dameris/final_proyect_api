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

    public const COMPOSITIONS = [
        'Cotton',
        'Polyester',
        'Rayon',
        'Linen',
    ];

    public const FITS = [
        'Oversize',
        'Regular',
        'Slim',
        'Superslim',
        'Boxy',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "tshirt_name" => ["required", "string", "max:50", Rule::unique(table: "tshirts", column: "tshirt_name")->ignore(id: request("tshirt"), idColumn: "id")],
            "tshirt_composition" => ["required", "string", "max:50", Rule::in(self::COMPOSITIONS)],
            "tshirt_fit" => ["required", "string", "max:50", Rule::in(self::FITS)],
            "tshirt_price" => ["required", "numeric", "min:0"],
            "tshirt_img1" => ["nullable", "image", "mimes:jpg,jpeg,png,gif", "max:2048"],
            "tshirt_img2" => ["nullable", "image", "mimes:jpg,jpeg,png,gif", "max:2048"]
        ];
    }

    public function messages(): array
    {
        return [
            "tshirt_name.unique" => "The tshirt already exists.",
            "tshirt_img1.mimes",
            "tshirt_img2.mimes" => "The image/s must be a JPG, JPEG, PNG, or GIF."
        ];
    }
}
