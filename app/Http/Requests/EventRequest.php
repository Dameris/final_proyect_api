<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            "title" => ["required", "string", "max:255", Rule::unique("events", "title")->ignore(request("event"), "id")],
            "description" => ["nullable", "string"],
            "start_date" => ["required", "date"],
            "end_date" => ["nullable", "date", "after_or_equal:start_date"],
            "location" => [
                "nullable",
                "string",
                "max:255"
            ],
            "color" => ["nullable", "string", "max:20"],
            "all_day" => ["boolean"],
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function messages(): array
    {
        return [
            "title.required" => "The event title is required.",
            "title.unique" => "This event already exists.",
            "start_date.required" => "Start date is required.",
            "start_date.date" => "Start date must be a valid date.",
            "end_date.after_or_equal" => "End date must be after or equal to start date.",
        ];
    }
}
