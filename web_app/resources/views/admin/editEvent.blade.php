@extends('layouts.agencor')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1>{{__('messages.editEvent')}}</h1>
            <div id="eventDetails">
                <form method="POST" action="{{ route('edit-event.submit', $event->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <label for="title" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventTitle') }} (*)</label>

                        <div class="col-md-6">
                            <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ $event->title }}" required autocomplete="title" autofocus>

                            @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <input type="hidden" name="id" value="{{ $event->id }}">

                    <div class="row mb-3">
                        <label for="short_description" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventShortDescription') }}</label>

                        <div class="col-md-6">
                            <input id="short_description" type="text" class="form-control @error('short_description') is-invalid @enderror" name="short_description" value="{{ $event->short_description }}" autocomplete="short_description">

                            @error('short_description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="long_description" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventLongDescription') }} (*)</label>

                        <div class="col-md-6">
                            <textarea name="long_description" class="form-control @error('long_description') is-invalid @enderror">{{ old('long_description', $event->long_description) }}</textarea>
                            @error('long_description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div id="date-range">
                        <div class="row mb-3">
                            <label for="datetime_start" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventStartDate') }}</label>

                            <div class="col-md-6">
                                <input id="datetime_start" type="datetime-local" class="form-control @error('datetime_start') is-invalid @enderror" name="datetime_start" value="{{ $event->datetime_start }}" autocomplete="datetime_start">

                                @error('datetime_start')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="datetime_end" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventEndDate') }}</label>

                            <div class="col-md-6">
                                <input id="datetime_end" type="datetime-local" class="form-control @error('datetime_end') is-invalid @enderror" name="datetime_end" value="{{ $event->datetime_end }}" autocomplete="datetime_end">

                                @error('datetime_end')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div id="date-range">
                        <div class="row mb-3">
                            <label for="datetime_start_featured" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventStartDateFeatured') }}</label>

                            <div class="col-md-6">
                                <input id="datetime_start_featured" type="datetime-local" class="form-control @error('datetime_start_featured') is-invalid @enderror" name="datetime_start_featured" value="{{ $event->datetime_start_featured }}" autocomplete="datetime_start_featured">

                                @error('datetime_start_featured')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="datetime_end_featured" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventEndDateFeatured') }}</label>

                            <div class="col-md-6">
                                <input id="datetime_end_featured" type="datetime-local" class="form-control @error('datetime_end_featured') is-invalid @enderror" name="datetime_end_featured" value="{{ $event->datetime_end_featured }}" autocomplete="datetime_end_featured">

                                @error('datetime_end_featured')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="is_time_set" class="form-check-label col-md-4 col-form-label text-md-end">{{ __('messages.eventIsTimeSet') }}</label>

                        <div class="col-md-6">
                            <input id="is_time_set" type="checkbox" class="form-check-input @error('is_time_set') is-invalid @enderror" name="is_time_set" autocomplete="is_time_set" @if($event->is_time_set) checked @endif/>

                            @error('is_time_set')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="capacity" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventCapacity') }}</label>

                        <div class="col-md-6">
                            <input id="capacity" type="number" class="form-control @error('capacity') is-invalid @enderror" name="capacity" value="{{ $event->capacity }}" autocomplete="duration">

                            @error('duration')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="min_price" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventPriceOrMinPrice') }}</label>

                        <div class="col-md-6">
                            <input id="min_price" type="number" class="form-control @error('min_price') is-invalid @enderror" name="min_price" min="0" max="5000000" value="{{ $event->min_price }}" autocomplete="min_price">

                            @error('min_price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="max_price" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventMaxPrice') }}</label>

                        <div class="col-md-6">
                            <input id="max_price" type="number" class="form-control @error('max_price') is-invalid @enderror" name="max_price" min="0" max="5000000" value="{{ $event->max_price }}" autocomplete="max_price">

                            @error('max_price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="location" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventLocation') }}</label>

                        <div class="col-md-6">
                            <input id="location" type="text" class="form-control @error('location') is-invalid @enderror" name="location" value="{{ $event->location }}" autocomplete="location">

                            @error('location')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="show_location_map" class="form-check-label col-md-4 col-form-label text-md-end">{{ __('messages.eventShowLocationMap') }}</label>

                        <div class="col-md-6">
                            <input id="show_location_map" type="checkbox" class="form-check-input @error('show_location_map') is-invalid @enderror" name="show_location_map" autocomplete="show_location_map" @if($event->show_location_map) checked @endif/>

                            @error('show_location_map')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="url" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventUrl') }}</label>

                        <div class="col-md-6">
                            <input id="url" type="text" class="form-control @error('location') is-invalid @enderror" name="url" value="{{ $event->url }}" autocomplete="url">

                            @error('url')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="url" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventCategories') }}</label>

                        <div class="col-md-6">
                            @foreach($categories as $category)
                            <input class="form-check-input" type="checkbox" name="category[]" value="{{$category->id}}" @if(in_array($category->id, $event->categories->pluck('id')->toArray())) checked @endif> {{$category->name}}<br>
                            @endforeach
                            @error('category')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @if($event->media_filename)
                    <div class="row mb-3">
                        <label for="url" class="col-md-4 col-form-label text-md-end">{{ __('messages.deleteMedia') }}</label>

                        <div class="col-md-6">
                            <input id="delete_media" type="checkbox" class="form-check-input @error('delete_media') is-invalid @enderror" name="delete_media" autocomplete="delete_media"/>
                            @error('deleteImage')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <label for="file" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventImage') }}</label>

                        <div class="col-md-6">
                            <input id="file" type="file" class="form-control @error('file') is-invalid @enderror" name="file" autocomplete="file">

                            @error('file')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            @if($event->media_filename)
                            <div class="img-fluid mt-3 mb-3">
                                <img style="max-height:240px" src="{{url('storage/images/'.$event->media_filename)}}">
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="url" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventMediaAlt') }}</label>

                        <div class="col-md-6">
                            <textarea name="media_alt" class="form-control @error('media_alt') is-invalid @enderror">{{ $event->media_alt }}</textarea>

                            @error('media_alt')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <a href="{{ route('edit-event.cancel') }}" class="btn btn-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                            <button type="submit" aria-label="{{__('messages.next')}}" class="btn btn-primary">
                                {{ __('messages.next') }}
                            </button>
                        </div>
                    </div>
                </form>
                <div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection