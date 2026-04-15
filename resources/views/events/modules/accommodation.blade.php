@extends('layouts.app')
@section('title', 'Unterkunft – '.$event->title)
@section('page-title', 'Modul 2: Unterkunft')
@section('content')
<div class="mb-4">
    <a href="{{ route('events.show', $event) }}" class="text-sm text-green-600 hover:text-green-700">← Zurück zur Veranstaltung</a>
    <p class="text-xs text-gray-500 mt-1">UZ 62 Kriterien U1–U3 · Max. 12 Punkte</p>
</div>

{{-- Add hotel form --}}
<div class="card mb-6" x-data="{ open: false }">
    <div class="flex items-center justify-between">
        <h3 class="text-base font-semibold text-gray-900">Unterkunft hinzufügen</h3>
        <button type="button" @click="open = !open" class="btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Neue Unterkunft
        </button>
    </div>

    <div x-show="open" x-cloak class="mt-4 pt-4 border-t border-gray-100">
        <form method="POST" action="{{ route('events.modules.accommodation.store', $event) }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Hotelname <span class="text-red-500">*</span></label>
                    <input type="text" name="hotel_name" required class="form-input" placeholder="z.B. Hotel Sacher Wien">
                </div>
                <div>
                    <label class="form-label">Stadt</label>
                    <input type="text" name="hotel_city" class="form-input">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Zertifizierungstyp (U3)</label>
                    <select name="certification_type" class="form-select">
                        <option value="none">Keine Zertifizierung</option>
                        <option value="umweltzeichen">Österreichisches Umweltzeichen (+3 P)</option>
                        <option value="eu_ecolabel">EU Ecolabel (+3 P)</option>
                        <option value="green_key">Green Key (+3 P)</option>
                        <option value="emas">EMAS (+3 P)</option>
                        <option value="iso14001">ISO 14001 (+2 P)</option>
                        <option value="other">Andere Zertifizierung</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Entfernung zum Veranstaltungsort (km)</label>
                    <input type="number" step="0.1" name="distance_to_venue_km" class="form-input">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Gebuchte Zimmer (Kontingent)</label>
                    <input type="number" name="contingent_reserved" class="form-input" placeholder="Anzahl Zimmer">
                </div>
                <div>
                    <label class="form-label">Nächte</label>
                    <input type="number" name="nights_reserved" class="form-input" placeholder="Anzahl Nächte">
                </div>
            </div>
            <div class="flex gap-3">
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" name="has_env_certification" value="1" class="form-checkbox">
                    <span>Hat Umweltzertifizierung (U1)</span>
                </label>
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" name="hotel_informed_of_green_event" value="1" class="form-checkbox">
                    <span>Über Green Event informiert (U2)</span>
                </label>
            </div>
            <button type="submit" class="btn-primary">Unterkunft speichern</button>
        </form>
    </div>
</div>

{{-- Accommodations list --}}
@if($accommodations->isEmpty())
    <div class="card text-center py-8 text-gray-400">
        <p class="text-sm">Noch keine Unterkünfte erfasst.</p>
    </div>
@else
    <div class="space-y-3">
        @foreach($accommodations as $hotel)
        <div class="card">
            <div class="flex items-start justify-between">
                <div>
                    <h4 class="font-semibold text-gray-900">{{ $hotel->hotel_name }}</h4>
                    <p class="text-sm text-gray-500">{{ $hotel->hotel_city }} · {{ $hotel->distance_to_venue_km }} km zum Veranstaltungsort</p>
                    <div class="flex items-center gap-3 mt-2 text-sm">
                        @if($hotel->has_env_certification)
                            <span class="badge-green">✓ Zertifiziert ({{ $hotel->certification_type }})</span>
                        @else
                            <span class="badge-gray">Keine Zertifizierung</span>
                        @endif
                        @if($hotel->hotel_informed_of_green_event)
                            <span class="badge-green">✓ Informiert</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-400 mt-1">{{ $hotel->contingent_reserved }} Zimmer · {{ $hotel->nights_reserved }} Nächte</p>
                </div>
                <form method="POST" action="{{ route('events.modules.accommodation.destroy', [$event, $hotel]) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-400 hover:text-red-600 text-xs">Entfernen</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Points summary --}}
    @php
        $totalPoints = 0;
        foreach($accommodations as $h) {
            if($h->has_env_certification) $totalPoints += 3;
            elseif($h->has_secondary_certification) $totalPoints += 2;
            elseif($h->self_declaration_completed && $h->self_declaration_points >= 15) $totalPoints += 1;
        }
    @endphp
    <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-xl">
        <p class="text-sm font-semibold text-green-800">U3 Punkte: {{ min($totalPoints, 12) }} / 12 Punkte</p>
    </div>
@endif
@endsection
