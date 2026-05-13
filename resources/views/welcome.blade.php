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
    test working
    <nav class="flex items-center justify-between px-4 sm:px-6 lg:px-8 py-4 sm:py-5 border-b border-gray-100">
        <div class="flex items-center gap-2 flex-shrink-0">
            <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
            </div>
            <span class="font-bold text-gray-900 text-sm sm:text-base">GreenEventPro</span>
        </div>
        <div class="flex items-center gap-2 sm:gap-3 lg:gap-4">
            
            <div class="flex items-center gap-1 bg-gray-50 rounded-lg p-1">
                <a href="{{ route('locale.switch', 'de') }}"
                    class="px-2 py-1 text-xs font-semibold rounded-md transition-colors {{ app()->getLocale() == 'de' ? 'bg-white text-green-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    DE
                </a>
                <a href="{{ route('locale.switch', 'en') }}"
                    class="px-2 py-1 text-xs font-semibold rounded-md transition-colors {{ app()->getLocale() == 'en' ? 'bg-white text-green-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    EN
                </a>
            </div>
            <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                {{ __('Sign in') }}
            </a>
            <a href="{{ route('login') }}" class="sm:hidden inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
            </a>
            <a href="{{ route('register') }}"
                class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 bg-green-600 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-green-700 transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span class="hidden sm:inline">{{ __('Start for free') }}</span>
                <span class="sm:hidden">{{ __('Start') }}</span>
            </a>
        </div>
    </nav>

    
    <section class="px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20 text-center">
        <div class="inline-flex items-center gap-2 px-3 py-1 bg-green-50 border border-green-200 rounded-full text-xs sm:text-sm text-green-700 font-medium mb-4 sm:mb-6">
            <svg class="w-5 h-5 inline-block text-green-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg> {{ __('Austrian Ecolabel UZ 62 certified') }}
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 leading-tight mb-4 sm:mb-6">
            {{ __('Sustainable events.') }}<br>
            <span class="text-green-600">{{ __('Simply certified.') }}</span>
        </h1>
        <p class="text-base sm:text-lg lg:text-xl text-gray-500 max-w-2xl mx-auto mb-6 sm:mb-8 px-2">
            {{ __('GreenEventPro automatically calculates the CO2 footprint of your events and evaluates all UZ 62 criteria – from mobility to catering and technology.') }}
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4">
            <a href="{{ route('register') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-colors shadow-lg shadow-green-200">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                {{ __('Start now for free') }}
            </a>
        </div>
    </section>

    
    <section class="bg-gray-50 py-12 sm:py-16 lg:py-20">
        <div class="px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-center text-gray-900 mb-8 sm:mb-12">
                {{ __('Everything you need for green events') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8 max-w-6xl mx-auto">
                @foreach ([['<svg class="w-6 h-6 text-green-600 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>', __('Mobility & Traffic'), __('Capture travel routes, modal split, shuttle services and calculate CO2 from traffic.')], ['<svg class="w-6 h-6 text-orange-500 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>', __('Catering & Verpflegung'), __('All 34 C-Kriterien of UZ 62: Organic share, fair trade, regional, vegetarian/vegan.')], ['<svg class="w-6 h-6 text-blue-500 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>', __('UZ 62 Scoring Engine'), __('Automatic evaluation of all MUST and SHOULD criteria with immediate results.')], ['<svg class="w-6 h-6 text-teal-500 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>', __('CO2 Footprint'), __('Calculation according to international emission factors (GHG Protocol, IPCC).')], ['<svg class="w-6 h-6 text-red-500 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>', __('PDF Reports'), __('Ready-to-print reports and completed Green Events Austria checklist.')], ['<svg class="w-6 h-6 text-yellow-500 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>', __('Certification Help'), __('Direct preparation for submission to greeneventsaustria.at.')]] as [$icon, $title, $desc])
                    <div class="bg-white rounded-xl p-5 sm:p-6 shadow-sm border border-gray-100 h-full">
                        <div class="text-2xl sm:text-3xl mb-3">{!! $icon !!}</div>
                        <h3 class="font-semibold text-gray-900 mb-2 text-sm sm:text-base">{{ $title }}</h3>
                        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    
    <section class="py-12 sm:py-16 lg:py-20 text-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">{{ __('Ready for your first Green Event?') }}</h2>
            <p class="text-gray-500 mb-6 sm:mb-8 text-sm sm:text-base">{{ __('Start for free. No credit card required.') }}</p>
            <a href="{{ route('register') }}"
                class="inline-flex items-center justify-center gap-2 px-6 sm:px-8 py-3 sm:py-4 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition-colors text-base sm:text-lg shadow-lg shadow-green-200 w-full sm:w-auto">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                {{ __('Register now') }}
            </a>
        </div>
    </section>

    <footer class="border-t border-gray-100 py-6 sm:py-8 text-center text-xs sm:text-sm text-gray-400 px-4">
        &copy; {{ date('Y') }} GreenEventPro · {{ __('Austrian Ecolabel UZ 62') }} · <a
            
    </footer>
</body>

</html>
