@extends('layouts.agencor')

@section('categories')
    @include('layouts.categories')
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-start">
        <div class="d-flex justify-content-between ml-auto mb-3">
            <div class="fs-4 text-start">{{__('messages.advancedSearch')}}</div>
        </div>
        <div id="eventForm">
            <div>{{__('messages.fillAdvancedSearch')}}</div>
            <form method="GET" action="{{ route('search') }}" accept-charset="utf-8" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <label for="title" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventTitle') }}</label>

                    <div class="col-md-6">
                        <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="" autocomplete="title" autofocus>

                        @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="description" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventDescription') }}</label>

                    <div class="col-md-6">
                        <textarea id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" autocomplete="description"></textarea>

                        @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div id="date-range">
                    <div class="row mb-3">
                        <label for="before" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventBeforeDate') }}</label>

                        <div class="col-md-6">
                            <input id="before" type="date" class="form-control @error('before') is-invalid @enderror" name="before" autocomplete="before">

                            @error('before')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="after" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventAfterDate') }}</label>

                        <div class="col-md-6">
                            <input id="after" type="date" class="form-control @error('after') is-invalid @enderror" name="after" autocomplete="after">

                            @error('after')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="min_price" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventMinPrice') }}</label>
                    <div class="col-md-6">
                        <input id="min_price" type="number" step="0.01" class="form-control @error('min_price') is-invalid @enderror" name="min_price" autocomplete="min_price">
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
                        <input id="max_price" type="number" step="0.01" class="form-control @error('max_price') is-invalid @enderror" name="max_price" autocomplete="max_price">
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
                        <input id="location" type="text" class="form-control @error('location') is-invalid @enderror" name="location" autocomplete="location">

                        @error('location')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="category" class="col-md-4 col-form-label text-md-end">{{ __('messages.eventCategories') }}</label>

                    <div class="col-md-6">
                        @foreach($categories as $category)
                        <input class="form-check-input" type="checkbox" name="category[]" value="{{$category->id}}"> {{$category->name}}<br>
                        @endforeach
                        @error('category')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" aria-label="{{__('messages.search')}}" class="btn btn-primary">
                            {{ __('messages.search') }}
                        </button>
                    </div>
                </div>
            </form>
            <div>
            </div>
        </div>
        @endsection