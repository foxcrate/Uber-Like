@extends('provider.layout.app')

@section('content')
    <div class="pro-dashboard-head">
        <div class="container">
            <a href="{{url('provider/earnings')}}" class="pro-head-link">@lang('user.Payment Statements')</a>
            <a href="{{url('provider/upcoming')}}" class="pro-head-link active">@lang('user.Upcoming')</a>
            <a href="{{url('provider/new_car')}}" class="pro-head-link">@lang('user.New Car')</a>
            <a href="{{url('provider/trips')}}" class="pro-head-link ">@lang('user.my_trips')</a>

            <!--         <a href="new-provider-patner-invoices.html" class="pro-head-link">Payment Invoices</a>
                     <a href="new-provider-banking.html" class="pro-head-link">Banking</a> -->
        </div>
    </div>

    <div class="pro-dashboard-content">

        <!-- Earning Content -->
        <div class="earning-content gray-bg">
            <div class="container">


                <!-- Earning section -->
                <div class="earning-section earn-main-sec pad20">
                    <!-- Earning section head -->
                    <div class="earning-section-head row no-margin">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 no-left-padding">
                            <h3 class="earning-section-tit">@lang('user.Upcoming Tips')</h3>
                        </div>
                    </div>
                    <!-- End of earning section head -->

                    <!-- Earning-section content -->
                    <div class="tab-content list-content">
                        <div class="list-view pad30 ">

                            <table class="earning-table table table-responsive">
                                <thead>
                                <tr>
                                    <th>@lang('user.Pickup Time')</th>
                                    <th>@lang('user.Vehicle')</th>
                                    <th>@lang('user.Pickup Address')</th>
                                    <th>@lang('user.Status')</th>
                                    <th>@lang('user.Action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $fully_sum = 0; ?>
                                @foreach($fully as $each)
                                    <tr>
                                        <td>{{date('Y D, M d - H:i A',strtotime($each->schedule_at))}}</td>
                                        <td>
                                            @if($each->service_type)
                                                {{$each->service_type->name}}
                                            @endif
                                        </td>
                                        <td>
                                            {{$each->s_address}}
                                        </td>

                                        <td>{{$each->status}}</td>
                                        <td>
                                            <a href="{{route('provider.cancel')}}?id={{$each->id}}"
                                               style="margin-top: 0px;" class="full-primary-btn fare-btn">@lang('user.Cancel')</a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>

                    </div>
                    <!-- End of earning section -->
                </div>
            </div>
            <!-- Endd of earning content -->
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        document.getElementById('set_fully_sum').textContent = "{{currency($fully_sum)}}";
    </script>
@endsection