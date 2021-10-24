@extends('admin.layout.base')

@section('title', 'Site Settings ')

@section('content')


<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
			<h5>@lang('admin.Site_Settings')</h5>

            <form class="form-horizontal" action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}

				<div class="form-group row">
					<label for="site_title" class="col-xs-2 col-form-label">@lang('admin.Site_Name_ar')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('site_title', 'الباز')  }}" name="site_title" required id="site_title" placeholder="@lang('admin.Site_Name')">
					</div>
				</div>

				<div class="form-group row">
					<label for="site_title_en" class="col-xs-2 col-form-label">@lang('admin.Site_Name_en')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('site_title_en', 'AilBaz')  }}" name="site_title_en" required id="site_title_en" placeholder="@lang('admin.Site_Name')">
					</div>
				</div>

				<div class="form-group row">
					<label for="site_logo" class="col-xs-2 col-form-label">@lang('admin.Site_Logo')</label>
					<div class="col-xs-10">
						@if(Setting::get('site_logo')!='')
	                    <img style="height: 90px; margin-bottom: 15px;" src="{{ url('/').Setting::get('site_logo', asset('logo-black.png')) }}">
	                    @endif
						<input type="file" accept="image/*" name="site_logo" class=" form-control-file" id="site_logo" aria-describedby="fileHelp">
						<!-- class="dropify form-control-file" -->
					</div>
				</div>


				<div class="form-group row">
					<label for="site_icon" class="col-xs-2 col-form-label">@lang('admin.Site_Icon')</label>
					<div class="col-xs-10">
						@if(Setting::get('site_icon')!='')
	                    <img style="height: 90px; margin-bottom: 15px;" src="{{ url('/').Setting::get('site_icon') }}">
	                    @endif
						<input type="file" accept="image/*" name="site_icon" class=" form-control-file" id="site_icon" aria-describedby="fileHelp">
					</div>
				</div>

				<div class="form-group row">
					<label for="site_splash" class="col-xs-2 col-form-label">@lang('admin.Site_Splash')</label>
					<div class="col-xs-10">
						@if(Setting::get('site_splash')!='')
	                    <img style="height: 90px; margin-bottom: 15px;" src="{{ url('/').Setting::get('site_splash') }}">
	                    @endif
						<input type="file" accept="image/*" name="site_splash" class=" form-control-file" id="site_splash" aria-describedby="fileHelp">
					</div>
				</div>





				<div class="form-group row">
					<label for="app_status" class="col-xs-2 col-form-label">@lang('admin.Application_Status')</label>
					<div class="col-xs-10">
						<select class="form-control" id="app_status" name="app_status">
							<option value="1" @if(Setting::get('app_status', 0) == 1) selected @endif>@lang('admin.Enable')</option>
							<option value="0" @if(Setting::get('app_status', 0) == 0) selected @endif>@lang('admin.Disable')</option>
						</select>
					</div>
				</div>

				<div class="form-group row">
					<label for="app_msg" class="col-xs-2 col-form-label">@lang('admin.Application_Message')</label>
					<div class="col-xs-10">
						<textarea class="form-control" name="app_msg" id="app_msg">{{ Setting::get('app_msg') }}</textarea>
					</div>
				</div>

				<script src="https://cdn.ckeditor.com/4.11.2/standard/ckeditor.js"></script>

				<div class="form-group row">
					<label for="app_msg" class="col-xs-2 col-form-label">@lang('admin.Address')</label>
					<div class="col-xs-10">
						<textarea class="form-control ckeditor" name="address" id="address">{{ Setting::get('address') }}</textarea>
					</div>
				</div>

				<script>
					CKEDITOR.replace( 'address' ); // id
				</script>



{{--				<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>--}}
{{--				<script src="https://cdn.ckeditor.com/4.11.2/standard/ckeditor.js"></script>--}}
{{--				<script src="https://cdn.ckeditor.com/4.11.2/standard/ckeditor.js"></script>--}}
{{--				<script>--}}
{{--					CKEDITOR.replace( 'article-ckeditor' );--}}
{{--				</script>--}}


