<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use Carbon\Carbon;

class EventDetailController extends Controller
{
    public function eventDetailView(Request $req, $eventId)
    {
        $event = Event::where('id', '=', $eventId)->first();
        if ($event) {
            if ($event->datetime_start) {
                $event->date_start_parsed = Carbon::parse($event->datetime_start)->isoFormat('dddd, D \d\e MMMM \d\e YYYY');
                $event->time_start_parsed = ($event->is_time_set == 1 || $event->is_time_set === NULL)
                    ? Carbon::parse($event->datetime_start)->isoFormat('H:mm\h')
                    : null;
            }
            if ($event->datetime_end) {
                $event->date_end_parsed = Carbon::parse($event->datetime_end)->isoFormat('dddd, D \d\e MMMM \d\e YYYY');
                $event->time_end_parsed = ($event->is_time_set == 1 || $event->is_time_set === NULL)
                    ? Carbon::parse($event->datetime_end)->isoFormat('H:mm\h')
                    : null;
            }
        } else {
            return redirect(route('home'))->with('error', __('messages.errorEventNotFound'));
        }
        $categories = Category::where('show_on_homepage', true)->get();
        $event_db = Event::where('id', '=', $eventId)->first();
        if ($event_db->views == null) {
            $event_db->views = 0;
        }
        $event_db->views = $event->views + 1;
        $event_db->save();
        return view('eventDetail', compact('event', 'categories'));
    }
}
