@extends('layouts.app')
@section('title', __('Dashboard'))
@section('page-title', __('Dashboard'))
@section('header-actions')
    <a href="{{ route('events.create') }}" class="btn-primary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        {{ __('New Event') }}
    </a>
@endsection
@section('content')

{{-- KPI Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Events') }}</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalEvents }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Certified') }}</p>
                <p class="text-3xl font-bold text-green-600 mt-1">{{ $certifiedEvents }}</p>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Total CO2') }}</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($totalCo2 / 1000, 1) }}<span class="text-base font-medium text-gray-500 ml-1">t</span></p>
            </div>
            <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Avg. UZ 62 Score') }}</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $avgScore ? number_format($avgScore, 1) : '–' }}<span class="text-base font-medium text-gray-500 ml-1">%</span></p>
            </div>
            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </div>
        </div>
    </div>
</div>

{{-- Subscription notice --}}
@if($organization->subscription_plan === 'free')
<div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3">
    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <div>
        <p class="text-sm font-semibold text-amber-800">{{ __('Free Plan – Upgrade for more features') }}</p>
        <p class="text-sm text-amber-700 mt-1">{{ __('You are currently using the free plan (max. 2 events).') }} <a href="{{ route('organization.billing') }}" class="font-medium underline">{{ __('Upgrade now') }} &rarr;</a></p>
    </div>
</div>
@endif

{{-- Recent Events --}}
<div class="card">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-semibold text-gray-900">{{ __('Recent Events') }}</h2>
        <a href="{{ route('events.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">{{ __('Show all') }} &rarr;</a>
    </div>
    @if($events->isEmpty())
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <p class="text-gray-500 text-sm">{{ __('No events yet.') }}</p>
            <a href="{{ route('events.create') }}" class="btn-primary mt-4 inline-flex">{{ __('Create first event') }}</a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                        <th class="pb-3 pr-4">{{ __('Event') }}</th>
                        <th class="pb-3 pr-4">{{ __('Date') }}</th>
                        <th class="pb-3 pr-4">{{ __('Participants') }}</th>
                        <th class="pb-3 pr-4">UZ 62</th>
                        <th class="pb-3 pr-4">CO₂/P</th>
                        <th class="pb-3">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($events as $event)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 pr-4">
                            <a href="{{ route('events.show', $event) }}" class="font-medium text-gray-900 hover:text-green-600">{{ $event->title }}</a>
                            <p class="text-xs text-gray-400">{{ $event->type_label }}</p>
                        </td>
                        <td class="py-3 pr-4 text-gray-600 whitespace-nowrap">{{ $event->start_date?->format('d.m.Y') }}</td>
                        <td class="py-3 pr-4 text-gray-600">{{ number_format($event->expected_participants) }}</td>
                        <td class="py-3 pr-4">
                            @if($event->uz62_percentage)
                                <span class="{{ $event->uz62_passed ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                    {{ number_format($event->uz62_percentage, 1) }}%
                                </span>
                            @else
                                <span class="text-gray-400">–</span>
                            @endif
                        </td>
                        <td class="py-3 pr-4 text-gray-600">
                            {{ $event->carbon_footprint_per_person ? number_format($event->carbon_footprint_per_person, 1) . ' kg' : '–' }}
                        </td>
                        <td class="py-3">
                            <span class="{{ $event->getStatusBadgeClass() }}">
                                {{ ucfirst($event->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
