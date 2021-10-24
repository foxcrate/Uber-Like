@component('mail::message')
# Introduction

Eil-Baz Accepted Data.

<p>Your reset code is : {{$code}}</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
