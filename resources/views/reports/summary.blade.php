@extends('layouts.app')
@section('title', 'Zusammenfassung – '.$event->title)
@section('page-title', 'Event-Zusammenfassung')
@section('header-actions')
    <a href="{{ route('events.show', $event) }}" class="btn-secondary">← Zurück</a>
@endsection
@section('content')
<div class="max-w-3xl space-y-6">
    <div class="card">
        <h2 class="text-lg font-bold text-gray-900 mb-4">{{ $event->title }}</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div><p class="text-xs text-gray-500">Typ</p><p class="font-medium">{{ $event->type_label }}</p></div>
            <div><p class="text-xs text-gray-500">Datum</p><p class="font-medium">{{ $event->start_date?->format('d.m.Y') }}</p></div>
            <div><p class="text-xs text-gray-500">Ort</p><p class="font-medium">{{ $event->venue_city }}</p></div>
            <div><p class="text-xs text-gray-500">Teilnehmer</p><p class="font-medium">{{ number_format($event->expected_participants) }}</p></div>
        </div>
    </div>

    @if($event->latestUz62Score)
    <div class="card border-l-4 {{ $event->latestUz62Score->passed ? 'border-green-500' : 'border-orange-400' }}">
        <h3 class="section-title">UZ 62 Ergebnis</h3>
        <div class="flex items-center gap-6">
            <div class="text-4xl font-bold {{ $event->latestUz62Score->passed ? 'text-green-600' : 'text-orange-500' }}">
                {{ number_format($event->latestUz62Score->percentage, 1) }}%
            </div>
            <div>
                <p class="text-sm font-semibold {{ $event->latestUz62Score->passed ? 'text-green-700' : 'text-orange-700' }}">
                    {{ $event->latestUz62Score->passed ? '✓ Zertifizierungsfähig' : '✗ Nicht zertifizierungsfähig' }}
                </p>
                <p class="text-xs text-gray-500 mt-1">{{ $event->latestUz62Score->points_achieved }} / {{ $event->latestUz62Score->points_max }} Punkte</p>
                <p class="text-xs text-gray-500">MUSS-Kriterien: {{ $event->latestUz62Score->muss_passed ? 'Alle bestanden' : count($event->latestUz62Score->muss_failed_criteria ?? []).' fehlgeschlagen' }}</p>
            </div>
        </div>
    </div>
    @endif

    @if($event->latestCarbonReport)
    <div class="card border-l-4 {{ $event->latestCarbonReport->co2_per_person <= 30 ? 'border-green-500' : 'border-yellow-400' }}">
        <h3 class="section-title">CO₂-Fußabdruck</h3>
        <div class="grid grid-cols-3 gap-4 text-center">
            <div><div class="text-2xl font-bold text-gray-900">{{ number_format($event->latestCarbonReport->co2_total / 1000, 2) }}t</div><div class="text-xs text-gray-500">Gesamt</div></div>
            <div><div class="text-2xl font-bold {{ $event->latestCarbonReport->co2_per_person <= 30 ? 'text-green-600' : 'text-yellow-600' }}">{{ number_format($event->latestCarbonReport->co2_per_person, 1) }}kg</div><div class="text-xs text-gray-500">Pro Person</div></div>
            <div><div class="text-2xl font-bold text-blue-600">{{ number_format($event->latestCarbonReport->co2_compensation / 1000, 2) }}t</div><div class="text-xs text-gray-500">Kompensiert</div></div>
        </div>
    </div>
    @endif
</div>
@endsection
