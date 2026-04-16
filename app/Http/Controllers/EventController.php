<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\CarbonFootprintService;
use App\Services\UZ62ScoringService;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function __construct(
        private CarbonFootprintService $carbonService,
        private UZ62ScoringService $scoringService
    ) {}

    public function index()
    {
        $events = Event::where('organization_id', Auth::user()->organization_id)
            ->orderBy('start_date', 'desc')
            ->paginate(12);

        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create', [
            'eventTypes' => Event::TYPES,
        ]);
    }

    public function store(StoreEventRequest $request)
    {
        $validated = $request->validated();

        $validated['organization_id'] = Auth::user()->organization_id;
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'draft';
        $validated['certification_phase'] = 'none';
        $validated['is_outdoor'] = $request->boolean('is_outdoor');
        $validated['is_hybrid'] = $request->boolean('is_hybrid');

        $event = Event::create($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'Veranstaltung wurde erfolgreich erstellt.');
    }

    public function show(Event $event)
    {
        $this->authorizeEvent($event);

        $event->load([
            'mobility', 'accommodations', 'venue', 'procurement',
            'exhibitor', 'catering', 'communication', 'social',
            'technology', 'tvProduction', 'latestCarbonReport', 'latestUz62Score',
            'documents',
        ]);

        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $this->authorizeEvent($event);
        return view('events.edit', [
            'event' => $event,
            'eventTypes' => Event::TYPES,
        ]);
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $this->authorizeEvent($event);

        $validated = $request->validated();

        $validated['is_outdoor'] = $request->boolean('is_outdoor');
        $validated['is_hybrid'] = $request->boolean('is_hybrid');

        $event->update($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'Veranstaltung wurde aktualisiert.');
    }

    public function destroy(Event $event)
    {
        $this->authorizeEvent($event);
        $event->delete();
        return redirect()->route('events.index')
            ->with('success', 'Veranstaltung wurde gelöscht.');
    }

    private function authorizeEvent(Event $event): void
    {
        if ($event->organization_id !== Auth::user()->organization_id) {
            abort(403);
        }
    }
}
