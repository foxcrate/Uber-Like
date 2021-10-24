@extends('admin.layout.base')

@section('title', 'Scheduled Rides ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('admin.Scheduled Rides')</h5>
                @if(count($requests) != 0)
                    <table class="table table-striped table-bordered dataTable" id="table-2">
                        <thead>
                        <tr>
                            <th>@lang('admin.ID')</th>
                            <th>@lang('admin.Request Id')</th>
                            <th>@lang('admin.User Name')</th>
                            <th>@lang('admin.Provider Name')</th>
                            <th>@lang('admin.Scheduled Date') & @lang('admin.Time')</th>
                            <th>@lang('admin.Status')</th>
                            <th>@lang('admin.Payment Mode')</th>
                            <th>@lang('admin.Payment Status')</th>
                            <th>@lang('admin.Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($requests as $index => $request)
                            <tr>
                                <td>{{$index + 1}}</td>
                                <td>
                                    @if($request->user)
                                        {{$request->user->booking_id}}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($request->user)
                                        {{$request->user->first_name}} {{$request->user->last_name}}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($request->provider)
                                        {{$request->provider->first_name}} {{$request->provider->last_name}}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{$request->schedule_at}}</td>
                                <td>
                                    {{$request->status}}
                                </td>

                                <td>{{$request->payment_mode}}</td>
                                <td>
                                    @if($request->paid)
                                        @lang('admin.Paid')
                                    @else
                                        @lang('admin.Not Paid')
                                    @endif
                                </td>
                                <td>
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-info dropdown-toggle"
                                                data-toggle="dropdown">Action
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{ route('admin.requests.edit', $request->id) }}"
                                                   class="btn btn-default"><i class="fa fa-edit"></i> Edit</a>

                                                <br>
                                                <a href="{{ route('admin.requests.show', $request->id) }}"
                                                   class="btn btn-default"><i class="fa fa-search"></i> More Details</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>@lang('admin.ID')</th>
                            <th>@lang('admin.Request Id')</th>
                            <th>@lang('admin.User Name')</th>
                            <th>@lang('admin.Provider Name')</th>
                            <th>@lang('admin.Scheduled Date') & @lang('admin.Time')</th>
                            <th>@lang('admin.Status')</th>
                            <th>@lang('admin.Payment Mode')</th>
                            <th>@lang('admin.Payment Status')</th>
                            <th>@lang('admin.Action')</th>
                        </tr>
                        </tfoot>
                    </table>
                @else
                    <h6 class="no-result">@lang('admin.No results found')</h6>
                @endif
            </div>
        </div>
    </div>
@endsection