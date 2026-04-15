<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $organization = $user->organization;

        $events = Event::where('organization_id', $organization->id)
            ->orderBy('start_date', 'desc')
            ->take(5)
            ->get();

        $totalEvents = Event::where('organization_id', $organization->id)->count();
        $certifiedEvents = Event::where('organization_id', $organization->id)
            ->where('status', 'certified')->count();
        $totalCo2 = Event::where('organization_id', $organization->id)
            ->sum('carbon_footprint_kg_co2');
        $avgScore = Event::where('organization_id', $organization->id)
            ->whereNotNull('uz62_percentage')
            ->avg('uz62_percentage');

        return view('dashboard.index', compact(
            'organization', 'events', 'totalEvents',
            'certifiedEvents', 'totalCo2', 'avgScore'
        ));
    }
}
