@extends('user.layout.app')

@section('content')
<style>
    .nav>li>a:focus, .nav>li>a:hover, .nav>li.active>a, .nav>li.active>a:focus, .nav>li.active>a:hover {
        text-decoration: none;
        background-color: #39c0ea;
        color: #fff;
    }
    .banner-form .fields {
        margin-bottom: 25px!important;
        padding-bottom: 25px;
        border-bottom: 1px solid #eee;
    }
    .icon_h5{
        background-color: #39c0ea;
        /* width: 60px; */
        text-align: center;
        margin: 0px 10px;
        padding: 7px;
        border-radius: 10px;
        margin-right: 8px!important;
        margin-bottom: 5px;
        font-size: 12px!important;
        font-weight: bold;
        letter-spacing: 1px;
        color: #ffffff!important;
    }
    .icon_text{
        margin-right: 20px;
        color: #fffdfb !important;
    }
    .note-or{
        font-weight: 600;
        font-size: 15px;
    }
    .note-or a{
        font-weight: 600;
        font-size: 15px;
    }
    .menu-btn{
        font-size: 18px;
        font-weight: bold;
        margin-top: 18px;
        padding: 12px 30px !important;
        border-radius: 10px;
        background-color: #39c0ea;
    }
    .menu-btn:hover{
        color: #39c0ea;
        background-color: #eeeeeebd;
        border-radius: 14px;
    }
    .note-or a {
        color: #39c0ea;
    }
    .mar_b{
        margin-bottom: 25px!important;
    }
    .service:hover{
        box-shadow: 1px 2px 8px 6px #0000001a;
    }


    .mar_ban{
        margin-left:20px;
        margin-right:20px
    }
    .mar_ban {

        margin-top: 20px;
    }
    @media (min-width: 1200px){

        .icon_h5{
        background-color: #39c0ea;
        /* width: 60px; */
        text-align: center;
        margin: 0px 10px;
        padding: 7px;
        border-radius: 10px;
        margin-right: 50px!important;
        margin-bottom: 5px;
        font-size: 15px!important;
        font-weight: bold;
        letter-spacing: 1px;
        color: #ffffff!important;
    }
    .banner-form{
        margin-bottom:200px!important;
    }
    .banner {
        background-size: cover;
        padding-top: 80px;
        padding-bottom: 181px!important;
        position: relative;
    }
    }

    @media (min-width: 992px){


        .banner {
        background-size: cover;
        padding-top: 80px;
        padding-bottom: 0px!important;
        position: relative;
        width:100%;
    }
    .banner-form{
        margin-bottom:69px;
    }
    .icon_h5{
        background-color: #39c0ea;
        /* width: 60px; */
        text-align: center;
        margin: 0px 15px!important;
        padding: 7px;
        border-radius: 10px;
        margin-right: 50px!important;
        margin-bottom: 5px;
        font-size: 15px!important;
        font-weight: bold;
        letter-spacing: 1px;
        color: #ffffff!important;
    }

    }

</style>
    <div class="banner row no-margin " style="
            background-image: url('{{url('/').Setting::get('First_site_photo', asset(''))}}');
            height: 100%;
            ">
        <!-- <div class="banner-overlay"></div> -->
        <div class="row">

            {{--            @if(app()->getLocale()=="en")--}}
            {{--            --}}
            {{--            @else--}}
            {{--            @endif--}}
            @if(app()->getLocale()=="en")
                <div class="col-md-8 col-sm-12 text-center mar_b mar_ban"  style="
                                                            float: left;">
                        @if(Setting::get("site_create_en",'<span class="strong">الموقع تحت الانشاء والتعديل</span><br>') != null || Setting::get("site_create_en",'<span class="strong">الموقع تحت الانشاء والتعديل</span><br>') != '' || Setting::get("site_create",'<span class="strong">الموقع تحت الانشاء والتعديل</span><br>') != ' ')
                                        <marquee direction="left" scrollamount="5" onmouseover="this.stop();" onmouseout="this.start();" class="alert alert-info"
                                            style="background-color: rgba(255,255,255,0.23); border-color: rgba(255,255,255,0.23);color: #bb0000;
                                                    font-size: 20px;
                                                    font-weight: bold;
                                                    margin-bottom: 0px;
                                                     margin-top: 5px;">
                                                {!! (Setting::get("site_create_en",'<span class="strong">الموقع تحت الانشاء والتعديل</span><br>')) !!}
                                    </marquee>
                                @endif
                    <h2 class="banner-head">
                        {!! (Setting::get("text_first_en",'<span class="strong">Get there</span><br> Your day belongs to you')) !!}
                    </h2>
                    <a style=" color: aliceblue;" class="menu-btn" href="{{url('/provider/register')}}">Be a
                        Companion</a>
                </div>
            @else
                <div class="col-md-8 col-sm-12 text-center mar_b mar_ban"  style="
    float: left;
    ">
