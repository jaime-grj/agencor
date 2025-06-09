<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'agencor') }}@yield('title')</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        (function() {
            // Immediately set the theme based on localStorage value
            var darkMode = localStorage.getItem('dark') == 1;
            document.documentElement.setAttribute('data-bs-theme', darkMode ? 'dark' : 'light');
        })();
    </script>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'agencor') }}
                </a>
                <button aria-label="{{ __('messages.toggleNavigation') }}" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <form class="d-flex w-50" method="GET" action="{{ route('search') }}">
                    <div class="input-group w-100">
                        <input class="form-control custom-border-color" name="title" type="search" value="{{ request('title') }}" placeholder="{{ __('messages.search') }}" aria-label="{{ __('messages.search') }}">
                        <button class="btn btn-outline-secondary" type="submit" aria-label="{{ __('messages.search') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                            </svg>
                        </button>
                    </div>
                </form>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Dark theme selector -->
                        <li class="nav-item d-flex align-items-center">
                            <button id="themeToggle" aria-label="{{ __('messages.toggleTheme') }}" class="btn btn-highcontrast d-flex align-items-center gap-2">
                                <svg id="themeIcon" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path id="themeIconPath" />
                                </svg>
                                <span id="themeText"></span>
                            </button>
                        </li>
                        <!-- Authentication Links -->
                        @guest
                        <!--@if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif-->
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('messages.logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-start">
                    @if (session()->has('msg'))
                    <div class="alert alert-success" role="alert">
                        {{session()->get('msg')}}
                    </div>
                    @endif
                    @if (session()->has('error'))
                    <div class="alert alert-danger" role="alert">
                        {{session()->get('error')}}
                    </div>
                    @endif
                </div>
                @if(Auth::user() && Auth::user()->type == 'Admin')
                <div class="d-flex justify-content-start">
                    <a href="/newEvent" aria-label="{{__('messages.newEvent')}}" class="mb-4 btn btn btn-success me-1">{{__('messages.newEvent')}}</a>
                    <a href="/newCategory" aria-label="{{__('messages.newCategory')}}" class="mb-4 btn btn btn-success me-1">{{__('messages.newCategory')}}</a>
                    <a href="/categories" aria-label="{{__('messages.manageCategories')}}" class="mb-4 btn btn btn-success me-1">{{__('messages.manageCategories')}}</a>
                </div>
                @endif
            </div>
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    @yield('categories')
                    <div class="d-flex flex-wrap gap-1">
                        <button aria-label="{{__('messages.zoomIn')}}" class="btn btn-highcontrast me-1" onclick="zoomIn()">
                            <i class="bi bi-zoom-in"></i> {{__('messages.zoomIn')}}
                        </button>
                        <button aria-label="{{__('messages.zoomOut')}}" class="btn btn-highcontrast me-1" onclick="zoomOut()">
                            <i class="bi bi-zoom-out"></i> {{__('messages.zoomOut')}}
                        </button>
                        <button aria-label="{{__('messages.zoomReset')}}" class="btn btn-highcontrast" onclick="resetZoom()">
                            <i class="bi bi-arrow-counterclockwise"></i> {{__('messages.zoomReset')}}
                        </button>
                    </div>
                </div>
                @yield('content')
            </div>
        </main>
    </div>
    <footer class="footer mt-auto py-3">
        <div class="container">
            <span class="text-muted">{{__('messages.copyright')}} {{date('Y')}}</span>
        </div>
    </footer>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggleBtn = document.getElementById("themeToggle");
        const iconPath = document.getElementById("themeIconPath");
        const textEl = document.getElementById("themeText");

        const sunIcon = `M12 3v1m0 16v1m9-9h-1M4 12H3
                          m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707
                          m12.728 0l-.707.707M6.343 17.657l-.707.707
                          M16 12a4 4 0 11-8 0 4 4 0 018 0z`;

        const moonIcon = `M20.354 15.354A9 9 0 018.646 3.646
                          9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z`;

        function setTheme(isDark) {
            document.documentElement.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');
            localStorage.dark = isDark ? 1 : 0;

            iconPath.setAttribute("d", isDark ? sunIcon : moonIcon);
            textEl.textContent = isDark
                ? "{{ __('messages.changeToLightTheme') }}"
                : "{{ __('messages.changeToDarkTheme') }}";
        }

        // Init on load
        const isDark = localStorage.dark == 1;
        setTheme(isDark);

        toggleBtn.addEventListener("click", () => {
            setTheme(localStorage.dark != 1);
        });
    });
</script>

<script>
    // Get elements
    const scrollableWrapper = document.getElementById('scrollableWrapper');
    const scrollLeftBtn = document.getElementById('scrollLeft');
    const scrollRightBtn = document.getElementById('scrollRight');

    // Scroll amount
    const scrollAmount = 500; // Adjust as needed for how far to scroll

    // Scroll left
    scrollLeftBtn.addEventListener('click', () => {
        scrollableWrapper.scrollBy({
            left: -scrollAmount,
            behavior: 'smooth'
        });
    });

    // Scroll right
    scrollRightBtn.addEventListener('click', () => {
        scrollableWrapper.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
    });
</script>
<script>
    let currentZoom = parseFloat(localStorage.getItem('zoomLevel')) || 1;
    document.body.style.zoom = currentZoom;

    function zoomIn() {
        currentZoom = Math.min(currentZoom + 0.1, 2); // Max 200%
        applyZoom();
    }

    function zoomOut() {
        currentZoom = Math.max(currentZoom - 0.1, 0.5); // Min 50%
        applyZoom();
    }

    function applyZoom() {
        document.body.style.zoom = currentZoom;
        localStorage.setItem('zoomLevel', currentZoom.toFixed(2));
    }

    function resetZoom() {
        currentZoom = 1;
        applyZoom();
    }
</script>

<!--<script>
$(function() {
    $('#date-range').hide();
    $('#date-exact').hide();
    showForm($('#is_date_range').val());
    $('#is_date_range').change(function(){
        showForm($(this).val());
    });

});

function showForm(dateType){
    if(dateType == '0'){
        $('#date-exact').show();
        $('#date-range').hide();
    }
    else if (dateType == '1'){
        $('#date-range').show();
        $('#date-exact').hide();
    }
}
</script>-->

</html>