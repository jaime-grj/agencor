<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class ApiController extends Controller
{
    public function getCategories()
    {
        $categories = Category::where('show_on_homepage', true)->paginate(5);
        return response()->json(
            ['data' => $categories]
        );
    }

    public function getCategoryById($categoryId)
    {
        $category = Category::where('id', '=', $categoryId)->first();
        return response()->json(
            ['data' => $category]
        );
    }
    
    public function getEvents(Request $req)
    {
        $events = Event::where('datetime_start', '>=', date('Y-m-d H:i:s'))->orWhere('datetime_end', '>=', date('Y-m-d H:i:s'))->orderBy('datetime_start', 'asc')->paginate(5);
        return response()->json(
            ['data' => $events]
        );
    }

    public function getFeaturedEvents()
    {
        $now = date("Y-m-d H:i:s");
        $events_highlight = Event::where('datetime_start_featured', '<=', $now)->where('datetime_end_featured', '>=', $now)->orderBy('datetime_start', 'asc')->limit(10)->get();
        return response()->json(
            ['data' => $events_highlight]
        );
    }

    public function getEventById(Request $req)
    {
        $event = Event::where('id', '=', $req->id)->first();
        return response()->json(
            ['data' => $event]
        );
    }

    public function getEventsByCategory($categoryId)
    {
        $event = Event::with('categories')->whereHas('categories', function (Builder $query) use ($categoryId) {
            $query->where('id', $categoryId);
        })->paginate(5);
        return response()->json(
            ['data' => $event]
        );
    }

    public function getSearchResults(Request $req)
    {
        if (count($req->all()) == 0) {
            return response()->json(
                ['error' => __('messages.searchNoDataError')],
                400
            );
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
                $categoryIds = is_array($req->category) ? $req->category : [$req->category];
                $query->with('categories')->whereHas('categories', function (Builder $query) use ($categoryIds) {
                    $query->whereIn('id', $categoryIds);
                });
            });

            $events = $query->paginate(5);
            return response()->json(
                ['data' => $events]
            );
        }
    }
}
