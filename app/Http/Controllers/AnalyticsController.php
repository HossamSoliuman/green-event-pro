<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function index()
    {
        $orgId = Auth::user()->organization_id;
        $events = Event::where('organization_id', $orgId)
            ->whereNotNull('carbon_footprint_kg_co2')
            ->orderBy('start_date')
            ->get();

        $co2ByEvent = $events->map(fn($e) => [
            'title' => $e->title,
            'co2' => $e->carbon_footprint_kg_co2,
            'co2_per_person' => $e->carbon_footprint_per_person,
            'uz62_score' => $e->uz62_percentage,
        ]);

        return view('analytics.index', compact('events', 'co2ByEvent'));
    }

    public function export()
    {
        $orgId = Auth::user()->organization_id;
        $events = Event::where('organization_id', $orgId)->get();
        $csv = "Title,Type,Start Date,Participants,CO2 Total,CO2/Person,UZ62 Score,UZ62 Passed\n";
        foreach ($events as $e) {
            $csv .= "\"{$e->title}\",{$e->type},{$e->start_date},{$e->expected_participants},{$e->carbon_footprint_kg_co2},{$e->carbon_footprint_per_person},{$e->uz62_percentage},{$e->uz62_passed}\n";
        }
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="analytics-export.csv"',
        ]);
    }
}
