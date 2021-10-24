<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title'){{ Setting::get('site_title', 'Imcanat') }}</title>
    {{--    <link rel="shortcut icon" type="image/png" href="{{ url('/').Setting::get('site_icon') }}"/>--}}
    <link style="float: right" rel="shortcut icon" type="image/png" href="{{ url('/').Setting::get('site_icon') }}"/>


    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="{{ asset('asset/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/style.css') }}" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>

</head>
<body>

<div class="full-page-bg" style="background-image: url('{{asset(Setting::get('provider_backgruond_photo'))}}');">
    <div class="log-overlay"></div>
    <div class="full-page-bg-inner">
        <div class="row no-margin">
            <div class="col-md-6 log-left">
                <span class="login-logo" style="background: none"><img
                            src="{{url('/').Setting::get('site_logo', asset('logo-black.png'))}}"
                            style="height: 120px;border-radius: 50%;border: white solid 2px"></span>
                @if(app()->getLocale()=="en")
                    <h3>{!!Setting::get('provider_big_title_en',asset(''))!!}</h3>
                    <h3>{!!Setting::get('provider_small_title_en',asset(''))!!}</h3>
                    <p>{!!Setting::get('provider_ditals_en',asset(''))!!}</p>
                @else
                    <h3>{!!Setting::get('provider_big_title_ar',asset(''))!!}</h3>
                    <h3>{!!Setting::get('provider_small_title_ar',asset(''))!!}</h3>
                    <p>{!!Setting::get('provider_ditals_ar',asset(''))!!}</p>
                @endif

            </div>
            <div class="col-md-6 log-right">
                <div class="login-box-outer">
                    <div class="login-box row no-margin">
                        @yield('content')
                    </div>
                    <div class="log-copy"><p
                                class="no-margin">{{ Setting::get('site_copyright', '&copy; '.date('Y').' Appoets') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('asset/js/jquery.min.js') }}"></script>
<script src="{{ asset('asset/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('asset/js/scripts.js') }}"></script>

@yield('scripts')

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.js-example-basic-single').select2();
    });
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>
</body>
</html>
