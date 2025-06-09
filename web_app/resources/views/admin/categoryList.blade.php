@extends('layouts.agencor')

@section('content')
<div class="container">
  <div class="row justify-content-start">
    <h2>{{ __('messages.categories') }}</h2>
    <ul class="list-group">

    </ul>
    <div class="container">
      <ul class="list-group">
        @foreach($categories as $category)
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <div class="flex-grow-1 me-3">
            <a href="/categoryDetail/{{$category->id}}">
              {{ $category->name }}
              @if ($category->show_on_homepage == 0)
                {{ __('messages.categoryHiddenInHomepage') }}
              @endif
            </a>
          </div>
          <div class="flex-shrink-0">
            <a href="/editCategory/{{$category->id}}" aria-label="{{__('messages.editCategory')}}" type="button" class="btn btn-primary me-1">{{ __('messages.edit') }}</a>
            <a href="/deleteCategory/{{$category->id}}" aria-label="{{__('messages.deleteCategory')}}" type="button" class="btn btn-danger">{{ __('messages.delete') }}</a>
          </div>
        </li>
        @endforeach
      </ul>
    </div>
  </div>
  @endsection
