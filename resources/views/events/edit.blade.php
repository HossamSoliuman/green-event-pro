@extends('layouts.app')
@section('title', __('Edit event'))
@section('page-title', __('Edit event'))
@section('content')
<div class="max-w-2xl">
    <div class="card">
        <form method="POST" action="{{ route('events.update', $event) }}" class="space-y-6">
            @csrf @method('PUT')
            <div>
                <h3 class="section-title">{{ __('Basic Information') }}</h3>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">{{ __('Event Title') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $event->title) }}" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">{{ __('Event Type') }} <span class="text-red-500">*</span></label>
                        <select name="type" required class="form-select">
                            @foreach($eventTypes as $key => $label)
                                <option value="{{ $key }}" {{ old('type', $event->type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">{{ __('Status') }}</label>
                        <select name="status" class="form-select">
                            @foreach(['draft'=>'Entwurf','active'=>'Aktiv','completed'=>'Abgeschlossen','certified'=>'Zertifiziert'] as $val => $label)
                                <option value="{{ $val }}" {{ old('status', $event->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">{{ __('Description') }}</label>
                        <textarea name="description" rows="3" class="form-input">{{ old('description', $event->description) }}</textarea>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="section-title">{{ __('Date & Participants') }}</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Startdatum</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $event->start_date?->format('Y-m-d')) }}" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Enddatum</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $event->end_date?->format('Y-m-d')) }}" required class="form-input">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="form-label">Erwartete Teilnehmeranzahl</label>
                    <input type="number" name="expected_participants" value="{{ old('expected_participants', $event->expected_participants) }}" required min="1" class="form-input">
                </div>
            </div>
            <div>
                <h3 class="section-title">{{ __('Venue') }}</h3>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Name der Location</label>
                        <input type="text" name="venue_name" value="{{ old('venue_name', $event->venue_name) }}" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">{{ __('Address') }}</label>
                        <input type="text" name="venue_address" value="{{ old('venue_address', $event->venue_address) }}" class="form-input">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Stadt</label>
                            <input type="text" name="venue_city" value="{{ old('venue_city', $event->venue_city) }}" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Land</label>
                            <select name="venue_country" class="form-select">
                                <option value="AT" {{ old('venue_country', $event->venue_country) === 'AT' ? 'selected' : '' }}>{{ __('Austria') }}</option>
                                <option value="DE" {{ old('venue_country', $event->venue_country) === 'DE' ? 'selected' : '' }}>{{ __('Germany') }}</option>
                                <option value="CH" {{ old('venue_country', $event->venue_country) === 'CH' ? 'selected' : '' }}>{{ __('Switzerland') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="section-title">{{ __('Special features') }}</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_outdoor" value="1" {{ old('is_outdoor', $event->is_outdoor) ? 'checked' : '' }} class="form-checkbox">
                        <span class="text-sm text-gray-700">Outdoor / Open-Air Veranstaltung</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_hybrid" value="1" {{ old('is_hybrid', $event->is_hybrid) ? 'checked' : '' }} class="form-checkbox">
                        <span class="text-sm text-gray-700">Hybride Veranstaltung</span>
                    </label>
                </div>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Änderungen speichern</button>
                <a href="{{ route('events.show', $event) }}" class="btn-secondary">{{ __('Cancel') }}</a>
                <form method="POST" action="{{ route('events.destroy', $event) }}" class="ml-auto"
                      onsubmit="return confirm('Veranstaltung wirklich löschen?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger">Löschen</button>
                </form>
            </div>
        </form>
    </div>
</div>
@endsection
