@extends('layouts.agencor')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="fs-1">{{__('messages.deleteCategory')}}</div>
            <div class="fs-5 fw-bold">{{__('messages.deleteCategoryWarning')}}</div>
            <div class="fs-6 fw-bold">
                {{__('messages.deleteCategoryWarning2')}}
                <ul>
                    <li>{{__('messages.deleteCategoryWarning3')}}</li>
                    <li>{{__('messages.deleteCategoryWarning4')}}</li>
                </ul>
            </div>

            <div class="pt-3 text-start text-break">
                <div class="fw-bold">{{ $category -> name }}</div>
                <div>{{ $category -> description }}</div>
            </div>
            <form action="{{route('delete-category.submit', $category->id)}}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$category->id}}" />
                <input type="submit" class="mt-4 btn btn-danger" aria-label="{{__('messages.deleteCategory')}}" value="{{__('messages.delete')}}" />
            </form>
        </div>
    </div>
</div>
@endsection