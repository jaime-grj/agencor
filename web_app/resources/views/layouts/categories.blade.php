<div class="d-flex flex-wrap gap-1">
    @foreach($categories as $category)
    <a class="btn btn-primary mb-1" href="/category/{{$category->id}}">{{$category->name}}</a>
    @endforeach
    @if(Auth::user() && Auth::user()->type == 'Admin')
    <a class="btn btn-primary mb-1" href="/category/uncategorized">{{ __('messages.uncategorized') }}</a>
    @endif
</div>