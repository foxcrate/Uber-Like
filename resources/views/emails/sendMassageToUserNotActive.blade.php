<!-- @if($user->lang == 'EN')
@component('mail::message')
# Introduction

<div>Successful subscription</div>
<p>An amount was withdrawn :{{$price}}</p>
<p>Rest in your wallet :{{$user->last_name}}</p>
<p> The subscription is valid until the beginning of the day :{{$user->last_name}}</p>


Thanks,<br>
{{ config('app.name') }}
@endcomponent
@else   {{--   if($provider->profile->language == 'AR')--}} -->
@component('mail::message')
# Introduction
<div style="text-align: right; direction: rtl">
    <div>لم يتم الاشتراك لعدم وجود رصيد كافي </div>
    <p>المبلغ المطلوب : <span> {{$price}} L.E </span>  </p>
    <p> رصيد محفظتك : <span>{{$user->wallet_balance}}  L.E</span> </p>

</div>

شكرا , <br>
{{ config('app.name') }}
@endcomponent
<!-- @endif -->
