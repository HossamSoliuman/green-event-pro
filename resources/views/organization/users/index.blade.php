@extends('layouts.app')
@section('title', 'Benutzer')
@section('page-title', 'Benutzer verwalten')
@section('content')
<div class="max-w-3xl space-y-6">

<div class="card">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-semibold text-gray-900">Team-Mitglieder ({{ $users->count() }})</h3>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                <th class="pb-3">Name</th>
                <th class="pb-3">E-Mail</th>
                <th class="pb-3">Rolle</th>
                <th class="pb-3">Erstellt</th>
                <th class="pb-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($users as $user)
            <tr>
                <td class="py-3">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-semibold text-xs">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span class="font-medium text-gray-900">{{ $user->name }}</span>
                        @if($user->id === auth()->id())
                            <span class="badge-blue">Sie</span>
                        @endif
                    </div>
                </td>
                <td class="py-3 text-gray-500">{{ $user->email }}</td>
                <td class="py-3">
                    <span class="@if($user->role==='owner') badge-green @elseif($user->role==='admin') badge-blue @else badge-gray @endif">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td class="py-3 text-gray-400 text-xs">{{ $user->created_at->format('d.m.Y') }}</td>
                <td class="py-3 text-right">
                    @if($user->id !== auth()->id() && auth()->user()->isOwner())
                    <form method="POST" action="{{ route('organization.users.destroy', $user) }}"
                          onsubmit="return confirm('Benutzer wirklich entfernen?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-600 text-xs">Entfernen</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if(auth()->user()->canManageUsers())
<div class="card">
    <h3 class="section-title">Neuen Benutzer einladen</h3>
    <form method="POST" action="{{ route('organization.users.invite') }}" class="space-y-4">
        @csrf
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="form-label">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="form-input">
            </div>
            <div>
                <label class="form-label">E-Mail <span class="text-red-500">*</span></label>
                <input type="email" name="email" required class="form-input">
            </div>
            <div>
                <label class="form-label">Rolle <span class="text-red-500">*</span></label>
                <select name="role" required class="form-select">
                    <option value="editor">Editor (Bearbeiten)</option>
                    <option value="admin">Admin (alle Rechte)</option>
                    <option value="viewer">Viewer (nur lesen)</option>
                </select>
            </div>
        </div>
        <div class="p-3 bg-blue-50 rounded-lg text-xs text-blue-700">
            ℹ️ Der Benutzer erhält ein temporäres Passwort und kann sich sofort anmelden. Bitte teilen Sie die Zugangsdaten manuell mit.
        </div>
        <button type="submit" class="btn-primary">Benutzer hinzufügen</button>
    </form>
</div>
@endif

</div>
@endsection
