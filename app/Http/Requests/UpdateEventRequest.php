<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Event;

class UpdateEventRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', array_keys(Event::TYPES)),
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'expected_participants' => 'required|integer|min:1',
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'nullable|string|max:255',
            'venue_city' => 'required|string|max:255',
            'venue_country' => 'required|string|max:2',
            'venue_lat' => 'nullable|numeric',
            'venue_lng' => 'nullable|numeric',
            'is_outdoor' => 'boolean',
            'is_hybrid' => 'boolean',
            'status' => 'in:draft,active,completed,certified',
        ];
    }
}
