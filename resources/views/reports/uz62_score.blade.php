@extends('layouts.app')
@section('title', 'UZ 62 Scorecard – '.$event->title)
@section('page-title', 'UZ 62 Zertifizierungsbewertung')
@section('header-actions')
    <a href="{{ route('events.reports.uz62.pdf', $event) }}" class="btn-secondary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        PDF exportieren
    </a>
    <a href="{{ route('events.show', $event) }}" class="btn-secondary">← Zurück</a>
@endsection
@section('content')

{{-- Overall result --}}
<div class="card mb-6">
    <div class="flex flex-wrap items-center gap-8">
        <div class="text-center">
            <div class="text-5xl font-bold {{ $details['passed'] ? 'text-green-600' : 'text-red-500' }} mb-1">
                {{ number_format($details['percentage'], 1) }}%
            </div>
            <div class="text-sm text-gray-500">Gesamtpunktzahl</div>
            <div class="mt-2">
                @if($details['passed'])
                    <span class="badge-green text-sm px-3 py-1">✓ Zertifizierungsvoraussetzungen erfüllt</span>
                @else
                    <span class="badge-red text-sm px-3 py-1">✗ Voraussetzungen nicht erfüllt</span>
                @endif
            </div>
        </div>
        <div class="flex-1">
            <div class="h-4 bg-gray-100 rounded-full overflow-hidden mb-2">
                <div class="h-full rounded-full transition-all {{ $details['passed'] ? 'bg-green-500' : 'bg-orange-400' }}"
                     style="width: {{ min($details['percentage'], 100) }}%"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-500">
                <span>0%</span>
                <span class="text-orange-600 font-medium">28% Mindestanforderung</span>
                <span>100%</span>
            </div>
            <div class="mt-3 grid grid-cols-3 gap-4 text-sm">
                <div class="text-center bg-gray-50 rounded-lg p-2">
                    <div class="font-bold text-gray-900">{{ $details['points_achieved'] }}</div>
                    <div class="text-xs text-gray-500">Punkte erreicht</div>
                </div>
                <div class="text-center bg-gray-50 rounded-lg p-2">
                    <div class="font-bold text-gray-900">{{ $details['points_max'] }}</div>
                    <div class="text-xs text-gray-500">Punkte möglich</div>
                </div>
                <div class="text-center {{ $details['muss_passed'] ? 'bg-green-50' : 'bg-red-50' }} rounded-lg p-2">
                    <div class="font-bold {{ $details['muss_passed'] ? 'text-green-700' : 'text-red-700' }}">
                        {{ $details['muss_passed'] ? '✓ Alle' : '✗ '.count($details['muss_failed']).' offen' }}
                    </div>
                    <div class="text-xs text-gray-500">MUSS-Kriterien</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Failed MUSS criteria warning --}}
@if(!$details['muss_passed'] && !empty($details['muss_failed']))
<div class="card mb-6 border-red-200 bg-red-50">
    <h3 class="text-sm font-semibold text-red-800 mb-3">
        ⚠️ Fehlgeschlagene MUSS-Kriterien ({{ count($details['muss_failed']) }})
    </h3>
    <p class="text-xs text-red-600 mb-3">Alle MUSS-Kriterien müssen erfüllt sein, um eine Zertifizierung zu erhalten.</p>
    <div class="flex flex-wrap gap-2">
        @foreach($details['muss_failed'] as $code)
        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-mono rounded-md border border-red-200">{{ $code }}</span>
        @endforeach
    </div>
</div>
@endif

