<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrgSettingsController extends Controller
{
    public function edit()
    {
        return view('organization.settings', ['organization' => Auth::user()->organization]);
    }

    public function update(Request $request)
    {
        $org = Auth::user()->organization;
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'country' => 'nullable|string|max:2',
            'vat_number' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
        ]);
        $org->update($validated);
        return back()->with('success', 'Einstellungen gespeichert.');
    }
}
