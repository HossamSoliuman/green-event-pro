<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1f2937; margin: 0; padding: 20px; }
h1 { font-size: 16px; color: white; }
h2 { font-size: 13px; color: #15803d; border-bottom: 1px solid #d1fae5; padding-bottom: 4px; margin: 16px 0 8px; }
.header { background: #166534; color: white; padding: 16px 20px; margin: -20px -20px 20px; }
.header p { color: #bbf7d0; margin: 2px 0 0; font-size: 9px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
th { background: #f0fdf4; text-align: left; padding: 5px 8px; font-size: 9px; text-transform: uppercase; color: #166534; border-bottom: 1px solid #d1fae5; }
td { padding: 5px 8px; border-bottom: 1px solid #f3f4f6; }
.kpi-grid { display: table; width: 100%; margin-bottom: 16px; }
.kpi-cell { display: table-cell; width: 25%; padding: 8px; text-align: center; background: #f0fdf4; border: 1px solid #d1fae5; }
.kpi-val { font-size: 20px; font-weight: bold; color: #166534; }
.kpi-label { font-size: 8px; color: #6b7280; margin-top: 2px; }
.bar-row { margin-bottom: 6px; }
.bar-label { font-size: 9px; color: #374151; margin-bottom: 2px; }
.bar-outer { background: #e5e7eb; height: 10px; border-radius: 4px; overflow: hidden; }
.bar-inner { background: #22c55e; height: 10px; }
footer { margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 8px; color: #9ca3af; font-size: 8px; text-align: center; }
</style>
</head>
<body>
<div class="header">
    <h1>🌍 CO₂-Fußabdruck Bericht</h1>
    <p>{{ $event->title }} · {{ $event->start_date?->format('d.m.Y') }} · {{ number_format($event->expected_participants) }} Teilnehmende</p>
    <p>Erstellt am {{ now()->format('d.m.Y H:i') }} · GreenEventPro</p>
</div>

<div class="kpi-grid">
    <div class="kpi-cell"><div class="kpi-val">{{ number_format($report->co2_total / 1000, 2) }}t</div><div class="kpi-label">Gesamt CO₂</div></div>
    <div class="kpi-cell"><div class="kpi-val">{{ number_format($report->co2_per_person, 1) }}kg</div><div class="kpi-label">CO₂ pro Person</div></div>
    <div class="kpi-cell"><div class="kpi-val">{{ number_format($report->co2_compensation / 1000, 2) }}t</div><div class="kpi-label">Kompensiert</div></div>
    <div class="kpi-cell"><div class="kpi-val">{{ number_format($report->co2_net / 1000, 2) }}t</div><div class="kpi-label">Netto CO₂</div></div>
</div>

<h2>CO₂ nach Emissionsquellen</h2>
@php
$rows = [
    ['Mobilität Teilnehmende', $report->co2_participant_travel],
    ['Verpflegung / Catering', $report->co2_catering],
    ['Unterkunft', $report->co2_accommodation],
    ['Energie / Veranstaltungsort', $report->co2_venue_energy],
    ['TV / Live-Produktion', $report->co2_tv_production_power],
    ['Equipment-Transport', $report->co2_equipment_transport],
    ['Mobilität Personal', $report->co2_staff_travel],
    ['TV-Team Reisen', $report->co2_tv_crew_travel],
    ['Sonstiges', $report->co2_other],
];
$maxVal = max(array_column($rows, 1) ?: [1]);
@endphp
@foreach($rows as [$label, $val])
@if($val > 0)
<div class="bar-row">
    <div class="bar-label">{{ $label }}: {{ number_format($val, 0) }} kg CO₂ ({{ $report->co2_total > 0 ? number_format($val / $report->co2_total * 100, 1) : 0 }}%)</div>
    <div class="bar-outer"><div class="bar-inner" style="width:{{ $maxVal > 0 ? min($val / $maxVal * 100, 100) : 0 }}%"></div></div>
</div>
@endif
@endforeach

<h2>Benchmarks</h2>
<table>
    <tr><th>Veranstaltung</th><th style="text-align:right">kg CO₂/Person</th><th>Bewertung</th></tr>
    <tr>
        <td>{{ $event->title }}</td>
        <td style="text-align:right"><strong>{{ number_format($report->co2_per_person, 1) }}</strong></td>
        <td>{{ $report->co2_per_person <= 30 ? '🌱 Sehr gut' : ($report->co2_per_person <= 100 ? '⚡ Durchschnittlich' : '⚠️ Verbesserungsbedarf') }}</td>
    </tr>
    <tr><td>Ø Green Event Ziel</td><td style="text-align:right">30,0</td><td>Benchmark</td></tr>
    <tr><td>Ø Standard Event</td><td style="text-align:right">100,0</td><td>Benchmark</td></tr>
</table>

<h2>Emissionsfaktoren (Quelle: BMKOES / UBA Austria)</h2>
<table>
    <tr><th>Verkehrsmittel/Quelle</th><th style="text-align:right">Faktor</th></tr>
    <tr><td>PKW (Durchschnitt)</td><td style="text-align:right">0,210 kg CO₂/km/Person</td></tr>
    <tr><td>Flugzeug (Kurzstrecke)</td><td style="text-align:right">0,255 kg CO₂/km/Person</td></tr>
    <tr><td>Zug (AT/DE-Durchschnitt)</td><td style="text-align:right">0,032 kg CO₂/km/Person</td></tr>
    <tr><td>Österr. Strommix</td><td style="text-align:right">0,158 kg CO₂/kWh</td></tr>
    <tr><td>Hotelübernachtung (Ø)</td><td style="text-align:right">18,0 kg CO₂/Nacht/Person</td></tr>
</table>

<footer>GreenEventPro CO₂-Bericht · Basierend auf internationalen Emissionsfaktoren (GHG Protocol, IPCC) · www.greenevents.at</footer>
</body>
</html>
