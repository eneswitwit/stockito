@php
    $config = [
        'appName' => config('app.name'),
        'locale' => $locale = app()->getLocale(),
        'locales' => config('app.locales'),
        'githubAuth' => config('services.github.client_id'),
    ];

    $polyfills = [
        'Promise',
        'Object.assign',
        'Object.values',
        'Array.prototype.find',
        'Array.prototype.findIndex',
        'Array.prototype.includes',
        'String.prototype.includes',
        'String.prototype.startsWith',
        'String.prototype.endsWith',
    ];
@endphp

        <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
<header id="home">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand float-left"  href="#">
            <img src="{{ asset('images/landingpage/logo.png') }}" alt="image" height="40px">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#site-nav"
                aria-controls="site-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse float-right" id="site-nav">
            <ul class="navbar-nav text-sm-left ml-auto">
                <li class="nav-item text-center">
                    <a href="/login" class="btn align-middle btn-outline-primary my-2 my-lg-0">Login</a>
                </li>
                <li class="nav-item text-center">
                    <a href="/register" class="btn align-middle btn-primary my-2 my-lg-0">Sign Up</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container fluid" style="margin:0px;">
        @foreach($images as $image)
            <img src="{{ route('image', ['name' => $image->file_name]) }}" alt="">
        @endforeach
    </div>
    {{-- Global configuration object --}}
    <script>window.config = @json($config);</script>

    <script src="https://js.stripe.com/v3/"></script>

    {{-- Polyfill JS features via polyfill.io --}}
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features={{ implode(',', $polyfills) }}"></script>

    {{-- Load the application scripts --}}
    @if (app()->isLocal())
        <script src="{{ mix('js/app.js') }}"></script>
    @else
        <script src="{{ asset('js/manifest.js') }}"></script>
        <script src="{{ asset('js/vendor.js') }}"></script>
        <script src="{{ asset('js/app.js') }}"></script>
@endif

</body>
</html>