@component('mail::message')
# Introduction

Eil-Baz Reset Password.

<p>Your reset code is : {{$code}}</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
