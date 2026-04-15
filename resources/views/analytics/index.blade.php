@extends('layouts.app')
@section('title', 'Analytics')
@section('page-title', 'Multi-Event Analytics')
@section('header-actions')
    <a href="{{ route('analytics.export') }}" class="btn-secondary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        CSV Export
    </a>
@endsection
@section('content')

@if($events->isEmpty())
<div class="card text-center py-12 text-gray-400">
    <p class="text-sm">Keine Veranstaltungen mit CO₂-Daten vorhanden.</p>
    <p class="text-xs mt-1">Erstellen Sie Veranstaltungen und berechnen Sie den CO₂-Fußabdruck.</p>
</div>
@else

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="card">
        <h3 class="section-title">CO₂ pro Person – Entwicklung</h3>
        <canvas id="co2Chart" height="220"></canvas>
    </div>
    <div class="card">
        <h3 class="section-title">UZ 62 Score – Entwicklung</h3>
        <canvas id="scoreChart" height="220"></canvas>
    </div>
</div>

<div class="card">
    <h3 class="section-title">Alle Veranstaltungen im Vergleich</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                    <th class="pb-3 pr-4">Veranstaltung</th>
                    <th class="pb-3 pr-4">Datum</th>
                    <th class="pb-3 pr-4">Typ</th>
                    <th class="pb-3 pr-4">TN</th>
                    <th class="pb-3 pr-4">CO₂ Gesamt</th>
                    <th class="pb-3 pr-4">CO₂/Person</th>
                    <th class="pb-3 pr-4">UZ 62 Score</th>
                    <th class="pb-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($events as $event)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 pr-4">
                        <a href="{{ route('events.show', $event) }}" class="font-medium text-gray-900 hover:text-green-600">{{ $event->title }}</a>
                    </td>
                    <td class="py-3 pr-4 text-gray-500 text-xs whitespace-nowrap">{{ $event->start_date?->format('d.m.Y') }}</td>
                    <td class="py-3 pr-4 text-gray-500 text-xs">{{ $event->type_label }}</td>
                    <td class="py-3 pr-4 text-gray-600">{{ number_format($event->expected_participants) }}</td>
                    <td class="py-3 pr-4 text-gray-600">
                        {{ $event->carbon_footprint_kg_co2 ? number_format($event->carbon_footprint_kg_co2 / 1000, 2).'t' : '–' }}
                    </td>
                    <td class="py-3 pr-4">
                        @if($event->carbon_footprint_per_person)
                            <span class="{{ $event->carbon_footprint_per_person <= 30 ? 'text-green-600' : ($event->carbon_footprint_per_person <= 100 ? 'text-yellow-600' : 'text-red-600') }} font-medium">
                                {{ number_format($event->carbon_footprint_per_person, 1) }} kg
                            </span>
                        @else
                            <span class="text-gray-400">–</span>
                        @endif
                    </td>
                    <td class="py-3 pr-4">
                        @if($event->uz62_percentage)
                            <div class="flex items-center gap-2">
                                <span class="{{ $event->uz62_passed ? 'text-green-600' : 'text-orange-500' }} font-semibold">
                                    {{ number_format($event->uz62_percentage, 1) }}%
                                </span>
                                <div class="w-16 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $event->uz62_passed ? 'bg-green-500' : 'bg-orange-400' }} rounded-full"
                                         style="width: {{ min($event->uz62_percentage, 100) }}%"></div>
                                </div>
                            </div>
                        @else
                            <span class="text-gray-400">–</span>
                        @endif
                    </td>
                    <td class="py-3"><span class="{{ $event->getStatusBadgeClass() }}">{{ ucfirst($event->status) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection

@push('scripts')
@if(!$events->isEmpty())
<script>
const labels = @json($co2ByEvent->pluck('title'));
const co2Data = @json($co2ByEvent->pluck('co2_per_person'));
const scoreData = @json($co2ByEvent->pluck('uz62_score'));

new Chart(document.getElementById('co2Chart').getContext('2d'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'kg CO₂/Person',
            data: co2Data,
            borderColor: '#16a34a',
            backgroundColor: 'rgba(22,163,74,0.1)',
            tension: 0.3,
            fill: true,
            pointBackgroundColor: '#16a34a',
        }, {
            label: 'Ziel (30 kg)',
            data: labels.map(() => 30),
            borderColor: '#86efac',
            borderDash: [5,5],
            pointRadius: 0,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } },
        scales: { y: { beginAtZero: true, ticks: { callback: v => v + ' kg' } } }
    }
});

new Chart(document.getElementById('scoreChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            label: 'UZ 62 Score (%)',
            data: scoreData,
            backgroundColor: scoreData.map(v => v >= 28 ? '#4ade80' : '#fb923c'),
            borderRadius: 6,
        }, {
            label: 'Mindestanforderung (28%)',
            data: labels.map(() => 28),
            type: 'line',
            borderColor: '#f97316',
            borderDash: [5,5],
            pointRadius: 0,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } },
        scales: { y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } } }
    }
});
</script>
@endif
@endpush
