@component('mail::message')
# Introduction

Eil-Baz Reset Password User.
@component('mail::button', ['url' => url('new-password-user/'.$id.'/'.$code)])
     Reset Password User
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
