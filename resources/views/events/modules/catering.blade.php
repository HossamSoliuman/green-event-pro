@extends('layouts.app')
@section('title', 'Verpflegung – '.$event->title)
@section('page-title', 'Modul 6: Verpflegung & Gastronomie')
@section('content')
<div class="mb-4">
    <a href="{{ route('events.show', $event) }}" class="text-sm text-green-600 hover:text-green-700">← Zurück zur Veranstaltung</a>
    <p class="text-xs text-gray-500 mt-1">UZ 62 Kriterien C1–C34, VK1–VK4 · Max. 39,5 Punkte</p>
</div>

<form method="POST" action="{{ route('events.modules.catering.update', $event) }}"
    x-data="{ cateringType: '{{ $module->catering_type ?? 'external_caterer' }}', hasStands: {{ $module->food_stands_count > 0 ? 'true' : 'false' }} }">
    @csrf @method('PUT')
    <div class="space-y-6">

    {{-- Caterer Info --}}
    <div class="card">
        <h3 class="section-title">Catering-Anbieter</h3>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="form-label">Catering-Art</label>
                <select name="catering_type" x-model="cateringType" class="form-select">
                    <option value="external_caterer">Externer Caterer</option>
                    <option value="own_kitchen">Eigene Küche</option>
                    <option value="gastronomy">Gastronomie im Haus</option>
                    <option value="mixed">Gemischt</option>
                    <option value="none">Keine Verpflegung</option>
                </select>
            </div>
            <div>
                <label class="form-label">Name des Caterers</label>
                <input type="text" name="catering_company_name" value="{{ $module->catering_company_name }}" class="form-input">
            </div>
        </div>
        <div>
            <label class="form-label">Caterer-Zertifizierungen (Auto-Fill Kriterien)</label>
            <div class="grid grid-cols-2 gap-3 mt-2">
                <label class="flex items-center gap-2 p-3 border border-gray-100 rounded-lg cursor-pointer hover:border-green-200">
                    <input type="checkbox" name="caterer_has_umweltzeichen" value="1" {{ $module->caterer_has_umweltzeichen ? 'checked' : '' }} class="form-checkbox">
                    <span class="text-sm">🌿 Österreichisches Umweltzeichen (+3 P, füllt C2–C17 aus)</span>
                </label>
                <label class="flex items-center gap-2 p-3 border border-gray-100 rounded-lg cursor-pointer hover:border-green-200">
                    <input type="checkbox" name="caterer_is_100pct_vegan_bio" value="1" {{ $module->caterer_is_100pct_vegan_bio ? 'checked' : '' }} class="form-checkbox">
                    <span class="text-sm">🌱 100% veganes Bio-Catering (füllt mehrere Kriterien aus)</span>
                </label>
                <label class="flex items-center gap-2 p-3 border border-gray-100 rounded-lg cursor-pointer hover:border-green-200">
                    <input type="checkbox" name="caterer_has_bio_certification" value="1" {{ $module->caterer_has_bio_certification ? 'checked' : '' }} class="form-checkbox">
                    <span class="text-sm">Bio-Zertifizierung (+2 P)</span>
                </label>
            </div>
        </div>
    </div>

    {{-- MUSS Criteria --}}
    <div class="card">
        <h3 class="section-title">MUSS-Kriterien Verpflegung <span class="muss-badge">MUSS – alle erforderlich</span></h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @php
            $mussCriteria = [
                ['reusable_cups_and_dishes', 'C2 – Mehrweg-Geschirr und Tassen', true],
                ['drinks_in_bulk_containers', 'C3 – Getränke in Mehrweggebinden, keine Kapseln', true],
                ['food_waste_eco_disposal', 'C4 – Lebensmittelabfälle fachgerecht entsorgt', true],
                ['no_open_front_coolers', 'C5 – Keine Open-Front-Kühler', true],
                ['no_gas_patio_heaters', 'C6 – Keine Gaspilze/Heizstrahler', true],
                ['free_tap_water', 'C7 – Kostenloses Leitungswasser', true],
                ['two_seasonal_regional_ingredients', 'C8 – Mind. 2 saisonale/regionale Hauptzutaten', true],
                ['two_regional_drinks', 'C9 – Mind. 2 regionale Getränke', true],
                ['one_bio_drink_and_ingredient', 'C10 – Mind. 1 Bio-Getränk und 1 Bio-Zutat', true],
                ['one_fair_trade_product', 'C11 – Mind. 1 Fairtrade-Produkt', true],
                ['sustainable_seafood', 'C12 – Nachhaltige Meeresfrüchte (MSC/ASC)', true],
                ['no_endangered_species', 'C13 – Keine bedrohten Arten', true],
                ['free_range_eggs', 'C14 – Freilandeier (mind. Bodenhaltung)', true],
                ['vegetarian_option', 'C15 – Vegetarisches Hauptgericht', true],
                ['staff_informed', 'C16 – Catering-Personal informiert', true],
                ['quality_communicated_to_guests', 'C17 – Qualität nach außen kommuniziert', true],
            ];
            @endphp
            @foreach($mussCriteria as [$field, $label])
            <label class="flex items-start gap-3 p-3 rounded-lg border border-gray-100 hover:border-green-200 cursor-pointer">
                <input type="checkbox" name="{{ $field }}" value="1" {{ $module->$field ? 'checked' : '' }} class="form-checkbox mt-0.5">
                <span class="text-sm text-gray-700">{{ $label }}</span>
            </label>
            @endforeach
        </div>
    </div>

    {{-- Bio-Anteil --}}
    <div class="card">
        <h3 class="section-title">Bio-Anteil (SOLL)</h3>
        <div class="space-y-3">
            @foreach([
                ['bio_100pct', 'C19a – 100% Bio (+5 P)'],
                ['bio_50pct_main_and_drinks', 'C19b – 50% Bio (Hauptspeisen + Getränke) (+3,5 P)'],
                ['bio_30pct_main_and_drinks', 'C19c – 30% Bio (Hauptspeisen + Getränke) (+3 P)'],
                ['bio_50pct_main_only', 'C19d – 50% Bio (nur Hauptspeisen) (+2 P)'],
                ['bio_30pct_main_only', 'C19e – 30% Bio (nur Hauptspeisen) (+1 P)'],
            ] as [$field, $label])
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="radio" name="bio_level" value="{{ $field }}"
                    {{ $module->$field ? 'checked' : '' }} class="h-4 w-4 text-green-600 border-gray-300">
                <span class="text-sm text-gray-700">{{ $label }}</span>
            </label>
            @endforeach
        </div>
    </div>

    {{-- Vegetarisch/Vegan --}}
    <div class="card">
        <h3 class="section-title">Vegetarisch & Vegan (SOLL)</h3>
        <div class="grid grid-cols-2 gap-3">
            <label class="flex items-center gap-3 p-3 border border-gray-100 rounded-lg cursor-pointer hover:border-green-200">
                <input type="checkbox" name="vegetarian_full_menu" value="1" {{ $module->vegetarian_full_menu ? 'checked' : '' }} class="form-checkbox">
                <span class="text-sm">C32 – Vollständiges vegetarisches Menü (+2 P)</span>
            </label>
            <label class="flex items-center gap-3 p-3 border border-gray-100 rounded-lg cursor-pointer hover:border-green-200">
                <input type="checkbox" name="vegan_full_menu" value="1" {{ $module->vegan_full_menu ? 'checked' : '' }} class="form-checkbox">
                <span class="text-sm">C33 – Vollständiges veganes Menü (+3 P)</span>
            </label>
        </div>
    </div>

    {{-- Fairtrade --}}
    <div class="card">
        <h3 class="section-title">Fairtrade-Produkte (C26)</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach([
                ['fair_trade_coffee', 'Kaffee (+1 P)'],
                ['fair_trade_tea', 'Tee (+0,5 P)'],
                ['fair_trade_cocoa', 'Kakao'],
                ['fair_trade_chocolate', 'Schokolade'],
            ] as [$field, $label])
            <label class="flex items-center gap-2 text-sm cursor-pointer">
                <input type="checkbox" name="{{ $field }}" value="1" {{ $module->$field ? 'checked' : '' }} class="form-checkbox">
                <span class="text-gray-700">{{ $label }}</span>
            </label>
            @endforeach
        </div>
    </div>

    {{-- Additional SOLL --}}
    <div class="card">
        <h3 class="section-title">Weitere SOLL-Kriterien</h3>
        <div class="grid grid-cols-2 gap-3">
            @foreach([
                ['no_still_mineral_water', 'C34 – Kein stilles Mineralwasser (+1 P)'],
                ['origin_labeled_on_menu', 'C17a – Herkunft auf Speisekarte ausgewiesen'],
                ['food_waste_calculated', 'C30a – Lebensmittelverluste berechnet (+1 P)'],
                ['leftover_food_solution', 'C30b – Lösung für Speisereste (Tafel, etc.) (+1 P)'],
                ['special_diet_allergies', 'C31a – Allergie-Speisenauswahl (+0,5 P)'],
                ['special_diet_religious', 'C31b – Religiöse Ernährungsformen (+0,5 P)'],
                ['eco_cleaning_dishwash', 'C28 – Ökologische Reinigungsmittel (+1 P)'],
                ['regional_typical_dishes', 'C27 – Regionale Spezialitäten (+1 P)'],
            ] as [$field, $label])
            <label class="flex items-center gap-2 p-3 border border-gray-100 rounded-lg cursor-pointer hover:border-green-200 text-sm">
                <input type="checkbox" name="{{ $field }}" value="1" {{ $module->$field ? 'checked' : '' }} class="form-checkbox">
                <span class="text-gray-700">{{ $label }}</span>
            </label>
            @endforeach
        </div>
        <div class="mt-4">
            <label class="form-label">Anzahl lokaler Spezialitäten (C25 – je 0,5 P, max. 2 P)</label>
            <input type="number" name="local_specialties_count" value="{{ $module->local_specialties_count ?? 0 }}" class="form-input max-w-xs" min="0" max="4">
        </div>
    </div>

    {{-- Food Stands --}}
    <div class="card">
        <h3 class="section-title">Verköstigungsstände (VK1–VK4)</h3>
        <div class="mb-4">
            <label class="form-label">Anzahl Verköstigungsstände</label>
            <input type="number" name="food_stands_count" value="{{ $module->food_stands_count ?? 0 }}" class="form-input max-w-xs" min="0"
                x-on:change="hasStands = $event.target.value > 0">
        </div>
        <div x-show="hasStands" class="space-y-3">
            @foreach([
                ['food_stands_briefed', 'VK1 – Standbetreiber:innen informiert (MUSS)'],
                ['food_stands_contracted', 'VK2 – Vertragliche Vereinbarung (MUSS)'],
                ['food_stands_veg_options_min2', 'VK3 – Mind. 2 vegetarische Gerichte (MUSS)'],
                ['food_stands_50pct_voluntary', 'VK4 – 50% freiwillig nachhaltig (+3 P)'],
                ['food_stands_100pct_voluntary', 'VK4 – 100% freiwillig nachhaltig (+6 P)'],
            ] as [$field, $label])
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="{{ $field }}" value="1" {{ $module->$field ? 'checked' : '' }} class="form-checkbox">
                <span class="text-sm text-gray-700">{{ $label }}</span>
            </label>
            @endforeach
        </div>
    </div>

    <div class="card">
        <label class="form-label">Notizen</label>
        <textarea name="notes" rows="3" class="form-input">{{ $module->notes }}</textarea>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="btn-primary">Modul speichern</button>
        <a href="{{ route('events.show', $event) }}" class="btn-secondary">Abbrechen</a>
    </div>

    </div>
</form>
@endsection
