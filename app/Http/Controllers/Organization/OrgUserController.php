<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrgUserController extends Controller
{
    public function index()
    {
        $users = User::where('organization_id', Auth::user()->organization_id)->get();
        return view('organization.users.index', compact('users'));
    }

    public function invite(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:admin,editor,viewer',
        ]);

        User::create([
            'organization_id' => Auth::user()->organization_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(Str::random(16)),
            'role' => $request->role,
            'locale' => 'de',
        ]);

        return redirect()->route('organization.users')->with('success', 'Benutzer eingeladen.');
    }

    public function destroy(User $user)
    {
        if ($user->organization_id !== Auth::user()->organization_id) abort(403);
        if ($user->id === Auth::id()) return back()->with('error', 'Sie können sich nicht selbst löschen.');
        $user->delete();
        return redirect()->route('organization.users')->with('success', 'Benutzer entfernt.');
    }
}
