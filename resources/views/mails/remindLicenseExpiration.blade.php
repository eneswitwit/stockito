@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}

    {{ nl2br('Hey there!') }}
    <br>
    <br>
    {{ nl2br('Your ' . $licenseType . '-license for the media ' . $mediaNumber . ' with the invoice number ' . $invoiceNumber . ' is expiring in ' . $daysLeft . ' days.')}}
    <br>
    <br>
    {{ nl2br('Kind regards') }}
    <br>
    {{ nl2br('Your Stockito Team') }}

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent