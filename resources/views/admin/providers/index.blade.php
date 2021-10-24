@extends('admin.layout.base')

@section('title', 'Providers ')

@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">
            @include('flash::message')
            <div class="box box-block bg-white">
                <h5 class="mb-1">
                    @lang('admin.Providers')
                    @if(Setting::get('app_status', 0) != 1)
                        <span class="pull-right">(*personal information hidden in demo)</span>
                    @endif
                </h5>
                @can('اضافه السائقين')
                    <a href="{{ route('admin.provider.create') }}" style="margin-left: 1em;"
                       class="btn btn-primary pull-right"><i class="fa fa-plus"></i> @lang('admin.Add New Provider')
                    </a>
                @endcan
                <table class="table table-sm table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.Full_Name')</th>
                        <th>@lang('admin.Email')</th>
                        <th>@lang('admin.Mobile')</th>
                        {{-- <th>@lang('admin.Company Name')</th> --}}
                        <th>@lang('admin.Total_Requests')</th>
                        {{-- <th>@lang('admin.Accepted_Requests')</th>
                        <th>@lang('admin.Cancelled_Requests_User')</th>
                        <th>@lang('admin.Cancelled_Requests')</th> --}}
                        <th>@lang('admin.h_Amount')</th>
                        <th>@lang('admin.Cars')</th>
                        {{-- <th>@lang('admin.Send')</th> --}}
                        {{-- <th>@lang('admin.Wallet_Amount')</th> --}}


                        <th>@lang('admin.Documents_Service_Type')</th>

                        {{-- <th>alo</th> --}}
                        <th>@lang('admin.Online')</th>
                        @if(auth()->user()->can('تعديل السائقين') || auth()->user()->can('حذف السائقين') || auth()->user()->can('عرض بيان السائق') || auth()->user()->can('عرض الرحلات الخاصه بالسائق')
                                || auth()->user()->can('الاضافه فى محفظة السائق') || auth()->user()->can('تعطيل حساب السائق') || auth()->user()->can('تفعيل حساب السائق') || auth()->user()->can('حظر حساب السائق'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($providers as $index => $provider)
                        <tr>
                            <td>{{ $provider->id }}</td>
                            <td>{{base64_decode( $provider->first_name) }} {{ base64_decode($provider->last_name) }}</td>
                            @if(Setting::get('demo_mode', 0) == 1)
                                <td>{{ substr($provider->email, 0, 3).'****'.substr($provider->email, strpos($provider->email, "@")) }}</td>
                            @else
                                <td>{{ $provider->email }}</td>
                            @endif
                            @if($provider->mobile == null)
                                <td>-----</td>
                            @else
                                <td>{{ $provider->mobile }}</td>
                            @endif

                            {{-- @if($provider->fleet == null)
                                <td>لا توجد</td>
                            @else
                                <td>{{ $provider->fleets->name }}</td>
                            @endif --}}

                            <td>{{ \App\UserRequests::where('provider_id',$provider->id)->count() + \App\ProviderUserRequests::where('provider_id', $provider->id)->count() }}</td>
                            {{-- <td>{{ \App\UserRequests::where('provider_id',$provider->id)->where('status', 'COMPLETED')->count() }}</td>
                            <td>{{ \App\UserRequests::where('provider_id',$provider->id)->where('status', 'CANCELLED')->count() }}</td>
                            <td>{{ \App\ProviderUserRequests::where('provider_id', $provider->id)->count() }}</td> --}}

                            @if($provider->created_at == null)
                            <td>لا توجد</td>
                            @else
                            <td>{{ $provider->created_at }}</td>
                            @endif
                            {{-- <td><p>{{$provider->cars}}</p></td> --}}

                            <td>
                            @if(count($provider->cars) != 0)
                                @foreach($provider->cars as $car)
                                    {{-- <div class="alert pt-8 alert-success">{{$car->id}}</div> --}}
                                    <span class="btn btn-primary mb-1">{{$car->id}}</span>

                                @endforeach
                            @else
                                {{-- <div class="alert alert-danger">لا يوجد</div> --}}
                                <span class="btn btn-danger">لا يوجد</span>
                            @endif
                            </td>

                            <div class="row">
                                <div class="col-sm">
                                    {{-- <td>
                                        <a class="btn btn-success btn-block"
                                        href="{{route('admin.send_message', $provider->id )}}">@lang('admin.Send Message')</a>
                                    </td> --}}

                                    {{-- <td>{{ number_format($provider->wallet_balance, 2, '.', ',')}}</td> --}}

                                    <td>
                                        @if($provider->pending_documents() > 0 || $provider->service == null)
                                            <a class="btn btn-danger btn-block label-right"
                                            href="{{route('admin.provider.show', $provider->id )}}">@lang('admin.Attention')
                                                <span class="btn-label">{{ $provider->pending_documents() }}</span></a>

                                        @else
                                            <a class="btn btn-primary btn-block"
                                            href="{{route('admin.provider.show', $provider->id )}}">@lang('admin.All_Set')</a>
                                            @if($provider->register_from == 'web')
                                                <label class="btn btn-block btn-warning">@lang('admin.web')</label>
                                            @elseif($provider->register_from == 'andriod')
                                                <label class="btn btn-block btn-success">@lang('admin.andriod')</label>
                                            @else
                                                <p>Unknown</p>
                                            @endif
                                        @endif
                                    </td>

                                    {{-- <td>alo</td> --}}

                                    <td>
                                        @if($provider->service)
                                            @if($provider->service->status == 'active')
                                                <label class="btn btn-block btn-primary">@lang('admin.Yes')</label>


                                            @else
                                                <label class="btn btn-block btn-warning">@lang('admin.No')</label>

                                            @endif
                                        @else
                                            <label class="btn btn-block btn-danger">@lang('admin.N_A')</label>
                                        @endif
                                    </td>

                                    @if(auth()->user()->can('تعديل السائقين') || auth()->user()->can('حذف السائقين') || auth()->user()->can('عرض بيان السائق') || auth()->user()->can('عرض الرحلات الخاصه بالسائق')
                                        || auth()->user()->can('الاضافه فى محفظة السائق') || auth()->user()->can('تعطيل حساب السائق') || auth()->user()->can('تفعيل حساب السائق') || auth()->user()->can('حظر حساب السائق'))
                                        <td>
                                            <div class="input-group-btn">
                                                @can('الاضافه فى محفظة السائق')
                                                    <button type="button" class="btn btn-info btn-md Open-Wallet"
                                                            data-toggle="modal" data-id="{{$provider->id}}"
                                                            data-target="#Wallet" style="margin-bottom: 10px">
                                                        @lang('admin.Wallet')
                                                    </button>
                                                @endcan

                                                @if(auth()->user()->can('تعطيل حساب السائق') || auth()->user()->can('تفعيل حساب السائق') || auth()->user()->can('حظر حساب السائق'))
                                                    <div class="dropdown" style="margin-bottom: 10px">
                                                        @if($provider->status == 'approved')

                                                            <button class="btn btn-success btn-block dropdown-toggle"
                                                                    type="button"
                                                                    id="dropdownMenuButton" data-toggle="dropdown"
                                                                    aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                @lang('admin.approved')
                                                            </button>
                                                        @elseif($provider->status == 'onboarding')
                                                            <button class="btn btn-danger btn-block dropdown-toggle"
                                                                    type="button"
                                                                    id="dropdownMenuButton" data-toggle="dropdown"
                                                                    aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                @lang('admin.onboarding')
                                                            </button>
                                                        @elseif($provider->status == 'banned')
                                                            <button class="btn btn-black btn-block dropdown-toggle"
                                                                    type="button"
                                                                    id="dropdownMenuButton" data-toggle="dropdown"
                                                                    aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                @lang('admin.banned')
                                                            </button>
                                                        @endif
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            @if($provider->status == 'approved')
                                                                @can('تعطيل حساب السائق')
                                                                    <a class="dropdown-item"
                                                                    href="{{ route('admin.provider.disapprove', $provider->id ) }}">
                                                                        @lang('admin.onboardings')
                                                                    </a>
                                                                @endcan
                                                                @can('حظر حساب السائق')
                                                                    <a class="dropdown-item"
                                                                    href="{{ route('admin.provider.banned', $provider->id ) }}">
                                                                        @lang('admin.banneds')
                                                                    </a>
                                                                @endcan
                                                            @elseif($provider->status == 'onboarding')
                                                                @can('تفعيل حساب السائق')
                                                                    <a class="dropdown-item"
                                                                    href="{{ route('admin.provider.approve', $provider->id ) }}">
                                                                        @lang('admin.approveds')
                                                                    </a>
                                                                @endcan
                                                                @can('حظر حساب السائق')
                                                                    <a class="dropdown-item"
                                                                    href="{{ route('admin.provider.banned', $provider->id ) }}">
                                                                        @lang('admin.banneds')
                                                                    </a>
                                                                @endcan
                                                            @elseif($provider->status == 'banned')
                                                                @can('تفعيل حساب السائق')
                                                                    <a class="dropdown-item"
                                                                    href="{{ route('admin.provider.approve', $provider->id ) }}">
                                                                        @lang('admin.approveds')
                                                                    </a>
                                                                @endcan
                                                                @can('تعطيل حساب السائق')
                                                                    <a class="dropdown-item"
                                                                    href="{{ route('admin.provider.disapprove', $provider->id ) }}">
                                                                        @lang('admin.onboardings')
                                                                    </a>
                                                                @endcan
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                <button type="button"
                                                        class="btn btn-info btn-block dropdown-toggle"
                                                        data-toggle="dropdown">@lang('admin.Action')
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @can('عرض الرحلات الخاصه بالسائق')
                                                        <li>
                                                            <a href="{{ route('admin.provider.request', $provider->id) }}"
                                                            class="btn btn-default"><i
                                                                        class="fa fa-search"></i> @lang('admin.Trips Provider')
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('عرض بيان السائق')
                                                        <li>
                                                            <a href="{{ route('admin.provider.statement', $provider->id) }}"
                                                            class="btn btn-default"><i
                                                                        class="fa fa-account"></i> @lang('admin.Statements')</a>
                                                        </li>
                                                    @endcan
                                                    @can('تعديل السائقين')
                                                        <li>
                                                            <a href="{{ route('admin.provider.edit', $provider->id) }}"
                                                            class="btn btn-default"><i
                                                                        class="fa fa-pencil"></i> @lang('admin.Edit')
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('حذف السائقين')
                                                        <li>
                                                            <form action="{{ route('admin.provider.destroy', $provider->id) }}"
                                                                method="POST">
                                                                {{ csrf_field() }}
                                                                <input type="hidden" name="_method" value="DELETE">
                                                                <button class="btn btn-default look-a-like"
                                                                        onclick="return confirm('Are you sure?')"><i
                                                                            class="fa fa-trash"></i> @lang('admin.Delete')
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </div>

                                        </td>
                                    @endif
                                    </tr>

                                </div>
                            </div>

                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.Full_Name')</th>
                        <th>@lang('admin.Email')</th>
                        <th>@lang('admin.Mobile')</th>

                        {{-- <th>@lang('admin.Company Name')</th> --}}
                        <th>@lang('admin.Total_Requests')</th>
                        {{-- <th>@lang('admin.Accepted_Requests')</th>
                        <th>@lang('admin.Cancelled_Requests_User')</th>
                        <th>@lang('admin.Cancelled_Requests')</th> --}}

                        {{-- <th>@lang('admin.Send')</th>
                        <th>@lang('admin.Wallet_Amount')</th>
                        <th>@lang('admin.Documents_Service_Type')</th> --}}

                        <th>@lang('admin.h_Amount')</th>
                        <th>@lang('admin.Cars')</th>
                        <th>@lang('admin.Documents_Service_Type')</th>
                        {{-- <th>alo</th> --}}
                        <th>@lang('admin.Online')</th>
                        @if(auth()->user()->can('تعديل السائقين') || auth()->user()->can('حذف السائقين') || auth()->user()->can('عرض بيان السائق') || auth()->user()->can('عرض الرحلات الخاصه بالسائق')
                                || auth()->user()->can('الاضافه فى محفظة السائق') || auth()->user()->can('تعطيل حساب السائق') || auth()->user()->can('تفعيل حساب السائق') || auth()->user()->can('حظر حساب السائق'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div id="Wallet" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-horizontal" action="{{route('admin.provider.wallet')}}" method="POST"
                      enctype="multipart/form-data" role="form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">@lang('admin.Wallet')</h4>
                    </div>
                    <div class="modal-body">
                        {{csrf_field()}}
                        <input type="hidden" name="user_id">
                        <div class="form-group row">
                            <label for="cash" class="col-xs-12 col-form-label">@lang('admin.Blanace')</label>
                            <div class="col-xs-10">
                                <input class="form-control" type="number" name="cash" step=".01" id="cash"
                                       placeholder="@lang('admin.Blanace')">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
                        <button type="submit" class="btn btn-success">@lang('admin.Update_Wallet')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('body').on('click', '.Open-Wallet', function () {
            var first_name = $(this).closest('tr').find('td').eq(1).text();
            $('#Wallet .modal-header h4').text(' ' + first_name + '@lang('admin.Add Or Remove Balanace For')');

            $('#Wallet').find('input[name="user_id"]').val($(this).data('id'));
            $('#Wallet').find('input[name="wallet_balance"]').val($(this).data('wallet'));
            // alert($(this).data('id'));
        })
    </script>
@endsection
