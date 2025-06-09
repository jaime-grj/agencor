@extends('layouts.agencor')

@section('categories')
    @include('layouts.categories')
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-body position-relative">
                    @if (Auth::user() && Auth::user()->type == 'Admin')
                       
                        <div class="position-absolute top-0 end-0 m-2 d-flex align-items-center">
                            <div class="btn-group me-2" role="group">
                                <a href="{{ route('edit-event.view', $event->id) }}" aria-label="{{__('messages.editEvent')}}" class="badge bg-primary me-1 mb-2">{{__('messages.edit')}}</a>
                                <a href="{{ route('delete-event.view', $event->id) }}" aria-label="{{__('messages.deleteEvent')}}" class="badge bg-danger mb-2">{{__('messages.delete')}}</a>
                            </div>
                        @if ($event->views != null)
                            <span class="badge bg-secondary mb-2" aria-label="{{__('messages.views')}}">
                                <i class="fas fa-eye"></i> {{ $event->views }}
                            </span>
                         @endif
                        </div>
                    @endif

                    @if (
                        ($event->datetime_end && \Carbon\Carbon::parse($event->datetime_end)->isPast()) ||
                        ($event->datetime_start && !$event->datetime_end && \Carbon\Carbon::parse($event->datetime_start)->isPast())
                    )
                        <div class="text-center fs-1 mb-2">
                            <span class="badge bg-dark-red">{{ __('messages.eventEnded') }}</span>
                        </div>
                    @endif
                    <!-- Event Title -->
                    <h2 class="card-title text-center">{{ $event->title }}</h2>

                    <!-- Event Timing -->
                    @if($event->datetime_start || $event->datetime_end)

                    @if ($event->date_start_parsed != $event->date_end_parsed)
                    @if($event->datetime_start)
                    <div class="fs-5 text-center">
                        <i class="fas fa-calendar-alt"></i> {{ $event->date_start_parsed }}
                        @if($event->datetime_end) {{ __('messages.to') }} {{ $event->date_end_parsed }}
                        @endif
                    </div>
                    @endif
                    @else
                    <div class="fs-5 text-center">
                        <i class="fas fa-calendar-alt"></i> {{ $event->date_start_parsed }}
                    </div>
                    @if ($event->is_time_set == 1 || $event->is_time_set === NULL)
                    <div class="fs-5 text-center">
                        <i class="fas fa-clock"></i> {{ $event->time_start_parsed }} {{ __('messages.to') }} {{ $event->time_end_parsed }}
                    </div>
                    @endif
                    @endif
                    @endif

                    <!-- Event Image -->
                    @if($event->media_filename)
                    <div class="text-center my-4">
                        <img class="img-fluid rounded" src="{{ url('storage/images/'.$event->media_filename) }}" style="max-height:600px; width:auto;" alt="{{ $event->media_alt }}">
                    </div>
                    @endif

                    <!-- Event Description -->
                    <p class="card-text mt-3">{!! nl2br(e($event->long_description)) !!}</p>


                    <!-- Event Location and Map -->
                    @if($event->location)
                    <div class="mt-3">
                        <h5><i class="fas fa-map-marker-alt"></i> {{__('messages.eventLocation')}}: {{ $event->location }}</h5>
                        @if ($event->show_location_map)
                        <div class="embed-responsive embed-responsive-16by9 mt-2">
                            <iframe class="embed-responsive-item" style="border:0;" src="https://www.google.com/maps/embed/v1/place?q={{ $event->location }}&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8" allowfullscreen></iframe>
                        </div>
                        @endif
                        <small>{{ __('messages.mapLocationsInaccurate') }}</small>
                    </div>
                    @endif


                    <!-- Event Capacity -->
                    @if ($event->capacity)
                    <div class="mt-3">
                        <i class="fas fa-users"></i> {{__('messages.eventCapacity')}}: {{ $event->capacity }} {{__('messages.people')}}
                    </div>
                    @endif

                    <!-- Event Price -->
                    @if ($event->min_price !== null)
                        <div class="mt-3">
                            @if ($event->max_price && $event->min_price != $event->max_price)
                                <i class="fas fa-coins"></i> {{ __('messages.eventPrice') }}: {{ $event->min_price }} {{ __('messages.to') }} {{ $event->max_price }} €
                            @elseif ($event->min_price == 0)
                                <i class="fas fa-coins"></i> {{ __('messages.eventFree') }}
                            @else
                                <i class="fas fa-coins"></i> {{ __('messages.eventPrice') }}: {{ $event->min_price }} €
                            @endif
                        </div>
                    @endif

                    <!-- Event URL -->
                    @if ($event->url)
                    <div class="mt-3">
                        <i class="fas fa-link"></i> {{__('messages.eventMoreInfo')}} <a href="{{$event->url}}" target="_blank">{{Str::limit($event->url, 100)}}</a>
                    </div>
                    @endif


                    <!-- Event Categories -->
                    @if ($event->categories && count($event->categories) > 0)
                    <div class="mt-3">
                        <h6><i class="fas fa-tags"></i> {{__('messages.eventCategories')}}:</h6>
                        <div class="d-flex flex-wrap">
                            @foreach ($event->categories as $category)
                            <a href="/category/{{$category->id}}" class="badge bg-primary text-white me-2">{{ $category->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Social Sharing Buttons -->
                    <h6><i class="fas fa-share-alt mt-2"></i> {{__('messages.share')}}</h6>
                    <div>
                        <a href="https://wa.me/?text={{ urlencode($event->title . ' ' . url('/event/'.$event->id)) }}" aria-label="{{__('messages.shareOn')}} WhatsApp" target="_blank" class="btn btn-outline-success btn-sm me-1">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/event/'.$event->id)) }}" aria-label="{{__('messages.shareOn')}} Facebook" target="_blank" class="btn btn-outline-primary btn-sm me-1">
                            <i class="fab fa-facebook"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($event->title) }}&url={{ urlencode(url('/event/'.$event->id)) }}" aria-label="{{__('messages.shareOn')}} Twitter/X" target="_blank" class="btn btn-outline-info btn-sm me-1">
                            <i class="fab fa-twitter"></i> Twitter/X
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection