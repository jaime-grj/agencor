@extends('layouts.agencor')

@section('content')
<div class="container">
    <div class="row justify-content-start">
        <div class="col-md-10">
            <h1>{{__('messages.newEvent')}}</h1>
            <h6>{{__('messages.mandatoryFields')}}</h6>
            <div id="eventForm">
                <form method="POST" action="{{ route('new-event.submit') }}" accept-charset="utf-8" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <label for="title" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventTitle') }} (*)</label>

                        <div class="col-md-6">
                            <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $event_session['title'] ?? '') }}" required autocomplete="title" autofocus>

                            @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="short_description" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventShortDescription') }}</label>

                        <div class="col-md-6">
                            <input id="short_description" type="text" class="form-control @error('short_description') is-invalid @enderror" name="short_description" value="{{ old('short_description', $event_session['short_description'] ?? '') }}" autocomplete="short_description">

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
                            <textarea id="long_description" class="form-control @error('long_description') is-invalid @enderror" name="long_description" required autocomplete="long_description">{{ old('long_description', $event_session['long_description'] ?? '') }}</textarea>
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
                                <input id="datetime_start" type="datetime-local" class="form-control @error('datetime_start') is-invalid @enderror" name="datetime_start" value="{{ old('datetime_start', $event_session['datetime_start'] ?? '') }}" autocomplete="datetime_start">

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
                                <input id="datetime_end" type="datetime-local" class="form-control @error('datetime_end') is-invalid @enderror" name="datetime_end" value="{{ old('datetime_end', $event_session['datetime_end'] ?? '') }}" autocomplete="datetime_end">

                                @error('datetime_end')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div id="date-range-featured">
                        <div class="row mb-3">
                            <label for="datetime_start_featured" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventStartDateFeatured') }}</label>

                            <div class="col-md-6">
                                <input id="datetime_start_featured" type="datetime-local" class="form-control @error('datetime_start_featured') is-invalid @enderror" name="datetime_start_featured" value="{{ old('datetime_start_featured', $event_session['datetime_start_featured'] ?? '') }}" autocomplete="datetime_start_featured">

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
                                <input id="datetime_end_featured" type="datetime-local" class="form-control @error('datetime_end_featured') is-invalid @enderror" name="datetime_end_featured" value="{{ old('datetime_end_featured', $event_session['datetime_end_featured'] ?? '') }}" autocomplete="datetime_end_featured">

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
                        <input id="is_time_set" type="checkbox"
                            class="form-check-input @error('is_time_set') is-invalid @enderror"
                            name="is_time_set"
                            value="1"
                            {{ old('is_time_set', $event_session['is_time_set'] ?? false) ? 'checked' : '' }}
                            autocomplete="is_time_set" />
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
                            <input id="capacity" type="number" class="form-control @error('capacity') is-invalid @enderror" name="capacity" min="0" max="5000000" value="{{ old('capacity', $event_session['capacity'] ?? '') }}" autocomplete="capacity">

                            @error('capacity')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="min_price" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventPriceOrMinPrice') }}</label>

                        <div class="col-md-6">
                            <input id="min_price" type="number" class="form-control @error('min_price') is-invalid @enderror" name="min_price" min="0" max="5000000" value="{{ old('min_price', $event_session['min_price'] ?? '') }}" autocomplete="min_price">

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
                            <input id="max_price" type="number" class="form-control @error('max_price') is-invalid @enderror" name="max_price" min="0" max="5000000" value="{{ old('max_price', $event_session['max_price'] ?? '') }}" autocomplete="max_price">

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
                            <input id="location" type="text" class="form-control @error('location') is-invalid @enderror" name="location" value="{{ old('location', $event_session['location'] ?? '') }}" autocomplete="location">

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
                        <input id="show_location_map" type="checkbox"
                            class="form-check-input @error('show_location_map') is-invalid @enderror"
                            name="show_location_map"
                            value="1"
                            {{ old('show_location_map', $event_session['show_location_map'] ?? false) ? 'checked' : '' }}
                            autocomplete="show_location_map" />
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
                            <input id="url" type="text" class="form-control @error('location') is-invalid @enderror" name="url" value="{{ old('url', $event_session['url'] ?? '') }}" autocomplete="url">

                            @error('url')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="categories" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventCategories') }}</label>

                        <div class="col-md-6">
                            @php
                                $selectedCategories = old('category', $event_session['category'] ?? []);
                            @endphp

                            @foreach($categories as $category)
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="category[]"
                                    value="{{ $category->id }}"
                                    {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                                {{ $category->name }}<br>
                            @endforeach
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="file" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventImage') }}</label>

                        <div class="col-md-6">
                            <input id="file" type="file" class="form-control @error('file') is-invalid @enderror" name="file" value="{{ old('file') }}" autocomplete="file">

                            @error('file')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="media_alt" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventMediaAlt') }}</label>

                        <div class="col-md-6">
                            <textarea name="media_alt" class="form-control @error('media_alt') is-invalid @enderror">{{ old('media_alt', $event_session['media_alt'] ?? '') }}</textarea>

                            @error('media_alt')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <a href="{{ route('new-event.cancel') }}" class="btn btn-secondary">
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
        @endsection