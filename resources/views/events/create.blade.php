@extends('layouts.app')
@section('title', 'New Event')
@section('page-title', __('Create new event'))
@section('content')
    <div class="max-w-2xl">
        <div class="card">
            <form method="POST" action="{{ route('events.store') }}" class="space-y-6">
                @csrf

                <div>
                    <h3 class="section-title">{{ __('Basic Information') }}</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">{{ __('Event Title') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required class="form-input"
                                placeholder="z.B. Jahreskonferenz Nachhaltigkeit 2025">
                        </div>
                        <div>
                            <label class="form-label">{{ __('Event Type') }} <span class="text-red-500">*</span></label>
                            <select name="type" required class="form-select">
                                <option value="">{{ __('Please select...') }}</option>
                                @foreach ($eventTypes as $key => $label)
                                    <option value="{{ $key }}" {{ old('type') === $key ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">{{ __('Description') }}</label>
                            <textarea name="description" rows="3" class="form-input">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="section-title">{{ __('Date & Participants') }}</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">{{ __('Start Date') }} <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" required
                                class="form-input">
                        </div>
                        <div>
                            <label class="form-label">{{ __('End Date') }} <span class="text-red-500">*</span></label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}" required
                                class="form-input">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="form-label">{{ __('Expected Participants') }} <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="expected_participants" value="{{ old('expected_participants') }}"
                            required min="1" class="form-input">
                    </div>
                </div>

                <div>
                    <h3 class="section-title">{{ __('Venue') }}</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">{{ __('Location Name') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="venue_name" value="{{ old('venue_name') }}" required
                                class="form-input" placeholder="z.B. Wiener Stadthalle">
                        </div>
                        <div>
                            <label class="form-label">{{ __('Address') }}</label>
                            <input type="text" name="venue_address" value="{{ old('venue_address') }}"
                                class="form-input">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">{{ __('City') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="venue_city" value="{{ old('venue_city') }}" required
                                    class="form-input">
                            </div>
                            <div>
                                <label class="form-label">{{ __('Country') }} <span class="text-red-500">*</span></label>
                                <select name="venue_country" class="form-select">
                                    <option value="AT" {{ old('venue_country', 'AT') === 'AT' ? 'selected' : '' }}>
                                        {{ __('Austria') }}</option>
                                    <option value="DE" {{ old('venue_country') === 'DE' ? 'selected' : '' }}>
                                        {{ __('Germany') }}</option>
                                    <option value="CH" {{ old('venue_country') === 'CH' ? 'selected' : '' }}>
                                        {{ __('Switzerland') }}</option>
                                    <option value="Other">{{ __('Other') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">{{ __('Latitude (optional)') }}</label>
                                <input type="number" step="0.000001" name="venue_lat" value="{{ old('venue_lat') }}"
                                    class="form-input" placeholder="48.2082">
                            </div>
                            <div>
                                <label class="form-label">{{ __('Longitude (optional)') }}</label>
                                <input type="number" step="0.000001" name="venue_lng" value="{{ old('venue_lng') }}"
                                    class="form-input" placeholder="16.3738">
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="section-title">{{ __('Special features') }}</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_outdoor" value="1"
                                {{ old('is_outdoor') ? 'checked' : '' }} class="form-checkbox">
                            <span
                                class="text-sm text-gray-700">{{ __('Outdoor / Open-Air Event (Vb criteria apply)') }}</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_hybrid" value="1"
                                {{ old('is_hybrid') ? 'checked' : '' }} class="form-checkbox">
                            <span
                                class="text-sm text-gray-700">{{ __('Hybrid Event (M17 becomes MUST criteria)') }}</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn-primary">
                        {{ __('Create event') }}
                    </button>
                    <a href="{{ route('events.index') }}" class="btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
