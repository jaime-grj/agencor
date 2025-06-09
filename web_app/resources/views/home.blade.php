@extends('layouts.agencor')

@section('categories')
    @include('layouts.categories')
@endsection

@section('content')
    <div class="row justify-content-start">
        @if (count($events_featured) > 0)
        <div class="fs-4 text-center">{{ __('messages.featured') }}</div>

        <div class="scrollable-container position-relative">
            <button aria-label="{{ __('messages.scrollLeft') }}" class="scroll-btn left" id="scrollLeft">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button aria-label="{{ __('messages.scrollRight') }}"class="scroll-btn right" id="scrollRight">
                <i class="fas fa-chevron-right"></i>
            </button>

            <div class="scrollable-wrapper d-flex" id="scrollableWrapper">
                @foreach($events_featured as $event)
                <div class="col-sm-4 mt-1 mb-4 p-0">
                    <div class="h-100 mx-1 text-center p-0">
                        @if($event->media_filename)
                        <a href="/event/{{$event->id}}">
                            <img class="event-img" style="max-height:200px;border-radius: 0.25rem" src="{{url('storage/images/'.$event->media_filename)}}" @if($event->media_alt) alt="{{$event->media_alt}}" @endif>
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>


        </div>
        @endif
        <div class="fs-4 mb-3 text-center">{{ __('messages.nextEvents') }}</div>

        @if (count($events) > 0)
        @foreach($events as $event)
        <div class="col-sm-4 mt-1 mb-4">
            <div class="card h-100 d-flex flex-column card-hover-animation custom-border-color">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="text-break">
                        <a href="/event/{{$event -> id}}" class="@if (!Auth::user() || Auth::user()->type != 'Admin') stretched-link @endif fw-bold">
                            {{ Str::limit($event -> title, 50) }}
                        </a>
                    </div>
                    @if(Auth::user() && Auth::user()->type == 'Admin')
                    <div class="d-flex">
                        <a href="{{ route('edit-event.view', $event->id) }}" class="btn btn-sm btn-primary me-1">{{__('messages.edit')}}</a>
                        <a href="{{ route('delete-event.view', $event->id) }}" class="btn btn-sm btn-danger">{{__('messages.delete')}}</a>
                    </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="mb-1 text-break">{{ Str::limit($event -> short_description, 50) }}</div>
                    @if($event->media_filename)
                    <div class="d-flex justify-content-center align-items-center" style="min-height: 200px;">
                        <a href="/event/{{$event -> id}}">
                            <img style="max-height:320px;max-width:320px;height:auto;width:100%;border-radius: 0.25rem" src="{{url('storage/images/'.$event->media_filename)}}" @if($event->media_alt) alt="{{$event->media_alt}}" @endif)></img>
                        </a>
                    </div>
                    @endif
                </div>
                @if($event->datetime_start)
                    @if($event->datetime_end)
                        <div class="card-footer px-3 pb-0">
                            <p>{{__('messages.from')}} {{$event -> datetime_start}}</p>
                        </div>
                    @else
                    <div class="card-footer px-3 pb-0">
                        <p>{{$event -> datetime_start}}</p>
                    </div>
                    @endif
                @elseif ($event->url)
                <div class="card-footer">
                    <p>Más información: <a href="{{$event -> url}}" target="_blank">{{Str::limit($event -> url)}}</a></p>
                </div>
                @endif
            </div>
        </div>

        @endforeach
        <div class="row mt-3 justify-content-end">
            {{$events->links()}}
        </div>
        @else
        <div class="fs-5 mb-3 text-center">{{ __('messages.noEvents') }}</div>
        @endif
    </div>
@endsection