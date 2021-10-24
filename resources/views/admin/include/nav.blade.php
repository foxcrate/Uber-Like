<div class="site-sidebar">
    <div class="custom-scroll custom-scroll-light">
        <ul class="sidebar-menu">
            <li class="menu-title">@lang('admin.Admin Dashboard')</li>
            <li>
                <a href="{{ route('admin.dashboard') }}" class="waves-effect waves-light">
                    <span class="s-icon"><i class="ti-anchor"></i></span>
                    <span class="s-text">@lang('admin.Dashboard')</span>
                </a>
            </li>

            @can('عرض المرسل')
                <li>
                    <a href="{{ route('admin.dispatcher.index') }}" class="waves-effect waves-light">
                        <span class="s-icon"><i class="ti-target"></i></span>
                        <span class="s-text">@lang('admin.Dispatcher Panel')</span>
                    </a>
                </li>
            @endcan
            @can('عرض الخريطه')
                    <li class="with-sub">
                        <a href="#" class="waves-effect  waves-light">
                            <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                            <span class="s-icon"><i class="ti-map-alt"></i></span>
                            <span class="s-text">@lang('admin.Maps')</span>
                        </a>
                        <ul>
                        <li>
                             <a href="{{ route('admin.map.index') }}" class="waves-effect waves-light">
                                    <span class="s-icon"><i class="ti-map-alt"></i></span>
                                    <span class="s-text">@lang('admin.Map')</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.heatmap') }}" class="waves-effect waves-light">
                                <span class="s-icon"><i class="ti-map"></i></span>
                                <span class="s-text">@lang('admin.HotMap View')</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.heatmap.dr') }}" class="waves-effect waves-light">
                                <span class="s-icon"><i class="ti-map"></i></span>
                                <span class="s-text">@lang('admin.HotMap View2')</span>
                            </a>
                        </li>
                        </ul>
                    </li>
                <li>

                </li>
            @endcan

            @can('عرض الخريطه الحراريه')
                <li>

                </li>
            @endcan
            @if(auth()->user()->can('اضافه موديل السياره') || auth()->user()->can('عرض موديل السياره') || auth()->user()->can('تعديل موديل السياره') || auth()->user()->can('حذف موديل السياره')
                || auth()->user()->can('اضافه انواع النقل') || auth()->user()->can('عرض انواع النقل') || auth()->user()->can('تعديل انواع النقل') || auth()->user()->can('حذف انواع النقل')
                || auth()->user()->can('اضافه أنواع الخدمات') || auth()->user()->can('عرض أنواع الخدمات') || auth()->user()->can('تعديل أنواع الخدمات') || auth()->user()->can('حذف أنواع الخدمات')
                || auth()->user()->can('اضافه السيارات') || auth()->user()->can('عرض السيارات') || auth()->user()->can('تعديل السيارات') || auth()->user()->can('حذف السيارات')
                || auth()->user()->can('اضافه رحلات الباص المجدوله') || auth()->user()->can('عرض رحلات الباص المجدوله') || auth()->user()->can('تعديل رحلات الباص المجدوله') || auth()->user()->can('حذف رحلات الباص المجدوله')
                || auth()->user()->can('اضافه الايام وفترات الرحلات') || auth()->user()->can('عرض الايام وفترات الرحلات') || auth()->user()->can('تعديل الايام وفترات الرحلات') || auth()->user()->can('حذف الايام وفترات الرحلات'))

                <li class="menu-title">@lang('admin.Transportation')</li>
                @if(auth()->user()->can('اضافه الايام وفترات الرحلات') || auth()->user()->can('عرض الايام وفترات الرحلات') || auth()->user()->can('تعديل الايام وفترات الرحلات') || auth()->user()->can('حذف الايام وفترات الرحلات'))

                    <li>
                        <a href="{{ route('admin.day-trip-time.index') }}" class="waves-effect  waves-light">
                            <span class="s-icon"><i class="ti-infinite"></i></span>
                            <span class="s-text">@lang('admin.dayTripTimes')</span>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('اضافه محطات الوقوف') || auth()->user()->can('عرض محطات الوقوف') || auth()->user()->can('تعديل محطات الوقوف') || auth()->user()->can('حذف محطات الوقوف'))

                    <li>
                        <a href="{{ route('admin.station.index') }}" class="waves-effect  waves-light">
                            <span class="s-icon"><i class="ti-infinite"></i></span>
                            <span class="s-text">@lang('admin.station')</span>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('اضافه رحلات الباص المجدوله') || auth()->user()->can('عرض رحلات الباص المجدوله') || auth()->user()->can('تعديل رحلات الباص المجدوله') || auth()->user()->can('حذف رحلات الباص المجدوله'))

                    <li>
                        <a href="{{ route('admin.itinerary.index') }}" class="waves-effect  waves-light">
                            <span class="s-icon"><i class="ti-infinite"></i></span>
                            <span class="s-text">@lang('admin.itineraries')</span>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('اضافه انواع النقل') || auth()->user()->can('عرض انواع النقل') || auth()->user()->can('تعديل انواع النقل') || auth()->user()->can('حذف انواع النقل'))

                    <li>
                        <a href="{{ route('admin.transtype.index') }}" class="waves-effect  waves-light">
                            <span class="s-icon"><i class="ti-infinite"></i></span>
                            <span class="s-text">@lang('admin.Transportation Types')</span>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('اضافه أنواع الخدمات') || auth()->user()->can('عرض أنواع الخدمات') || auth()->user()->can('تعديل أنواع الخدمات') || auth()->user()->can('حذف أنواع الخدمات'))

                    <li>
                        <a href="{{ route('admin.service.index') }}" class="waves-effect  waves-light">
                            <span class="s-icon"><i class="ti-view-grid"></i></span>
                            <span class="s-text">@lang('admin.Service Types')</span>
                        </a>
                    </li>
                @endif
                {{--            @can('عرض السيارات')--}}
                @if(auth()->user()->can('اضافه السيارات') || auth()->user()->can('عرض السيارات') || auth()->user()->can('تعديل السيارات') || auth()->user()->can('حذف السيارات'))

                    <li>
                        <a href="{{ route('admin.carclass.index') }}" class="waves-effect  waves-light">
                            <span class="s-icon"><i class="ti-car"></i></span>
                            <span class="s-text">@lang('admin.Cars_Types')</span>
                        </a>
                    </li>
                @endif
                {{--            111111111111111111111111111111111111111111111111111111111111111111111111111--}}
                @if(auth()->user()->can('اضافه موديل السياره') || auth()->user()->can('عرض موديل السياره') || auth()->user()->can('تعديل موديل السياره') || auth()->user()->can('حذف موديل السياره'))
                    <li>
                        <a href="{{ route('admin.carmodel.index') }}" class="waves-effect  waves-light">
                            <span class="s-icon"><i class="ti-infinite"></i></span>
                            <span class="s-text">@lang('admin.Car Models')</span>
                        </a>
                    </li>
                @endif
            @endif
            @if(auth()->user()->can('اضافه المستخدمين') || auth()->user()->can('عرض المستخدمين') || auth()->user()->can('تعديل المستخدمين') || auth()->user()->can('حذف المستخدمين')
              || auth()->user()->can('اضافه مالك الشركه') || auth()->user()->can('عرض مالك الشركه') || auth()->user()->can('تعديل مالك الشركه') || auth()->user()->can('حذف مالك الشركه')
              || auth()->user()->can('اضافه السائقين') || auth()->user()->can('عرض السائقين') || auth()->user()->can('تعديل السائقين') || auth()->user()->can('حذف السائقين')
              || auth()->user()->can('اضافه المشرفين') || auth()->user()->can('عرض المشرفين') || auth()->user()->can('تعديل المشرفين') || auth()->user()->can('حذف المشرفين')
              || auth()->user()->can('اضافه الرتب') || auth()->user()->can('عرض الرتب') || auth()->user()->can('تعديل الرتب') || auth()->user()->can('حذف الرتب')
              || auth()->user()->can('اضافه المرسلات') || auth()->user()->can('عرض المرسلات') || auth()->user()->can('تعديل المرسلات') || auth()->user()->can('حذف المرسلات')
              || auth()->user()->can('اضافه مالك الشركه') || auth()->user()->can('عرض مالك الشركه') || auth()->user()->can('تعديل مالك الشركه') || auth()->user()->can('حذف مالك الشركه')
              || auth()->user()->can('اضافه السيارات الخاصه بالسائقين') || auth()->user()->can('عرض السيارات الخاصه بالسائقين') || auth()->user()->can('تعديل السيارات الخاصه بالسائقين') || auth()->user()->can('حذف السيارات الخاصه بالسائقين'))

                <li class="menu-title">@lang('admin.Members')</li>
                @if(auth()->user()->can('اضافه المستخدمين') || auth()->user()->can('عرض المستخدمين') || auth()->user()->can('تعديل المستخدمين') || auth()->user()->can('حذف المستخدمين'))
                    <li class="with-sub">
                        <a href="#" class="waves-effect  waves-light">
                            <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                            <span class="s-icon"><i class="ti-crown"></i></span>
                            <span class="s-text">@lang('admin.Users')</span>
                        </a>
                        <ul>
                            @can('عرض المستخدمين')
                                <li><a href="{{ route('admin.user.index') }}">@lang('admin.List Users')</a></li>
                            @endcan
                            @can('عرض المستخدمين المحذوفين')
                                <li><a href="{{ route('admin.trashedUser') }}">@lang('admin.UsersTrashed')</a></li>
                            @endcan
                            @can('اضافه المستخدمين')
                                <li><a href="{{ route('admin.user.create') }}">@lang('admin.Add New User')</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif
                @if(auth()->user()->can('عرض سيارات بدون سائق') || auth()->user()->can('تعديل سيارات بدون سائق') )
                    <li class="with-sub">
                        <a href="#" class="waves-effect  waves-light">
                            <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                            <span class="s-icon"><i class="ti-car"></i></span>
                            <span class="s-text">@lang('admin.cars_ad')</span>
                        </a>
                        <ul>
                            @can('عرض سيارات بدون سائق')
                                <li><a href="{{ route('admin.order.index') }}">@lang('admin.view all')</a></li>
                            @endcan
                            @can('تعديل سيارات بدون سائق')
                                <li><a href="{{ route('admin.order.create') }}">@lang('user.Add Car')</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
                @if(auth()->user()->can('اضافه السائقين') || auth()->user()->can('عرض السائقين') || auth()->user()->can('تعديل السائقين') || auth()->user()->can('حذف السائقين'))
                    <li class="with-sub">
                        <a href="#" class="waves-effect  waves-light">
                            <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                            <span class="s-icon"><i class="ti-car"></i></span>
                            <span class="s-text">@lang('admin.Providers')</span>
                        </a>
                        <ul>
                            @can('عرض السائقين')
                                <li><a href="{{ route('admin.provider.index') }}">@lang('admin.List Providers')</a></li>
                            @endcan
                            @can('عرض السائقين المحذوفين')
                                <li><a href="{{ route('admin.trashed') }}">@lang('admin.ProvidersTrashed')</a></li>
                            @endcan
                            @can('اضافه السائقين')
                                <li><a href="{{ route('admin.provider.create') }}">@lang('admin.Add New Provider')</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @if(auth()->user()->can('اضافه السيارات الخاصه بالسائقين') || auth()->user()->can('عرض السيارات الخاصه بالسائقين') || auth()->user()->can('تعديل السيارات الخاصه بالسائقين') || auth()->user()->can('حذف السيارات الخاصه بالسائقين'))
                    <li class="with-sub">
                        <a href="#" class="waves-effect  waves-light">
                            <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                            <span class="s-icon"><i class="ti-car"></i></span>
                            <span class="s-text">@lang('admin.Car Provider')</span>
                        </a>
                        <ul>
                            @can('عرض السيارات الخاصه بالسائقين')
                                <li><a href="{{ route('admin.car.index') }}">@lang('admin.List Car Providers')</a></li>
                            @endcan
                            @can('اضافه السيارات الخاصه بالسائقين')
                                <li><a href="{{ route('admin.car.create') }}">@lang('admin.Add New Car Provider')</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
                @if(auth()->user()->can('اضافه المشرفين') || auth()->user()->can('عرض المشرفين') || auth()->user()->can('تعديل المشرفين') || auth()->user()->can('حذف المشرفين'))
                    <li class="with-sub">
                        <a href="#" class="waves-effect  waves-light">
                            <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                            <span class="s-icon"><i class="ti-crown"></i></span>
                            <span class="s-text">@lang('admin.Admins')</span>
                        </a>
                        <ul>
                            @can('عرض المشرفين')
                                <li><a href="{{ route('admin.admin.index') }}">@lang('admin.List Admins')</a></li>
                            @endcan
                            @can('اضافه المشرفين')
                                <li><a href="{{ route('admin.admin.create') }}">@lang('admin.Add New Admin')</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @if(auth()->user()->can('اضافه الصلاحيات') || auth()->user()->can('عرض الصلاحيات') )
                    <li class="with-sub">
                        <a href="#" class="waves-effect  waves-light">
                            <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                            <span class="s-icon"><i class="ti-crown"></i></span>
                            <span class="s-text">@lang('admin.Permissions')</span>
                        </a>
                        <ul>
                            @can('عرض الصلاحيات')
                            <li><a href="{{ route('admin.permission.index') }}">@lang('admin.List Permissions')</a></li>
                            @endcan
                            @can('اضافه الصلاحيات')
                            <li><a href="{{ route('admin.permission.create') }}">@lang('admin.Add New Permission')</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endif

                @if(auth()->user()->can('اضافه الرتب') || auth()->user()->can('عرض الرتب') || auth()->user()->can('تعديل الرتب') || auth()->user()->can('حذف الرتب'))
                    <li class="with-sub">
                        <a href="#" class="waves-effect  waves-light">
                            <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                            <span class="s-icon"><i class="ti-crown"></i></span>
                            <span class="s-text">@lang('admin.Roles')</span>
                        </a>
                        <ul>
                            @can('عرض الرتب')
                                <li><a href="{{ route('admin.role.index') }}">@lang('admin.List Roles')</a></li>
                            @endcan
                            @can('اضافه الرتب')
                                <li><a href="{{ route('admin.role.create') }}">@lang('admin.Add New Role')</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif
                @if(auth()->user()->can('اضافه المرسلات') || auth()->user()->can('عرض المرسلات') || auth()->user()->can('تعديل المرسلات') || auth()->user()->can('حذف المرسلات'))
                    <li class="with-sub">
                        <a href="#" class="waves-effect  waves-light">
                            <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                            <span class="s-icon"><i class="ti-crown"></i></span>
                            <span class="s-text">@lang('admin.Dispatcher')</span>
                        </a>
                        <ul>
                            @can('عرض المرسلات')
                                <li>
                                    <a href="{{ route('admin.dispatch-manager.index') }}">@lang('admin.List Dispatcher')</a>
                                </li>
                            @endcan
                            @can('اضافه المرسلات')
                                <li>
                                    <a href="{{ route('admin.dispatch-manager.create') }}">@lang('admin.Add New Dispatcher')</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
                @if(auth()->user()->can('اضافه مالك الشركه') || auth()->user()->can('عرض مالك الشركه') || auth()->user()->can('تعديل مالك الشركه') || auth()->user()->can('حذف مالك الشركه'))
                    <li class="with-sub">
                        <a href="#" class="waves-effect  waves-light">
                            <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                            <span class="s-icon"><i class="ti-crown"></i></span>
                            <span class="s-text">@lang('admin.Fleet Owner')</span>
                        </a>
                        <ul>
                            @can('عرض مالك الشركه')
                                <li><a href="{{ route('admin.fleet.index') }}">@lang('admin.List Fleets')</a></li>
                            @endcan
                                @can('عرض الشركات المحذوفه')
                                    <li><a href="{{ route('admin.trashedFleet') }}">@lang('admin.FleetsTrashed')</a></li>
                                @endcan
                            @can('اضافه مالك الشركه')
                                <li><a href="{{ route('admin.fleet.create') }}">@lang('admin.Add New Fleet Owner')</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                   @if( auth()->user()->can('عرض مالك الشركه'))
                           <li class="with-sub">
                          <a href="#" class="waves-effect  waves-light">
                                  <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                            <span class="s-icon"><i class="ti-crown"></i></span>
                            <span class="s-text">@lang('admin.revenues com')</span>
                        </a>
                        <ul>
                            <li><a href="{{ route('admin.revenues.index') }}">@lang('admin.Baz company revenues')</a></li>
                            <li><a href="{{ route('admin.revenue.monthly') }}">@lang('admin.Baz company revenues1')</a></li>
                            <li><a href="{{ route('admin.revenue.yearly') }}">@lang('admin.Baz company revenues2')</a></li>
                        </ul>
                    </li>
                @endif

                {{--            @if(auth()->user()->can('اضافه مديرى الحسابات') || auth()->user()->can('عرض مديرى الحسابات') || auth()->user()->can('تعديل مديرى الحسابات') || auth()->user()->can('حذف مديرى الحسابات'))--}}
                {{--                <li class="with-sub">--}}
                {{--                    <a href="#" class="waves-effect  waves-light">--}}
                {{--                        <span class="s-caret"><i class="fa fa-angle-down"></i></span>--}}
                {{--                        <span class="s-icon"><i class="ti-crown"></i></span>--}}
                {{--                        <span class="s-text">@lang('admin.Account Manager')</span>--}}
                {{--                    </a>--}}
                {{--                    <ul>--}}
                {{--                        @can('عرض مديرى الحسابات')--}}
                {{--                            <li>--}}
                {{--                                <a href="{{ route('admin.account-manager.index') }}">@lang('admin.List Account Managers')</a>--}}
                {{--                            </li>--}}
                {{--                        @endcan--}}
                {{--                        @can('اضافه مديرى الحسابات')--}}
                {{--                            <li>--}}
                {{--                                <a href="{{ route('admin.account-manager.create') }}">@lang('admin.Add New Account Manager')</a>--}}
                {{--                            </li>--}}
                {{--                        @endcan--}}
                {{--                    </ul>--}}
                {{--                </li>--}}
                {{--            @endif--}}
            @endif

            @if(auth()->user()->can('عرض البيانات العموميه') || auth()->user()->can('عرض بيان السائق') || auth()->user()->can('عرض البيان اليوميه') || auth()->user()->can('عرض البيان الشهريه') || auth()->user()->can('عرض البيان السنوى')
                || auth()->user()->can('عرض تعليقات المستخدم') || auth()->user()->can('عرض تعليقات السائق') || auth()->user()->can('عرض الخريطه'))
                <li class="menu-title">الإيميلات</li>
                @if(auth()->user()->can('عرض البيانات العموميه') || auth()->user()->can('عرض بيان السائق') || auth()->user()->can('عرض البيان اليوميه') || auth()->user()->can('عرض البيان الشهريه') || auth()->user()->can('عرض البيان السنوى'))
                    <li class="with-sub">
                        <a href="#" class="waves-effect  waves-light">
                            <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                            <span class="s-icon"><i class="ti-crown"></i></span>
                            <span class="s-text">@lang('admin.Statements')</span>
                        </a>
                        <ul>
                            @can('عرض البيانات العموميه')
                                <li>
                                    <a href="{{ route('admin.ride.statement') }}">@lang('admin.Overall Ride Statments')</a>
                                </li>
                            @endcan
                            @can('عرض بيان السائق')
                                <li>
                                    <a href="{{ route('admin.ride.statement.provider') }}">@lang('admin.Provider Statement')</a>
                                </li>
                            @endcan
                            @can('عرض البيان اليوميه')
                                <li>
                                    <a href="{{ route('admin.ride.statement.today') }}">@lang('admin.Daily Statement')</a>
                                </li>
                            @endcan
                            @can('عرض البيان الشهريه')
                                <li>
                                    <a href="{{ route('admin.ride.statement.monthly') }}">@lang('admin.Monthly Statement')</a>
                                </li>
                            @endcan
                            @can('عرض البيان السنوى')
                                <li>
                                    <a href="{{ route('admin.ride.statement.yearly') }}">@lang('admin.Yearly Statement')</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @if(auth()->user()->can('عرض تعليقات المستخدم') || auth()->user()->can('عرض تعليقات السائق'))
                    <li class="with-sub">
                        <a href="#" class="waves-effect  waves-light">
                            <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                            <span class="s-icon"><i class="ti-view-grid"></i></span>
                            <span class="s-text">@lang('admin.Ratings') &amp; @lang('admin.Reviews')</span>
                        </a>
                        <ul>
                            @can('عرض تعليقات المستخدم')
                                <li><a href="{{ route('admin.user.review') }}">@lang('admin.User Ratings')</a></li>
                            @endcan
                            @can('عرض تعليقات السائق')
                                <li><a href="{{ route('admin.provider.review') }}">@lang('admin.Provider Ratings')</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
            @endif
            @if(auth()->user()->can('عرض الرحلات') || auth()->user()->can('عرض الرحلات المجدوله'))
                <li class="menu-title">@lang('admin.Requests')</li>
                @can('عرض الرحلات')
                    <li>
                        <a href="{{ route('admin.requests.index') }}" class="waves-effect  waves-light">
                            <span class="s-icon"><i class="ti-infinite"></i></span>
                            <span class="s-text">@lang('admin.Request History')</span>
                        </a>
                    </li>
                @endcan
                @can('عرض الرحلات المجدوله')
                    <li>
                        <a href="{{ route('admin.requests.scheduled') }}" class="waves-effect  waves-light">
                            <span class="s-icon"><i class="ti-palette"></i></span>
                            <span class="s-text">@lang('admin.Scheduled Rides')</span>
                        </a>
                    </li>
                @endcan
            @endif

            @if(auth()->user()->can('اضافه المحافظه') || auth()->user()->can('عرض المحافظه') || auth()->user()->can('تعديل المحافظه') || auth()->user()->can('حذف المحافظه') || auth()->user()->can('اضافه المدينه') || auth()->user()->can('عرض المدينه') || auth()->user()->can('تعديل المدينه') || auth()->user()->can('حذف المدينه'))

                <li class="menu-title">@lang('admin.City and Governorate')</li>

                @if(auth()->user()->can('اضافه المدينه') || auth()->user()->can('عرض المدينه') ||
                auth()->user()->can('تعديل المدينه') || auth()->user()->can('حذف المدينه'))
                    <li class="with-sub">
                        <a href="#" class="waves-effect  waves-light">
                            <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                            <span class="s-icon"><i class="ti-crown"></i></span>
                            <span class="s-text">@lang('admin.Cities')</span>
                        </a>
                        <ul>
                            @can('عرض المدينه')
                                <li><a href="{{ route('admin.city.index') }}">@lang('admin.list_city')</a></li>
                            @endcan
                            @can('اضافه المدينه')
                                <li><a href="{{ route('admin.city.create') }}">@lang('admin.addCity')</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif


                @if(auth()->user()->can('اضافه المحافظه') || auth()->user()->can('عرض المحافظه') ||
                auth()->user()->can('تعديل المحافظه') || auth()->user()->can('حذف المحافظه'))
                    <li class="with-sub">
                        <a href="#" class="waves-effect  waves-light">
                            <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                            <span class="s-icon"><i class="ti-crown"></i></span>
                            <span class="s-text">@lang('admin.Governorates')</span>
                        </a>
                        <ul>
                            @can('عرض المحافظه')
                                <li><a href="{{ route('admin.governorate.index') }}">@lang('admin.list_governorate')</a>
                                </li>
                            @endcan
                            @can('اضافه المحافظه')
                                <li><a href="{{ route('admin.governorate.create') }}">@lang('admin.addGovernorate')</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
            @endif


            @if(auth()->user()->can('اضافه المستندات') || auth()->user()->can('عرض المستندات') || auth()->user()->can('تعديل المستندات') || auth()->user()->can('حذف المستندات')
                || auth()->user()->can('اضافه البروموكود') || auth()->user()->can('عرض البروموكود') || auth()->user()->can('تعديل البروموكود') || auth()->user()->can('حذف البروموكود')
                || auth()->user()->can('اضافه التعاقدات') || auth()->user()->can('عرض التعاقدات') || auth()->user()->can('تعديل التعاقدات') || auth()->user()->can('حذف التعاقدات')
                || auth()->user()->can('اضافه تعاقدات الشركات') || auth()->user()->can('عرض تعاقدات الشركات') || auth()->user()->can('تعديل تعاقدات الشركات') || auth()->user()->can('حذف تعاقدات الشركات'))
                <li class="menu-title">@lang('admin.General')</li>
                {{--                @if(auth()->user()->can('اضافه المستندات') || auth()->user()->can('عرض المستندات') || auth()->user()->can('تعديل المستندات') || auth()->user()->can('حذف المستندات'))--}}
                {{--                    <li class="with-sub">--}}
                {{--                        <a href="#" class="waves-effect  waves-light">--}}
                {{--                            <span class="s-caret"><i class="fa fa-angle-down"></i></span>--}}
                {{--                            <span class="s-icon"><i class="ti-layout-tab"></i></span>--}}
                {{--                            <span class="s-text">@lang('admin.Documents')</span>--}}
                {{--                        </a>--}}
                {{--                        <ul>--}}
                {{--                            @can('عرض المستندات')--}}
                {{--                                <li><a href="{{ route('admin.document.index') }}">@lang('admin.List Documents')</a></li>--}}
                {{--                            @endcan--}}
                {{--                            @can('اضافه المستندات')--}}
                {{--                                <li><a href="{{ route('admin.document.create') }}">@lang('admin.Add New Document')</a></li>--}}
                {{--                            @endcan--}}
                {{--                        </ul>--}}
                {{--                    </li>--}}
                {{--                @endif--}}
                @if(auth()->user()->can('اضافه البروموكود') || auth()->user()->can('عرض البروموكود') || auth()->user()->can('تعديل البروموكود') || auth()->user()->can('حذف البروموكود'))
                    <li class="with-sub">
                        <a href="#" class="waves-effect  waves-light">
                            <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                            <span class="s-icon"><i class="ti-layout-tab"></i></span>
                            <span class="s-text">@lang('admin.Promocodes')</span>
                        </a>
                        <ul>
                            @can('عرض البروموكود')
                                <li><a href="{{ route('admin.promocode.index') }}">@lang('admin.List Promocodes')</a>
                                </li>
                            @endcan
                            @can('اضافه البروموكود')
                                <li><a href="{{ route('admin.promocode.create') }}">@lang('admin.Add New Promocode')</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
                @if(auth()->user()->can('اضافه التعاقدات') || auth()->user()->can('عرض التعاقدات') || auth()->user()->can('تعديل التعاقدات') || auth()->user()->can('حذف التعاقدات'))
                    <li class="with-sub">
                        <a href="#" class="waves-effect  waves-light">
                            <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                            <span class="s-icon"><i class="ti-layout-tab"></i></span>
                            <span class="s-text">@lang('admin.revenues')</span>
                        </a>
                        <ul>
                            @can('عرض التعاقدات')
                                <li><a href="{{ route('admin.revenue.index') }}">@lang('admin.List revenues')</a>
                                </li>
                            @endcan
                            @can('اضافه التعاقدات')
                                <li><a href="{{ route('admin.revenue.create') }}">@lang('admin.Add New revenue')</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
                @if(auth()->user()->can('اضافه تعاقدات الشركات') || auth()->user()->can('عرض تعاقدات الشركات') || auth()->user()->can('تعديل تعاقدات الشركات') || auth()->user()->can('حذف تعاقدات الشركات'))
                    <li class="with-sub">
                        <a href="#" class="waves-effect  waves-light">
                            <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                            <span class="s-icon"><i class="ti-layout-tab"></i></span>
                            <span class="s-text">@lang('admin.company subscriptions')</span>
                        </a>
                        <ul>
                            @can('عرض تعاقدات الشركات')
                                <li>
                                    <a href="{{ route('admin.company-subscription.index') }}">@lang('admin.List company subscriptions')</a>
                                </li>
                            @endcan
                            @can('اضافه تعاقدات الشركات')
                                <li>
                                    <a href="{{ route('admin.company-subscription.create') }}">@lang('admin.Add New company subscription')</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
            @endif
            @if(auth()->user()->can('اعدادات الموقع') || auth()->user()->can('تعديل اعدادات الموقع')
                            || auth()->user()->can('تعديل اعدادات الدفع') || auth()->user()->can('الاعدادات الرئيسيه') || auth()->user()->can('تعديل الاعدادات الرئيسيه')
                            || auth()->user()->can('عرض شروط الخدمه') || auth()->user()->can('اضافه شروط الخدمه') || auth()->user()->can('حذف شروط الخدمه')
                            || auth()->user()->can('عرض تاريخ الدفع') || auth()->user()->can('اعدادات الدفع') || auth()->user()->can('تعديل اعدادات الدفع'))
                <li class="menu-title">@lang('admin.Payment Details')</li>
                @can('عرض تاريخ الدفع')
                    <li>
                        <a href="{{ route('admin.payment') }}" class="waves-effect  waves-light">
                            <span class="s-icon"><i class="ti-infinite"></i></span>
                            <span class="s-text">@lang('admin.Payment History')</span>
                        </a>
                    </li>
                @endcan
                @if(auth()->user()->can('اعدادات الدفع') || auth()->user()->can('تعديل اعدادات الدفع'))
                    <li>
                        <a href="{{ route('admin.settings.payment') }}" class="waves-effect  waves-light">
                            <span class="s-icon"><i class="ti-money"></i></span>
                            <span class="s-text">@lang('admin.Payment Settings')</span>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('اعدادات الموقع') || auth()->user()->can('تعديل اعدادات الموقع')
                                || auth()->user()->can('تعديل اعدادات الدفع') || auth()->user()->can('الاعدادات الرئيسيه') || auth()->user()->can('تعديل الاعدادات الرئيسيه')
                                || auth()->user()->can('عرض شروط الخدمه') || auth()->user()->can('اضافه شروط الخدمه') || auth()->user()->can('حذف شروط الخدمه'))
                    <li class="with-sub">
                        <a href="#" class="waves-effect  waves-light">
                            <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                            <span class="s-icon"><i class="ti-layout-tab"></i></span>
                            <span class="s-text">@lang('admin.Setting')</span>
                        </a>
                        <ul>
                            @if(auth()->user()->can('اعدادات الموقع') || auth()->user()->can('تعديل اعدادات الموقع'))
                                <li><a href="{{ route('admin.settings') }}">@lang('admin.Site Settings')</a></li>
                            @endif
                            @if(auth()->user()->can('الاعدادات الرئيسيه') || auth()->user()->can('تعديل الاعدادات الرئيسيه'))
                                <li><a href="{{ route('admin.dash_settings') }}">@lang('admin.main Settings')</a></li>
                            @endif
                            @if(auth()->user()->can('اضافه المنشورات') || auth()->user()->can('عرض المنشورات') || auth()->user()->can('تعديل المنشورات') || auth()->user()->can('حذف المنشورات'))
                                <li><a href="{{ route('admin.box.index') }}">@lang('admin.Boxes')</a></li>
                            @endif
                            @if(auth()->user()->can('عرض شروط الخدمه') || auth()->user()->can('اضافه شروط الخدمه') || auth()->user()->can('حذف شروط الخدمه'))
                                <li>
                                    <a href="{{ route('admin.condition_settings') }}">@lang('admin.Service Conditions')</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            @endif
            {{--			<li class="menu-title">Settings</li>--}}
            {{--			<li>--}}
            {{--				<a href="{{ route('admin.settings') }}" class="waves-effect  waves-light">--}}
            {{--					<span class="s-icon"><i class="ti-settings"></i></span>--}}
            {{--					<span class="s-text">Site Settings</span>--}}
            {{--				</a>--}}
            {{--			</li>--}}

            {{--			<li>--}}
            {{--				<a href="{{ route('admin.dash_settings') }}" class="waves-effect  waves-light">--}}
            {{--					<span class="s-icon"><i class="ti-settings"></i></span>--}}
            {{--					<span class="s-text">main page</span>--}}
            {{--				</a>--}}
            {{--			</li>--}}

            {{--			<li class="menu-title">Others</li>--}}
            {{--			<li>--}}
            {{--				<a href="{{ route('admin.privacy') }}" class="waves-effect waves-light">--}}
            {{--					<span class="s-icon"><i class="ti-help"></i></span>--}}
            {{--				<span class="s-text">Privacy Policy</span>--}}
            {{--				</a>--}}
            {{--			</li>--}}
            {{--			<li>--}}
            {{--				<a href="{{ route('admin.help') }}" class="waves-effect waves-light">--}}
            {{--				<span class="s-icon"><i class="ti-help"></i></span>--}}
            {{--				<span class="s-text">Help</span>--}}
            {{--				</a>--}}
            {{--			</li>--}}
            {{--			<li>--}}
            {{--				<a href="{{route('admin.translation') }}" class="waves-effect waves-light">--}}
            {{--					<span class="s-icon"><i class="ti-smallcap"></i></span>--}}
            {{--					<span class="s-text">Translations</span>--}}
            {{--				</a>--}}
            {{--			</li>--}}


            <li class="menu-title">@lang('admin.Account')</li>
            <li>
                <a href="{{ route('admin.profile') }}" class="waves-effect  waves-light">
                    <span class="s-icon"><i class="ti-user"></i></span>
                    <span class="s-text">@lang('admin.Account Settings')</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.password') }}" class="waves-effect  waves-light">
                    <span class="s-icon"><i class="ti-exchange-vertical"></i></span>
                    <span class="s-text">@lang('admin.Change Password')</span>
                </a>
            </li>
            <li class="compact-hide">
                <a href="{{ url('/admin/logout') }}"
                   onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                    <span class="s-icon"><i class="ti-power-off"></i></span>
                    <span class="s-text">@lang('admin.Logout')</span>
                </a>

                <form id="logout-form" action="{{ url('/admin/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>

        </ul>
    </div>
</div>
