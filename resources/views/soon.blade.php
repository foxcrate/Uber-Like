@extends('user.layout.base1')
@section('content')
<style>
    body, html {
  padding: 0px;
  margin: 0px;
  width: 100%;
  height: 100%;
  background-image: linear-gradient(120deg, #fccb90 0%, #d57eeb 100%);
  overflow-y: hiiden;
}

@keyframes moveRight {
  100% {
    transform: translateX(-2900px);
  }
}
@keyframes suspension {
  0% {
    transform: translateY(-1px);
  }
  50% {
    transform: translateY(2px);
  }
  100% {
    transform: translateY(-1px);
  }
}
.night {
  position: relative;
  margin: 5rem auto;
  width: 70vw;
  height: 80vh;

  border-radius: 5px;
  box-shadow: 1px 2px 60px rgba(0, 0, 0, 0.4);
  overflow-x: hidden;
}
.night .road {
  height: 140px;
  width: 500%;
  position: absolute;
  bottom: 0%;
  left: 0%;
  background-repeat: repeat-x;
  animation: moveRight 6s linear infinite;
}
.night .car {
  position: absolute;
  bottom: 8%;
  left: 24%;
  animation: suspension 2s linear infinite;
}
.text_soon {
    text-align: center;
    margin-right: 140px;

}
.text_soon .header_soon{
    color: #ed3237;
    font-weight: 900;
    font-size: xxx-large;

}
.text_soon .body_soon{
    line-height: 50px;
    color: #066c93;
    font-weight: 600

}
.text_soon .body_soon a{
    font-weight: 600;
    color: #eee;
    cursor: pointer;
}
.text_soon .body_soon a:hover{
    font-weight: 700;
    color:#066c93;
}
.text_soon .body_soon a img{
    width:100px;
}
</style>
<div class="night"style="background: url({{asset('asset/img/TxCPDcN.png')}});background-size: cover;" >
@if(app()->getLocale()=="en")

        <div class="text_soon ">
            <h2 class="header_soon" >@lang('user.Wait for us soon !!!')<h2>
            <p class="body_soon">You can order now through the application
            <br> <a href="{{Setting::get('store_link_android','#')}}" class="app">
                        <img src="{{asset('asset/img/playstore.png')}}">
            </a> </p>

        </div>
        @else
        <div class="text_soon ">
            <h2 class="header_soon" > @lang('user.Wait for us soon !!!') <h2>
            <p class="body_soon">يمكنك الان طلب الخدمة او الحجز من خلال
            التطبيق<br> <a href="{{Setting::get('store_link_android','#')}}" class="app">
                        <img src="{{asset('asset/img/playstore.png')}}">
            </a> للتحميل اضغط </p>

        </div>
        @endif
    <div class="road" style="background: url({{asset('asset/img/1b.png')}});"></div>
    <div class="car">

    <img src="{{asset('asset/img/1c.png')}}"/></div>
</div>
@endsection
