@if($provider->profile->language == 'EN')
@component('mail::message')
# Introduction

<div>Successful subscription</div>
<p>An amount was withdrawn :{{$price}}</p>
<p>Rest in your wallet :{{$provider->last_name}}</p>
<p> The subscription is valid until the beginning of the day :{{$provider->last_name}}</p>


Thanks,<br>
{{ config('app.name') }}
@endcomponent
@else   {{--   if($provider->profile->language == 'AR')--}}
@component('mail::message')
# Introduction
<div>تم الاشتراك بنجاح </div>
<p>تم سحب مبلغ :{{$price}}</p>
<p>باقي في محفظتك :{{$provider->wallet_balance}}</p>
<p>الاشتراك ساري  </p>
<p>  من يوم:{{date("Y-m-d")}}</p>
<p> حتي نهاية يوم:{{$date}}</p>

شكرا , <br>
{{ config('app.name') }}
@endcomponent
<!-- @endif -->
