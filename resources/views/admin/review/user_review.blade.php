@extends('admin.layout.base')

@section('title', 'User Reviews ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            
            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('admin.User Reviews')</h5>
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                        <tr>
                            <th>@lang('admin.ID')</th>
                            <th>@lang('admin.Request ID')</th>
                            <th>@lang('admin.User Name')</th>
                            <th>@lang('admin.Provider Name')</th>
                            <th>@lang('admin.Rating')</th>
                            <th>@lang('admin.Date') & @lang('admin.Time')</th>
                            <th>@lang('admin.Comments')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($Reviews as $index => $review)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$review->request_id}}</td>
                            <td>
                                @if($review->user)
                                    {{ $review->user->first_name }} {{ $review->user->last_name }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($review->provider)
                                    {{ $review->provider->first_name }} {{ $review->provider->last_name }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <div className="rating-outer">
                                    <input type="hidden" value="{{$review->user_rating}}" name="rating" class="rating"/>
                                </div>
                            </td>
                            <td>{{$review->created_at}}</td>
                            <td>{{$review->user_comment}}</td>
                            
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>@lang('admin.ID')</th>
                            <th>@lang('admin.Request ID')</th>
                            <th>@lang('admin.User Name')</th>
                            <th>@lang('admin.Provider Name')</th>
                            <th>@lang('admin.Rating')</th>
                            <th>@lang('admin.Date') & @lang('admin.Time')</th>
                            <th>@lang('admin.Comments')</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </div>
    </div>
@endsection