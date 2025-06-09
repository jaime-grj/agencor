@extends('layouts.agencor')

@section('categories')
    @include('layouts.categories')
@endsection

@section('content')
    <div class="row justify-content-start">
        <div class="d-flex justify-content-between ml-auto mb-3">
            <div class="fs-4 text-start">{{__('messages.searchResults')}}</div>
            <a href="/search/advanced" class="text-center btn btn-primary me-1">{{__('messages.advancedSearch')}}</a>
        </div>
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
                    @if (
                        ($event->datetime_end && \Carbon\Carbon::parse($event->datetime_end)->isPast()) ||
                        ($event->datetime_start && !$event->datetime_end && \Carbon\Carbon::parse($event->datetime_start)->isPast())
                    )
                        <div class="d-flex justify-content-center">
                            <span class="badge fs-5 bg-dark-red text-center">{{ __('messages.eventEnded') }}</span>
                        </div>
                    @endif
                    <div class="mb-1 text-break">{{ Str::limit($event -> short_description, 50) }}</div>
                    @if($event->media_filename)
                    <div class="d-flex justify-content-center align-items-center" style="min-height: 200px;">
                        <a href="/event/{{$event -> id}}">
                            <img style="max-height:320px;max-width:320px;height:auto;width:100%;border-radius: 0.25rem" src="{{url('storage/images/'.$event->media_filename)}}" @if($event->media_alt) alt="{{$event->media_alt}}" @endif></img>
                        </a>
                    </div>
                    @endif
                </div>
                @if($event->datetime_start_parsed)
                <div class="card-footer px-3 pb-0">
                    <p>{{$event -> datetime_start_parsed}}</p>
                </div>
                @elseif ($event->url)
                <div class="card-footer">
                    <p>Más información: <a href="{{$event -> url}}" target="_blank">{{Str::limit($event -> url)}}</a></p>
                </div>
                @endif
            </div>
        </div>
        @endforeach
        <div class="row mt-3 justify-content-end">
            {{$events->appends(request()->query())->links()}}
        </div>
    </div>
@endsection