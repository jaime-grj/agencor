@extends('layouts.agencor')

@section('content')
<div class="container">
    <div class="row justify-content-start">
        <div class="col-md-10">
            <h1>{{__('messages.editCategory')}}</h1>
            <div id="eventForm">
                <form method="POST" action="{{ route('edit-category.submit', $category->id) }}" accept-charset="utf-8" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Nombre') }}</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{$category->name}}" required autocomplete="name" autofocus>

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
                            <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{$category->description}}" autocomplete="description" autofocus>

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
                            <input id="show_on_homepage" type="checkbox" class="form-check-input @error('show_on_homepage') is-invalid @enderror" value="1" name="show_on_homepage" autocomplete="show_on_homepage" @if ($category->show_on_homepage == 1) checked @endif />

                            @error('show_on_homepage')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
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