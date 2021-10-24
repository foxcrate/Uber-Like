@extends('user.layout.auth')

@section('content')


<?php $login_user = asset('asset/img/login-user-bg.jpg'); ?>
<div class="full-page-bg" style="background-image: url('http://192.168.1.200/ailbaz_server/public/{{Setting::get('user_backgruond_photo')}}');">

    <div class="log-overlay"></div>
    <div class="full-page-bg-inner">
        <div class="row no-margin">
            <div class="col-md-6 log-left">
                <span style="background: none" class="login-logo"><img style="height: 120px;border-radius: 50%;border: white solid 2px" src="{{ url('/').Setting::get('site_logo', asset('logo-black.png'))}}"></span>
                @if(app()->getLocale()=="en")
                    <h2>{!!Setting::get('big_title_en',asset(''))!!}</h2>
                    <p>{!!Setting::get('small_title_en',asset(''))!!}</p>
                @else
                    <h2>{!!Setting::get('big_title_ar',asset(''))!!}</h2>
                    <p>{!!Setting::get('small_title_ar',asset(''))!!}</p>
                @endif

            </div>
            <div class="col-md-6 log-right">
                <div class="login-box-outer">
                    <div class="login-box row no-margin">
                        {{-- {{ $code }} --}}
                        @include('flash::message')
                        <div class="col-md-12">
                            <a class="log-blk-btn" href="{{url('provider.login')}}">@lang('user.already_have_an_account')</a>

                        </div>

                        <div class="row">
                            <!-- -->
                            <h3 style="margin-top: 80px;">@lang('user.otp')</h3>

                           <div id="first_step" >
                               <div class="col-md-12">
                               <div id="recaptcha-container"></div>

                                   <input type="number" class="form-control" name="phoneOTP" id="phoneOTP" >

                               </div>

                               <div class="col-md-6">
                                   <button id="1111" class="log-teal-btn" type="submit"> @lang('user.codeConfirmation')</button>
                               </div>

                               <div class="col-md-6">
                                   <button id="2222" class="log-teal-btn" type="submit"  >@lang('user.sendAnotherCode')</button>
                                   <p>@lang('user.antherCodeAfterMin')</p>
                               </div>

                           </div>

                       </div>

                       <form id="theForm" action="{{ url('/register2_provider') }}" method="post">

                            @csrf
                            <div class="form-group">
                                {{-- <input type="hidden" id="input1" name="{{$user->_token}}" value="3487"> --}}
                                <input type="hidden" id="input2" name="first_name" value="{{$user->first_name}}">
                                <input type="hidden" id="input3" name="last_name" value="{{$user->last_name}}">
                                <input type="hidden" id="input4" name="email" value="{{$user->email}}">
                                <input type="hidden" id="input5" name="country_code" value="{{$user->country_code}}">
                                <input type="hidden" id="input6" name="phone_number" value="{{$user->phone_number}}">
                                <input type="hidden" id="input7" name="identity_number" value="{{$user->identity_number}}">
                                <input type="hidden" id="input8" name="car_license_type" value="{{$user->car_license_type}}">
                                <input type="hidden" id="input9" name="password" value="{{$user->password}}">
                                <input type="hidden" id="input10" name="password_confirmation" value="{{$user->password_confirmation}}">
                                <input type="hidden" id="input11" name="governorate_id" value="{{$user->governorate_id}}">
                            </div>

                        </form>

                        <div class="col-md-12">
                            <p class="helper">@lang('user.or') <a href="{{route('provider.login')}}">@lang('user.sing_in')</a>@lang('user.with_your_user_accont')</p>
                            <p class="helper"> <a href="{{ url('/') }}">@lang('user.back')</a></p>
                        </div>

                    </div>
                    <div class="log-copy"><p class="no-margin">{{ Setting::get('site_copyright', '&copy; '.date('Y').' Appoets') }}</p></div>
                </div>
            </div>
        </div>
    </div>


