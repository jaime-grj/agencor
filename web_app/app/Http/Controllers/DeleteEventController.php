<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

use App\Models\Event;
use Carbon\Carbon;

class DeleteEventController extends Controller
{
    public function deleteEventView(Request $req, $eventId)
    {
        $event = Event::where('id', '=', $eventId)->first();
        if (Auth::user()->type == 'Admin') {
            if ($event) {
                $event->datetime_start = ($event->is_time_set == 1 || $event->is_time_set === NULL)
                    ? Carbon::parse($event->datetime_start)->isoFormat('dddd, D \d\e MMMM \d\e YYYY, H:mm\h')
                    : Carbon::parse($event->datetime_start)->isoFormat('dddd, D \d\e MMMM \d\e YYYY');
                return view('admin.deleteEvent', compact('event'));
            } else {
                return redirect(route('home'))->with('error', __('messages.eventNotFound'));
            }
        } else {
            return redirect(route('home'))->with('error', __('messages.accessDenied'));
        }
    }

    public function deleteEventSubmit(Request $req)
    {
        $event = Event::where('id', $req->id)->first();
        if ($event != null) {
            $event_image = $event->image_filename;
            if (Auth::user()->type == 'Admin') {
                $res = Event::where('id', $req->id)->delete();
                if ($res) {
                    if ($event_image) {
                        if (File::exists(public_path('images/' . $event_image))) {
                            File::delete(public_path('images/' . $event_image));
                        } else {
                            return redirect(route('home'))->with('msg', __('messages.eventDeletedNotFile'));
                        }
                    }
                    return redirect(route('home'))->with('msg', __('messages.eventDeleted'));
                } else {
                    return redirect(route('home'))->with('error', __('messages.errorDeletingEvent'));
                }
            } else {
                return redirect(route('home'))->with('error', __('messages.accessDenied'));
            }
        } else {
            return redirect(route('home'))->with('error', __('messages.errorEventNotFound'));
        }
    }
}
