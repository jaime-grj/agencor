<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\Category;
use App\Models\Event;
use Carbon\Carbon;

class CategoryDetailController extends Controller
{

    public function categoryDetailView(Request $req, $categoryId)
    {
        $categories = Category::where('show_on_homepage', true)->get();
        if ($categoryId == 'uncategorized') {
            $events = Event::without('categories')->get();
            foreach ($events as $event) {
                if ($event->datetime_start) {
                    $event->datetime_start = ($event->is_time_set == 1 || $event->is_time_set === NULL)
                        ? Carbon::parse($event->datetime_start)->isoFormat('LLLL')
                        : Carbon::parse($event->datetime_start)->isoFormat('dddd, LL');
                }
            }
            return view('categoryDetail', compact('events', 'categories'));
        } else {
            $category = Category::where('id', '=', $categoryId)->first();
            if ($category) {
                $events = $category->events()->get();
                foreach ($events as $event) {
                    if ($event->datetime_start) {
                        $event->datetime_start = ($event->is_time_set == 1 || $event->is_time_set === NULL)
                            ? Carbon::parse($event->datetime_start)->isoFormat('LLLL')
                            : Carbon::parse($event->datetime_start)->isoFormat('dddd, LL');
                    }
                }
                return view('categoryDetail', compact('category', 'events', 'categories'));
            } else {
                return redirect(route('home'))->with('error', __('messages.errorCategoryNotFound'));
            }
        }
    }


    public function categoryList(Request $req)
    {
        $categories = Category::all();
        return view('admin.categoryList', compact('categories'));
    }
}
