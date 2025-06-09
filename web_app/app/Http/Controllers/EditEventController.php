<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Event;
use App\Models\Category;
use Carbon\Carbon;

class EditEventController extends Controller
{
    public function editEventView(Request $req, $eventId)
    {
        $categories = Category::all();
        $event = Event::where('id', '=', $eventId)->with('categories')->first();
        if ($event) {
            return view('admin.editEvent', compact('event', 'categories'));
        } else {
            return redirect(route('home'))->with('error', __('messages.eventNotFound'));
        }
    }

    public function editEventSubmit(Request $req, $eventId)
    {
        if (Auth::user()->type == 'Admin') {
            $validated = Validator::make($req->all(), [
                'id' => 'required',
                'title' => 'required',
                'short_description' => 'nullable',
                'long_description' => 'required',
                'datetime_start' => 'date|nullable',
                'datetime_end' => 'date|nullable',
                'is_time_set' => 'nullable',
                'capacity' => 'numeric|min:0|max:5000000|nullable',
                'min_price' => 'numeric|min:0|max:5000000|nullable',
                'max_price' => 'numeric|min:0|max:5000000|nullable',
                'media_alt' => 'nullable',
                'location' => 'nullable',
                'show_location_map' => 'nullable',
                'url' => 'nullable',
                'category' => 'nullable',
                'delete_media' => 'nullable',
                'datetime_start_featured' => 'date|nullable',
                'datetime_end_featured' => 'date|nullable',
            ])->after(function ($validator) use (&$req) {
                if ($req->min_price !== null && $req->max_price !== null && $req->min_price > $req->max_price) {
                    $validator->errors()->add('max_price', __('messages.validationPriceRangeError'));
                }

                // Adjust datetime if time is not set
                if ($req->is_time_set == false || $req->is_time_set === '0' || $req->is_time_set === 0 || $req->is_time_set === null) {
                    if (!empty($req->datetime_start)) {
                        $req->merge([
                            'datetime_start' => Carbon::parse($req->datetime_start)->startOfDay()->format('Y-m-d H:i:s')
                        ]);
                    }
                    if (!empty($req->datetime_end)) {
                        $req->merge([
                            'datetime_end' => Carbon::parse($req->datetime_end)->endOfDay()->format('Y-m-d H:i:s')
                        ]);
                    }
                }

                if (!empty($req->datetime_start) && !empty($req->datetime_end)) {
                    $start = Carbon::parse($req->datetime_start);
                    $end = Carbon::parse($req->datetime_end);
                    if ($start->gt($end)) {
                        $validator->errors()->add('datetime_end', __('messages.validationDateRangeError'));
                    }
                }
            })->validate();
            $file_validated  = $req->validate([
                'file' => 'image|max:30000|nullable'
            ]);
            if ($req->file) {
                $filename = time() . '_' . $req->file->getClientOriginalName();
                $req->file->move(public_path('storage/tmp/'), $filename);
                $validated['media_filename'] = $filename;
                $event = Event::where('id', '=', $eventId)->first();
                $validated['old_media_filename'] = $event->media_filename;
            }
            Session::put('event', $validated);
            return redirect(route('edit-event-confirm.view', ['eventId' => $eventId]));
        } else {
            return redirect(route('home'))->with('error', __('messages.accessDenied'));
        }
    }

    public function editEventConfirmView(Request $req, $eventId)
    {
        if (Auth::user()->type == 'Admin') {
            $event_session = $req->session()->get('event');
            $categories = Category::all();
            if (isset($event_session['id'])) {
                if ($eventId != $event_session['id']) {
                    $req->session()->forget('event');
                    return redirect(route('home'))->with('error', __('messages.errorEditingMultipleEvents'));
                }
            }
            else {
                $req->session()->forget('event');
                return redirect(route('home'))->with('error', __('messages.errorObtainingEventId'));
            }
            $event = Event::where('id', '=', $eventId)->first();
            if ($event_session) {
                return view('admin.editEventConfirm', compact('event_session', 'categories', 'event'));
            } else {
                return redirect(route('home'));
            }
        } else {
            return redirect(route('home'))->with('error', __('messages.accessDenied'));
        }
    }

    public function editEventConfirmSubmit(Request $req)
    {
        if (Auth::user()->type !== 'Admin') {
            return redirect(route('home'))->with('error', __('messages.accessDenied'));
        }

        $event_session = $req->session()->pull('event');
        $event = Event::where('id', '=', $req->eventId)->first();
        if (!$event) {
            return redirect(route('home'))->with('error', __('messages.errorEventNotFound'));
        }

        $fields = [
            'title', 'short_description', 'long_description',
            'datetime_start', 'datetime_end', 'capacity',
            'min_price', 'max_price', 'media_alt',
            'datetime_start_featured', 'datetime_end_featured',
            'location', 'url', 'media_filename'
        ];

        foreach ($fields as $field) {
            if (isset($event_session[$field])) {
                $event->$field = $event_session[$field];
            }
        }

        $event->is_time_set = isset($event_session['is_time_set']) && $event_session['is_time_set'] === 'on' ? 1 : 0;
        $event->show_location_map = isset($event_session['show_location_map']) && $event_session['show_location_map'] === 'on' ? 1 : 0;


        if (!empty($event_session['delete_media'])) {
            $event->media_filename = null;
            if (File::exists(public_path('storage/images/' . $event->media_filename))) {
                File::delete(public_path('storage/images/' . $event->media_filename));
            }
        }

        try {
            $saved = $event->save();
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect(route('home'))->with('error', __('messages.errorSavingInDatabase') . ': ' . $ex->getMessage());
        }

        if (!$saved) {
            return redirect(route('home'))->with('error', __('messages.errorSavingInDatabase'));
        }

        if (!empty($event->media_filename)) {
            if (File::exists(public_path('storage/images/' . $event->media_filename)) == false) {
                $tmpPath = public_path('storage/tmp/' . $event->media_filename);
                $finalPath = public_path('storage/images/' . $event->media_filename);

                if (File::exists($tmpPath)) {
                    File::move($tmpPath, $finalPath);
                    File::delete(public_path('storage/images/' . $event_session['old_media_filename']));
                } else {
                    $event->media_filename = $event_session['old_media_filename'];
                    try {
                        $saved = $event->save();
                    } catch (\Illuminate\Database\QueryException $ex) {
                        return redirect(route('home'))->with('error', __('messages.errorEditingEventAndRevertingFilename') . ': ' . $ex->getMessage());
                    }
                    if (!$saved) {
                        return redirect(route('home'))->with('error', __('messages.errorEditingEventAndRevertingFilename'));
                    }
                    return redirect(route('home'))->with('error', __('messages.errorEditingEventFileNotFound'));
                }
            }
        }

        if (isset($event_session['category'])) {
            $event->categories()->sync($event_session['category']);
        }

        return redirect(route('home'))->with('msg', __('messages.eventEdited'));
    }

    public function editEventCancel(Request $req)
    {
        $req->session()->forget('event');
        return redirect(route('home'));
    }
}