@if(Setting::get("site_create",'<span class="strong">الموقع تحت الانشاء والتعديل</span><br>') != null || Setting::get("site_create",'<span class="strong">الموقع تحت الانشاء والتعديل</span><br>') != '' || Setting::get("site_create",'<span class="strong">الموقع تحت الانشاء والتعديل</span><br>') != ' ')
                                    <marquee direction="right" scrollamount="5" onmouseover="this.stop();" onmouseout="this.start();" class="alert alert-info"
                                        style="background-color: rgba(255,255,255,0.23); border-color: rgba(255,255,255,0.23);color: #bb0000;
                                                font-size: 20px;
                                                font-weight: bold;
                                                margin-bottom: 0px;
                                                margin-top: 5px;">
                                            {!! (Setting::get("site_create",'<span class="strong">الموقع تحت الانشاء والتعديل</span><br>')) !!}
                                    </marquee>
                                @endif
                    <h2 class="banner-head">{!! (Setting::get("text_first",'<span class="strong">الباز</span><br>يرحب بكم')) !!}</h2>
                    <a class="menu-btn" href="{{url('/provider/register')}}">@lang('user.become_a_driv')</a>
                </div>
            @endif

            @if(app()->getLocale()=="en")
                <div class="col-md-3 col-sm-12 mar_ban" style="float: right; background-color: rgba(255,255,255,0);width: 296px;">
                    <div class="banner-form" style="background-color: rgba(255,255,255,0.23); color: #fff;    border-top-width: 0px;">

                        <div class="row no-margin fields">
                            <div class="left">
                                <img src="{{ asset('asset/img/5.png') }}">
                            </div>
                            <div class="right">
                                <a href="{{ url('register') }}">

                                    <h3>Sign up to Ride</h3>
                                    <h5 class="icon_h5" style="
    margin: 0px 5px !important;
    padding: 5px 3px;