{{--				<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>--}}
{{--				<script src="/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>--}}
{{--				<script>--}}
{{--					$('textarea').ckeditor();--}}
{{--					// $('.textarea').ckeditor(); // if class is prefered.--}}
{{--				</script>--}}

                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">@lang('admin.Copyright_Content')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ Setting::get('site_copyright', '&copy; '.date('Y').' Appoets') }}" name="site_copyright" id="site_copyright" placeholder="@lang('admin.Site_Copyright')Site Copyright">
                    </div>
                </div>

				<div class="form-group row">
					<label for="store_link_android" class="col-xs-2 col-form-label">@lang('admin.Playstore_link')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('store_link_android', '')  }}" name="store_link_android"  id="store_link_android" placeholder="@lang('admin.Site_Settings')">
					</div>
				</div>

				<div class="form-group row">
					<label for="store_link_android_provider" class="col-xs-2 col-form-label">@lang('admin.store_link_android_provider')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('store_link_android_provider', '')  }}" name="store_link_android_provider"  id="store_link_android_provider" placeholder="@lang('admin.store_link_android_provider')">
					</div>
				</div>

				<div class="form-group row">
					<label for="store_link_ios" class="col-xs-2 col-form-label">@lang('admin.Appstore_Link')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('store_link_ios', '')  }}" name="store_link_ios"  id="store_link_ios" placeholder="@lang('admin.Site_Settings')Appstore link">
					</div>
				</div>

				<div class="form-group row">
					<label for="provider_select_timeout" class="col-xs-2 col-form-label">@lang('admin.Provider_Accept_Timeout')</label>
					<div class="col-xs-10">
						<input class="form-control" type="floa" value="{{ Setting::get('provider_select_timeout', '60')  }}" name="provider_select_timeout" required id="provider_select_timeout" placeholder="@lang('admin.Provider_Accept_Timeout')">
					</div>
				</div>

				<div class="form-group row">
					<label for="provider_search_radius" class="col-xs-2 col-form-label">@lang('admin.provider_search_radius')</label>
					<div class="col-xs-10">
						<input class="form-control" type="floa" value="{{ Setting::get('provider_search_radius', '100')  }}" name="provider_search_radius" required id="provider_search_radius" placeholder="@lang('admin.Provider_Search_Radius')">
					</div>
				</div>
				<div class="form-group row">
					<label for="user_search_radius" class="col-xs-2 col-form-label">@lang('admin.user_search_radius')</label>
					<div class="col-xs-10">
						<input class="form-control" type="floa" value="{{ Setting::get('user_search_radius', '100')  }}" name="user_search_radius" required id="user_search_radius" placeholder="@lang('admin.User_search_radius')">
					</div>
				</div>

				<div class="form-group row">
					<label for="sos_number" class="col-xs-2 col-form-label">@lang('admin.SOS_Number')</label>
					<div class="col-xs-10">
						<input class="form-control" type="floa" value="{{ Setting::get('sos_number', '911')  }}" name="sos_number" required id="sos_number" placeholder="@lang('admin.SOS_Number')">
					</div>
				</div>

				<div class="form-group row">
					<label for="contact_number" class="col-xs-2 col-form-label">@lang('admin.Contact_Number')</label>
					<div class="col-xs-10">
						<input class="form-control" type="floa" value="{{ Setting::get('contact_number', '911')  }}" name="contact_number" required id="contact_number" placeholder="@lang('admin.Contact_Number')">
					</div>
				</div>

				<div class="form-group row">
					<label for="contact_email" class="col-xs-2 col-form-label">@lang('admin.Contact_Email')</label>
					<div class="col-xs-10">
						<input class="form-control" type="email" value="{{ Setting::get('contact_email', '')  }}" name="contact_email" required id="contact_email" placeholder="@lang('admin.Contact_Email')">
					</div>
				</div>

                <div class="form-group row">
					<label for="email_password" class="col-xs-2 col-form-label">@lang('admin.email_password')</label>
					<div class="col-xs-10">
						<input class="form-control" type="password" name="email_password" required id="email_password" value={{ $admin->email_password }}>
					</div>
				</div>

				<div class="form-group row">
					<label for="social_login" class="col-xs-2 col-form-label">@lang('admin.Social_Login')</label>
					<div class="col-xs-10">
						<select class="form-control" id="social_login" name="social_login">
							<option value="1" @if(Setting::get('social_login', 0) == 1) selected @endif>@lang('admin.Enable')</option>
							<option value="0" @if(Setting::get('social_login', 0) == 0) selected @endif>@lang('admin.Disable')</option>
						</select>
					</div>
				</div>

				<div class="form-group row">
					<label for="interval_time" class="col-xs-2 col-form-label">@lang('admin.App_Interval_Time')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('interval_time', '')  }}" name="interval_time" required id="interval_time" placeholder="@lang('admin.App_Interval_Time')">
					</div>
				</div>
				<div class="form-group row">
					<label for="search_title" class="col-xs-2 col-form-label">@lang('admin.site_serche_en')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('search_title', '')  }}" name="search_title" required id="search_title" placeholder="العنوان" search_title>
					</div>
				</div>

				<div class="form-group row">
					<label for="search_title_en" class="col-xs-2 col-form-label">@lang('admin.site_serche_en')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('search_title_en', '')  }}" name="search_title_en" required id="search_title_en" placeholder="Title" search_title_en>
					</div>
				</div>


				<!--    Social Links -->
				<div class="form-group row">
					<label for="facebook_link" class="col-xs-2 col-form-label">@lang('admin.site_Facebook')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text"
							   value="{{ Setting::get('facebook_link', '')  }}"
							   name="facebook_link" id="facebook_link"
							   placeholder="https://www.facebook.com/yourpage">
					</div>
				</div>



				<div class="form-group row">
					<label for="twitter_link" class="col-xs-2 col-form-label">@lang('admin.site_Twitter')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text"
							   value="{{ Setting::get('twitter_link', '')  }}"
							   name="twitter_link"
							   id="twitter_link"
							   placeholder="https://www.twitter.com/yourpage">
					</div>
				</div>



				<div class="form-group row">
					<label for="youtube_link" class="col-xs-2 col-form-label">@lang('admin.site_youtube')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text"
							   value="{{ Setting::get('youtube_link', '')  }}"
							   name="youtube_link"
							   id="youtube_link"
							   placeholder="https://www.youtube.com/channel">
					</div>
				</div>
				<div class="form-group row">
					<label for="site_phone" class="col-xs-2 col-form-label">@lang('admin.site_phone')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text"
							   value="{{ Setting::get('site_phone', '')  }}"
							   name="site_phone"
							   id="site_phone"
							   placeholder="+201000000000">
					</div>
				</div>
				<div class="form-group row">
					<label for="whatsap_link" class="col-xs-2 col-form-label">@lang('admin.site_whatsapp')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text"
							   value="{{ Setting::get('whatsap_link', '')  }}"
							   name="whatsap_link"
							   id="whatsap_link"
							   placeholder="+201000000000">
					</div>
				</div>
				<div class="form-group row">
					<label for="site_create" class="col-xs-2 col-form-label">@lang('admin.site_create')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text"
							   value="{{ Setting::get('site_create', '')  }}"
							   name="site_create"
							   id="site_create"
							   placeholder="{{ trans('admin.site_create') }}">
					</div>
				</div>

				<div class="form-group row">
					<label for="site_create_en" class="col-xs-2 col-form-label">@lang('admin.site_create_en')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text"
							   value="{{ Setting::get('site_create_en', '')  }}"
							   name="site_create_en"
							   id="site_create_en"
							   placeholder="{{ trans('admin.site_create_en') }}">
					</div>
				</div>

                <div class="form-group row">
					<label for="site_create_en" class="col-xs-2 col-form-label">@lang('admin.user_andriod_version')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text"
							   value="{{ Setting::get('user_andriod_version', '')  }}"
							   name="user_andriod_version"
							   id="user_andriod_version"
							   placeholder="{{ Setting::get('user_andriod_version', '')  }}">
					</div>
				</div>

                <div class="form-group row">
					<label for="site_create_en" class="col-xs-2 col-form-label">@lang('admin.user_andriod_version_importance')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text"
							   value="{{ Setting::get('user_andriod_version_importance', '')  }}"
							   name="user_andriod_version_importance"
							   id="user_andriod_version_importance"
							   placeholder="{{ Setting::get('user_andriod_version_importance', '')  }}">
					</div>
				</div>

                <div class="form-group row">
					<label for="site_create_en" class="col-xs-2 col-form-label">@lang('admin.provider_andriod_version')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text"
							   value="{{ Setting::get('provider_andriod_version', '')  }}"
							   name="provider_andriod_version"
							   id="provider_andriod_version"
							   placeholder="{{ Setting::get('provider_andriod_version', '')  }}">
					</div>
				</div>

                <div class="form-group row">
					<label for="site_create_en" class="col-xs-2 col-form-label">@lang('admin.provider_andriod_version_importance')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text"
							   value="{{ Setting::get('provider_andriod_version_importance', '')  }}"
							   name="provider_andriod_version_importance"
							   id="provider_andriod_version_importance"
							   placeholder="{{ Setting::get('provider_andriod_version_importance', '')  }}">
					</div>
				</div>

                <div class="form-group row">
					<label for="site_create_en" class="col-xs-2 col-form-label">@lang('admin.gift_percentage')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text"
							   value="{{ Setting::get('gift_percentage', 'x')  }}"
							   name="gift_percentage"
							   id="gift_percentage"
							   placeholder="{{ Setting::get('gift_percentage', '')  }}">
					</div>
				</div>

				

				<div class="form-group row">
					<label for="gift_image" class="col-xs-2 col-form-label">@lang('admin.gift_image')</label>
					<div class="col-xs-10">
						@if(Setting::get('gift_image')!='')
	                    <img style="height: 200px; margin-bottom: 15px;" src="{{ asset( Setting::get('gift_image','uploads/logo_black') ) }}">
	                    @endif
						<input type="file" accept="image/*" name="gift_image" class=" form-control-file" id="site_logo" aria-describedby="fileHelp">
						<!-- class="dropify form-control-file" -->
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">@lang('admin.up_sit_set')</button>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>
@endsection
