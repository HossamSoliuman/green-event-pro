<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenEventPro – {{ __('Sustainable Events & UZ 62 Certification') }}</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-white">

    {{-- Nav --}}
    <nav class="flex items-center justify-between px-8 py-5 border-b border-gray-100 max-w-6xl mx-auto">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
            </div>
            <span class="font-bold text-gray-900">GreenEventPro</span>
        </div>
        <div class="flex items-center gap-4">
            <!-- Language Switcher -->
            <div class="flex items-center gap-2 bg-gray-50 rounded-lg p-1 mr-2">
                <a href="{{ route('locale.switch', 'de') }}"
                    class="px-2 py-1 text-xs font-semibold rounded-md transition-colors {{ app()->getLocale() == 'de' ? 'bg-white text-green-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    DE
                </a>
                <a href="{{ route('locale.switch', 'en') }}"
                    class="px-2 py-1 text-xs font-semibold rounded-md transition-colors {{ app()->getLocale() == 'en' ? 'bg-white text-green-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    EN
                </a>
            </div>
            {{-- <a href="{{ route('pricing') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Pricing') }}</a> --}}
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Sign in') }}</a>
            <a href="{{ route('register') }}"
                class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700">{{ __('Start for free') }}</a>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="max-w-6xl mx-auto px-8 py-20 text-center">
        <div
            class="inline-flex items-center gap-2 px-3 py-1 bg-green-50 border border-green-200 rounded-full text-xs text-green-700 font-medium mb-6">
            🌿 {{ __('Austrian Ecolabel UZ 62 certified') }}
        </div>
        <h1 class="text-5xl font-extrabold text-gray-900 leading-tight mb-6">
            {{ __('Sustainable events.') }}<br>
            <span class="text-green-600">{{ __('Simply certified.') }}</span>
        </h1>
        <p class="text-xl text-gray-500 max-w-2xl mx-auto mb-8">
            {{ __('GreenEventPro automatically calculates the CO2 footprint of your events and evaluates all UZ 62 criteria – from mobility to catering and technology.') }}
        </p>
        <div class="flex items-center justify-center gap-4">
            <a href="{{ route('register') }}"
                class="px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-colors shadow-lg shadow-green-200">
                {{ __('Start now for free') }} →
            </a>
            {{-- <a href="{{ route('pricing') }}"
                class="px-6 py-3 bg-white text-gray-700 font-semibold rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors">
                {{ __('View pricing') }}
            </a> --}}
        </div>
    </section>

    {{-- Features --}}
    <section class="bg-gray-50 py-16">
        <div class="max-w-6xl mx-auto px-8">
            <h2 class="text-2xl font-bold text-center text-gray-900 mb-12">
                {{ __('Everything you need for green events') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ([['🚆', __('Mobility & Traffic'), __('Capture travel routes, modal split, shuttle services and calculate CO2 from traffic.')], ['🍽️', __('Catering & Verpflegung'), __('All 34 C-Kriterien of UZ 62: Organic share, fair trade, regional, vegetarian/vegan.')], ['📊', __('UZ 62 Scoring Engine'), __('Automatic evaluation of all MUST and SHOULD criteria with immediate results.')], ['🌍', __('CO2 Footprint'), __('Calculation according to international emission factors (GHG Protocol, IPCC).')], ['📄', __('PDF Reports'), __('Ready-to-print reports and completed Green Events Austria checklist.')], ['🏆', __('Certification Help'), __('Direct preparation for submission to greeneventsaustria.at.')]] as [$icon, $title, $desc])
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
            <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Ready for your first Green Event?') }}</h2>
            <p class="text-gray-500 mb-8">{{ __('Start for free. No credit card required.') }}</p>
            <a href="{{ route('register') }}"
                class="px-8 py-4 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition-colors text-lg shadow-lg shadow-green-200">
                {{ __('Register now') }} →
            </a>
        </div>
    </section>

    <footer class="border-t border-gray-100 py-8 text-center text-sm text-gray-400">
        &copy; {{ date('Y') }} GreenEventPro · {{ __('Austrian Ecolabel UZ 62') }} · <a
            {{-- href="{{ route('pricing') }}" class="hover:text-gray-600">{{ __('Pricing') }}</a> --}}
    </footer>
</body>

</html>