">SIGN UP <i class="icon_text fa fa-chevron-right"></i></h5>
                                </a>
                            </div>
                        </div>
                        <div class="row no-margin fields">
                            <div class="left">
                                <img src="{{ asset('asset/img/6.png') }}">
                            </div>
                            <div class="right">
                                <a href="{{url('/provider/register')}}" style="color: #fff">
                                    <h3>Sign up to Drive</h3>
                                    <h5 class="icon_h5" style="
                                            margin: 0px 5px !important;
                                            padding: 5px 3px;"
                                            >SIGN UP <i class="icon_text fa fa-chevron-right"></i></h5>
                                </a>
                            </div>
                        </div>
                        <div class="row no-margin fields">
                            <div class="left">
                            <img class="imgBorder" src="{{ asset('asset/img/8.png') }}">
                            </div>
                            <div class="right">
                            <a href="{{url('/company/login')}}" style="color: #fff">
                            <h3>Sign up to company</h3>
                            <h5 class="icon_h5" style="
                                margin: 0px 5px !important;
                                padding: 5px 3px;">
                                <i class="fa fa-chevron-left icon_text"></i>SIGN UP
                            </h5>
                            </a>
                            </div>
                        </div>
                        <p class="note-or">Or <a href="{{ url('/provider/login') }}">sign in</a> with your rider
                            account.</p>
                    </div>
                </div>
        </div>
        @else
            <div class="col-md-3 mar_ban col-sm-12" style="float: right; background-color: rgba(255,255,255,0);width: 296px;">
                <div class="banner-form" style=" margin-top: 5px;border-top: none;background-color: rgba(255,255,255,0.23); color: #fff;">

                    <div class="row no-margin fields">
                        <div class="left">
                            <img src="{{ asset('asset/img/5.png') }}">
                        </div>
                        <div class="right">
                            <a href="{{ url('register') }}">
                                <h3>تسجيل عميل جديد</h3>
                                <h5 class="icon_h5"><i class="fa fa-chevron-left icon_text"></i>سجل</h5>
                            </a>
                        </div>
                    </div>
                    <div class="row no-margin fields">
                        <div class="left">
                            <img src="{{ asset('asset/img/6.png') }}">
                        </div>
                        <div class="right">
                            <a href="{{url('/provider/register')}}" style="color: #fff">
                                <h3>تسجيل سائق جديد</h3>
                                <h5 class="icon_h5"><i class="fa fa-chevron-left icon_text"></i>سجل</h5>
                            </a>
                        </div>
                    </div>
                    <div class="row no-margin fields">
                        <div class="left">
                            <img class="imgBorder" src="{{ asset('asset/img/8.png') }}">
                        </div>
                        <div class="right">
                            <a href="{{url('/company/login')}}" style="color: #fff">
                                <h3>تسجيل دخول الشركه</h3>
                                <h5 class="icon_h5"><i class="fa fa-chevron-left icon_text"></i>سجل</h5>
                            </a>
                        </div>
                    </div>
                    <p class="note-or"> أو <a href="{{ url('/provider/login') }}" > تسجيل الدخول</a> باستخدام حساب السائق
                        الخاص بك. </p>
                </div>
            </div>
    </div>
    @endif
    </div>
    @if(app()->getLocale()=="en")
    <div class="row white-section no-margin">
            <div class="container-fluid">
                <section class="why text-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12" style="
    background-color: #eeeeee1f;
    margin: 25px 0px;
    box-shadow: 1px 1px 15px 10px #eee;
">
                                <div class="title">
                                    <h2> {{Setting::get('about_title_en','Why choose us')}}  </h2>
                                    <h6>{{Setting::get('about_small_title_en','Best services in the city')}} </h6>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            @if(Setting::get('first_name_en',asset('')) != '' && Setting::get('first_details_en',asset('')) != '')
                                    <div class="col-sm-4">
                                        <div class="service" style="
                                                    background-color: rgba(248,248,249,1);
                                                    padding: 15px;
                                                    border-radius: 15px;
                                                    line-height: 25px;
                                                    margin-bottom: 5px;">

                                            <img src="{{url('/').Setting::get('First_about_photo',asset(''))}}"
                                                style="width: 100px;height: 100px">
                                            {{--                            <i class="fab fa-accessible-icon " style="font-size: 5em; color:#ff9000; margin-bottom: 10px"></i>--}}
                                            <p><strong>
                                                    {!!Setting::get('first_name_en',asset(''))!!}
                                                </strong><br>
                                                {!!Setting::get('first_details_en',asset(''))!!}
                                            </p>
                                        </div>
                                    </div>
                            @endif
                            @if(Setting::get('second_name_en',asset('')) != '' && Setting::get('second_details_en',asset('')) != '')
                                <div class="col-sm-4">
                                    <div class="service" style="background-color: rgba(248,248,249,1);
                                                                            padding: 15px;
                                                                            border-radius: 15px;
                                                                            line-height: 25px;
                                                                            margin-bottom: 5px; ">
                                        <img src="{{url('/').Setting::get('Second_about_photo',asset(''))}}"
                                            style="width: 100px;height: 100px">

                                        {{--                            <i class="fab fa-accessible-icon " style="font-size: 5em; color:#ff9000; margin-bottom: 10px"></i>--}}
                                        <p><strong>
                                                {!!Setting::get('second_name_en',asset(''))!!}
                                            </strong><br>
                                            {!!Setting::get('second_details_en',asset(''))!!}
                                        </p>
                                    </div>
                                </div>
                            @endif
                            @if(Setting::get('third_name_en',asset('')) != '' && Setting::get('third_details_en',asset('')) != '')
                                    <div class="col-sm-4">
                                        <div class="service" style="
                                                    background-color: rgba(248,248,249,1);
                                                    padding: 15px;
                                                    border-radius: 15px;
                                                    line-height: 25px;
                                                    margin-bottom: 5px;
                                                              ">
                                            <img src="{{url('/').Setting::get('Third_about_photo',asset(''))}}"
                                                style="width: 100px;height: 100px">
                                            <p><strong>
                                                    {!!Setting::get('third_name_en',asset(''))!!}
                                                </strong><br>
                                                {!!Setting::get('third_details_en',asset(''))!!}
                                            </p>
                                        </div>
                                    </div>
                            @endif
                        </div>
                    </div>
                </section>
            </div>
        </div>

    @else
    <div class="row white-section no-margin">
            <div class="container-fluid">
                <section class="why text-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12" style="
    background-color: #eeeeee1f;
    margin: 25px 0px;
    box-shadow: 1px 1px 15px 10px #eee;
