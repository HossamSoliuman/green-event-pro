@extends('layouts.app')
@section('title', 'Neue Veranstaltung')
@section('page-title', 'Neue Veranstaltung erstellen')
@section('content')
<div class="max-w-2xl">
    <div class="card">
        <form method="POST" action="{{ route('events.store') }}" class="space-y-6">
            @csrf

            <div>
                <h3 class="section-title">Grundinformationen</h3>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Veranstaltungstitel <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required class="form-input"
                            placeholder="z.B. Jahreskonferenz Nachhaltigkeit 2025">
                    </div>
                    <div>
                        <label class="form-label">Veranstaltungstyp <span class="text-red-500">*</span></label>
                        <select name="type" required class="form-select">
                            <option value="">Bitte wählen...</option>
                            @foreach($eventTypes as $key => $label)
                                <option value="{{ $key }}" {{ old('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Beschreibung</label>
                        <textarea name="description" rows="3" class="form-input">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="section-title">Datum & Teilnehmer</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Startdatum <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Enddatum <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" required class="form-input">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="form-label">Erwartete Teilnehmeranzahl <span class="text-red-500">*</span></label>
                    <input type="number" name="expected_participants" value="{{ old('expected_participants') }}" required min="1" class="form-input">
                </div>
            </div>

            <div>
                <h3 class="section-title">Veranstaltungsort</h3>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Name der Location <span class="text-red-500">*</span></label>
                        <input type="text" name="venue_name" value="{{ old('venue_name') }}" required class="form-input"
                            placeholder="z.B. Wiener Stadthalle">
                    </div>
                    <div>
                        <label class="form-label">Adresse</label>
                        <input type="text" name="venue_address" value="{{ old('venue_address') }}" class="form-input">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Stadt <span class="text-red-500">*</span></label>
                            <input type="text" name="venue_city" value="{{ old('venue_city') }}" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Land <span class="text-red-500">*</span></label>
                            <select name="venue_country" class="form-select">
                                <option value="AT" {{ old('venue_country', 'AT') === 'AT' ? 'selected' : '' }}>Österreich</option>
                                <option value="DE" {{ old('venue_country') === 'DE' ? 'selected' : '' }}>Deutschland</option>
                                <option value="CH" {{ old('venue_country') === 'CH' ? 'selected' : '' }}>Schweiz</option>
                                <option value="Other">Sonstiges</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Breitengrad (optional)</label>
                            <input type="number" step="0.000001" name="venue_lat" value="{{ old('venue_lat') }}" class="form-input" placeholder="48.2082">
                        </div>
                        <div>
                            <label class="form-label">Längengrad (optional)</label>
                            <input type="number" step="0.000001" name="venue_lng" value="{{ old('venue_lng') }}" class="form-input" placeholder="16.3738">
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="section-title">Besonderheiten</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_outdoor" value="1" {{ old('is_outdoor') ? 'checked' : '' }} class="form-checkbox">
                        <span class="text-sm text-gray-700">Outdoor / Open-Air Veranstaltung (Vb-Kriterien gelten)</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_hybrid" value="1" {{ old('is_hybrid') ? 'checked' : '' }} class="form-checkbox">
                        <span class="text-sm text-gray-700">Hybride Veranstaltung (M17 wird MUSS-Kriterium)</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    Veranstaltung erstellen
                </button>
                <a href="{{ route('events.index') }}" class="btn-secondary">Abbrechen</a>
            </div>
        </form>
    </div>
</div>
@endsection
