@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot
    {{-- Body --}}
    <p>Hey! You have been added to the creative team of the brand {{ $brand_name }} with the role as {{$role}}. Please sign up to access the platform by clicking on the link below.</p>
    {{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    @endcomponent
@endslot

@component('mail::button', ['url' => $url])
    Sign Up
@endcomponent

@endcomponent