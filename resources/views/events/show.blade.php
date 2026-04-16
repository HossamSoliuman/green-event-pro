@extends('layouts.app')
@section('title', $event->title)
@section('page-title', $event->title)
@section('header-actions')
    <a href="{{ route('events.edit', $event) }}" class="btn-secondary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Bearbeiten
    </a>
    <button id="recalcBtn" onclick="recalculate()" class="btn-primary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        Score berechnen
    </button>
@endsection

@section('content')
<div x-data="{ tab: 'overview' }">

{{-- Event Header --}}
<div class="card mb-4">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="{{ $event->getStatusBadgeClass() }}">{{ ucfirst($event->status) }}</span>
                @if($event->is_hybrid)<span class="badge-blue">Hybrid</span>@endif
                @if($event->is_outdoor)<span class="badge-yellow">Outdoor</span>@endif
            </div>
            <p class="text-sm text-gray-500">{{ $event->type_label }} · {{ $event->venue_name }}, {{ $event->venue_city }} · {{ $event->start_date?->format('d.m.Y') }}@if($event->start_date != $event->end_date) – {{ $event->end_date?->format('d.m.Y') }}@endif</p>
            <p class="text-sm text-gray-500 mt-1">{{ number_format($event->expected_participants) }} Teilnehmer·innen · {{ $event->getDurationDays() }} Tag(e)</p>
        </div>
        <div class="flex items-center gap-6">
            {{-- UZ62 Score --}}
            <div class="text-center">
                <div class="text-2xl font-bold {{ $event->uz62_passed ? 'text-green-600' : ($event->uz62_percentage ? 'text-orange-500' : 'text-gray-400') }}">
                    {{ $event->uz62_percentage ? number_format($event->uz62_percentage, 1).'%' : '–' }}
                </div>
                <div class="text-xs text-gray-500 mt-0.5">UZ 62 Score</div>
                @if($event->uz62_passed)
                    <span class="badge-green mt-1">Bestanden</span>
                @elseif($event->uz62_percentage)
                    <span class="badge-red mt-1">Nicht bestanden</span>
                @endif
            </div>
            {{-- CO2 --}}
            <div class="text-center">
                <div class="text-2xl font-bold {{ $event->carbon_footprint_per_person <= 30 ? 'text-green-600' : ($event->carbon_footprint_per_person <= 100 ? 'text-yellow-600' : 'text-red-600') }}">
                    {{ $event->carbon_footprint_per_person ? number_format($event->carbon_footprint_per_person, 1) : '–' }}
                </div>
                <div class="text-xs text-gray-500 mt-0.5">kg CO₂/Person</div>
                @if($event->carbon_footprint_per_person <= 30 && $event->carbon_footprint_per_person)
                    <span class="badge-green mt-1">🌱 Green</span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Module Navigation Tabs --}}
<div class="mb-4 overflow-x-auto">
    <div class="flex gap-1 bg-white border border-gray-200 rounded-xl p-1 w-max min-w-full">
        @php
        $tabs = [
            'overview' => ['icon' => '📊', 'label' => 'Übersicht'],
            'mobility' => ['icon' => '🚆', 'label' => 'Mobilität'],
            'accommodation' => ['icon' => '🏨', 'label' => 'Unterkunft'],
            'venue' => ['icon' => '🏛️', 'label' => 'Veranstaltungsort'],
            'procurement' => ['icon' => '♻️', 'label' => 'Beschaffung'],
            'exhibitors' => ['icon' => '🏢', 'label' => 'Aussteller'],
            'catering' => ['icon' => '🍽️', 'label' => 'Verpflegung'],
            'communication' => ['icon' => '📣', 'label' => 'Kommunikation'],
            'social' => ['icon' => '🤝', 'label' => 'Soziales'],
            'technology' => ['icon' => '🔊', 'label' => 'Technik'],
            'tv_production' => ['icon' => '📺', 'label' => 'TV/Live'],
            'reports' => ['icon' => '📋', 'label' => 'Berichte'],
            'documents' => ['icon' => '📁', 'label' => 'Dokumente'],
        ];
        @endphp
        @foreach($tabs as $key => $tab)
        <button @click="tab = '{{ $key }}'"
            :class="tab === '{{ $key }}' ? 'bg-green-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50'"
            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition-all">
            <span>{{ $tab['icon'] }}</span>
            <span>{{ $tab['label'] }}</span>
        </button>
        @endforeach
    </div>
