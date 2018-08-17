@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot
    {{-- Body --}}
    <p>Hello! You have been added to brand's creative team by brand {{ $brand_name }} with role - {{$role}}! Please, register your account by link below!</p>
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