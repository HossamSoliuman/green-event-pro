@extends('layouts.app')
@section('title', __('Events'))
@section('page-title', __('Events'))
@section('header-actions')
    <a href="{{ route('events.create') }}" class="btn-primary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        {{ __('New Event') }}
    </a>
@endsection
@section('content')

@if($events->isEmpty())
    <div class="card text-center py-16">
        <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('No events yet') }}</h3>
        <p class="text-gray-500 text-sm mb-6">{{ __('Create your first sustainable event and start with the UZ 62 certification.') }}</p>
        <a href="{{ route('events.create') }}" class="btn-primary">{{ __('Create first event') }}</a>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($events as $event)
        <div class="card hover:shadow-md transition-shadow group">
            <div class="flex items-start justify-between mb-3">
                <span class="{{ $event->getStatusBadgeClass() }}">{{ ucfirst($event->status) }}</span>
                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <a href="{{ route('events.edit', $event) }}" class="p-1 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                </div>
            </div>
            <a href="{{ route('events.show', $event) }}">
                <h3 class="font-semibold text-gray-900 hover:text-green-600 transition-colors mb-1">{{ $event->title }}</h3>
            </a>
            <p class="text-xs text-gray-500 mb-3">{{ $event->type_label }} · {{ $event->venue_city }}</p>

            <div class="flex items-center gap-4 text-xs text-gray-500 mb-4">
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $event->start_date?->format('d.m.Y') }}
                </span>
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ number_format($event->expected_participants) }} TN
                </span>
            </div>

            {{-- Score bars --}}
            <div class="space-y-2">
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-500">{{ __('UZ 62 Score') }}</span>
                        <span class="{{ $event->uz62_passed ? 'text-green-600 font-semibold' : 'text-gray-600' }}">
                            {{ $event->uz62_percentage ? number_format($event->uz62_percentage, 1).'%' : __('Not calculated') }}
                        </span>
                    </div>
                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        @if($event->uz62_percentage)
                        <div class="h-full rounded-full {{ $event->uz62_passed ? 'bg-green-500' : 'bg-orange-400' }}"
                             style="width: {{ min($event->uz62_percentage, 100) }}%"></div>
                        @endif
                    </div>
                </div>
                @if($event->carbon_footprint_per_person)
                <div class="flex justify-between text-xs">
                    <span class="text-gray-500">CO₂/Person</span>
                    <span class="{{ $event->carbon_footprint_per_person <= 30 ? 'text-green-600' : ($event->carbon_footprint_per_person <= 100 ? 'text-yellow-600' : 'text-red-600') }} font-medium">
                        {{ number_format($event->carbon_footprint_per_person, 1) }} kg
                    </span>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $events->links() }}</div>
@endif
@endsection