</div>

{{-- Overview Tab --}}
<div x-show="tab === 'overview'" x-cloak>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Module completion --}}
        <div class="card">
            <h3 class="section-title">Modul-Fortschritt</h3>
            @php
            $modules = [
                'Mobilität' => $event->mobility !== null,
                'Unterkunft' => $event->accommodations->isNotEmpty(),
                'Veranstaltungsort' => $event->venue !== null,
                'Beschaffung' => $event->procurement !== null,
                'Aussteller' => $event->exhibitor !== null,
                'Verpflegung' => $event->catering !== null,
                'Kommunikation' => $event->communication !== null,
                'Soziales' => $event->social !== null,
                'Technik' => $event->technology !== null,
                'TV/Live' => $event->tvProduction !== null,
            ];
            $completed = array_sum($modules);
            $total = count($modules);
            @endphp
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500">{{ $completed }}/{{ $total }} Module ausgefüllt</span>
                <span class="text-sm font-semibold text-gray-900">{{ round($completed / $total * 100) }}%</span>
            </div>
            <div class="h-2 bg-gray-100 rounded-full mb-4">
                <div class="h-2 bg-green-500 rounded-full transition-all" style="width: {{ $completed / $total * 100 }}%"></div>
            </div>
            <div class="space-y-2">
                @foreach($modules as $name => $done)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">{{ $name }}</span>
                    @if($done)
                        <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    @else
                        <span class="w-4 h-4 rounded-full border-2 border-gray-300 inline-block"></span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- UZ62 Score breakdown --}}
        <div class="card">
            <h3 class="section-title">UZ 62 Bewertung</h3>
            @if($event->latestUz62Score)
                @php $score = $event->latestUz62Score; @endphp
                <div class="flex items-center gap-4 mb-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold {{ $score->passed ? 'text-green-600' : 'text-red-500' }}">
                            {{ number_format($score->percentage, 1) }}%
                        </div>
                        <div class="text-xs text-gray-500">von 100%</div>
                    </div>
                    <div class="flex-1">
                        <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $score->passed ? 'bg-green-500' : 'bg-orange-400' }}"
                                 style="width: {{ min($score->percentage, 100) }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-400 mt-1">
                            <span>0%</span>
                            <span class="text-orange-500">28% Min.</span>
                            <span>100%</span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-xs text-gray-500 mb-1">Punkte erreicht</div>
                        <div class="font-semibold">{{ $score->points_achieved }} / {{ $score->points_max }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-xs text-gray-500 mb-1">MUSS-Kriterien</div>
                        <div class="font-semibold {{ $score->muss_passed ? 'text-green-600' : 'text-red-600' }}">
                            {{ $score->muss_passed ? '✓ Alle bestanden' : '✗ '.count($score->muss_failed_criteria ?? []).' fehlgeschlagen' }}
                        </div>
                    </div>
                </div>
                @if(!$score->muss_passed && $score->muss_failed_criteria)
                <div class="mt-3 p-3 bg-red-50 rounded-lg">
                    <p class="text-xs font-semibold text-red-700 mb-1">Fehlgeschlagene MUSS-Kriterien:</p>
                    <div class="flex flex-wrap gap-1">
                        @foreach($score->muss_failed_criteria as $code)
                            <span class="px-1.5 py-0.5 bg-red-100 text-red-700 text-xs rounded font-mono">{{ $code }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            @else
                <div class="text-center py-8 text-gray-400">
                    <p class="text-sm mb-3">Score noch nicht berechnet</p>
                    <button onclick="recalculate()" class="btn-primary text-xs">Jetzt berechnen</button>
                </div>
            @endif
        </div>

        {{-- Carbon Footprint --}}
        <div class="card">
            <h3 class="section-title">CO₂-Fußabdruck</h3>
            @if($event->latestCarbonReport)
                @php $cr = $event->latestCarbonReport; @endphp
                <div class="grid grid-cols-3 gap-3 mb-4">
                    <div class="text-center bg-gray-50 rounded-lg p-3">
                        <div class="text-lg font-bold text-gray-900">{{ number_format($cr->co2_total / 1000, 2) }}t</div>
                        <div class="text-xs text-gray-500">{{ __('Total CO2') }}</div>
                    </div>
                    <div class="text-center bg-gray-50 rounded-lg p-3">
                        <div class="text-lg font-bold {{ $cr->co2_per_person <= 30 ? 'text-green-600' : 'text-orange-500' }}">{{ number_format($cr->co2_per_person, 1) }}kg</div>
                        <div class="text-xs text-gray-500">Pro Person</div>
                    </div>
                    <div class="text-center bg-gray-50 rounded-lg p-3">
                        <div class="text-lg font-bold text-gray-900">{{ number_format($cr->co2_compensation / 1000, 2) }}t</div>
                        <div class="text-xs text-gray-500">Kompensiert</div>
                    </div>
                </div>
                <div class="space-y-2 text-xs">
                    @foreach([
                        ['Mobilität TN', $cr->co2_participant_travel],
                        ['Catering', $cr->co2_catering],
                        ['Unterkunft', $cr->co2_accommodation],
                        ['Energie/Technik', $cr->co2_venue_energy],
                        ['TV/Produktion', $cr->co2_tv_production_power],
                    ] as [$label, $val])
                    @if($val > 0)
                    <div class="flex items-center gap-2">
                        <span class="w-24 text-gray-500 flex-shrink-0">{{ $label }}</span>
                        <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-green-400 rounded-full" style="width: {{ $cr->co2_total > 0 ? min($val / $cr->co2_total * 100, 100) : 0 }}%"></div>
                        </div>
                        <span class="text-gray-600 w-16 text-right">{{ number_format($val, 0) }} kg</span>
                    </div>
                    @endif
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <p class="text-sm">CO₂-Bericht noch nicht erstellt</p>
                </div>
            @endif
        </div>

        {{-- Quick links to reports --}}
        <div class="card">
            <h3 class="section-title">Berichte & Exporte</h3>
            <div class="space-y-2">
                <a href="{{ route('events.reports.uz62', $event) }}" class="flex items-center justify-between p-3 rounded-lg border border-gray-100 hover:border-green-200 hover:bg-green-50 transition-colors group">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">📋</span>
                        <div>
                            <div class="text-sm font-medium text-gray-900">UZ 62 Scorecard</div>
                            <div class="text-xs text-gray-500">Vollständige Bewertung aller Kriterien</div>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('events.reports.carbon', $event) }}" class="flex items-center justify-between p-3 rounded-lg border border-gray-100 hover:border-green-200 hover:bg-green-50 transition-colors group">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">🌍</span>
                        <div>
                            <div class="text-sm font-medium text-gray-900">CO₂-Fußabdruck</div>
                            <div class="text-xs text-gray-500">Detaillierter Emissionsbericht</div>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('events.reports.checklist.pdf', $event) }}" class="flex items-center justify-between p-3 rounded-lg border border-gray-100 hover:border-green-200 hover:bg-green-50 transition-colors group">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">📄</span>
                        <div>
                            <div class="text-sm font-medium text-gray-900">Green Events Checkliste (PDF)</div>
                            <div class="text-xs text-gray-500">Ausgefüllte Checkliste für greeneventsaustria.at</div>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Module Tabs (each links to dedicated module page) --}}
