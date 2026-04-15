<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1f2937; margin: 0; padding: 20px; }
h1 { font-size: 18px; color: #166534; margin-bottom: 4px; }
h2 { font-size: 13px; color: #15803d; border-bottom: 1px solid #d1fae5; padding-bottom: 4px; margin-top: 16px; margin-bottom: 8px; }
h3 { font-size: 11px; color: #374151; margin: 12px 0 4px; }
.header { background: #166534; color: white; padding: 16px 20px; margin: -20px -20px 20px; }
.header h1 { color: white; margin: 0; font-size: 16px; }
.header p { color: #bbf7d0; margin: 4px 0 0; font-size: 9px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
th { background: #f0fdf4; text-align: left; padding: 5px 8px; font-size: 9px; text-transform: uppercase; color: #166534; border-bottom: 1px solid #d1fae5; }
td { padding: 5px 8px; border-bottom: 1px solid #f3f4f6; }
.pass { color: #16a34a; font-weight: bold; }
.fail { color: #dc2626; font-weight: bold; }
.badge-pass { background: #dcfce7; color: #166534; padding: 2px 6px; border-radius: 3px; font-size: 8px; }
.badge-fail { background: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 3px; font-size: 8px; }
.score-box { background: #f0fdf4; border: 1px solid #bbf7d0; padding: 12px; margin-bottom: 16px; text-align: center; }
.score-big { font-size: 32px; font-weight: bold; }
.info-grid { display: table; width: 100%; }
.info-cell { display: table-cell; width: 50%; padding-right: 10px; vertical-align: top; }
.muss-tag { background: #fee2e2; color: #991b1b; padding: 1px 4px; border-radius: 2px; font-size: 8px; font-weight: bold; }
.soll-tag { background: #dbeafe; color: #1d4ed8; padding: 1px 4px; border-radius: 2px; font-size: 8px; font-weight: bold; }
footer { margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 8px; color: #9ca3af; font-size: 8px; text-align: center; }
</style>
</head>
<body>
<div class="header">
    <h1>🌿 GreenEventPro – UZ 62 Bewertungsbericht</h1>
    <p>Österreichisches Umweltzeichen UZ 62 „Green Meetings und Green Events" · Erstellt am {{ now()->format('d.m.Y H:i') }}</p>
</div>

<div class="info-grid">
    <div class="info-cell">
        <h2>Veranstaltungsinformationen</h2>
        <table>
            <tr><td><strong>Titel</strong></td><td>{{ $event->title }}</td></tr>
            <tr><td><strong>Typ</strong></td><td>{{ $event->type_label }}</td></tr>
            <tr><td><strong>Datum</strong></td><td>{{ $event->start_date?->format('d.m.Y') }} – {{ $event->end_date?->format('d.m.Y') }}</td></tr>
            <tr><td><strong>Ort</strong></td><td>{{ $event->venue_name }}, {{ $event->venue_city }}</td></tr>
            <tr><td><strong>Teilnehmer</strong></td><td>{{ number_format($event->expected_participants) }}</td></tr>
        </table>
    </div>
    <div class="info-cell">
        <div class="score-box">
            <div class="score-big {{ $details['passed'] ? 'pass' : 'fail' }}">
                {{ number_format($details['percentage'], 1) }}%
            </div>
            <div style="color:#6b7280; font-size:9px; margin-top:4px;">von 100% (Mindest: 28%)</div>
            <div style="margin-top:8px;">
                @if($details['passed'])
                    <span class="badge-pass">✓ ZERTIFIZIERUNGSFÄHIG</span>
                @else
                    <span class="badge-fail">✗ VORAUSSETZUNGEN NICHT ERFÜLLT</span>
                @endif
            </div>
            <div style="margin-top:8px; font-size:9px; color:#374151;">
                Punkte: {{ $details['points_achieved'] }} / {{ $details['points_max'] }}
            </div>
        </div>
    </div>
</div>

<h2>MUSS-Kriterien Übersicht</h2>
@if($details['muss_passed'])
    <p class="pass">✓ Alle MUSS-Kriterien wurden erfüllt.</p>
@else
    <p class="fail">✗ Folgende MUSS-Kriterien wurden NICHT erfüllt und müssen vor der Zertifizierung behoben werden:</p>
    <table>
        <tr><th>Kriterium-Code</th><th>Status</th></tr>
        @foreach($details['muss_failed'] as $code)
        <tr>
            <td><span class="muss-tag">MUSS</span> {{ $code }}</td>
            <td class="fail">✗ Nicht erfüllt</td>
        </tr>
        @endforeach
    </table>
@endif

<h2>Punkte nach Abschnitten</h2>
<table>
    <tr>
        <th>Abschnitt</th>
        <th>MUSS</th>
        <th style="text-align:right">Erreicht</th>
        <th style="text-align:right">Möglich</th>
        <th style="text-align:right">%</th>
    </tr>
    @php
    $sectionNames = [
        'mobility' => 'Mobilität (M1–M17)',
        'accommodation' => 'Unterkunft (U1–U3)',
        'venue' => 'Veranstaltungsort (Va/Vb)',
        'procurement' => 'Beschaffung/Abfall (B1–B33)',
        'exhibitors' => 'Aussteller (A1–A8)',
        'catering' => 'Verpflegung (C1–C34)',
        'communication' => 'Kommunikation (K1–K7)',
        'social' => 'Soziales (S1–S13)',
        'technology' => 'Technik (T1–T5)',
    ];
    @endphp
    @foreach($details['sections'] as $key => $section)
    @if(isset($sectionNames[$key]))
    <tr>
        <td>{{ $sectionNames[$key] }}</td>
        <td>
            @if(empty($section['muss_failed']))
                <span class="badge-pass">✓ OK</span>
            @else
                <span class="badge-fail">✗ {{ count($section['muss_failed']) }} fehlen</span>
            @endif
        </td>
        <td style="text-align:right">{{ $section['points_achieved'] }}</td>
        <td style="text-align:right">{{ $section['points_max'] }}</td>
        <td style="text-align:right">{{ $section['points_max'] > 0 ? number_format($section['points_achieved'] / $section['points_max'] * 100, 1) : 0 }}%</td>
    </tr>
    @endif
    @endforeach
    <tr style="font-weight:bold; background:#f0fdf4;">
        <td>GESAMT</td>
        <td>@if($details['muss_passed'])<span class="badge-pass">✓ Alle OK</span>@else<span class="badge-fail">✗ Fehler</span>@endif</td>
        <td style="text-align:right">{{ $details['points_achieved'] }}</td>
        <td style="text-align:right">{{ $details['points_max'] }}</td>
        <td style="text-align:right">{{ number_format($details['percentage'], 1) }}%</td>
    </tr>
</table>

<footer>
    Dieser Bericht wurde automatisch von GreenEventPro erstellt. Grundlage: Österreichisches Umweltzeichen UZ 62 „Green Meetings und Green Events", Version 5.1, Ausgabe 1. Juli 2022.
    Für die offizielle Einreichung wenden Sie sich an: www.greeneventsaustria.at
</footer>
</body>
</html>
