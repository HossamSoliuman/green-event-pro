<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventAccommodation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccommodationController extends Controller
{
    public function index(Event $event)
    {
        $this->checkAccess($event);
        $accommodations = EventAccommodation::where('event_id', $event->id)->get();
        return view('events.modules.accommodation', compact('event', 'accommodations'));
    }

    public function store(Request $request, Event $event)
    {
        $this->checkAccess($event);
        $validated = $request->validate([
            'hotel_name' => 'required|string|max:255',
            'hotel_city' => 'nullable|string|max:255',
            'hotel_country' => 'nullable|string|max:2',
            'certification_type' => 'nullable|string',
            'has_env_certification' => 'boolean',
            'hotel_informed_of_green_event' => 'boolean',
            'distance_to_venue_km' => 'nullable|numeric',
            'contingent_reserved' => 'nullable|integer',
            'nights_reserved' => 'nullable|integer',
        ]);

        $validated['event_id'] = $event->id;
        $validated['organization_id'] = $event->organization_id;
        $validated['has_env_certification'] = $request->boolean('has_env_certification');
        $validated['hotel_informed_of_green_event'] = $request->boolean('hotel_informed_of_green_event');

        EventAccommodation::create($validated);

        return redirect()->route('events.modules.accommodation', $event)
            ->with('success', 'Unterkunft hinzugefügt.');
    }

    public function update(Request $request, Event $event, EventAccommodation $accommodation)
    {
        $this->checkAccess($event);
        $data = $request->except(['_token', '_method']);
        $data['has_env_certification'] = $request->boolean('has_env_certification');
        $data['hotel_informed_of_green_event'] = $request->boolean('hotel_informed_of_green_event');
        $accommodation->update($data);

        return redirect()->route('events.modules.accommodation', $event)
            ->with('success', 'Unterkunft aktualisiert.');
    }

    public function destroy(Event $event, EventAccommodation $accommodation)
    {
        $this->checkAccess($event);
        $accommodation->delete();
        return redirect()->route('events.modules.accommodation', $event)
            ->with('success', 'Unterkunft entfernt.');
    }

    private function checkAccess(Event $event): void
    {
        if ($event->organization_id !== Auth::user()->organization_id) abort(403);
    }
}