@foreach(['mobility','accommodation','venue','procurement','exhibitors','catering','communication','social','technology','tv_production'] as $mod)
<div x-show="tab === '{{ $mod }}'" x-cloak>
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-base font-semibold text-gray-900">
                {{ ['mobility'=>'Modul 1 – Mobilität & Klimaschutz','accommodation'=>'Modul 2 – Unterkunft','venue'=>'Modul 3 – Veranstaltungsort','procurement'=>'Modul 4 – Beschaffung, Abfall & Energie','exhibitors'=>'Modul 5 – Aussteller & Standbauer','catering'=>'Modul 6 – Verpflegung & Gastronomie','communication'=>'Modul 7 – Kommunikation','social'=>'Modul 8 – Soziale Verantwortung','technology'=>'Modul 9 – Veranstaltungstechnik','tv_production'=>'Modul 10 – TV / Live-Produktion'][$mod] }}
            </h3>
            <a href="{{ route('events.modules.'.$mod.($mod === 'accommodation' ? '' : ''), $event) }}" class="btn-primary">
                Modul bearbeiten →
            </a>
        </div>
        <p class="text-sm text-gray-500">Klicken Sie auf "Modul bearbeiten" um die Daten für dieses Modul einzugeben und zu speichern.</p>
    </div>