">
                                <div class="title">
                                    <h2> {{Setting::get('about_title','Why choose us')}}  </h2>
                                    <h6>{{Setting::get('about_small_title','Best services in the city')}} </h6>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                        @if(Setting::get('first_name',asset('')) != '' && Setting::get('first_details',asset('')) != '')
                            <div class="col-sm-4">
                                <div class="service" style="
                        background-color: rgba(248,248,249,1);
                        padding: 15px;
                        border-radius: 15px;
                        line-height: 25px;
                        margin-bottom: 5px;">

                                    <img src="{{url('/').Setting::get('First_about_photo',asset(''))}}"
                                         style="width: 100px;height: 100px">
                                    {{--                            <i class="fab fa-accessible-icon " style="font-size: 5em; color:#ff9000; margin-bottom: 10px"></i>--}}
                                    <p><strong>
                                            {!!Setting::get('first_name',asset(''))!!}
                                        </strong><br>
                                        {!!Setting::get('first_details',asset(''))!!}
                                    </p>
                                </div>
                            </div>
                            @endif
                            @if(Setting::get('second_name',asset('')) != '' && Setting::get('second_details',asset('')) != '')
                            <div class="col-sm-4">
                                <div class="service" style="
                        background-color: rgba(248,248,249,1);

                        padding: 15px;
                        border-radius: 15px;
                        line-height: 25px;
                        margin-bottom: 5px;
">
                                    <img src="{{url('/').Setting::get('Second_about_photo',asset(''))}}"
                                         style="width: 100px;height: 100px">

                                    {{--                            <i class="fab fa-accessible-icon " style="font-size: 5em; color:#ff9000; margin-bottom: 10px"></i>--}}
                                    <p><strong>
                                            {!!Setting::get('second_name',asset(''))!!}
                                        </strong><br>
                                        {!!Setting::get('second_details',asset(''))!!}
                                    </p>
                                </div>
                            </div>
                            @endif
                            @if(Setting::get('third_name',asset('')) != '' && Setting::get('third_details',asset('')) != '')
                            <div class="col-sm-4">
                                <div class="service" style="
                        background-color: rgba(248,248,249,1);
                        padding: 15px;
                        border-radius: 15px;
                        line-height: 25px;
                        margin-bottom: 5px;
">
                                    <img src="{{url('/').Setting::get('Third_about_photo',asset(''))}}"
                                         style="width: 100px;height: 100px">
                                    <p><strong>
                                            {!!Setting::get('third_name',asset(''))!!}
                                        </strong><br>
                                        {!!Setting::get('third_details',asset(''))!!}
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </section>
            </div>
        </div>
    <!--    <div class="row white-section no-margin">
            <div class="container-fluid">
                <section class="why text-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12" style="
    background-color: #eeeeee1f;
    margin: 25px 0px;
    box-shadow: 1px 1px 15px 10px #eee;
