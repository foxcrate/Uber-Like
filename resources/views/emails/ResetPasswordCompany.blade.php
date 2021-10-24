@component('mail::message')
# Introduction

Eil-Baz User Account Confirmation.
@component('mail::button', ['url' => url(route('company.reset',$id))])
    Account Confirmation
@endcomponent

<p>Your reset code is : {{$code}}</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
