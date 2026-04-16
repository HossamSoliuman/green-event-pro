@extends('layouts.app')
@section('title', __('Subscription'))
@section('page-title', 'Abonnement & Abrechnung')
@section('content')
<div class="max-w-4xl">

<div class="card mb-6 bg-green-50 border-green-200">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-green-700">Aktueller Plan</p>
            <p class="text-2xl font-bold text-green-800">{{ ucfirst($organization->subscription_plan ?? 'Free') }}</p>
            <p class="text-xs text-green-600 mt-1">Status: {{ ucfirst($organization->subscription_status ?? 'active') }}</p>
        </div>
        <div class="text-right text-sm text-green-700">
            @php $limits = $organization->getPlanLimits(); @endphp
            <p>{{ $limits['events_per_year'] === PHP_INT_MAX ? 'Unbegrenzte' : $limits['events_per_year'] }} Veranstaltungen/Jahr</p>
            <p>{{ $limits['users'] === PHP_INT_MAX ? 'Unbegrenzte' : $limits['users'] }} Benutzer</p>
            <p>{{ $limits['api_access'] ? '✓' : '✗' }} API-Zugang</p>
            <p>{{ $limits['white_label_pdf'] ? '✓' : '✗' }} White-Label PDF</p>
        </div>
    </div>
</div>

<h2 class="text-base font-semibold text-gray-900 mb-4">Verfügbare Pläne</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    @foreach($plans as $planKey => $plan)
    <div class="card border-2 {{ ($organization->subscription_plan ?? 'free') === $planKey ? 'border-green-500' : 'border-gray-100' }}">
        @if(($organization->subscription_plan ?? '') === $planKey)
            <div class="badge-green mb-2">Aktueller Plan</div>
        @endif
        @if($planKey === 'professional')
            <div class="badge-blue mb-2">Beliebt</div>
        @endif
        <h3 class="text-lg font-bold text-gray-900">{{ $plan['name'] }}</h3>
        <div class="my-3">
            <span class="text-3xl font-bold text-gray-900">€{{ $plan['price'] }}</span>
            <span class="text-sm text-gray-500">/Monat</span>
        </div>
        <ul class="space-y-2 text-sm text-gray-600 mb-4">
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ $plan['events'] === 'Unbegrenzt' ? 'Unbegrenzte Veranstaltungen' : $plan['events'].' Veranstaltungen/Jahr' }}
            </li>
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ $plan['users'] }} Benutzer
            </li>
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Alle UZ 62 Module
            </li>
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                PDF-Berichte
            </li>
            @if($planKey !== 'starter')
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                API-Zugang
            </li>
            @endif
            @if($planKey === 'enterprise')
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                White-Label PDFs
            </li>
            @endif
        </ul>
        @if(($organization->subscription_plan ?? 'free') !== $planKey)
        <button class="w-full py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
            Auf {{ $plan['name'] }} upgraden
        </button>
        @else
        <button class="w-full py-2 bg-gray-100 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed" disabled>
            Aktueller Plan
        </button>
        @endif
    </div>
    @endforeach
</div>

<div class="card bg-gray-50 text-sm text-gray-600">
    <p class="font-medium text-gray-800 mb-1">Enterprise / Agency Plan</p>
    <p>Benötigen Sie unbegrenzte Veranstaltungen, White-Label PDFs oder Reseller-Funktionen? Kontaktieren Sie uns für ein individuelles Angebot.</p>
    <a href="mailto:enterprise@greenevents.at" class="mt-2 inline-block text-green-600 hover:underline font-medium">enterprise@greenevents.at →</a>
</div>
</div>
@endsection
