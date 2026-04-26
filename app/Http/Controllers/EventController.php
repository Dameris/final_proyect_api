<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\EventRequest;
use Inertia\Inertia;
use Exception;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * List of events (Calendar)
     */
    public function index()
    {
        try {
            $events = Event::all()->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => \Carbon\Carbon::parse($event->start_date)->toIso8601String(),
                    'end' => $event->end_date
                        ? \Carbon\Carbon::parse($event->end_date)->toIso8601String()
                        : null,
                    'color' => $event->color,
                ];
            });

            return Inertia::render('Events/Index', [
                'events' => $events
            ]);
        } catch (Exception $e) {
            return back()->withErrors(["error" => "Error loading events"]);
        }
    }

    /**
     * Show create form
     */
    public function create()
    {
        return Inertia::render('Events/Create');
    }

    /**
     * Store new event
     */
    public function store(EventRequest $request)
    {
        try {
            Event::create($request->validated());

            return redirect()->route('events.index');
        } catch (Exception $e) {
            return back()->withErrors(["error" => "Error creating event"]);
        }
    }

    /**
     * Show edit form
     */
    public function edit(Event $event)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return Inertia::render('Events/Edit', [
            'event' => $event,
            'auth' => [
                'user' => $user ? $user->load('roles') : null,
            ],
        ]);
    }

    /**
     * Update event
     */
    public function update(EventRequest $request, Event $event)
    {
        try {
            $event->update($request->validated());

            return redirect()->route('events.index');
        } catch (Exception $e) {
            return back()->withErrors(["error" => "Error updating event"]);
        }
    }

    /**
     * Delete event
     */
    public function destroy(Event $event)
    {
        try {
            $event->delete();

            return redirect()->route('events.index');
        } catch (Exception $e) {
            return back()->withErrors(["error" => "Error deleting event"]);
        }
    }
}
