@component('mail::message')
# Introduction

Eil-Baz : Your mail ( {{$id}} ) has been created successfully. Contact the company to find out the account details.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