{{-- Section breakdown --}}
<div class="space-y-4">
    @php
    $sectionLabels = [
        'mobility' => ['🚆', 'Mobilität & Klimaschutz', 'M1–M17'],
        'accommodation' => ['🏨', 'Unterkunft', 'U1–U3'],
        'venue' => ['🏛️', 'Veranstaltungsort', 'Va/Vb'],
        'procurement' => ['♻️', 'Beschaffung, Abfall & Energie', 'B1–B33'],
        'exhibitors' => ['🏢', 'Aussteller & Standbauer', 'A1–A8'],
        'catering' => ['🍽️', 'Verpflegung & Gastronomie', 'C1–C34, VK1–VK4'],
        'communication' => ['📣', 'Kommunikation', 'K1–K7'],
        'social' => ['🤝', 'Soziale Verantwortung', 'S1–S13'],
        'technology' => ['🔊', 'Veranstaltungstechnik', 'T1–T5'],
    ];
    @endphp

    @foreach($details['sections'] as $sectionKey => $section)
    @if(isset($sectionLabels[$sectionKey]))
    @php [$icon, $label, $codes] = $sectionLabels[$sectionKey]; @endphp
    <div class="card" x-data="{ open: false }">
        <button type="button" @click="open = !open" class="w-full flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-xl">{{ $icon }}</span>
                <div class="text-left">
                    <div class="text-sm font-semibold text-gray-900">{{ $label }}</div>
                    <div class="text-xs text-gray-400">{{ $codes }}</div>
                </div>
            </div>
            <div class="flex items-center gap-4">
                @if(!empty($section['muss_failed']))
                    <span class="badge-red">{{ count($section['muss_failed']) }} MUSS fehlt</span>
                @else
                    <span class="badge-green">MUSS ✓</span>
                @endif
                <div class="text-right">
                    <div class="text-sm font-bold text-gray-900">{{ $section['points_achieved'] }} / {{ $section['points_max'] }} P</div>
                    <div class="text-xs text-gray-400">{{ $section['points_max'] > 0 ? number_format($section['points_achieved'] / $section['points_max'] * 100, 0) : 0 }}%</div>
                </div>
                <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </button>

        {{-- Progress bar --}}
        <div class="mt-3">
            <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full {{ empty($section['muss_failed']) ? 'bg-green-400' : 'bg-red-400' }}"
                     style="width: {{ $section['points_max'] > 0 ? min($section['points_achieved'] / $section['points_max'] * 100, 100) : 0 }}%"></div>
            </div>
        </div>

        <div x-show="open" x-cloak class="mt-4 pt-4 border-t border-gray-100">
            @if(!empty($section['muss_failed']))
            <div class="mb-3 p-3 bg-red-50 rounded-lg">
                <p class="text-xs font-semibold text-red-700 mb-1">Fehlende MUSS-Kriterien:</p>
                <div class="flex flex-wrap gap-1">
                    @foreach($section['muss_failed'] as $code)
                        <span class="px-1.5 py-0.5 bg-red-100 text-red-700 text-xs rounded font-mono">{{ $code }}</span>
                    @endforeach
                </div>
            </div>
            @endif
            <p class="text-xs text-gray-500">Erzielte Punkte: <strong>{{ $section['points_achieved'] }}</strong> von maximal <strong>{{ $section['points_max'] }}</strong> möglichen Punkten in diesem Abschnitt.</p>
            <div class="mt-3">
                @php
                $moduleRoute = [
                    'mobility' => 'events.modules.mobility',
                    'accommodation' => 'events.modules.accommodation',
                    'venue' => 'events.modules.venue',
                    'procurement' => 'events.modules.procurement',
                    'exhibitors' => 'events.modules.exhibitors',
                    'catering' => 'events.modules.catering',
                    'communication' => 'events.modules.communication',
                    'social' => 'events.modules.social',
                    'technology' => 'events.modules.technology',
                ][$sectionKey] ?? null;
                @endphp
                @if($moduleRoute)
                <a href="{{ route($moduleRoute, $event) }}" class="text-xs text-green-600 hover:text-green-700 font-medium">
                    Modul bearbeiten →
                </a>
                @endif
            </div>
        </div>
    </div>
    @endif
    @endforeach
</div>

{{-- Certification CTA --}}
@if($details['passed'])
<div class="mt-6 card bg-green-50 border-green-200 text-center">
    <div class="text-3xl mb-2">🏆</div>
    <h3 class="text-lg font-bold text-green-800 mb-2">Herzlichen Glückwunsch!</h3>
    <p class="text-sm text-green-700 mb-4">Ihre Veranstaltung erfüllt die Voraussetzungen für das Österreichische Umweltzeichen UZ 62 „Green Meeting & Green Event".</p>
    <a href="https://www.greeneventsaustria.at" target="_blank" class="btn-primary">
        Zertifizierung beantragen bei greeneventsaustria.at →
    </a>
</div>
@endif
@endsection
