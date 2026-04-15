<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTvProduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TvProductionController extends Controller
{
    public function edit(Event $event)
    {
        $this->checkAccess($event);
        $module = EventTvProduction::firstOrNew([
            'event_id' => $event->id,
            'organization_id' => $event->organization_id,
        ]);
        $view = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', 'TvProduction'));
        return view('events.modules.' . $view, compact('event', 'module'));
    }

    public function update(Request $request, Event $event)
    {
        $this->checkAccess($event);
        $data = $request->except(['_token', '_method']);
        $data['event_id'] = $event->id;
        $data['organization_id'] = $event->organization_id;

        EventTvProduction::updateOrCreate(['event_id' => $event->id], $data);

        return redirect()->route('events.show', $event)
            ->with('success', 'Daten erfolgreich gespeichert.');
    }

    private function checkAccess(Event $event): void
    {
        if ($event->organization_id !== Auth::user()->organization_id) abort(403);
    }
}
