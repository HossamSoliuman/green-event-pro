<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\CarbonFootprintService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class CarbonReportController extends Controller
{
    public function __construct(private CarbonFootprintService $carbonService) {}

    public function show(Event $event)
    {
        $this->checkAccess($event);
        $event->load(['mobility', 'catering', 'accommodations', 'technology', 'tvProduction']);
        $report = $this->carbonService->calculate($event);
        return view('reports.carbon', compact('event', 'report'));
    }

    public function pdf(Event $event)
    {
        $this->checkAccess($event);
        $event->load(['mobility', 'catering', 'accommodations', 'technology', 'tvProduction']);
        $report = $this->carbonService->calculate($event);
        $pdf = Pdf::loadView('reports.pdf.carbon_report', compact('event', 'report'));
        return $pdf->download("carbon-report-{$event->id}.pdf");
    }

    private function checkAccess(Event $event): void
    {
        if ($event->organization_id !== Auth::user()->organization_id) abort(403);
    }
}
