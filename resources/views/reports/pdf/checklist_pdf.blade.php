<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #1f2937; margin: 0; padding: 16px; }
h1 { font-size: 14px; color: white; margin: 0; }
h2 { font-size: 11px; color: #166534; border-bottom: 2px solid #22c55e; padding-bottom: 3px; margin: 14px 0 6px; }
.header { background: #166534; padding: 12px 16px; margin: -16px -16px 16px; }
.header p { color: #bbf7d0; font-size: 8px; margin: 3px 0 0; }
table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
th { background: #f0fdf4; padding: 4px 6px; text-align: left; font-size: 8px; color: #166534; border-bottom: 1px solid #d1fae5; }
td { padding: 4px 6px; border-bottom: 1px solid #f9fafb; vertical-align: top; }
.check-cell { width: 16px; text-align: center; font-size: 12px; }
.muss { color: #dc2626; font-size: 7px; font-weight: bold; border: 1px solid #dc2626; padding: 1px 2px; border-radius: 2px; }
.soll { color: #2563eb; font-size: 7px; font-weight: bold; border: 1px solid #2563eb; padding: 1px 2px; border-radius: 2px; }
.pts { color: #059669; font-size: 8px; font-weight: bold; }
footer { margin-top: 14px; border-top: 1px solid #e5e7eb; padding-top: 6px; font-size: 7px; color: #9ca3af; }
.two-col { column-count: 2; column-gap: 16px; }
</style>
</head>
<body>
<div class="header">
    <h1>Green Events Austria – Checkliste UZ 62</h1>
    <p>Österreichisches Umweltzeichen „Green Meetings und Green Events" – Version 5.1 (2022/2023)</p>
    <p>Veranstaltung: {{ $event->title }} · {{ $event->start_date?->format('d.m.Y') }} · {{ $event->venue_city }}</p>
</div>

<table>
    <tr>
        <td><strong>Veranstaltungstitel:</strong> {{ $event->title }}</td>
        <td><strong>Typ:</strong> {{ $event->type_label }}</td>
        <td><strong>Teilnehmer:</strong> {{ number_format($event->expected_participants) }}</td>
    </tr>
    <tr>
        <td><strong>Datum:</strong> {{ $event->start_date?->format('d.m.Y') }} – {{ $event->end_date?->format('d.m.Y') }}</td>
        <td><strong>Ort:</strong> {{ $event->venue_name }}, {{ $event->venue_city }}</td>
        <td><strong>Hybrid:</strong> {{ $event->is_hybrid ? 'Ja' : 'Nein' }}</td>
    </tr>
</table>

<h2>A. Mobilität & Klimaschutz</h2>
@php $m = $event->mobility; @endphp
<table>
    <tr><th class="check-cell">✓</th><th>Kriterium</th><th width="50">Typ</th><th width="40">Punkte</th></tr>
    @foreach([
        [$m?->venue_accessible_by_public_transport || $m?->shuttle_service_provided, 'M1 – Veranstaltungsort ohne PKW erreichbar', 'MUSS', '–'],
        [$m?->ticket_cooperation_with_transport, 'M6c – Kooperation mit Verkehrsunternehmen', 'SOLL', '1,5'],
        [$m?->transport_ticket_booked_for_participants, 'M6d – Ticket für TN gebucht', 'SOLL', '3,0'],
        [$m?->carpooling_organized, 'M6e – Carpooling organisiert', 'SOLL', '1,0'],
        [$m?->modal_split_surveyed, 'M11 – Modal Split erhoben', 'SOLL', '1,5'],
        [$m?->ghg_calculation_done, 'M13 – THG-Bilanz berechnet', 'SOLL', '2,0'],
        [$m?->ghg_compensation_done, 'M14 – Kompensation durchgeführt', 'SOLL', '3,0'],
    ] as [$val, $label, $type, $pts])
    <tr>
        <td class="check-cell">{{ $val ? '☑' : '☐' }}</td>
        <td>{{ $label }}</td>
        <td><span class="{{ strtolower($type) }}">{{ $type }}</span></td>
        <td class="pts">{{ $pts }}</td>
    </tr>
    @endforeach
</table>

<h2>B. Unterkunft</h2>
@php $a = $event->accommodations; @endphp
<table>
    <tr><th class="check-cell">✓</th><th>Kriterium</th><th width="50">Typ</th><th width="40">Punkte</th></tr>
    <tr>
        <td class="check-cell">{{ $a->where('has_env_certification', true)->isNotEmpty() ? '☑' : '☐' }}</td>
        <td>U1 – Mind. 1 Unterkunft mit Umweltzertifizierung</td>
        <td><span class="muss">MUSS</span></td>
        <td class="pts">–</td>
    </tr>
    <tr>
        <td class="check-cell">{{ $a->where('hotel_informed_of_green_event', true)->isNotEmpty() ? '☑' : '☐' }}</td>
        <td>U2 – Unterkünfte über Green Event informiert</td>
        <td><span class="muss">MUSS</span></td>
        <td class="pts">–</td>
    </tr>
    <tr>
        <td class="check-cell">{{ $a->isNotEmpty() ? '☑' : '☐' }}</td>
        <td>U3 – Zertifizierte Unterkünfte (je 1–3 Punkte)</td>
        <td><span class="soll">SOLL</span></td>
        <td class="pts">max 12</td>
    </tr>
</table>

<h2>C. Verpflegung (Auswahl MUSS-Kriterien)</h2>
@php $c = $event->catering; @endphp
<table>
    <tr><th class="check-cell">✓</th><th>Kriterium</th><th width="50">Typ</th><th width="40">Punkte</th></tr>
    @foreach([
        [$c?->reusable_cups_and_dishes, 'C2 – Mehrweg-Geschirr', 'MUSS', '–'],
        [$c?->free_tap_water, 'C7 – Kostenloses Leitungswasser', 'MUSS', '–'],
        [$c?->two_seasonal_regional_ingredients, 'C8 – 2 saisonale/regionale Hauptzutaten', 'MUSS', '–'],
        [$c?->one_bio_drink_and_ingredient, 'C10 – 1 Bio-Produkt und 1 Bio-Getränk', 'MUSS', '–'],
        [$c?->one_fair_trade_product, 'C11 – 1 Fairtrade-Produkt', 'MUSS', '–'],
        [$c?->vegetarian_option, 'C15 – Vegetarisches Gericht', 'MUSS', '–'],
        [$c?->vegan_full_menu, 'C33 – Vollständig veganes Menü', 'SOLL', '3,0'],
        [$c?->bio_100pct, 'C19a – 100% Bio', 'SOLL', '5,0'],
    ] as [$val, $label, $type, $pts])
    <tr>
        <td class="check-cell">{{ $val ? '☑' : '☐' }}</td>
        <td>{{ $label }}</td>
        <td><span class="{{ strtolower($type) }}">{{ $type }}</span></td>
        <td class="pts">{{ $pts }}</td>
    </tr>
    @endforeach
</table>

<h2>D. Kommunikation</h2>
@php $k = $event->communication; @endphp
<table>
    <tr><th class="check-cell">✓</th><th>Kriterium</th><th width="50">Typ</th></tr>
    @foreach([
        [$k?->internal_comm_done, 'K1 – Interne Kommunikation durchgeführt'],
        [$k?->external_comm_in_invitation || $k?->external_comm_on_website, 'K2 – Externe Kommunikation (Einladung/Website)'],
        [$k?->green_contact_name, 'K3 – Ansprechperson benannt'],
        [$k?->feedback_survey_done, 'K4 – Feedback der Gäste eingeholt'],
    ] as [$val, $label])
    <tr>
        <td class="check-cell">{{ $val ? '☑' : '☐' }}</td>
        <td>{{ $label }}</td>
        <td><span class="muss">MUSS</span></td>
    </tr>
    @endforeach
</table>

<h2>E. Unterschriften</h2>
<table>
    <tr>
        <td style="padding:20px 8px; border-bottom: 1px solid #374151;">
            <p>Ort, Datum: ______________________</p>
            <p style="margin-top:24px;">Unterschrift Veranstalter:in: ______________________</p>
        </td>
        <td style="padding:20px 8px; border-bottom: 1px solid #374151;">
            <p>Eingereicht bei: <strong>Green Events Austria</strong></p>
            <p>Österreichisches Umweltzeichen / BMKOES</p>
            <p>www.greeneventsaustria.at</p>
        </td>
    </tr>
</table>

<footer>
    Checkliste erstellt mit GreenEventPro · Basierend auf UZ 62 „Green Meetings und Green Events", Version 5.1, Juli 2022 ·
    Diese Checkliste dient als Vorausfüllung. Die offizielle Einreichung erfolgt über www.greeneventsaustria.at
</footer>
</body>
</html>
