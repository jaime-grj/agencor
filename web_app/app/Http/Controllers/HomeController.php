<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $events = Event::where('datetime_start', '>=', date('Y-m-d H:i:s'))->orWhere('datetime_end', '>=', date('Y-m-d H:i:s'))->orderBy('datetime_start', 'asc')->paginate(9);
        $now = date("Y-m-d H:i:s");
        $events_featured = Event::where('datetime_start_featured', '<=', $now)->where('datetime_end_featured', '>=', $now)->orderBy('datetime_start', 'asc')->limit(10)->get();
        foreach ($events as $event) {
            if ($event->datetime_start) {
                $event->datetime_start = ($event->is_time_set == 1 || $event->is_time_set === NULL)
                    ? Carbon::parse($event->datetime_start)->isoFormat('LLLL')
                    : Carbon::parse($event->datetime_start)->isoFormat('dddd, LL');
            }
        }

        foreach ($events_featured as $event) {
            if ($event->datetime_start) {
                $event->datetime_start = ($event->is_time_set == 1 || $event->is_time_set === NULL)
                    ? Carbon::parse($event->datetime_start)->isoFormat('LLLL')
                    : Carbon::parse($event->datetime_start)->isoFormat('dddd, LL');
            }
        }

        $categories = Category::where('show_on_homepage', true)->get();
        return view('home', compact('events', 'events_featured', 'categories'));
    }
    public function indexAdmin()
    {
        $events = Event::where('datetime_start', '>=', date('Y-m-d H:i:s'))->orWhere('datetime_end', '>=', date('Y-m-d H:i:s'))->orderBy('datetime_start', 'asc')->paginate(9);
        foreach ($events as $event) {
            if ($event->datetime_start) {
                $event->datetime_start = ($event->is_time_set == 1 || $event->is_time_set === NULL)
                    ? Carbon::parse($event->datetime_start)->isoFormat('LLLL')
                    : Carbon::parse($event->datetime_start)->isoFormat('dddd, LL');
            }
        }
        $categories = Category::where('show_on_homepage', true)->get();
        return view('home', compact('events', 'categories'));
    }
}
