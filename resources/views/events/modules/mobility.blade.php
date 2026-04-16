@extends('layouts.app')
@section('title', __('module.mobility') . ' – ' . $event->title)
@section('page-title', __('module_number', ['num' => 1]) . ': ' . __('module.mobility'))
@section('content')
    <div class="mb-4">
        <a href="{{ route('events.show', $event) }}"
            class="text-sm text-green-600 hover:text-green-700">{{ __('back_to_event') }}</a>
        <p class="text-xs text-gray-500 mt-1">{{ __('mobility.criteria_hint') }}</p>
    </div>

    <form method="POST" action="{{ route('events.modules.mobility.update', $event) }}" x-data="{ incentive: '{{ $module->incentive_type ?? 'none' }}' }">
        @csrf @method('PUT')
        <div class="space-y-6">

            {{-- MUSS: Erreichbarkeit --}}
            <div class="card">
                <h3 class="section-title">{{ __('mobility.accessibility_title') }} <span
                        class="muss-badge">{{ __('Must') }}</span></h3>
                <p class="text-xs text-gray-500 mb-4">{{ __('mobility.m1_hint') }}</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach ([['venue_accessible_by_public_transport', __('mobility.public_transport')], ['venue_accessible_by_foot', __('mobility.by_foot')], ['venue_accessible_by_bike', __('mobility.by_bike')], ['shuttle_service_provided', __('mobility.shuttle_service')]] as [$field, $label])
                        <label
                            class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:border-green-200 cursor-pointer">
                            <input type="checkbox" name="{{ $field }}" value="1"
                                {{ $module->$field ? 'checked' : '' }} class="form-checkbox">
                            <span class="text-sm text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="mt-4">
                    <label class="form-label">{{ __('mobility.shuttle_description') }}</label>
                    <input type="text" name="shuttle_service_description"
                        value="{{ $module->shuttle_service_description }}" class="form-input"
                        placeholder="{{ __('mobility.shuttle_placeholder') }}">
                </div>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="form-label">{{ __('mobility.distance_airport') }}</label>
                        <input type="number" step="0.1" name="venue_distance_from_int_airport_km"
                            value="{{ $module->venue_distance_from_int_airport_km }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">{{ __('mobility.distance_station') }}</label>
                        <input type="number" step="0.1" name="venue_distance_from_int_station_km"
                            value="{{ $module->venue_distance_from_int_station_km }}" class="form-input">
                    </div>
                </div>
            </div>

            {{-- Fahrrad-Infrastruktur --}}
            <div class="card">
                <h3 class="section-title">{{ __('mobility.bike_infra_title') }}</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="form-label">{{ __('mobility.bike_parking_spaces') }}</label>
                        <input type="number" name="bike_parking_spaces" value="{{ $module->bike_parking_spaces ?? 0 }}"
                            class="form-input">
                    </div>
                    <div>
                        <label class="form-label">{{ __('mobility.ebike_charging') }}</label>
                        <input type="number" name="ebike_charging_stations"
                            value="{{ $module->ebike_charging_stations ?? 0 }}" class="form-input">
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach ([['bike_parking_secured', __('mobility.bike_parking_secured')], ['bike_rental_available', __('mobility.bike_rental')], ['bike_repair_station', __('mobility.bike_repair')], ['bike_route_communicated', __('mobility.bike_route_communicated')]] as [$field, $label])
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="checkbox" name="{{ $field }}" value="1"
                                {{ $module->$field ? 'checked' : '' }} class="form-checkbox">
                            <span class="text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Transport Incentives --}}
            <div class="card">
                <h3 class="section-title">{{ __('mobility.incentives_title') }}</h3>
                <div class="mb-4">
                    <label class="form-label">{{ __('mobility.incentive_type') }}</label>
                    <select name="incentive_type" x-model="incentive" class="form-select max-w-xs">
                        <option value="none">{{ __('mobility.incentive_none') }}</option>
                        <option value="discount">{{ __('mobility.incentive_discount') }}</option>
                        <option value="lottery">{{ __('mobility.incentive_lottery') }}</option>
                        <option value="ticket_included">{{ __('mobility.incentive_ticket_included') }}</option>
                    </select>
                </div>
                <div x-show="incentive !== 'none'" class="space-y-4">
                    <div>
                        <label class="form-label">{{ __('mobility.incentive_description') }}</label>
                        <input type="text" name="incentive_description" value="{{ $module->incentive_description }}"
                            class="form-input">
                    </div>
                    <div x-show="incentive === 'discount'">
                        <label class="form-label">{{ __('mobility.discount_percentage') }}</label>
                        <input type="number" step="0.1" name="discount_percentage"
                            value="{{ $module->discount_percentage }}" class="form-input max-w-xs">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 mt-4">
                    @foreach ([['ticket_cooperation_with_transport', __('mobility.m6c'), 'M6c'], ['transport_ticket_booked_for_participants', __('mobility.m6d'), 'M6d'], ['carpooling_organized', __('mobility.m6e'), 'M6e'], ['group_travel_organized', __('mobility.m6f'), 'M6f']] as [$field, $label, $code])
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="checkbox" name="{{ $field }}" value="1"
                                {{ $module->$field ? 'checked' : '' }} class="form-checkbox">
                            <span class="text-gray-700">{{ $label }} <span
                                    class="text-xs text-gray-400">{{ $code }}</span></span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Modal Split --}}
            <div class="card">
                <h3 class="section-title">{{ __('mobility.modal_split_title') }} <span class="soll-badge">+1,5 P</span>
                </h3>
                <label class="flex items-center gap-3 mb-4 cursor-pointer">
                    <input type="checkbox" name="modal_split_surveyed" value="1"
                        {{ $module->modal_split_surveyed ? 'checked' : '' }} class="form-checkbox">
                    <span class="text-sm text-gray-700">{{ __('mobility.modal_split_surveyed') }}</span>
                </label>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    @foreach ([['modal_split_car_percent', __('mobility.ms_car')], ['modal_split_public_transport_percent', __('mobility.ms_public_transport')], ['modal_split_bike_percent', __('mobility.ms_bike')], ['modal_split_flight_percent', __('mobility.ms_flight')], ['modal_split_other_percent', __('mobility.ms_other')]] as [$field, $label])
                        <div>
                            <label class="form-label text-xs">{{ $label }}</label>
                            <input type="number" step="0.1" min="0" max="100"
                                name="{{ $field }}" value="{{ $module->$field ?? 0 }}" class="form-input">
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- GHG Compensation --}}
            <div class="card">
                <h3 class="section-title">{{ __('mobility.ghg_title') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                    @foreach ([['ghg_compensation_communicated', __('mobility.m12'), 'M12'], ['ghg_calculation_done', __('mobility.m13'), 'M13'], ['ghg_compensation_done', __('mobility.m14'), 'M14']] as [$field, $label, $code])
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="checkbox" name="{{ $field }}" value="1"
                                {{ $module->$field ? 'checked' : '' }} class="form-checkbox">
                            <span class="text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ __('mobility.ghg_provider') }}</label>
                        <input type="text" name="ghg_compensation_provider"
                            value="{{ $module->ghg_compensation_provider }}" class="form-input"
                            placeholder="{{ __('mobility.ghg_provider_placeholder') }}">
                    </div>
                    <div>
                        <label class="form-label">{{ __('mobility.ghg_amount') }}</label>
                        <input type="number" step="0.1" name="ghg_compensation_amount_kg"
                            value="{{ $module->ghg_compensation_amount_kg }}" class="form-input">
                    </div>
                </div>
            </div>

            {{-- Hybrid --}}
            @if ($event->is_hybrid)
                <div class="card border-2 border-blue-200 bg-blue-50">
                    <h3 class="section-title text-blue-800">{{ __('mobility.hybrid_title') }} <span
                            class="muss-badge">{{ __('Must') }}</span></h3>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="hybrid_replaces_flights" value="1"
                            {{ $module->hybrid_replaces_flights ? 'checked' : '' }} class="form-checkbox">
                        <span
                            class="text-sm text-blue-800 font-medium">{{ __('mobility.hybrid_replaces_flights') }}</span>
                    </label>
                    <div class="mt-3">
                        <label class="form-label">{{ __('mobility.hybrid_speakers_remote') }}</label>
                        <input type="number" name="hybrid_speakers_remote"
                            value="{{ $module->hybrid_speakers_remote ?? 0 }}" class="form-input max-w-xs">
                    </div>
                </div>
            @endif

            <div class="card">
                <label class="form-label">{{ __('Notes') }}</label>
                <textarea name="notes" rows="3" class="form-input" placeholder="{{ __('mobility.notes_placeholder') }}">{{ $module->notes }}</textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">{{ __('Save') }}</button>
                <a href="{{ route('events.show', $event) }}" class="btn-secondary">{{ __('Cancel') }}</a>
            </div>

        </div>
    </form>
@endsection
