<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\UZ62ScoringService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class UZ62ReportController extends Controller
{
    public function __construct(private UZ62ScoringService $scoringService) {}

    public function show(Event $event)
    {
        $this->checkAccess($event);
        $event->load([
            'mobility', 'accommodations', 'venue', 'procurement',
            'exhibitor', 'catering', 'communication', 'social', 'technology',
        ]);
        $score = $this->scoringService->calculateScore($event);
        $details = $this->scoringService->evaluate($event);
        return view('reports.uz62_score', compact('event', 'score', 'details'));
    }

    public function pdf(Event $event)
    {
        $this->checkAccess($event);
        $event->load([
            'mobility', 'accommodations', 'venue', 'procurement',
            'exhibitor', 'catering', 'communication', 'social', 'technology',
        ]);
        $score = $this->scoringService->calculateScore($event);
        $details = $this->scoringService->evaluate($event);
        $pdf = Pdf::loadView('reports.pdf.uz62_report', compact('event', 'score', 'details'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download("uz62-report-{$event->id}.pdf");
    }

    private function checkAccess(Event $event): void
    {
        if ($event->organization_id !== Auth::user()->organization_id) abort(403);
    }
}
