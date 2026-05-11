@extends('layouts.app')
@section('title', __('module.accommodation') . ' – ' . $event->title)
@section('page-title', __('module_number', ['num' => 2]) . ': ' . __('module.accommodation'))
@section('content')
    <div class="mb-4">
        <a href="{{ route('events.show', $event) }}"
            class="text-sm text-green-600 hover:text-green-700">{{ __('back_to_event') }}</a>
        <p class="text-xs text-gray-500 mt-1">{{ __('accommodation.criteria_hint') }}</p>
    </div>

    
    <div class="card mb-6" x-data="{ open: false }">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-semibold text-gray-900">{{ __('accommodation.add_title') }}</h3>
            <button type="button" @click="open = !open" class="btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('accommodation.new_button') }}
            </button>
        </div>

        <div x-show="open" x-cloak class="mt-4 pt-4 border-t border-gray-100">
            <form method="POST" action="{{ route('events.modules.accommodation.store', $event) }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ __('accommodation.hotel_name') }} <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="hotel_name" required class="form-input"
                            placeholder="{{ __('accommodation.hotel_name_placeholder') }}">
                    </div>
                    <div>
                        <label class="form-label">{{ __('accommodation.city') }}</label>
                        <input type="text" name="hotel_city" class="form-input">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ __('accommodation.certification_type') }}</label>
                        <select name="certification_type" class="form-select">
                            <option value="none">{{ __('accommodation.cert_none') }}</option>
                            <option value="umweltzeichen">{{ __('accommodation.cert_umweltzeichen') }}</option>
                            <option value="eu_ecolabel">{{ __('accommodation.cert_eu_ecolabel') }}</option>
                            <option value="green_key">{{ __('accommodation.cert_green_key') }}</option>
                            <option value="emas">{{ __('accommodation.cert_emas') }}</option>
                            <option value="iso14001">{{ __('accommodation.cert_iso14001') }}</option>
                            <option value="other">{{ __('accommodation.cert_other') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">{{ __('accommodation.distance_to_venue') }}</label>
                        <input type="number" step="0.1" name="distance_to_venue_km" class="form-input">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ __('accommodation.contingent_reserved') }}</label>
                        <input type="number" name="contingent_reserved" class="form-input"
                            placeholder="{{ __('accommodation.rooms_count') }}">
                    </div>
                    <div>
                        <label class="form-label">{{ __('accommodation.nights') }}</label>
                        <input type="number" name="nights_reserved" class="form-input"
                            placeholder="{{ __('accommodation.nights_count') }}">
                    </div>
                </div>
                <div class="flex gap-3">
                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                        <input type="checkbox" name="has_env_certification" value="1" class="form-checkbox">
                        <span>{{ __('accommodation.has_env_certification') }}</span>
                    </label>
                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                        <input type="checkbox" name="hotel_informed_of_green_event" value="1" class="form-checkbox">
                        <span>{{ __('accommodation.informed_of_green_event') }}</span>
                    </label>
                </div>
                <button type="submit" class="btn-primary">{{ __('accommodation.save_button') }}</button>
            </form>
        </div>
    </div>

    
    @if ($accommodations->isEmpty())
        <div class="card text-center py-8 text-gray-400">
            <p class="text-sm">{{ __('accommodation.empty') }}</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($accommodations as $hotel)
                <div class="card">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $hotel->hotel_name }}</h4>
                            <p class="text-sm text-gray-500">{{ $hotel->hotel_city }} · {{ $hotel->distance_to_venue_km }}
                                {{ __('accommodation.km_to_venue') }}</p>
                            <div class="flex items-center gap-3 mt-2 text-sm">
                                @if ($hotel->has_env_certification)
                                    <span class="badge-green">✓ {{ __('accommodation.certified') }}
                                        ({{ $hotel->certification_type }})</span>
                                @else
                                    <span class="badge-gray">{{ __('accommodation.cert_none') }}</span>
                                @endif
                                @if ($hotel->hotel_informed_of_green_event)
                                    <span class="badge-green">✓ {{ __('accommodation.informed') }}</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 mt-1">{{ $hotel->contingent_reserved }}
                                {{ __('accommodation.rooms') }} · {{ $hotel->nights_reserved }}
                                {{ __('accommodation.nights') }}</p>
                        </div>
                        <form method="POST"
                            action="{{ route('events.modules.accommodation.destroy', [$event, $hotel]) }}">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="text-red-400 hover:text-red-600 text-xs">{{ __('Remove') }}</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        
        @php
            $totalPoints = 0;
            foreach ($accommodations as $h) {
                if ($h->has_env_certification) {
                    $totalPoints += 3;
                } elseif ($h->has_secondary_certification) {
                    $totalPoints += 2;
                } elseif ($h->self_declaration_completed && $h->self_declaration_points >= 15) {
                    $totalPoints += 1;
                }
            }
        @endphp
        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-xl">
            <p class="text-sm font-semibold text-green-800">
                {{ __('accommodation.points_summary', ['points' => min($totalPoints, 12)]) }}</p>
        </div>
    @endif
@endsection
