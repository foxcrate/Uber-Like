@component('mail::message')
# Introduction

Eil-Baz : Please Image {{$message}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
