<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\UZ62ScoringService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ChecklistController extends Controller
{
    public function pdf(Event $event)
    {
        if ($event->organization_id !== Auth::user()->organization_id) abort(403);
        $event->load(['mobility', 'accommodations', 'venue', 'procurement', 'catering', 'communication', 'social', 'technology']);
        $pdf = Pdf::loadView('reports.pdf.checklist_pdf', compact('event'));
        return $pdf->download("uz62-checklist-{$event->id}.pdf");
    }
}
