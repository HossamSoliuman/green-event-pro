@extends('layouts.app')
@section('title', ucfirst('venue') . ' – ' . $event->title)
@section('page-title', 'Modul: ' . ucfirst(str_replace('_', ' ', 'venue')))
@section('content')
    <div class="mb-4">
        <a href="{{ route('events.show', $event) }}" class="text-sm text-green-600 hover:text-green-700">← Zurück zur
            Veranstaltung</a>
    </div>
    <form method="POST" action="{{ route('events.modules.venue.update', $event) }}">
        @csrf @method('PUT')
        <div class="card space-y-4">
            <h3 class="section-title">{{ ucfirst(str_replace('_', ' ', 'venue')) }} Daten</h3>
            <p class="text-sm text-gray-500">Füllen Sie alle relevanten Felder für dieses Modul aus.</p>

            @foreach ($module->getFillable() as $field)
                @if (!in_array($field, ['event_id', 'organization_id', 'notes']))
                    @php $val = $module->{$field}; @endphp
                    <div>
                        <label class="form-label">{{ ucfirst(str_replace('_', ' ', $field)) }}</label>
                        @if (is_bool($val) || (in_array($val, [0, 1, '0', '1', true, false, null]) && str_contains($field, '_')))
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="{{ $field }}" value="1" {{ $val ? 'checked' : '' }}
                                    class="form-checkbox">
                                <span class="text-sm text-gray-600">Ja</span>
                            </label>
                        @elseif(is_numeric($val) || is_null($val))
                            <input type="number" step="0.01" name="{{ $field }}" value="{{ $val }}"
                                class="form-input">
                        @else
                            <input type="text" name="{{ $field }}" value="{{ $val }}" class="form-input">
                        @endif
                    </div>
                @endif
            @endforeach

            <div>
                <label class="form-label">Notizen</label>
                <textarea name="notes" rows="3" class="form-input">{{ $module->notes }}</textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">{{ __('Save') }}</button>
                <a href="{{ route('events.show', $event) }}" class="btn-secondary">{{ __('Cancel') }}</a>
            </div>
        </div>
    </form>
@endsection
