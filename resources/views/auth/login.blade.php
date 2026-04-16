@extends('layouts.guest')
@section('title', 'Anmelden')
@section('content')
<h2 class="text-xl font-bold text-gray-900 mb-6">{{ __('Sign in') }}</h2>
<form method="POST" action="{{ route('login') }}" class="space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">E-Mail</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password') }}</label>
        <input type="password" name="password" required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
    </div>
    <div class="flex items-center gap-2">
        <input type="checkbox" name="remember" id="remember" class="h-4 w-4 rounded border-gray-300 text-green-600">
        <label for="remember" class="text-sm text-gray-600">Angemeldet bleiben</label>
    </div>
    <button type="submit" class="w-full py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors">
        Anmelden
    </button>
</form>
<p class="text-center text-sm text-gray-500 mt-4">
    Noch kein Konto? <a href="{{ route('register') }}" class="text-green-600 font-medium hover:underline">Jetzt registrieren</a>
</p>
@endsection
