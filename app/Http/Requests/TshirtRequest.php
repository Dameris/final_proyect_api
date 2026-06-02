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
     */
    public function rules(): array
    {
        // Capturamos el ID de la ruta, dependas de cómo definiste el parámetro en web.php o api.php
        $tshirtId = $this->route('tshirt') ?? $this->route('id');

        return [
            "tshirt_name" => [
                "required",
                "string",
                "max:50",
                Rule::unique(table: "products", column: "name")
                    ->ignore(id: $tshirtId, idColumn: "id")
                    ->where(function ($query) {
                        return $query->where('type', 'tshirt');
                    })
            ],
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
            "tshirt_img1.mimes" => "The image/s must be a JPG, JPEG, PNG, or GIF.",
            "tshirt_img2.mimes" => "The image/s must be a JPG, JPEG, PNG, or GIF."
        ];
    }
}
