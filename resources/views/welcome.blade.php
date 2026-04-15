<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenEventPro – Nachhaltige Veranstaltungen & UZ 62 Zertifizierung</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-white">

{{-- Nav --}}
<nav class="flex items-center justify-between px-8 py-5 border-b border-gray-100 max-w-6xl mx-auto">
    <div class="flex items-center gap-2">
        <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
        </div>
        <span class="font-bold text-gray-900">GreenEventPro</span>
    </div>
    <div class="flex items-center gap-4">
        <a href="{{ route('pricing') }}" class="text-sm text-gray-600 hover:text-gray-900">Preise</a>
        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Anmelden</a>
        <a href="{{ route('register') }}" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700">Kostenlos starten</a>
    </div>
</nav>

{{-- Hero --}}
<section class="max-w-6xl mx-auto px-8 py-20 text-center">
    <div class="inline-flex items-center gap-2 px-3 py-1 bg-green-50 border border-green-200 rounded-full text-xs text-green-700 font-medium mb-6">
        🌿 Österreichisches Umweltzeichen UZ 62 zertifiziert
    </div>
    <h1 class="text-5xl font-extrabold text-gray-900 leading-tight mb-6">
        Nachhaltige Veranstaltungen.<br>
        <span class="text-green-600">Einfach zertifiziert.</span>
    </h1>
    <p class="text-xl text-gray-500 max-w-2xl mx-auto mb-8">
        GreenEventPro berechnet automatisch den CO₂-Fußabdruck Ihrer Events und bewertet alle UZ 62-Kriterien – von Mobilität über Catering bis zur Technik.
    </p>
    <div class="flex items-center justify-center gap-4">
        <a href="{{ route('register') }}" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-colors shadow-lg shadow-green-200">
            Jetzt kostenlos starten →
        </a>
        <a href="{{ route('pricing') }}" class="px-6 py-3 bg-white text-gray-700 font-semibold rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors">
            Preise ansehen
        </a>
    </div>
</section>

{{-- Features --}}
<section class="bg-gray-50 py-16">
    <div class="max-w-6xl mx-auto px-8">
        <h2 class="text-2xl font-bold text-center text-gray-900 mb-12">Alles was Sie für grüne Events brauchen</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['🚆', 'Mobilität & Transport', 'Erfassen Sie Anreisewege, Modal Split, Shuttleservices und berechnen Sie CO₂ aus dem Verkehr.'],
                ['🍽️', 'Catering & Verpflegung', 'Alle 34 C-Kriterien von UZ 62: Bio-Anteil, Fairtrade, regional, vegetarisch/vegan.'],
                ['📊', 'UZ 62 Scoring Engine', 'Automatische Bewertung aller MUSS- und SOLL-Kriterien mit sofortigem Ergebnis.'],
                ['🌍', 'CO₂-Fußabdruck', 'Berechnung nach internationalen Emissionsfaktoren (GHG Protocol, IPCC).'],
                ['📄', 'PDF-Berichte', 'Druckfertige Berichte und ausgefüllte Green Events Austria Checkliste.'],
                ['🏆', 'Zertifizierungshilfe', 'Direkte Vorbereitung für die Einreichung bei greeneventsaustria.at.'],
            ] as [$icon, $title, $desc])
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="text-3xl mb-3">{{ $icon }}</div>
                <h3 class="font-semibold text-gray-900 mb-2">{{ $title }}</h3>
                <p class="text-sm text-gray-500">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 text-center">
    <div class="max-w-2xl mx-auto px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Bereit für Ihr erstes Green Event?</h2>
        <p class="text-gray-500 mb-8">Kostenlos starten. Keine Kreditkarte erforderlich.</p>
        <a href="{{ route('register') }}" class="px-8 py-4 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition-colors text-lg shadow-lg shadow-green-200">
            Jetzt registrieren →
        </a>
    </div>
</section>

<footer class="border-t border-gray-100 py-8 text-center text-sm text-gray-400">
    &copy; {{ date('Y') }} GreenEventPro · Österreichisches Umweltzeichen UZ 62 · <a href="{{ route('pricing') }}" class="hover:text-gray-600">Preise</a>
</footer>
</body>
</html>