</div>
@endforeach

{{-- Reports Tab --}}
<div x-show="tab === 'reports'" x-cloak>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('events.reports.uz62', $event) }}" class="card hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-2xl">📋</div>
                <div>
                    <h4 class="font-semibold text-gray-900">UZ 62 Scorecard</h4>
                    <p class="text-xs text-gray-500 mt-1">Alle Kriterien, MUSS-Prüfung, Punktestand</p>
                </div>
            </div>
        </a>
        <a href="{{ route('events.reports.carbon', $event) }}" class="card hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-2xl">🌍</div>
                <div>
                    <h4 class="font-semibold text-gray-900">CO₂-Fußabdruck</h4>
                    <p class="text-xs text-gray-500 mt-1">Emissionen nach Kategorien</p>
                </div>
            </div>
        </a>
        <a href="{{ route('events.reports.uz62.pdf', $event) }}" class="card hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-2xl">📄</div>
                <div>
                    <h4 class="font-semibold text-gray-900">UZ 62 Report (PDF)</h4>
                    <p class="text-xs text-gray-500 mt-1">Druckbarer Bewertungsbericht</p>
                </div>
            </div>
        </a>
        <a href="{{ route('events.reports.carbon.pdf', $event) }}" class="card hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-2xl">📄</div>
                <div>
                    <h4 class="font-semibold text-gray-900">CO₂-Report (PDF)</h4>
                    <p class="text-xs text-gray-500 mt-1">Druckbarer Emissionsbericht</p>
                </div>
            </div>
        </a>
        <a href="{{ route('events.reports.checklist.pdf', $event) }}" class="card hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center text-2xl">✅</div>
                <div>
                    <h4 class="font-semibold text-gray-900">Green Events Checkliste (PDF)</h4>
                    <p class="text-xs text-gray-500 mt-1">Ausgefüllte Einreichcheckliste</p>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- Documents Tab --}}
<div x-show="tab === 'documents'" x-cloak>
    <div class="card">
        <h3 class="section-title">Dokumente hochladen</h3>
        <form method="POST" action="{{ route('events.documents.store', $event) }}" enctype="multipart/form-data" class="mb-6">
            @csrf
            <div class="flex items-end gap-3">
                <div class="flex-1">
                    <label class="form-label">Datei auswählen</label>
                    <input type="file" name="file" required class="form-input">
                </div>
                <div>
                    <label class="form-label">Modul</label>
                    <select name="module" class="form-select w-40">
                        <option value="">Allgemein</option>
                        <option value="mobility">Mobilität</option>
                        <option value="accommodation">{{ __('Accommodation') }}</option>
                        <option value="venue">{{ __('Venue') }}</option>
                        <option value="procurement">{{ __('Procurement') }}</option>
                        <option value="catering">Verpflegung</option>
                        <option value="communication">{{ __('Communication') }}</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">Hochladen</button>
            </div>
        </form>

        @if($event->documents->isNotEmpty())
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs font-semibold text-gray-500 uppercase border-b border-gray-100">
                <th class="pb-2">Dateiname</th>
                <th class="pb-2">Modul</th>
                <th class="pb-2">Hochgeladen</th>
                <th class="pb-2"></th>
            </tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($event->documents as $doc)
                <tr>
                    <td class="py-2">{{ $doc->file_name }}</td>
                    <td class="py-2 text-gray-500">{{ $doc->module ?? 'Allgemein' }}</td>
                    <td class="py-2 text-gray-500">{{ $doc->created_at->format('d.m.Y') }}</td>
                    <td class="py-2 text-right">
                        <form method="POST" action="{{ route('events.documents.destroy', [$event, $doc]) }}">
                            @csrf @method('DELETE')
                            <button class="text-red-500 text-xs hover:text-red-700">Löschen</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p class="text-sm text-gray-400">Noch keine Dokumente hochgeladen.</p>
        @endif
    </div>
</div>

</div>{{-- end x-data --}}

@endsection
@push('scripts')
<script>
function recalculate() {
    const btn = document.getElementById('recalcBtn');
    btn.textContent = 'Wird berechnet...';
    btn.disabled = true;
    fetch('{{ route('events.recalculate', $event) }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) window.location.reload();
    })
    .catch(() => { btn.textContent = 'Fehler'; })
    .finally(() => { btn.disabled = false; });
}
</script>
@endpush
