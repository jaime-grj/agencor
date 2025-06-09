<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class SearchController extends Controller
{
    public function searchView(Request $req)
    {
        if (count($req->all()) == 0) {
            return redirect(route('home'))->with('error', __('messages.searchNoDataError'));
        }
        else if ($req->filled('min_price') && $req->filled('max_price') && $req->min_price > $req->max_price) {
            return redirect()->back()->with('error', __('messages.searchPriceRangeError'));
        } else {
            $query = Event::query();
            $query->when($req->filled('title'), function ($query) use ($req) {
                $query->where('title', 'like', '%' . $req->title . '%');
            });
            $query->when($req->filled('description'), function ($query) use ($req) {
                $query->where(function ($q) use ($req) {
                    $q->where('short_description', 'like', '%' . $req->description . '%')
                    ->orWhere('long_description', 'like', '%' . $req->description . '%');
                });
            });
            if (!$req->filled('after') && !$req->filled('before')) {
                $query->where(function ($q) {
                    $q->where('datetime_start', '>=', Carbon::now())
                    ->orWhere('datetime_end', '>=', Carbon::now());
                });
            }
            $query->when($req->filled('before'), function ($query) use ($req) {
                $before = Carbon::parse($req->before);
                $query->where(function ($q) use ($before) {
                    $q->where(function ($sub) use ($before) {
                        $sub->whereNotNull('datetime_end')
                            ->where('datetime_end', '<', $before);
                    })->orWhere(function ($sub) use ($before) {
                        $sub->whereNull('datetime_end')
                            ->where('datetime_start', '<', $before);
                    });
                });
            });

            $query->when($req->filled('after'), function ($query) use ($req) {
                $after = Carbon::parse($req->after);
                $query->where(function ($q) use ($after) {
                    $q->where(function ($sub) use ($after) {
                        $sub->whereNull('datetime_end')
                            ->where('datetime_start', '>', $after);
                    })->orWhere(function ($sub) use ($after) {
                        $sub->whereNotNull('datetime_end')
                            ->where(function ($inner) use ($after) {
                                $inner->where('datetime_start', '>', $after)
                                    ->orWhere(function ($progressing) use ($after) {
                                        $progressing->where('datetime_start', '<=', $after)
                                                    ->where('datetime_end', '>=', $after);
                                    });
                            });
                    });
                });
            });
            $query->when($req->filled('location'), function ($query) use ($req) {
                $query->where('location', 'like', '%' . $req->location . '%');
            });

            $query->when($req->filled('min_price') || $req->filled('max_price'), function ($query) use ($req) {
                $query->where(function ($q) use ($req) {
                    if ($req->filled('min_price')) {
                        // Events with max_price or fixed price must be >= user's min_price
                        $q->where(function ($subQ) use ($req) {
                            $subQ->where('max_price', '>=', $req->min_price)
                                ->orWhere(function ($orQ) use ($req) {
                                    $orQ->whereNull('max_price')
                                        ->where('min_price', '>=', $req->min_price);
                                });
                        });
                    }

                    if ($req->filled('max_price')) {
                        // Events with min_price or fixed price must be <= user's max_price
                        $q->where(function ($subQ) use ($req) {
                            $subQ->where('min_price', '<=', $req->max_price)
                                ->orWhere(function ($orQ) use ($req) {
                                    $orQ->whereNull('max_price')
                                        ->where('min_price', '<=', $req->max_price);
                                });
                        });
                    }
                });
            });
            
            $query->when($req->filled('category'), function ($query) use ($req) {
                $query->with('categories')->whereHas('categories', function (Builder $query) use ($req) {
                    $query->whereIn('id', $req->category);
                });
            });

            $events = $query->paginate(6);
            foreach ($events as $event) {
                if ($event->datetime_start) {
                    $event->datetime_start_parsed = ($event->is_time_set == 1 || $event->is_time_set === NULL)
                        ? Carbon::parse($event->datetime_start)->isoFormat('dddd, D \d\e MMMM \d\e YYYY, H:mm\h')
                        : Carbon::parse($event->datetime_start)->isoFormat('dddd, D \d\e MMMM \d\e YYYY');
                }
            }
            $categories = Category::where('show_on_homepage', true)->get();
            return view('searchResults', compact('events', 'categories'));
        }
    }

    public function searchAdvancedView(Request $req)
    {
        $categories = Category::where('show_on_homepage', true)->get();
        return view('searchAdvanced', compact('categories'));
    }
}
