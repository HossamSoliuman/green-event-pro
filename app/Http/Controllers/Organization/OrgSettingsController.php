<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\UpdateOrgSettingsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrgSettingsController extends Controller
{
    public function edit()
    {
        return view('organization.settings', ['organization' => Auth::user()->organization]);
    }

    public function update(UpdateOrgSettingsRequest $request)
    {
        $org = Auth::user()->organization;
        $validated = $request->validated();
        $org->update($validated);
        return back()->with('success', 'Einstellungen gespeichert.');
    }
}
