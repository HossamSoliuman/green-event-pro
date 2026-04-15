<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\CarbonFootprintService;
use App\Services\UZ62ScoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoreController extends Controller
{
    public function __construct(
        private UZ62ScoringService $scoringService,
        private CarbonFootprintService $carbonService
    ) {}

    public function recalculate(Event $event)
    {
        if ($event->organization_id !== Auth::user()->organization_id) {
            abort(403);
        }

        $score = $this->scoringService->calculateScore($event);
        $carbon = $this->carbonService->calculate($event);

        return response()->json([
            'success' => true,
            'uz62_percentage' => $score->percentage,
            'uz62_passed' => $score->passed,
            'points_achieved' => $score->points_achieved,
            'points_max' => $score->points_max,
            'muss_passed' => $score->muss_passed,
            'muss_failed' => $score->muss_failed_criteria,
            'co2_total' => $carbon->co2_total,
            'co2_per_person' => $carbon->co2_per_person,
        ]);
    }
}
