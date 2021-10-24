@extends('admin.layout.base')

@section('title', 'Site Settings ')

@section('content')

    <script src="https://cdn.ckeditor.com/4.11.2/standard/ckeditor.js"></script>

    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5>@lang('admin.Site_Settings')</h5>

                <form class="form-horizontal" action="{{ route('admin.dash_settings.store') }}" method="POST"
                      enctype="multipart/form-data" role="form">
                    {{csrf_field()}}


                    <div class="form-group row">
                        <label for="First_site_photo"
                               class="col-xs-2 col-form-label">@lang('admin.First_site_photo')</label>
                        <div class="col-xs-10">
                            @if(Setting::get('First_site_photo')!='')
                                <img style="height: 90px; margin-bottom: 15px;"
                                     src="{{ url('/').Setting::get('First_site_photo') }}">
                            @endif
                            <input type="file" accept="image/*" name="First_site_photo"
                                   class="dropify form-control-file" id="First_site_photo" aria-describedby="fileHelp">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="text_first" class="col-xs-2 col-form-label">@lang('admin.note_First_site_photo_ar')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="text_first"
                                      id="text_first">{{ Setting::get('text_first') }}</textarea>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="text_first" class="col-xs-2 col-form-label">@lang('admin.note_First_site_photo_en')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="text_first_en"
                                      id="text_first">{{ Setting::get('text_first_en') }}</textarea>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="Second_site_photo"
                               class="col-xs-2 col-form-label">@lang('admin.Second_site_photo')</label>
                        <div class="col-xs-10">
                            @if(Setting::get('Second_site_photo')!='')
                                <img style="height: 90px; margin-bottom: 15px;"
                                     src="{{ url('/').Setting::get('Second_site_photo') }}">
                            @endif
                            <input type="file" accept="image/*" name="Second_site_photo"
                                   class="dropify form-control-file" id="Second_site_photo" aria-describedby="fileHelp">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="about_title" class="col-xs-2 col-form-label">@lang('admin.titel_about_ar')</label>
                        <div class="col-xs-10">
                            <input class="form-control " type="text"
                                   value="{{ Setting::get('about_title',  '  لماذا أخترتنا ')  }}" name="about_title"
                                   required id="about_title" placeholder="عنوان من نحن">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="about_title_en" class="col-xs-2 col-form-label">@lang('admin.titel_about_en')</label>
                        <div class="col-xs-10">
                            <input class="form-control " type="text"
                                   value="{{ Setting::get('about_title_en', 'Why choose us')  }}" name="about_title_en"
                                   required id="about_title_en" placeholder="@lang('admin.about_title')">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="about_small_title" class="col-xs-2 col-form-label">@lang('admin.note_about_ar')</label>
                        <div class="col-xs-10">
                            <input class="form-control " type="text"
                                   value="{{ Setting::get('about_small_title', 'أفضل الخدمات في المدينة')  }}"
                                   name="about_small_title" required id="about_small_title" placeholder="تفاصيل من نحن">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="about_small_title_en" class="col-xs-2 col-form-label">@lang('admin.note_about_en')</label>
                        <div class="col-xs-10">
                            <input class="form-control " type="text"
                                   value="{{ Setting::get('about_small_title_en', 'Best services in the city')  }}"
                                   name="about_small_title_en" required id="about_small_title_en"
                                   placeholder="@lang('admin.about_small_title')">
                        </div>
                    </div>


                    {{--				//first--}}
                    <div class="form-group row">
                        <label for="First_about_photo"
                               class="col-xs-2 col-form-label">@lang('admin.First_about_photo')</label>
                        <div class="col-xs-10">
                            @if(Setting::get('First_site_photo')!='')
                                <img style="height: 90px; margin-bottom: 15px;"
                                     src="{{ url('/').Setting::get('First_about_photo') }}">
                            @endif
                            <input type="file" accept="image/*" name="First_about_photo"
                                   class="dropify form-control-file" id="First_about_photo" aria-describedby="fileHelp">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="first_name" class="col-xs-2 col-form-label">@lang('admin.First_name_photo_ar')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="first_name"
                                      id="first_name">{{ Setting::get('first_name') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="first_name_en" class="col-xs-2 col-form-label">@lang('admin.First_name_photo_en')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="first_name_en"
                                      id="first_name_en">{{ Setting::get('first_name_en') }}</textarea>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="first_details" class="col-xs-2 col-form-label">@lang('admin.First_note_photo_ar')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="first_details"
                                      id="first_details">{{ Setting::get('first_details') }}</textarea>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="first_details_en" class="col-xs-2 col-form-label">@lang('admin.First_note_photo_en')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="first_details_en"
                                      id="first_details_en">{{ Setting::get('first_details_en') }}</textarea>
                        </div>
                    </div>

                    {{--				//second--}}
                    <div class="form-group row">
                        <label for="Second_about_photo" class="col-xs-2 col-form-label">@lang('admin.second_photo')</label>
                        <div class="col-xs-10">
                            @if(Setting::get('Second_about_photo')!='')
                                <img style="height: 90px; margin-bottom: 15px;"
                                     src="{{ url('/').Setting::get('Second_about_photo') }}">
                            @endif
                            <input type="file" accept="image/*" name="Second_about_photo"
                                   class="dropify form-control-file" id="Second_about_photo"
                                   aria-describedby="fileHelp">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="second_name" class="col-xs-2 col-form-label">@lang('admin.second_note_photo_ar')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="second_name"
                                      id="second_name">{{ Setting::get('second_name') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="second_name_en" class="col-xs-2 col-form-label">@lang('admin.second_name_photo_en')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="second_name_en"
                                      id="second_name_en">{{ Setting::get('second_name_en') }}</textarea>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="second_details" class="col-xs-2 col-form-label">@lang('admin.second_note_photo_ar')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="second_details"
                                      id="second_details">{{ Setting::get('second_details') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="second_details_en" class="col-xs-2 col-form-label">@lang('admin.second_note_photo_en')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="second_details_en"
                                      id="second_details_en">{{ Setting::get('second_details_en') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="Third_about_photo" class="col-xs-2 col-form-label">@lang('admin.third_photo')</label>
                        <div class="col-xs-10">
                            @if(Setting::get('Third_about_photo')!='')
                                <img style="height: 90px; margin-bottom: 15px;"
                                     src="{{ url('/').Setting::get('Third_about_photo') }}">
                            @endif
                            <input type="file" accept="image/*" name="Third_about_photo"
                                   class="dropify form-control-file" id="Third_about_photo" aria-describedby="fileHelp">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="third_name" class="col-xs-2 col-form-label">@lang('admin.third_name_photo_ar')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="third_name"
                                      id="third_name">{{ Setting::get('third_name') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="third_name_en" class="col-xs-2 col-form-label">@lang('admin.third_name_photo_en')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="third_name_en"
                                      id="third_name_en">{{ Setting::get('third_name_en') }}</textarea>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="third_details" class="col-xs-2 col-form-label">@lang('admin.third_note_photo_ar')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="third_details"
                                      id="third_details">{{ Setting::get('third_details') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="third_details_en" class="col-xs-2 col-form-label">@lang('admin.third_note_photo_en')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="third_details_en"
                                      id="third_details_en">{{ Setting::get('third_details_en') }}</textarea>
                        </div>
                    </div>
                    {{--


                    {{--last footer photo--}}

                    <div class="form-group row">
                        <label for="footer_photo" class="col-xs-2 col-form-label">@lang('admin.footer_photo')</label>
                        <div class="col-xs-10">
                            @if(Setting::get('footer_photo')!='')
                                <img style="height: 90px; margin-bottom: 15px;"
                                     src="{{ url('/').Setting::get('footer_photo') }}">
                            @endif
                            <input type="file" accept="image/*" name="footer_photo" class="dropify form-control-file"
                                   id="footer_photo" aria-describedby="fileHelp">
                        </div>
                    </div>


                    {{--login user backgruond photo and title--}}

                    <div class="form-group row">
                        <label for="user_backgruond_photo" class="col-xs-2 col-form-label">@lang('admin.user_login_photo')</label>
                        <div class="col-xs-10">
                            @if(Setting::get('user_backgruond_photo')!='')
                                <img style="height: 90px; margin-bottom: 15px;"
                                     src="{{ url('/').Setting::get('user_backgruond_photo') }}">
                            @endif
                            <input type="file" accept="image/*" name="user_backgruond_photo"
                                   class="dropify form-control-file" id="user_backgruond_photo"
                                   aria-describedby="fileHelp">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="big_title_ar" class="col-xs-2 col-form-label">@lang('admin.user_login_big_titel_ar')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="big_title_ar"
                                      id="big_title_ar">{{ Setting::get('big_title_ar') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="big_title_en" class="col-xs-2 col-form-label">@lang('admin.user_login_big_titel_en')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="big_title_en"
                                      id="big_title_en">{{ Setting::get('big_title_en') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="small_title_ar" class="col-xs-2 col-form-label">@lang('admin.user_login_titel_ar')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="small_title_ar"
                                      id="small_title_ar">{{ Setting::get('small_title_ar') }}</textarea>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="small_title_en" class="col-xs-2 col-form-label">@lang('admin.user_login_titel_en')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="small_title_en"
                                      id="small_title_en">{{ Setting::get('small_title_en') }}</textarea>
                        </div>
                    </div>
                    {{--End login user backgruond photo and title--}}



                    {{--login provider backgruond photo and title--}}

                    <div class="form-group row">
                        <label for="provider_backgruond_photo" class="col-xs-2 col-form-label">@lang('admin.dr_login_photo')</label>
                        <div class="col-xs-10">
                            @if(Setting::get('provider_backgruond_photo')!='')
                                <img style="height: 90px; margin-bottom: 15px;"
                                     src="{{ url('/').Setting::get('provider_backgruond_photo') }}">
                            @endif
                            <input type="file" accept="image/*" name="provider_backgruond_photo"
                                   class="dropify form-control-file" id="provider_backgruond_photo"
                                   aria-describedby="fileHelp">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="provider_big_title_ar" class="col-xs-2 col-form-label">@lang('admin.dr_login_big_titel_ar')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="provider_big_title_ar"
                                      id="provider_big_title_ar">{{ Setting::get('provider_big_title_ar') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="provider_big_title_en" class="col-xs-2 col-form-label">@lang('admin.dr_login_big_titel_en')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="provider_big_title_en"
                                      id="provider_big_title_en">{{ Setting::get('provider_big_title_en') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="provider_small_title_ar" class="col-xs-2 col-form-label">@lang('admin.dr_login_titel_ar')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="provider_small_title_ar"
                                      id="provider_small_title_ar">{{ Setting::get('provider_small_title_ar') }}</textarea>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="provider_small_title_en" class="col-xs-2 col-form-label">@lang('admin.dr_login_titel_en')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="provider_small_title_en"
                                      id="provider_small_title_en">{{ Setting::get('provider_small_title_en') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="provider_ditals_ar" class="col-xs-2 col-form-label">@lang('admin.dr_login_note_ar')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="provider_ditals_ar"
                                      id="provider_ditals_ar">{{ Setting::get('provider_ditals_ar') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="provider_ditals_en" class="col-xs-2 col-form-label">@lang('admin.dr_login_note_en')</label>
                        <div class="col-xs-10">
                            <textarea class="form-control ckeditor" name="provider_ditals_en"
                                      id="provider_ditals_en">{{ Setting::get('provider_ditals_en') }}</textarea>
                        </div>
                    </div>
                    {{--End login provider backgruond photo and title--}}


                    <div class="form-group row">
                        <label for="provider_backgruond_photo" class="col-xs-2 col-form-label">@lang('admin.company_login_photo')</label>
                        <div class="col-xs-10">
                            @if(Setting::get('company_backgruond_photo')!='')
                                <img style="height: 90px; margin-bottom: 15px;"
                                     src="{{ url('/').Setting::get('company_backgruond_photo') }}">
                            @endif
                            <input type="file" accept="image/*" name="company_backgruond_photo"
                                   class="dropify form-control-file" id="company_backgruond_photo"
                                   aria-describedby="fileHelp">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="provider_backgruond_photo" class="col-xs-2 col-form-label">@lang('admin.admin_login_photo')</label>
                        <div class="col-xs-10">
                            @if(Setting::get('admin_backgruond_photo')!='')
                                <img style="height: 90px; margin-bottom: 15px;"
                                     src="{{ url('/').Setting::get('admin_backgruond_photo') }}">
                            @endif
                            <input type="file" accept="image/*" name="admin_backgruond_photo"
                                   class="dropify form-control-file" id="admin_backgruond_photo"
                                   aria-describedby="fileHelp">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="zipcode" class="col-xs-2 col-form-label"></label>
                        <div class="col-xs-10">
                            <button type="submit" class="btn btn-primary">@lang('admin.up_sit_set')</button>
                        </div>
                    </div>

                </form>
{{--                <div class="form-group row" style="    margin-top: 10%;">--}}
{{--                    <label for="box2_link" class="col-xs-2 col-form-label">اضافة بوست للموقع</label>--}}
{{--                    <div class="col-xs-10">--}}
{{--                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal">--}}
{{--                            إضافة بوست جديد--}}
{{--                        </button>--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--                @if(App\Box::all()->count() != 0)--}}
{{--                    <div class="form-group row" style="    margin-top: 10%;">--}}
{{--                        <label for="box2_link" class="col-xs-2 col-form-label">حذف بوست للموقع</label>--}}
{{--                        <div class="col-xs-10">--}}
{{--                            <form id="box_delete_form" action="{{route('admin.box_delete.delete')}}" method="POST">--}}
{{--                                {{csrf_field()}}--}}
{{--                                <select id="box_id" name="box_id">--}}
{{--                                    @foreach(App\Box::all() as $item)--}}
{{--                                        <option value="{{$item->id}}">{{$item->title}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                                <button type="submit" class="btn btn-danger">--}}
{{--                                    حذف بوست من الموقع--}}
{{--                                </button>--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endif--}}
            </div>
        </div>
    </div>

    <!-- Button trigger modal -->


{{--    <!-- Modal -->--}}
{{--    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"--}}
{{--         aria-hidden="true">--}}
{{--        <div class="modal-dialog" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <form class="form-horizontal" action="{{ route('admin.box_settings.store') }}" method="POST"--}}
{{--                      enctype="multipart/form-data" role="form">--}}

{{--                    <div class="modal-header">--}}
{{--                        <h5 class="modal-title" id="exampleModalLabel">إضافة بوست جديد</h5>--}}
{{--                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                            <span aria-hidden="true">&times;</span>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                    <div class="modal-body">--}}
{{--                        {{csrf_field()}}--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="box_photo" class="col-xs-2 col-form-label">صورة البوست</label>--}}
{{--                            <div class="col-xs-10">--}}
{{--                                <input type="file" accept="image/*" name="box_photo" class="dropify form-control-file"--}}
{{--                                       id="box_photo" aria-describedby="fileHelp">--}}
{{--                            </div>--}}
{{--                        </div>--}}


{{--                        <div class="form-group row">--}}
{{--                            <label for="box_title" class="col-xs-2 col-form-label">عنوان البوست بالعربية</label>--}}
{{--                            <div class="col-xs-10">--}}
{{--                                <textarea class="form-control " name="box_title" id="box_title"></textarea>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group row">--}}
{{--                            <label for="box_title" class="col-xs-2 col-form-label">عنوان البوست بالإنجليزية</label>--}}
{{--                            <div class="col-xs-10">--}}
{{--                                <textarea class="form-control " name="box_title_en" id="box_title_en"></textarea>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group row">--}}
{{--                            <label for="box_details" class="col-xs-2 col-form-label">تفاصيل البوست بالعربية</label>--}}
{{--                            <div class="col-xs-10">--}}
{{--                                <textarea class="form-control " name="box_details" id="box_details"></textarea>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group row">--}}
{{--                            <label for="box_details" class="col-xs-2 col-form-label">تفاصيل البوست بالإنجليزية</label>--}}
{{--                            <div class="col-xs-10">--}}
{{--                                <textarea class="form-control " name="box_details_en" id="box_details_en"></textarea>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group row">--}}
{{--                            <label for="box_link" class="col-xs-2 col-form-label">رابط البوست</label>--}}
{{--                            <div class="col-xs-10">--}}
{{--                                <textarea class="form-control" name="box_link" id="box_link"></textarea>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                    <div class="modal-footer">--}}
{{--                        <button type="button" class="btn btn-secondary"--}}
{{--                                data-dismiss="modal">@lang('admin.Close')</button>--}}
{{--                        <button type="submit" class="btn btn-primary">حفظ</button>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

@endsection
