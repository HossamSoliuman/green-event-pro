<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') – GreenEventPro</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="h-full bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-green-600 rounded-2xl shadow-lg mb-4">
                <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">GreenEventPro</h1>
            <p class="text-sm text-gray-500 mt-1">{{ __('Sustainable Events · UZ 62 Certification') }}</p>
            
            <!-- Language Switcher -->
            <div class="flex items-center justify-center gap-2 mt-4">
                <a href="{{ route('locale.switch', 'de') }}" 
                   class="px-2 py-1 text-xs font-semibold rounded-md transition-colors {{ app()->getLocale() == 'de' ? 'bg-green-600 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 bg-gray-100' }}">
                    DE
                </a>
                <a href="{{ route('locale.switch', 'en') }}" 
                   class="px-2 py-1 text-xs font-semibold rounded-md transition-colors {{ app()->getLocale() == 'en' ? 'bg-green-600 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 bg-gray-100' }}">
                    EN
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 rounded-lg text-sm text-red-700">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
            @endif
            @yield('content')
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            &copy; {{ date('Y') }} GreenEventPro — {{ __('Austrian Ecolabel UZ 62') }}
        </p>
    </div>
</body>
</html>
