@extends('layouts.guest')
@section('title', 'Registrieren')
@section('content')
<h2 class="text-xl font-bold text-gray-900 mb-2">Konto erstellen</h2>
<p class="text-sm text-gray-500 mb-6">Starten Sie mit nachhaltigen Veranstaltungen nach UZ 62.</p>
<form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Organisationsname <span class="text-red-500">*</span></label>
        <input type="text" name="organization_name" value="{{ old('organization_name') }}" required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
            placeholder="z.B. Eventbüro Mustermann GmbH">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Ihr Name <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name') }}" required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">E-Mail <span class="text-red-500">*</span></label>
        <input type="email" name="email" value="{{ old('email') }}" required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Passwort <span class="text-red-500">*</span></label>
        <input type="password" name="password" required minlength="8"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Passwort bestätigen <span class="text-red-500">*</span></label>
        <input type="password" name="password_confirmation" required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
    </div>
    <button type="submit" class="w-full py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors">
        Konto erstellen
    </button>
</form>
<p class="text-center text-sm text-gray-500 mt-4">
    Bereits registriert? <a href="{{ route('login') }}" class="text-green-600 font-medium hover:underline">Anmelden</a>
</p>
@endsection
