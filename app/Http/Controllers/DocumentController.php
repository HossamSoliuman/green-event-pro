<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(Request $request, Event $event)
    {
        if ($event->organization_id !== Auth::user()->organization_id) abort(403);
        $request->validate(['file' => 'required|file|max:10240', 'module' => 'nullable|string']);
        $path = $request->file('file')->store("events/{$event->id}/documents");
        EventDocument::create([
            'event_id' => $event->id,
            'organization_id' => $event->organization_id,
            'module' => $request->module,
            'file_name' => $request->file('file')->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $request->file('file')->getMimeType(),
            'uploaded_by' => Auth::id(),
        ]);
        return back()->with('success', 'Dokument hochgeladen.');
    }

    public function destroy(Event $event, EventDocument $document)
    {
        if ($event->organization_id !== Auth::user()->organization_id) abort(403);
        Storage::delete($document->file_path);
        $document->delete();
        return back()->with('success', 'Dokument gelöscht.');
    }
}