{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/firebase/8.0.1/firebase.js"></script> --}}
<script src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.js'></script>
<script src="/js/lang.js"></script>
<script  type="text/javascript">

    /////////////////FireBase///////////////////////
    var btn = document.getElementById("2222");
    var content=document.getElementById("2222").textContent;
    //document.getElementById("2222").textContent={{"@lang('user.numbersInCarNumber')"}};
    document.getElementById("2222").disabled = true;
    //alert("alo");
    //alert(i18n.user.en.WrongCode);

    var count = 60;     // Set count
    var timer = null;  // For referencing the timer

    (function countDown(){
    // Display counter and start counting down
    btn.textContent = count;

    // Run the function again every second if the count is not zero
    if(count !== 0){
        timer = setTimeout(countDown, 1000);
        count--; // decrease the timer
    } else {
        // Enable the button
        btn.textContent = content;
        document.getElementById("2222").disabled = false;
    }
    }());
    //$app()->getLocale()




    // $(document).ready(function () {

    //     $('#1111').on('click', function() {

    //         document.getElementById("theForm").submit();
    //     });
    // });




    $(document).ready(function () {
        //var x= {!! json_encode(trans('user.doneConfirmation')) !!};

        onSignInSubmit();
    });

    $('#2222').on('click', function() {

        location.reload();


    });

    function onSignInSubmit() {
        $('#1111').on('click', function() {
            let phoneNo = '';
            if( $('#phoneOTP').val().length === 0 ){
                alert("أدخل كود التأكيد");
            }else{
                var code1 = $('#phoneOTP').val();
                //console.log(code1);
                $(this).attr('disabled', 'disabled');
                $(this).text('Processing..');
                if(code1 == {{ $code }}) {
                    //alert('Succecss');
                    $(this).text({!! json_encode(trans('user.doneConfirmation')) !!});

                    //var user = result.user;
                    //console.log(user);

                    document.getElementById("theForm").submit();

                    // ...
                }else{

                    // User couldn't sign in (bad verification code?)
                    // ...
                    $(this).removeAttr('disabled');
                    $(this).text({!! json_encode(trans('user.WrongCode')) !!});
                    setTimeout(() => {
                        $(this).text({!! json_encode(trans('user.codeConfirmation')) !!});
                    }, 2000);
                }
            }

        });
    }




    /*$(document).ready(function () {
        //var x= {!! json_encode(trans('user.doneConfirmation')) !!};

        const firebaseConfig = {
            apiKey: "AIzaSyBzprmMUES5HU8siRDUxLR_O2OELpjzJ3k",
            authDomain: "albaz-89c1a.firebaseapp.com",
            databaseURL: "https://albaz-89c1a.firebaseio.com",
            projectId: "albaz-89c1a",
            storageBucket: "albaz-89c1a.appspot.com",
            messagingSenderId: "245428456155",
            appId: "1:245428456155:web:d9e0fbfcb5cc17642c5afe",
            measurementId: "G-Z2WV0VYMSH"
        };


        //// Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        ////console.log("Recaptcha");

        window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
            'size': 'invisible',
            'callback': function (response) {
                // reCAPTCHA solved, allow signInWithPhoneNumber.
                console.log('recaptcha resolved');
            }
        });

          var phoneNo = "+"+"{{ 2 }}"+"{{$user->phone_number}}";
        //var phoneNo="+"+"201550307033";
        console.log(phoneNo);
        // getCode(phoneNo);
        var appVerifier = window.recaptchaVerifier;
        firebase.auth().signInWithPhoneNumber(phoneNo, appVerifier)
        .then(function (confirmationResult) {

            window.confirmationResult=confirmationResult;
            coderesult=confirmationResult;
            console.log(coderesult);
        }).catch(function (error) {
            console.log(error.message);

        });

        onSignInSubmit();
    });

    $('#2222').on('click', function() {

         var phoneNo = "+"+"{{ 2 }}"+"{{$user->phone_number}}";
        //var phoneNo="+"+"201550307033";
        console.log(phoneNo);
        // getCode(phoneNo);
        var appVerifier = window.recaptchaVerifier;
        firebase.auth().signInWithPhoneNumber(phoneNo, appVerifier)
        .then(function (confirmationResult) {

            window.confirmationResult=confirmationResult;
            coderesult=confirmationResult;
            console.log(coderesult);
        }).catch(function (error) {
            console.log(error.message);

        });
        onSignInSubmit2();


    });

    function onSignInSubmit() {
        $('#1111').on('click', function() {
            let phoneNo = '';
            if( $('#phoneOTP').val().length === 0 ){
                alert("أدخل كود التأكيد");
            }else{
                var code = $('#phoneOTP').val();
                console.log(code);
                $(this).attr('disabled', 'disabled');
                $(this).text('Processing..');
                confirmationResult.confirm(code).then(function (result) {
                    //alert('Succecss');
                    $(this).text({!! json_encode(trans('user.doneConfirmation')) !!});

                    var user = result.user;
                    console.log(user);

                    document.getElementById("theForm").submit();

                    // ...
                }.bind($(this))).catch(function (error) {

                    // User couldn't sign in (bad verification code?)
                    // ...
                    $(this).removeAttr('disabled');
                    $(this).text({!! json_encode(trans('user.WrongCode')) !!});
                    setTimeout(() => {
                        $(this).text({!! json_encode(trans('user.codeConfirmation')) !!});
                    }, 2000);
                }.bind($(this)));
            }

        });
    }

    function onSignInSubmit2() {
        $('#1111').on('click', function() {
            let phoneNo = '';
            var code = $('#phoneOTP').val();
            console.log(code);
            $(this).attr('disabled', 'disabled');
            $(this).text('Processing..');
            confirmationResult.confirm(code).then(function (result) {
                //alert('Succecss');
                $(this).text({!! json_encode(trans('user.codeConfirmation')) !!});
                var user = result.user;
                console.log(user);

                document.getElementById("theForm").submit();

                // ...
            }.bind($(this))).catch(function (error) {

                // User couldn't sign in (bad verification code?)
                // ...
                $(this).removeAttr('disabled');
                $(this).text({!! json_encode(trans('user.WrongCode')) !!});
                setTimeout(() => {
                    $(this).text({!! json_encode(trans('user.codeConfirmation')) !!});
                }, 2000);
            }.bind($(this)));

        });
    }*/

    ////////////////////////////////////////////////

</script>


</div>
@endsection

