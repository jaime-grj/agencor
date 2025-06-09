@extends('layouts.agencor')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="fs-1">{{__('messages.deleteEvent')}}</div>
            <div class="fs-5 fw-bold">{{__('messages.deleteEventWarning')}}</div>
            <div class="pt-3 text-start text-break">
                <div class="fw-bold">{{ $event -> title }}</div>
                <div>{{ $event -> description }}</div>
                @if($event->datetime_start)
                <div>{{__('messages.eventStartDate')}}: {{$event -> datetime_start}}</div>
                @endif
                @if($event->filename)
                <div class="img-fluid"><img style="max-height:200px;max-width:320px;height:100%;width:100%;" src="{{url('storage/images/'.$event->filename)}}"></img></div>
                @endif
            </div>
            <form action="{{route('delete-event.submit', $event->id)}}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$event->id}}" />
                <input type="submit" class="mt-4 btn btn-danger" aria-label="{{__('messages.deleteEvent')}}" value="{{__('messages.delete')}}" />
            </form>
        </div>
    </div>
</div>
@endsection