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

    public const COMPOSITIONS = [
        'Cotton',
        'Polyester',
        'Rayon',
        'Linen',
    ];

    public const FITS = [
        'Oversize',
        'Regular',
        'Skinny',
        'Superskinny',
    ];

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Capturamos el ID de la ruta dinámicamente
        $joggerId = $this->route('jogger') ?? $this->route('id');

        return [
            "jogger_name" => [
                "required",
                "string",
                "max:50",
                Rule::unique(table: "products", column: "name")
                    ->ignore(id: $joggerId, idColumn: "id")
                    ->where(function ($query) {
                        return $query->where('type', 'jogger');
                    })
            ],
            "jogger_composition" => ["required", "string", "max:50", Rule::in(self::COMPOSITIONS)],
            "jogger_fit" => ["required", "string", "max:50", Rule::in(self::FITS)],
            "jogger_price" => ["required", "numeric", "min:0"],
            "jogger_img1" => ["nullable", "image", "mimes:jpg,jpeg,png,gif", "max:2048"],
            "jogger_img2" => ["nullable", "image", "mimes:jpg,jpeg,png,gif", "max:2048"]
        ];
    }

    public function messages(): array
    {
        return [
            "jogger_name.unique" => "The jogger already exists.",
            "jogger_img1.mimes" => "The image/s must be a JPG, JPEG, PNG, or GIF.",
            "jogger_img2.mimes" => "The image/s must be a JPG, JPEG, PNG, or GIF."
        ];
    }
}
