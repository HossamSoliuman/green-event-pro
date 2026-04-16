@extends('layouts.app')
@section('title', 'Mobilität – '.$event->title)
@section('page-title', 'Modul 1: Mobilität & Klimaschutz')
@section('content')
<div class="mb-4">
    <a href="{{ route('events.show', $event) }}" class="text-sm text-green-600 hover:text-green-700">← Zurück zur Veranstaltung</a>
    <p class="text-xs text-gray-500 mt-1">UZ 62 Kriterien M1–M17 · Max. 27,5 Punkte</p>
</div>

<form method="POST" action="{{ route('events.modules.mobility.update', $event) }}" x-data="{ incentive: '{{ $module->incentive_type ?? 'none' }}' }">
    @csrf @method('PUT')
    <div class="space-y-6">

    {{-- MUSS: Erreichbarkeit --}}
    <div class="card">
        <h3 class="section-title">Erreichbarkeit des Veranstaltungsortes <span class="muss-badge">MUSS</span></h3>
        <p class="text-xs text-gray-500 mb-4">M1: Der Veranstaltungsort muss ohne privaten PKW erreichbar sein.</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach([
                ['venue_accessible_by_public_transport', 'Öffentliche Verkehrsmittel verfügbar'],
                ['venue_accessible_by_foot', 'Zu Fuß erreichbar'],
                ['venue_accessible_by_bike', 'Mit Fahrrad erreichbar'],
                ['shuttle_service_provided', 'Shuttle-Service angeboten'],
            ] as [$field, $label])
            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:border-green-200 cursor-pointer">
                <input type="checkbox" name="{{ $field }}" value="1" {{ $module->$field ? 'checked' : '' }} class="form-checkbox">
                <span class="text-sm text-gray-700">{{ $label }}</span>
            </label>
            @endforeach
        </div>
        <div class="mt-4">
            <label class="form-label">Shuttle-Beschreibung</label>
            <input type="text" name="shuttle_service_description" value="{{ $module->shuttle_service_description }}" class="form-input" placeholder="z.B. Gratis-Shuttle vom Bahnhof Wien Mitte">
        </div>
        <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
                <label class="form-label">Entfernung nächster Flughafen (km)</label>
                <input type="number" step="0.1" name="venue_distance_from_int_airport_km" value="{{ $module->venue_distance_from_int_airport_km }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Entfernung nächster Fernbahnhof (km)</label>
                <input type="number" step="0.1" name="venue_distance_from_int_station_km" value="{{ $module->venue_distance_from_int_station_km }}" class="form-input">
            </div>
        </div>
    </div>

    {{-- Fahrrad-Infrastruktur --}}
    <div class="card">
        <h3 class="section-title">Fahrrad-Infrastruktur</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
            <div>
                <label class="form-label">Fahrradabstellplätze</label>
                <input type="number" name="bike_parking_spaces" value="{{ $module->bike_parking_spaces ?? 0 }}" class="form-input">
            </div>
            <div>
                <label class="form-label">E-Bike Ladestationen</label>
                <input type="number" name="ebike_charging_stations" value="{{ $module->ebike_charging_stations ?? 0 }}" class="form-input">
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach([
                ['bike_parking_secured', 'Gesicherter Abstellplatz (+0,5 P)'],
                ['bike_rental_available', 'Fahrradverleih (+0,5 P)'],
                ['bike_repair_station', 'Fahrradreparaturstation (+0,5 P)'],
                ['bike_route_communicated', 'Fahrradroute kommuniziert'],
            ] as [$field, $label])
            <label class="flex items-center gap-2 text-sm cursor-pointer">
                <input type="checkbox" name="{{ $field }}" value="1" {{ $module->$field ? 'checked' : '' }} class="form-checkbox">
                <span class="text-gray-700">{{ $label }}</span>
            </label>
            @endforeach
        </div>
    </div>

    {{-- Transport Incentives --}}
    <div class="card">
        <h3 class="section-title">Anreize für klimafreundliche Anreise</h3>
        <div class="mb-4">
            <label class="form-label">Art des Anreizes (M6)</label>
            <select name="incentive_type" x-model="incentive" class="form-select max-w-xs">
                <option value="none">Kein Anreiz</option>
                <option value="discount">Rabatt auf Ticket</option>
                <option value="lottery">Verlosung</option>
                <option value="ticket_included">Ticket im Preis inkludiert</option>
            </select>
        </div>
        <div x-show="incentive !== 'none'" class="space-y-4">
            <div>
                <label class="form-label">Beschreibung des Anreizes</label>
                <input type="text" name="incentive_description" value="{{ $module->incentive_description }}" class="form-input">
            </div>
            <div x-show="incentive === 'discount'">
                <label class="form-label">Rabatt in %</label>
                <input type="number" step="0.1" name="discount_percentage" value="{{ $module->discount_percentage }}" class="form-input max-w-xs">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3 mt-4">
            @foreach([
                ['ticket_cooperation_with_transport', 'Kooperation mit Verkehrsunternehmen (+1,5 P)', 'M6c'],
                ['transport_ticket_booked_for_participants', 'Ticket für TN gebucht (+3 P)', 'M6d'],
                ['carpooling_organized', 'Carpooling organisiert (+1 P)', 'M6e'],
                ['group_travel_organized', 'Gruppenreise organisiert (+1,5 P)', 'M6f'],
            ] as [$field, $label, $code])
            <label class="flex items-center gap-2 text-sm cursor-pointer">
                <input type="checkbox" name="{{ $field }}" value="1" {{ $module->$field ? 'checked' : '' }} class="form-checkbox">
                <span class="text-gray-700">{{ $label }} <span class="text-xs text-gray-400">{{ $code }}</span></span>
            </label>
            @endforeach
        </div>
    </div>

    {{-- Modal Split --}}
    <div class="card">
        <h3 class="section-title">Modal Split (M11) <span class="soll-badge">+1,5 P</span></h3>
        <label class="flex items-center gap-3 mb-4 cursor-pointer">
            <input type="checkbox" name="modal_split_surveyed" value="1" {{ $module->modal_split_surveyed ? 'checked' : '' }} class="form-checkbox">
            <span class="text-sm text-gray-700">Modal Split wurde erhoben / wird erhoben</span>
        </label>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            @foreach([
                ['modal_split_car_percent', 'PKW %'],
                ['modal_split_public_transport_percent', 'ÖV %'],
                ['modal_split_bike_percent', 'Fahrrad %'],
                ['modal_split_flight_percent', 'Flug %'],
                ['modal_split_other_percent', 'Sonstiges %'],
            ] as [$field, $label])
            <div>
                <label class="form-label text-xs">{{ $label }}</label>
                <input type="number" step="0.1" min="0" max="100" name="{{ $field }}" value="{{ $module->$field ?? 0 }}" class="form-input">
            </div>
            @endforeach
        </div>
    </div>

    {{-- GHG Compensation --}}
    <div class="card">
        <h3 class="section-title">Treibhausgaskompensation</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
            @foreach([
                ['ghg_compensation_communicated', 'Kompensation kommuniziert (+1 P)', 'M12'],
                ['ghg_calculation_done', 'THG-Bilanz berechnet (+2 P)', 'M13'],
                ['ghg_compensation_done', 'Kompensation durchgeführt (+3 P)', 'M14'],
            ] as [$field, $label, $code])
            <label class="flex items-center gap-2 text-sm cursor-pointer">
                <input type="checkbox" name="{{ $field }}" value="1" {{ $module->$field ? 'checked' : '' }} class="form-checkbox">
                <span class="text-gray-700">{{ $label }}</span>
            </label>
            @endforeach
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="form-label">Kompensations-Anbieter</label>
                <input type="text" name="ghg_compensation_provider" value="{{ $module->ghg_compensation_provider }}" class="form-input" placeholder="z.B. atmosfair, ClimatePartner">
            </div>
            <div>
                <label class="form-label">Kompensierte Menge (kg CO₂)</label>
                <input type="number" step="0.1" name="ghg_compensation_amount_kg" value="{{ $module->ghg_compensation_amount_kg }}" class="form-input">
            </div>
        </div>
    </div>

    {{-- Hybrid --}}
    @if($event->is_hybrid)
    <div class="card border-2 border-blue-200 bg-blue-50">
        <h3 class="section-title text-blue-800">Hybrid-Veranstaltung (M17) <span class="muss-badge">MUSS</span></h3>
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="hybrid_replaces_flights" value="1" {{ $module->hybrid_replaces_flights ? 'checked' : '' }} class="form-checkbox">
            <span class="text-sm text-blue-800 font-medium">Das Hybridformat ersetzt Flugreisen von Referent:innen / Teilnehmenden</span>
        </label>
        <div class="mt-3">
            <label class="form-label">Anzahl remote teilnehmender Referent:innen</label>
            <input type="number" name="hybrid_speakers_remote" value="{{ $module->hybrid_speakers_remote ?? 0 }}" class="form-input max-w-xs">
        </div>
    </div>
    @endif

    <div class="card">
        <label class="form-label">Notizen</label>
        <textarea name="notes" rows="3" class="form-input" placeholder="Weitere Hinweise zur Mobilität...">{{ $module->notes }}</textarea>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="btn-primary">Modul speichern</button>
        <a href="{{ route('events.show', $event) }}" class="btn-secondary">{{ __('Cancel') }}</a>
    </div>

    </div>
</form>
@endsection
