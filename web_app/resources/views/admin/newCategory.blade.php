@extends('layouts.agencor')

@section('content')
<div class="container">
    <div class="row justify-content-start">
        <div class="col-md-10">
            <h1>{{__('messages.newCategory')}}</h1>
            <div id="eventForm">
                <form method="POST" action="{{ route('new-category.submit') }}" accept-charset="utf-8" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('messages.categoryName') }}</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="" required autocomplete="name" autofocus>

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="description" class="col-md-4 col-form-label text-md-end">{{ __('messages.categoryDescription') }}</label>

                        <div class="col-md-6">
                            <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="" autocomplete="description" autofocus>

                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="show_on_homepage" class="form-check-label col-md-4 col-form-label text-md-end">{{ __('messages.categoryShowOnHomepage') }}</label>

                        <div class="col-md-6">
                            <input id="show_on_homepage" type="checkbox"
                                class="form-check-input @error('show_on_homepage') is-invalid @enderror"
                                name="show_on_homepage"
                                value="1"
                                autocomplete="show_on_homepage" />
                            @error('show_on_homepage')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
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