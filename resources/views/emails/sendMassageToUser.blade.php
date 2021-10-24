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
    <div>تم الاشتراك بنجاح </div>
    <p>تم سحب مبلغ : <span> {{$price}} L.E </span>  </p>
    <p> باقي في محفظتك : <span>{{$user->wallet_balance}}  L.E</span> </p>
    <p>الاشتراك ساري  </p>
    <p>  من يوم:{{date("Y-m-d")}}</p>
    <p> حتي نهاية يوم:{{$date}}</p>

</div>


شكرا , <br>
{{ config('app.name') }}
@endcomponent
<!-- @endif -->
