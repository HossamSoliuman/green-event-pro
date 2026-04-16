<?php

namespace App\Http\Requests\Modules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAccommodationRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'hotel_name' => 'required|string|max:255',
            'hotel_city' => 'nullable|string|max:255',
            'hotel_country' => 'nullable|string|max:2',
            'certification_type' => 'nullable|string',
            'has_env_certification' => 'boolean',
            'hotel_informed_of_green_event' => 'boolean',
            'distance_to_venue_km' => 'nullable|numeric',
            'contingent_reserved' => 'nullable|integer',
            'nights_reserved' => 'nullable|integer',
        ];
    }
}
