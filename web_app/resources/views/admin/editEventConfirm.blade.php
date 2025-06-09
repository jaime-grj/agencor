@extends('layouts.agencor')

@section('content')
<div class="container">
    <div class="row justify-content-start">
        <div class="col-md-10">
            <h1>{{__('messages.editEventConfirm')}}</h1>
            <div id="eventDetails">
                <form method="POST" action="{{ route('edit-event-confirm.submit', $event->id) }}" accept-charset="utf-8" enctype="multipart/form-data">
                    @csrf
                    @if(isset($event_session["title"]))
                    <div class="row mb-3">
                        <label for="title" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventTitle') }}</label>

                        <div class="col-md-6">
                            <input disabled id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{$event_session['title']}}" required autocomplete="title" autofocus>

                            @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    @if(isset($event_session["short_description"]))
                    <div class="row mb-3">
                        <label for="short_description" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventShortDescription') }}</label>

                        <div class="col-md-6">
                            <input disabled id="short_description" type="text" class="form-control @error('short_description') is-invalid @enderror" name="short_description" value="{{$event_session['short_description']}}" required autocomplete="short_description">

                            @error('short_description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    @if(isset($event_session["long_description"]))
                    <div class="row mb-3">
                        <label for="long_description" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventLongDescription') }}</label>

                        <div class="col-md-6">
                            <textarea disabled id="long_description" class="form-control @error('long_description') is-invalid @enderror" name="long_description" required autocomplete="long_description" autofocus>{{$event_session['long_description']}}</textarea>

                            @error('long_description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    @if(isset($event_session["datetime_start"]))
                    <div class="row mb-3">
                        <label for="datetime_start" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventStartDate') }}</label>

                        <div class="col-md-6">
                            <input disabled id="datetime_start" type="datetime-local" class="form-control @error('datetime_start') is-invalid @enderror" name="datetime_start" value="{{$event_session['datetime_start']}}" autocomplete="datetime_start">

                            @error('datetime_start')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    @if(isset($event_session["datetime_end"]))
                    <div class="row mb-3">
                        <label for="datetime_end" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventEndDate') }}</label>

                        <div class="col-md-6">
                            <input disabled id="datetime_end" type="datetime-local" class="form-control @error('datetime_end') is-invalid @enderror" name="datetime_end" value="{{$event_session['datetime_end']}}" autocomplete="datetime_end">

                            @error('datetime_end')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    @if(isset($event_session["datetime_start_featured"]))
                    <div class="row mb-3">
                        <label for="datetime_start_featured" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventStartDateFeatured') }}</label>

                        <div class="col-md-6">
                            <input disabled id="datetime_start_featured" type="datetime-local" class="form-control @error('datetime_start_featured') is-invalid @enderror" name="datetime_start_featured" value="{{$event_session['datetime_start_featured']}}" autocomplete="datetime_start_featured">

                            @error('datetime_start_featured')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    @if(isset($event_session["datetime_end_featured"]))
                    <div class="row mb-3">
                        <label for="datetime_end_featured" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventEndDateFeatured') }}</label>

                        <div class="col-md-6">
                            <input disabled id="datetime_end_featured" type="datetime-local" class="form-control @error('datetime_end_featured') is-invalid @enderror" name="datetime_end_featured" value="{{$event_session['datetime_end_featured']}}" autocomplete="datetime_end_featured">

                            @error('datetime_end_featured')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <label for="is_time_set" class="form-check-label col-md-4 col-form-label text-md-end">{{ __('messages.eventIsTimeSet') }}</label>

                        <div class="col-md-6">
                            <input disabled id="is_time_set" type="checkbox" @if(isset($event_session["is_time_set"])) checked @endif class="form-check-input @error('is_time_set') is-invalid @enderror" name="is_time_set" autocomplete="is_time_set" />

                            @error('is_time_set')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @if(isset($event_session["capacity"]))
                    <div class="row mb-3">
                        <label for="capacity" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventCapacity') }}</label>

                        <div class="col-md-6">
                            <input disabled id="capacity" type="number" class=" form-control @error('capacity') is-invalid @enderror" name="capacity" value="{{$event_session['capacity']}}" required autocomplete="capacity">

                            @error('capacity')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    @if(isset($event_session["min_price"]))
                    <div class="row mb-3">
                        <label for="min_price" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventPriceOrMinPrice') }}</label>

                        <div class="col-md-6">
                            <input disabled id="capacity" type="number" class=" form-control @error('min_price') is-invalid @enderror" name="min_price" value="{{$event_session['min_price']}}" required autocomplete="min_price">

                            @error('min_price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    @if(isset($event_session["max_price"]))
                    <div class="row mb-3">
                        <label for="max_price" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventMaxPrice') }}</label>

                        <div class="col-md-6">
                            <input disabled id="max_price" type="number" class=" form-control @error('max_price') is-invalid @enderror" name="max_price" value="{{$event_session['max_price']}}" required autocomplete="max_price">

                            @error('max_price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    @if(isset($event_session["location"]))
                    <div class="row mb-3">
                        <label for="location" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventLocation') }}</label>

                        <div class="col-md-6">
                            <input disabled id="location" type="text" class=" form-control @error('location') is-invalid @enderror" name="location" value="{{$event_session['location']}}" autocomplete="location">

                            @error('location')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <label for="show_location_map" class="form-check-label col-md-4 col-form-label text-md-end">{{ __('messages.eventShowLocationMap') }}</label>

                        <div class="col-md-6">
                            <input disabled id="show_location_map" type="checkbox" @if(isset($event_session["show_location_map"])) checked @endif class="form-check-input @error('show_location_map') is-invalid @enderror" name="show_location_map" autocomplete="show_location_map" />

                            @error('show_location_map')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @if(isset($event_session["url"]))
                    <div class="row mb-3">
                        <label for="url" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventUrl') }}</label>

                        <div class="col-md-6">
                            <input disabled id="url" type="text" class="form-control @error('url') is-invalid @enderror" name="url" value="{{$event_session['url']}}" autocomplete="url">

                            @error('url')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    @if(isset($event_session["category"]))
                    <div class="row mb-3">
                        <label for="url" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventCategories') }}</label>

                        <div class="col-md-6">
                            @foreach($categories as $category)
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="category[]"
                                    value="{{ $category->id }}"
                                    {{ in_array($category->id, $event_session["category"]) ? 'checked' : '' }}
                                    disabled>
                                {{ $category->name }}<br>
                            @endforeach
                            @error('category')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    @if(isset($event_session["media_filename"]))
                    <div class="row mb-3">
                        <label for="file" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventImage') }}</label>

                        <div class="col-md-6">
                            <img style="max-height:200px;max-width:320px;height:100%;width:100%;" src="{{url('storage/tmp/'.$event_session['media_filename'])}}"></img>
                            @error('file')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @elseif(isset($event_session["delete_media"]))
                    <div class="row mb-3">
                        <label for="delete_media" class="form-check-label col-md-4 col-form-label text-md-end">{{ __('messages.deleteMedia') }}</label>

                        <div class="col-md-6">
                            <input disabled id="delete_media" type="checkbox" @if(isset($event_session["delete_media"])) checked @endif class="form-check-input @error('delete_media') is-invalid @enderror" name="delete_media" autocomplete="delete_media" />

                            @error('delete_media')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @elseif($event->media_filename)
                    <div class="row mb-3">
                        <label for="file" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventImage') }}</label>
                            <div class="col-md-6">
                                <div class="img-fluid mt-3 mb-3">
                                    <img style="max-height:240px" src="{{url('storage/images/'.$event->media_filename)}}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="url" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventMediaAlt') }}</label>

                        <div class="col-md-6">
                            <textarea disabled name="media_alt" class="form-control @error('media_alt') is-invalid @enderror">{{$event_session['media_alt']}}</textarea>

                            @error('media_alt')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <a href="{{ route('edit-event.view', $event->id) }}" aria-label="{{__('messages.previous')}}" class="btn btn-secondary me-2">
                                {{ __('messages.previous') }}
                            </a>
                            <button type="submit" aria-label="{{__('messages.save')}}" class="btn btn-primary">
                                {{ __('messages.save') }}
                            </button>
                        </div>
                    </div>
                </form>
            <div>
        </div>
    </div>
</div>
@endsection
