@extends('layouts.agencor')

@section('categories')
    @include('layouts.categories')
@endsection

@section('content')
    <div class="row justify-content-start">
        <div class="text-start text-break">
            @if(isset($category))
            <div class="fs-1">{{ $category -> name }}</div>
            <div>{{ $category -> description }}</div>
            @else
            <div class="fs-1">{{__('messages.uncategorized')}}</div>
            <div>{{__('messages.uncategorizedDescription')}}</div>
            @endif
        </div>
        @if ($events->count() > 0)
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
                        <a href="{{ route('edit-event.view', $event->id) }}" aria-label="{{__('messages.editCategory')}}" class="btn btn-sm btn-primary me-1">{{__('messages.edit')}}</a>
                        <a href="{{ route('delete-event.view', $event->id) }}" aria-label="{{__('messages.deleteCategory')}}" class="btn btn-sm btn-danger">{{__('messages.delete')}}</a>
                    </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="mb-1 text-break">{{ Str::limit($event -> short_description, 50) }}</div>
                    @if($event->media_filename)
                    <div class="d-flex justify-content-center align-items-center" style="min-height: 200px;">
                        <a href="/event/{{$event -> id}}">
                            <img style="max-height:320px;max-width:320px;height:auto;width:100%;border-radius: 0.25rem" src="{{url('storage/images/'.$event->media_filename)}}" @if($event->media_alt) alt="{{$event->media_alt}}" @endif></img>
                        </a>
                    </div>
                    @endif
                </div>
                @if($event->datetime_start)
                <div class="card-footer px-3 pb-0">
                    <p>{{$event -> datetime_start}}</p>
                </div>
                @elseif ($event->url)
                <div class="card-footer">
                    <p>Más información: <a href="{{$event -> url}}" target="_blank">{{Str::limit($event -> url)}}</a></p>
                </div>
                @endif
            </div>
        </div>
        @endforeach
        @else
        <h5 class="mt-3">{{__('messages.noEventsFoundInCategory')}}</h3>
            @endif
    </div>

@endsection

@if(isset($category))
@section('title') - {{ $category -> name }}@endsection
@else
@section('title') - {{__('messages.uncategorized')}}@endsection
@endif