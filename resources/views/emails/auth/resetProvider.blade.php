@component('mail::message')
# Introduction

Eil-Baz Confirmation Email.

<p>Your Confirmation Email code is :<span class="font-weight-bold"> {{$code}}</span></p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
