@component('mail::message')
# Introduction

Eil-Baz Reset Password Provider.
@component('mail::button', ['url' => url('provider/new-password-provider/'.$id.'/'.$code)])
    Reset Password
@endcomponent
{{--<p>Your Confirmation Email code is :<span class="font-weight-bold"> {{$code}}</span></p>--}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