">
                                <div class="title">
                                    <h2> {{Setting::get('about_title',' لماذا أخترتنا')}}  </h2>
                                    <h6>{{Setting::get('about_small_title','أفضل الخدمات في المدينة')}} </h6>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @foreach(\App\TransportationType::where('status', 1)->get() as $transportationType)
                                <div class="col-sm-4">
                                    <div class="service" style="
                                        background-color: rgba(248,248,249,1);padding: 15px;
                                        border-radius: 15px;line-height: 25px;margin-bottom: 5px;">

                                        <img src="{{asset($transportationType->image)}}"
                                             style="width: 100px;height: 100px">
                                        <p><strong>
                                                {{$transportationType->name}}
                                            </strong><br>
                                            الحموله {{$transportationType->capacity}}  راكب
                                        </p>
                                        <a href="{{url('services-transportation/'.$transportationType->id)}}" class="btn btn-circle btn-primary">انواع الخدمات</a>
                                    </div>
                                </div>
                        @endforeach
                        </div>
                    </div>
                </section>
            </div>
        </div>
-->
    @endif


    <div class="row">
        <div class="container-fluid">
            <img src="{{ url('/').Setting::get('Second_site_photo',  asset('')) }}" alt="Image"
                 style="width: 100%;height: 700px;" class="img-fluid">
        </div>
    </div>


    <?php
    $i = 3;
    foreach (App\Box::where('status', 1)->get() as $item) {
    if($i % 2){

    ?>
    @if(app()->getLocale()=='en')
        <div class="row white-section no-margin">
            <div class="container">
                <div class="col-md-6 img-block text-center">
                    <img style="width: 500px;height: 400px" src="{{asset($item->photo)}}">
                </div>
                <div class="col-md-6 content-block">
                    <h2>{!!$item->title_en!!}</h2>
                    <div class="title-divider"></div>
                    <p>{!! $item->details_en !!} </p>
                    <a target="_blank" class="content-more" href="{{$item->link}}">@lang('user.m_r_r') <i
                                class="fa fa-chevron-right"></i></a>
                </div>
            </div>
        </div>
    @else
        <div class="row white-section no-margin">
            <div class="container">
                <div class="col-md-6 img-block text-center">
                    <img style="width: 500px;height: 400px" src="{{asset($item->photo)}}">
                </div>
                <div class="col-md-6 content-block">
                    <h2>{!!$item->title!!}</h2>
                    <div class="title-divider"></div>
                    <p>{!! $item->details !!} </p>
                    <a target="_blank" class="content-more" href="{{$item->link}}">@lang('user.m_r_r') <i
                                class="fa fa-chevron-right"></i></a>
                </div>
            </div>
        </div>
    @endif


    <?php }else{?>

    @if(app()->getLocale()=='en')
        <div class="row gray-section no-margin">
            <div class="container">
                <div class="col-md-6 content-block">
                    <h2>{!! $item->title_en!!}</h2>
                    <div class="title-divider"></div>
                    <p>{!! $item->details_en !!} </p>
                    <a target="_blank" class="content-more" href="{{$item->link}}">@lang('user.m_r_r') <i
                                class="fa fa-chevron-right"></i></a>
                </div>
                <div class="col-md-6 img-block text-center">
                    <img style="width: 500px;height: 400px" src="{{asset($item->photo)}}">
                </div>
            </div>
        </div>


    @else
        <div class="row gray-section no-margin">
            <div class="container">
                <div class="col-md-6 content-block">
                    <h2>{!! $item->title!!}</h2>
                    <div class="title-divider"></div>
                    <p>{!! $item->details !!} </p>
                    <a target="_blank" class="content-more" href="{{$item->link}}">@lang('user.m_r_r') <i
                                class="fa fa-chevron-right"></i></a>
                </div>
                <div class="col-md-6 img-block text-center">
                    <img style="width: 500px;height: 400px" src="{{asset($item->photo)}}">
                </div>
            </div>
        </div>
    @endif


    <?php } $i++;}?>


    <div class="footer-city row no-margin"
         style="background-image: url({{url('/').Setting::get('footer_photo', asset('')) }});">
        <!--find-city-->

    </div>
@endsection
