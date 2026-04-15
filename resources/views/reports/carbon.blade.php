@extends('layouts.app')
@section('title', 'CO₂-Bericht – '.$event->title)
@section('page-title', 'CO₂-Fußabdruck')
@section('header-actions')
    <a href="{{ route('events.reports.carbon.pdf', $event) }}" class="btn-secondary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        PDF exportieren
    </a>
    <a href="{{ route('events.show', $event) }}" class="btn-secondary">← Zurück</a>
@endsection
@section('content')

{{-- Summary KPIs --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @php
    $perPersonClass = $report->co2_per_person <= 30 ? 'text-green-600' : ($report->co2_per_person <= 100 ? 'text-yellow-600' : 'text-red-600');
    @endphp
    <div class="card text-center">
        <div class="text-3xl font-bold text-gray-900">{{ number_format($report->co2_total / 1000, 2) }}</div>
        <div class="text-xs text-gray-500 mt-1">Tonnen CO₂ gesamt</div>
    </div>
    <div class="card text-center">
        <div class="text-3xl font-bold {{ $perPersonClass }}">{{ number_format($report->co2_per_person, 1) }}</div>
        <div class="text-xs text-gray-500 mt-1">kg CO₂ pro Person</div>
    </div>
    <div class="card text-center">
        <div class="text-3xl font-bold text-blue-600">{{ number_format($report->co2_compensation / 1000, 2) }}</div>
        <div class="text-xs text-gray-500 mt-1">Tonnen kompensiert</div>
    </div>
    <div class="card text-center">
        <div class="text-3xl font-bold {{ $report->is_carbon_neutral ? 'text-green-600' : 'text-gray-900' }}">
            {{ $report->is_carbon_neutral ? '0' : number_format($report->co2_net / 1000, 2) }}
        </div>
        <div class="text-xs text-gray-500 mt-1">Tonnen CO₂ netto</div>
        @if($report->is_carbon_neutral)
            <span class="badge-green mt-1">🌱 Klimaneutral</span>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Bar chart --}}
    <div class="card">
        <h3 class="section-title">CO₂ nach Kategorien</h3>
        <canvas id="carbonBarChart" height="220"></canvas>
    </div>

    {{-- Comparison --}}
    <div class="card">
        <h3 class="section-title">Vergleich mit Benchmarks</h3>
        <div class="space-y-4">
            @php
            $benchmarks = [
                ['Ihre Veranstaltung', $report->co2_per_person, $report->co2_per_person <= 30 ? 'bg-green-500' : ($report->co2_per_person <= 100 ? 'bg-yellow-500' : 'bg-red-500')],
                ['Ø Green Event', 30, 'bg-green-400'],
                ['Ø Standard Event', 100, 'bg-gray-400'],
            ];
            $maxVal = max(array_column($benchmarks, 1));
            @endphp
            @foreach($benchmarks as [$label, $val, $color])
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">{{ $label }}</span>
                    <span class="font-semibold">{{ number_format($val, 1) }} kg/P</span>
                </div>
                <div class="h-4 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full {{ $color }} rounded-full" style="width: {{ $maxVal > 0 ? min($val / $maxVal * 100, 100) : 0 }}%"></div>
                </div>
            </div>
            @endforeach

            <div class="mt-4 p-3 bg-gray-50 rounded-lg text-xs text-gray-500">
                <p><strong>Benchmark Quellen:</strong> Green Events Austria / BMKOES 2023</p>
                <p class="mt-1">Ø Green Event: &lt;30 kg CO₂/Person · Ø Standard Event: ~100 kg CO₂/Person</p>
            </div>
        </div>
    </div>
</div>

{{-- Detailed breakdown table --}}
<div class="card">
    <h3 class="section-title">Detaillierte CO₂-Aufschlüsselung</h3>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-xs font-semibold text-gray-500 uppercase border-b border-gray-100">
                <th class="pb-3">Emissionsquelle</th>
                <th class="pb-3 text-right">kg CO₂</th>
                <th class="pb-3 text-right">Anteil</th>
                <th class="pb-3">Anteil (Grafik)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @php
            $rows = [
                ['Mobilität Teilnehmende', $report->co2_participant_travel],
                ['Mobilität Personal', $report->co2_staff_travel],
                ['TV-Team Reisen', $report->co2_tv_crew_travel],
                ['Equipment Transport', $report->co2_equipment_transport],
                ['Energie / Veranstaltungsort', $report->co2_venue_energy],
                ['Verpflegung / Catering', $report->co2_catering],
                ['Unterkunft', $report->co2_accommodation],
                ['TV-Produktion Strom', $report->co2_tv_production_power],
                ['Sonstiges', $report->co2_other],
            ];
            @endphp
            @foreach($rows as [$label, $val])
            @if($val > 0)
            <tr>
                <td class="py-2.5 text-gray-700">{{ $label }}</td>
                <td class="py-2.5 text-right font-mono text-gray-900">{{ number_format($val, 1) }}</td>
                <td class="py-2.5 text-right text-gray-500">{{ $report->co2_total > 0 ? number_format($val / $report->co2_total * 100, 1) : 0 }}%</td>
                <td class="py-2.5 pl-4">
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden w-32">
                        <div class="h-full bg-green-400 rounded-full" style="width: {{ $report->co2_total > 0 ? min($val / $report->co2_total * 100, 100) : 0 }}%"></div>
                    </div>
                </td>
            </tr>
            @endif
            @endforeach
            <tr class="border-t-2 border-gray-300 font-semibold">
                <td class="py-2.5 text-gray-900">Gesamt (brutto)</td>
                <td class="py-2.5 text-right font-mono">{{ number_format($report->co2_total, 1) }}</td>
                <td class="py-2.5 text-right">100%</td>
                <td></td>
            </tr>
            @if($report->co2_compensation > 0)
            <tr class="text-blue-700">
                <td class="py-2.5">Kompensation</td>
                <td class="py-2.5 text-right font-mono">-{{ number_format($report->co2_compensation, 1) }}</td>
                <td></td><td></td>
            </tr>
            <tr class="font-bold text-green-700">
                <td class="py-2.5">Netto CO₂</td>
                <td class="py-2.5 text-right font-mono">{{ number_format($report->co2_net, 1) }}</td>
                <td></td><td></td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

@endsection
@push('scripts')
<script>
const ctx = document.getElementById('carbonBarChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Mobilität TN', 'Catering', 'Unterkunft', 'Energie', 'TV/Prod.', 'Transport', 'Sonstiges'],
        datasets: [{
            label: 'kg CO₂',
            data: [
                {{ $report->co2_participant_travel }},
                {{ $report->co2_catering }},
                {{ $report->co2_accommodation }},
                {{ $report->co2_venue_energy }},
                {{ $report->co2_tv_production_power }},
                {{ $report->co2_equipment_transport }},
                {{ $report->co2_other }}
            ],
            backgroundColor: ['#4ade80','#86efac','#22c55e','#16a34a','#15803d','#166534','#d1fae5'],
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { callback: v => v + ' kg' } } }
    }
});
</script>
@endpush
