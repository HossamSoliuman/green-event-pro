<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preise – GreenEventPro</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50">
<nav class="flex items-center justify-between px-8 py-5 bg-white border-b border-gray-100 max-w-6xl mx-auto">
    <a href="{{ route('home') }}" class="flex items-center gap-2">
        <div class="w-7 h-7 bg-green-600 rounded-lg flex items-center justify-center">
            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
        </div>
        <span class="font-bold text-gray-900">GreenEventPro</span>
    </a>
    <a href="{{ route('register') }}" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700">Kostenlos starten</a>
</nav>

<div class="max-w-5xl mx-auto px-8 py-16">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-3">Einfache, transparente Preise</h1>
        <p class="text-gray-500">Alle Pläne beinhalten alle UZ 62 Module, CO₂-Berechnung und PDF-Berichte.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @foreach([
            ['free', 'Free', '0', '2', '1', false, false, 'Für den Einstieg'],
            ['starter', 'Starter', '49', '5', '1', false, false, 'Für kleine Veranstalter'],
            ['professional', 'Professional', '149', '25', '5', true, false, 'Für Agenturen'],
            ['enterprise', 'Enterprise', '399', '∞', '20', true, true, 'Für große Organisationen'],
        ] as [$key, $name, $price, $events, $users, $api, $wl, $desc])
        <div class="bg-white rounded-2xl p-6 border-2 {{ $key === 'professional' ? 'border-green-500 shadow-xl' : 'border-gray-100' }} relative">
            @if($key === 'professional')
            <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                <span class="bg-green-600 text-white text-xs font-bold px-3 py-1 rounded-full">Beliebt</span>
            </div>
            @endif
            <h3 class="font-bold text-gray-900 text-lg">{{ $name }}</h3>
            <p class="text-xs text-gray-500 mb-4">{{ $desc }}</p>
            <div class="mb-4">
                <span class="text-3xl font-bold text-gray-900">€{{ $price }}</span>
                <span class="text-sm text-gray-400">/Monat</span>
            </div>
            <ul class="space-y-2 text-sm text-gray-600 mb-6">
                <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ $events }} Veranstaltungen/Jahr</li>
                <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ $users }} Benutzer</li>
                <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Alle UZ 62 Module</li>
                <li class="flex items-center gap-2"><span class="text-green-500">✓</span> CO₂-Berechnung</li>
                <li class="flex items-center gap-2"><span class="text-green-500">✓</span> PDF-Berichte</li>
                <li class="flex items-center gap-2"><span class="{{ $api ? 'text-green-500' : 'text-gray-300' }}">{{ $api ? '✓' : '✗' }}</span> API-Zugang</li>
                <li class="flex items-center gap-2"><span class="{{ $wl ? 'text-green-500' : 'text-gray-300' }}">{{ $wl ? '✓' : '✗' }}</span> White-Label PDF</li>
            </ul>
            <a href="{{ route('register') }}"
               class="block w-full py-2.5 text-center text-sm font-semibold rounded-xl {{ $key === 'professional' ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                {{ $key === 'free' ? 'Kostenlos starten' : 'Jetzt starten' }}
            </a>
        </div>
        @endforeach
    </div>

    <div class="mt-8 p-6 bg-white rounded-2xl border border-gray-100 text-center">
        <h3 class="font-bold text-gray-900 mb-2">Agency / Reseller Plan</h3>
        <p class="text-sm text-gray-500 mb-4">Unbegrenzte Veranstaltungen, Multi-Organisations-Verwaltung, Custom Branding, Priority Support.</p>
        <a href="mailto:enterprise@greenevents.at" class="px-6 py-2 border border-green-600 text-green-600 font-medium rounded-lg hover:bg-green-50 text-sm">Kontakt aufnehmen</a>
    </div>
</div>
</body>
</html>
