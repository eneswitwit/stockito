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
    {{ nl2br("Your storage is full. You and your creatives won't be able to upload more files to your brand. Resolve the problem by upgrading your plan and make more space for your brand.")}}
    <br>
    <br>
    <a style='font-family: "avenir" , "helvetica" , sans-serif ; box-sizing: border-box ; border-radius: 3px ; box-shadow: 0 2px 3px rgba(0 , 0 , 0 , 0.16) ; color: #fff ; display: inline-block ; text-decoration: none ; border-top: 10px solid #fea500 ; border-right: 18px solid #fea500 ; border-bottom: 10px solid #fea500 ; border-left: 18px solid #fea500 ; background-color: #fea500' href="https://stockito.com/select-plan">
        Upgrade Now
    </a>
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