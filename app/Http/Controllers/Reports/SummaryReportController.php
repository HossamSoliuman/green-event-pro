<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class SummaryReportController extends Controller
{
    public function show(Event $event)
    {
        if ($event->organization_id !== Auth::user()->organization_id) abort(403);
        $event->load(['latestCarbonReport', 'latestUz62Score', 'accommodations', 'catering', 'venue']);
        return view('reports.summary', compact('event'));
    }
}
