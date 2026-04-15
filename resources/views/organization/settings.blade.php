@extends('layouts.app')
@section('title', 'Einstellungen')
@section('page-title', 'Organisationseinstellungen')
@section('content')
<div class="max-w-2xl space-y-6">

<div class="card">
    <h3 class="section-title">Organisationsprofil</h3>
    <form method="POST" action="{{ route('organization.settings.update') }}" class="space-y-4">
        @csrf @method('PUT')
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="form-label">Organisationsname <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $organization->name) }}" required class="form-input">
            </div>
            <div>
                <label class="form-label">E-Mail</label>
                <input type="email" name="email" value="{{ old('email', $organization->email) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Telefon</label>
                <input type="text" name="phone" value="{{ old('phone', $organization->phone) }}" class="form-input">
            </div>
            <div class="col-span-2">
                <label class="form-label">Adresse</label>
                <input type="text" name="address" value="{{ old('address', $organization->address) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Land</label>
                <select name="country" class="form-select">
                    <option value="AT" {{ ($organization->country ?? 'AT') === 'AT' ? 'selected' : '' }}>Österreich</option>
                    <option value="DE" {{ ($organization->country ?? '') === 'DE' ? 'selected' : '' }}>Deutschland</option>
                    <option value="CH" {{ ($organization->country ?? '') === 'CH' ? 'selected' : '' }}>Schweiz</option>
                </select>
            </div>
            <div>
                <label class="form-label">UID-Nummer</label>
                <input type="text" name="vat_number" value="{{ old('vat_number', $organization->vat_number) }}" class="form-input" placeholder="ATU12345678">
            </div>
            <div class="col-span-2">
                <label class="form-label">Website</label>
                <input type="url" name="website" value="{{ old('website', $organization->website) }}" class="form-input" placeholder="https://www.example.at">
            </div>
        </div>
        <div class="pt-2">
            <button type="submit" class="btn-primary">Einstellungen speichern</button>
        </div>
    </form>
</div>

<div class="card bg-gray-50">
    <h3 class="section-title">Abonnement-Info</h3>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold text-gray-900">Aktueller Plan: <span class="text-green-700">{{ ucfirst($organization->subscription_plan ?? 'Free') }}</span></p>
            <p class="text-xs text-gray-500 mt-1">Status: {{ ucfirst($organization->subscription_status ?? 'active') }}</p>
        </div>
        <a href="{{ route('organization.billing') }}" class="btn-secondary">Plan ändern</a>
    </div>
</div>

</div>
@endsection
