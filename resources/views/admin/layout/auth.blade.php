<!DOCTYPE html>
<html lang="ar">

<head>
    <!-- Meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Title -->
    <title>{{ Setting::get('site_title', 'Imcanat') }}</title>
{{--    <link rel="shortcut icon" type="image/png" href="{{url('/').Setting::get('site_icon') }}"/>--}}
    <link style="float: right" rel="shortcut icon" type="image/png" href="{{ url('/').Setting::get('site_icon') }}"/>

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{asset('main/vendor/bootstrap4/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('main/vendor/themify-icons/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('main/vendor/font-awesome/css/font-awesome.min.css')}}">

    <link rel="stylesheet" href="{{asset('main/assets/css/core.css')}}">

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<style>
.sign-form .box {
    height: 100%;
    border-radius: 15px;
    box-shadow: 2px 2px 6px 5px #7d4a1cc2;
    background-color: rgb(255 255 255 / 31%);
}
.btn-purple {
    border-radius: 15px;
   /* margin: auto 20%;*/
    width: 100%;
    color: #eee;
    background-color: #a567e2;
    border-color: #a567e2;
    height: 45px;
    font-weight: 600;
    font-size: 15px;
}
.box h5 {
    color: #880000bf;
    font-weight: 900;
    font-size: 25px;
    margin: 10px;
    background-color: #00000012;
}
.sign-form .row {
    height: 400px;
}
.form-group {
    margin: 2rem 4rem !important;
    /* margin-top: 2rem; */
    font-weight: 800;

}
.sign-form .form-material .form-control {
    border: solid 1px #007eff;
    background-color: #ffffff00;
    padding: 1.1rem 1.5rem;
    border-radius: 10px;
    color: #eee;
    font-size: 15px;
    font-weight: 600;

}
.form-control::placeholder {
  color: #eeeeeebd;
}
.text-black .underline {
    font-weight: 500;
    font-size: 15px;
    border-bottom: 1px solid #333 !important;
}
.sign-form .form-material .form-control {
    padding: 5px;
    padding: 1.1rem 1.5rem;
    border-radius: 10px;
}
</style>

    <?php $background = asset('main/assets/img/photos-1/2.jpg'); ?>

    <body class="img-cover" style="background-image:  url('{{asset(Setting::get('admin_backgruond_photo'))}}');">
    
    <div class="container-fluid">

    @yield('content')

    </div>

        <!-- Vendor JS -->
        <script type="text/javascript" src="{{asset('main/vendor/jquery/jquery-1.12.3.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('main/vendor/tether/js/tether.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('main/vendor/bootstrap4/js/bootstrap.min.js')}}"></script>
    </body>
</html>
