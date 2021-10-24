@extends('admin.layout.base')

@section('title', 'Users ')

@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">
            @include('flash::message')
            <div class="box box-block bg-white">
                <h5 class="mb-1">
                    @lang('admin.Users')
                </h5>



                @can('اضافه المستخدمين')
                    <a href="{{ route('admin.user.create') }}" style="margin-left: 1em;"
                       class="btn btn-primary pull-right"><i class="fa fa-plus"></i> @lang('admin.Add_New_User') </a>
                @endcan
                <table class="table table-sm table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.First_Name')</th>
                        <th>@lang('admin.Last_Name')</th>
                        <th>@lang('admin.Email')</th>
                        <th>@lang('admin.Mobile')</th>
                        <th>@lang('admin.Rating')</th>
                        <th>@lang('admin.Wallet_Amount')</th>
                        <th>@lang('admin.h_Amount')</th>
                        <th>@lang('admin.register_from')</th>
                        @if(auth()->user()->can('تعديل المستخدمين') || auth()->user()->can('حذف المستخدمين'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $index => $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ base64_decode($user->first_name) }}</td>
                            <td>{{ base64_decode($user->last_name) }}</td>
                            @if(Setting::get('demo_mode', 0) == 1)
                                <td>{{ substr($user->email, 0, 3).'****'.substr($user->email, strpos($user->email, "@")) }}</td>
                            @else
                                <td>{{ $user->email }}</td>
                            @endif
                            @if(Setting::get('demo_mode', 0) == 1)
                                <td>+919876543210</td>
                            @else
                                <td>{{ $user->mobile }}</td>
                            @endif
                            <td>{{ $user->rating }}</td>
                            <td>{{ number_format($user->wallet_balance, 2, '.', ',')}}</td>
                            <td>{{ $user->created_at}}</td>
                            <td>
                                @if($user->register_from == 'web')
                                    <label class="btn btn-block btn-warning">Web</label>
                                @elseif($user->register_from == 'andriod')
                                    <label class="btn btn-block btn-success">Andriod</label>
                                @else
                                    <p>Unknown</p>
                                @endif
                            </td>
                            @if(auth()->user()->can('تعديل المستخدمين') || auth()->user()->can('حذف المستخدمين') || auth()->user()->can('محفظه المستخدم') || auth()->user()->can('عرض رحلات المستخدم'))
                                <td>
                                    <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="DELETE">
                                        @can('عرض رحلات المستخدم')
                                            <a href="{{ route('admin.user.request', $user->id) }}" class="btn btn-info"><i
                                                        class="fa fa-search"></i>@lang('admin.History')</a>
                                        @endcan
                                        @can('محفظه المستخدم')
                                            <button type="button" class="btn btn-info btn-md Open-Wallet"
                                                    data-toggle="modal" data-id="{{$user->id}}"
                                                    data-target="#Wallet">@lang('admin.Wallet')</button>
                                        @endcan
                                        @can('تعديل المستخدمين')
                                            <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-info"><i
                                                        class="fa fa-pencil"></i>@lang('admin.Edit')</a>
                                        @endcan
                                        @can('حذف المستخدمين')
                                            <button class="btn btn-danger" onclick="return confirm('Are you sure?')"><i
                                                        class="fa fa-trash"></i>@lang('admin.Delete')</button>
                                        @endcan
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.First_Name')</th>
                        <th>@lang('admin.Last_Name')</th>
                        <th>@lang('admin.Email')</th>
                        <th>@lang('admin.Mobile')</th>
                        <th>@lang('admin.Rating')</th>
                        <th>@lang('admin.Wallet_Amount')</th>
                        <th>@lang('admin.h_Amount')</th>
                        <th>@lang('admin.register_from')</th>
                        @if(auth()->user()->can('تعديل المستخدمين') || auth()->user()->can('حذف المستخدمين'))
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

            <!-- Modal content-->
            <div class="modal-content">
                <form class="form-horizontal" action="{{route('admin.user.wallet')}}" method="POST"
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
            var last_name = $(this).closest('tr').find('td').eq(2).text();
            $('#Wallet .modal-header h4').text(last_name + ' ' + first_name + '@lang('admin.Add Or Remove Balanace For')');

            $('#Wallet').find('input[name="user_id"]').val($(this).data('id'));
            $('#Wallet').find('input[name="wallet_balance"]').val($(this).data('wallet'));
            // alert($(this).data('id'));
        })
    </script>
@endsection
