@extends('admin.layout.base')

@section('title', 'Dashboard ')

@section('styles')
    <link rel="stylesheet" href="{{asset('main/vendor/jvectormap/jquery-jvectormap-2.0.3.css')}}">
@endsection
@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="row row-md">
                @if(auth()->user()->can('عرض الرحلات') || auth()->user()->can('عرض الرحلات المجدوله'))
                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="box box-block bg-white tile tile-1 mb-2">
                            <div class="t-icon right"><span class="bg-danger"></span><i class="ti-rocket"></i></div>
                            <div class="t-content">
                                <h6 class="text-uppercase mb-1">@lang('admin.Total No. of Rides')</h6>
                                <h1 class="mb-1">{{$rides->count()}}</h1>
                                <span class="tag tag-danger mr-0-5">@if($cancel_rides == 0 or $rides->count() == 0)
                                        0.00 @else {{round($cancel_rides/$rides->count(),2)}}%@endif</span>
                                <span class="text-muted font-90">% @lang('admin.down from cancelled Request')</span>
                            </div>
                        </div>
                    </div>
                @endif
                {{--                اعدادات الدفع--}}
                @if(auth()->user()->can('اعدادات الدفع') || auth()->user()->can('تعديل اعدادات الدفع'))
                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="box box-block bg-white tile tile-1 mb-2">
                            <div class="t-icon right"><span class="bg-success"></span><i class="ti-bar-chart"></i></div>
                            <div class="t-content">
                                <h6 class="text-uppercase mb-1">@lang('admin.Revenue')</h6>
                                <h1 class="mb-1">{{currency($revenue)}}</h1>
                                <i class="fa fa-caret-up text-success mr-0-5"></i><span> {{$rides->count()}} @lang('admin.Rides') @lang('admin.from')</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="box box-block bg-white tile tile-1 mb-2">
                            <div class="t-icon right"><span class="bg-warning"></span><i class="ti-archive"></i></div>
                            <div class="t-content">
                                <h6 class="text-uppercase mb-1">@lang('admin.Company revenue')</h6>
                                <h1 class="mb-1">{{ currency(($revenue * $commission_percentage)/100)}}</h1>
                            </div>
                        </div>
                    </div>
                @endif
                    @if(auth()->user()->can('عرض الرحلات') || auth()->user()->can('عرض الرحلات المجدوله'))
                <div class="col-lg-3 col-md-6 col-xs-12">
                    <div class="box box-block bg-white tile tile-1 mb-2">
                        <div class="t-icon right"><span class="bg-warning"></span><i class="ti-archive"></i></div>
                        <div class="t-content">
                            <h6 class="text-uppercase mb-1">@lang('admin.Total Cancelled Rides')</h6>
                            <h1 class="mb-1">{{$user_cancelled}}</h1>
                            <i class="fa fa-caret-down text-danger mr-0-5"></i><span>@if($cancel_rides == 0 or $rides->count() == 0)
                                    0.00 @else {{round($cancel_rides/$rides->count())}}
                                    % @endif  @lang('admin.Rides') @lang('admin.for')</span>
                        </div>
                    </div>
                </div>
                {{--            </div>--}}
                {{--            <div class="row row-md">--}}
                <div class="col-lg-3 col-md-6 col-xs-12">
                    <div class="box box-block bg-white tile tile-1 mb-2">
                        <div class="t-icon right"><span class="bg-primary"></span><i class="ti-view-grid"></i></div>
                        <div class="t-content">
                            <h6 class="text-uppercase mb-1">@lang('admin.User Cancelled Count')</h6>
                            <h1 class="mb-1">{{$user_cancelled}}</h1>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-xs-12">
                    <div class="box box-block bg-white tile tile-1 mb-2">
                        <div class="t-icon right"><span class="bg-danger"></span><i class="ti-bar-chart"></i></div>
                        <div class="t-content">
                            <h6 class="text-uppercase mb-1">@lang('admin.Provider Cancelled Count')</h6>
                            <h1 class="mb-1">{{$provider_cancelled->count()}}</h1>
                        </div>
                    </div>
                </div>
                @endif
                @if(auth()->user()->can('اضافه مالك الشركه') || auth()->user()->can('عرض مالك الشركه') || auth()->user()->can('تعديل مالك الشركه') || auth()->user()->can('حذف مالك الشركه'))
                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="box box-block bg-white tile tile-1 mb-2">
                            <div class="t-icon right"><span class="bg-warning"></span><i class="ti-rocket"></i></div>
                            <div class="t-content">
                                <h6 class="text-uppercase mb-1">@lang('admin.No. of Fleets')</h6>
                                <h1 class="mb-1">{{$fleet}}</h1>
                            </div>
                        </div>
                    </div>
                @endif
                @if(auth()->user()->can('اضافه أنواع الخدمات') || auth()->user()->can('عرض أنواع الخدمات') || auth()->user()->can('تعديل أنواع الخدمات') || auth()->user()->can('حذف أنواع الخدمات'))
                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="box box-block bg-white tile tile-1 mb-2">
                            <div class="t-icon right"><span class="bg-primary"></span><i class="ti-view-grid"></i></div>
                            <div class="t-content">
                                <h6 class="text-uppercase mb-1">@lang('admin.No. of Service Types')</h6>
                                <h1 class="mb-1">{{$service}}</h1>
                            </div>
                        </div>
                    </div>
                @endif
                {{--            </div>--}}


                {{--		111111111111111111111111111         --}}

                @inject('admin', 'App\Admin')
                @inject('box', 'App\Box')
                @inject('car_classes', 'App\CarClass')
                @inject('car_models', 'App\CarModel')
                @inject('cars', 'App\Car')


                {{--            <div class="row row-md">--}}
                @if(auth()->user()->can('اضافه المشرفين') || auth()->user()->can('عرض المشرفين') || auth()->user()->can('تعديل المشرفين') || auth()->user()->can('حذف المشرفين'))
                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="box box-block bg-white tile tile-1 mb-2">
                            <div class="t-icon right"><span class="bg-primary"></span><i class="ti-view-grid"></i></div>
                            <div class="t-content">
                                <h6 class="text-uppercase mb-1">@lang('admin.admins')</h6>
                                <h1 class="mb-1">{{$admin->count()}}</h1>
                            </div>
                        </div>
                    </div>
                @endif
                @if(auth()->user()->can('اضافه المنشورات') || auth()->user()->can('عرض المنشورات') || auth()->user()->can('تعديل المنشورات') || auth()->user()->can('حذف المنشورات'))
                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="box box-block bg-white tile tile-1 mb-2">
                            <div class="t-icon right"><span class="bg-danger"></span><i class="ti-bar-chart"></i></div>
                            <div class="t-content">
                                <h6 class="text-uppercase mb-1">@lang('admin.Box')</h6>
                                <h1 class="mb-1">{{$box->count()}}</h1>
                            </div>
                        </div>
                    </div>
                @endif
                @if(auth()->user()->can('اضافه السيارات') || auth()->user()->can('عرض السيارات') || auth()->user()->can('تعديل السيارات') || auth()->user()->can('حذف السيارات'))

                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="box box-block bg-white tile tile-1 mb-2">
                            <div class="t-icon right"><span class="bg-warning"></span><i class="ti-rocket"></i></div>
                            <div class="t-content">
                                <h6 class="text-uppercase mb-1">@lang('admin.car_classes')</h6>
                                <h1 class="mb-1">{{$cars->count()}}</h1>
                            </div>
                        </div>
                    </div>
                @endif
                @if(auth()->user()->can('اضافه موديل السياره') || auth()->user()->can('عرض موديل السياره') || auth()->user()->can('تعديل موديل السياره') || auth()->user()->can('حذف موديل السياره'))
                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="box box-block bg-white tile tile-1 mb-2">
                            <div class="t-icon right"><span class="bg-success"></span><i class="ti-bar-chart"></i></div>
                            <div class="t-content">
                                <h6 class="text-uppercase mb-1">@lang('admin.car_models')</h6>
                                <h1 class="mb-1">{{$car_models->count()}}</h1>
                            </div>
                        </div>
                    </div>
                @endif
                {{--            </div>--}}


                {{--	11111111111111111111111111111111111111111       --}}


                {{--		222222222222222222222222222222222         --}}

                @inject('fleets', 'App\Fleet')
                @inject('providers', 'App\Provider')
                @inject('users', 'App\User')


                {{--            <div class="row row-md">--}}
                {{-- @if(auth()->user()->can('اضافه مالك الشركه') || auth()->user()->can('عرض مالك الشركه') || auth()->user()->can('تعديل مالك الشركه') || auth()->user()->can('حذف مالك الشركه'))

                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="box box-block bg-white tile tile-1 mb-2">
                            <div class="t-icon right"><span class="bg-primary"></span><i class="ti-view-grid"></i></div>
                            <div class="t-content">
                                <h6 class="text-uppercase mb-1">@lang('admin.fleets')</h6>
                                <h1 class="mb-1">{{$fleets->count()}}</h1>
                            </div>
                        </div>
                    </div>
                @endif --}}
                @if(auth()->user()->can('اضافه السائقين') || auth()->user()->can('عرض السائقين') || auth()->user()->can('تعديل السائقين') || auth()->user()->can('حذف السائقين'))

                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="box box-block bg-white tile tile-1 mb-2">
                            <div class="t-icon right"><span class="bg-danger"></span><i class="ti-bar-chart"></i></div>
                            <div class="t-content">
                                <h6 class="text-uppercase mb-1">@lang('admin.providers')</h6>
                                <h1 class="mb-1">{{$providers->count()}}</h1>
                            </div>
                        </div>
                    </div>
                @endif
                @if(auth()->user()->can('اضافه المستخدمين') || auth()->user()->can('عرض المستخدمين') || auth()->user()->can('تعديل المستخدمين') || auth()->user()->can('حذف المستخدمين'))

                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="box box-block bg-white tile tile-1 mb-2">
                            <div class="t-icon right"><span class="bg-warning"></span><i class="ti-rocket"></i></div>
                            <div class="t-content">
                                <h6 class="text-uppercase mb-1">@lang('admin.users')</h6>
                                <h1 class="mb-1">{{$users->count()}}</h1>
                            </div>
                        </div>
                    </div>
                @endif
                @if(auth()->user()->can('اضافه تعاقدات الشركات') || auth()->user()->can('عرض تعاقدات الشركات') || auth()->user()->can('تعديل تعاقدات الشركات') || auth()->user()->can('حذف تعاقدات الشركات'))
                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="box box-block bg-white tile tile-1 mb-2">
                            <div class="t-icon right"><span class="bg-success"></span><i class="ti-bar-chart"></i></div>
                            <div class="t-content">
                                <h6 class="text-uppercase mb-1">@lang('admin.company_subscription')</h6>
                                <h1 class="mb-1">{{$company_subscription}}</h1>
                            </div>
                        </div>
                    </div>
                @endif
                {{--            </div>--}}

                {{--            <div class="row row-md">--}}
                @if(auth()->user()->can('اضافه التعاقدات') || auth()->user()->can('عرض التعاقدات') || auth()->user()->can('تعديل التعاقدات') || auth()->user()->can('حذف التعاقدات'))
                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="box box-block bg-white tile tile-1 mb-2">
                            <div class="t-icon right"><span class="bg-primary"></span><i class="ti-view-grid"></i></div>
                            <div class="t-content">
                                <h6 class="text-uppercase mb-1">@lang('admin.provider_subscription')</h6>
                                <h1 class="mb-1">{{$provider_subscription}}</h1>
                            </div>
                        </div>
                    </div>
                @endif
                {{--		<div class="col-lg-3 col-md-6 col-xs-12">--}}
                {{--			<div class="box box-block bg-white tile tile-1 mb-2">--}}
                {{--				<div class="t-icon right"><span class="bg-danger"></span><i class="ti-bar-chart"></i></div>--}}
                {{--				<div class="t-content">--}}
                {{--					<h6 class="text-uppercase mb-1">@lang('admin.providers')</h6>--}}
                {{--					<h1 class="mb-1">{{$providers->count()}}</h1>--}}
                {{--				</div>--}}
                {{--			</div>--}}
                {{--		</div>--}}
                {{--		<div class="col-lg-3 col-md-6 col-xs-12">--}}
                {{--			<div class="box box-block bg-white tile tile-1 mb-2">--}}
                {{--				<div class="t-icon right"><span class="bg-warning"></span><i class="ti-rocket"></i></div>--}}
                {{--				<div class="t-content">--}}
                {{--					<h6 class="text-uppercase mb-1">@lang('admin.users')</h6>--}}
                {{--					<h1 class="mb-1">{{$users->count()}}</h1>--}}
                {{--				</div>--}}
                {{--			</div>--}}
                {{--		</div>--}}

                {{--		<div class="col-lg-3 col-md-6 col-xs-12">--}}
                {{--			<div class="box box-block bg-white tile tile-1 mb-2">--}}
                {{--				<div class="t-icon right"><span class="bg-success"></span><i class="ti-bar-chart"></i></div>--}}
                {{--				<div class="t-content">--}}
                {{--					<h6 class="text-uppercase mb-1">@lang('admin.company_subscription')</h6>--}}
                {{--					<h1 class="mb-1">{{$company_subscription}}</h1>--}}
                {{--				</div>--}}
                {{--			</div>--}}
                {{--		</div>--}}
                {{--            </div>--}}

                {{--	222222222222222222222222222222222222222222222       --}}

                {{--            <div class="row row-md mb-2">--}}
                <div class="col-md-12">
                    <div class="box bg-white">
                        <div class="box-block clearfix">
                            <h5 class="float-xs-left">@lang('admin.Recent Rides')</h5>
                            <div class="float-xs-right">
                                <button class="btn btn-link btn-sm text-muted" type="button"><i class="ti-close"></i>
                                </button>
                            </div>
                        </div>
                        <table class="table mb-md-0">
                            <tbody>
                            <?php $diff = ['-success', '-info', '-warning', '-danger']; ?>
                                @foreach($rides as $index => $ride)
                                    @if($index == 10)
                                        @break
                                    @endif
                                    <tr>
                                        <th scope="row">{{$index + 1}}</th>
                                        <td>{{base64_decode($ride->user->first_name)}} {{base64_decode($ride->user->last_name)}}</td>
                                        <td>
                                            @if($ride->status != "CANCELLED")
                                                <a class="text-primary"
                                                href="{{route('admin.requests.show',$ride->id)}}"><span
                                                            class="underline">@lang('admin.View Ride Details')</span></a>
                                            @else
                                                <span>@lang('admin.No Details Found')</span>
                                            @endif
                                        </td>
                                        {{--								<td>--}}
                                        {{--									<span class="text-muted">{{$ride->created_at->diffForHumans()}}</span>--}}
                                        {{--								</td>--}}
                                        <td>
                                                <span class="tag tag-success">{{$ride->status}}</span>

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
