<?php

namespace App\Http\Controllers;

use App\events;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function retrieve() {
        $events = events::all();
        return $events;
    }
    public function create(Request $request) {
        $event = new events;
        $event->title = $request->title;
        $event->description = $request->description;
        $event->start = $request->start;
        $event->end = $request->end;
        $event->classNames = $request->classNames;
        $event->save();
    }
    public function remove(Request $request) {
        $event = events::findOrFail($request->id); //look for specific Id;
        $event->delete();
    }
    public function update(Request $request) {
        $event = events::findOrFail($request->id);
        $event->title = $request->title;
        $event->description = $request->description;
        $event->save();
    }
}
