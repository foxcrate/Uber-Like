@extends('admin.layout.base')

@section('title', $page)

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
            	<h3>{{$page}}</h3>

            	<div style="text-align: center;padding: 20px;color: blue;font-size: 24px;">
            		<p><strong>
            			<span>@lang('admin.Over_All_Earning'){{currency($revenue[0]->overall)}}</span>
            			<br>
            			<span>@lang('admin.Over_All_Commission'){{currency($revenue[0]->commission)}}</span>
            		</strong></p>
            	</div>

            	<div class="row">

	            	<div class="col-lg-4 col-md-6 col-xs-12">
						<div class="box box-block bg-white tile tile-1 mb-2">
							<div class="t-icon right"><span class="bg-danger"></span><i class="ti-rocket"></i></div>
							<div class="t-content">
								<h6 class="text-uppercase mb-1">@lang('admin.Total_No_of_Rides')</h6>
								<h1 class="mb-1">{{$rides->count()}}</h1>
								<span class="text-muted font-90">@lang('admin.down_from_cancelled_Request')</span>
							</div>
						</div>
					</div>


					<div class="col-lg-4 col-md-6 col-xs-12">
						<div class="box box-block bg-white tile tile-1 mb-2">
							<div class="t-icon right"><span class="bg-success"></span><i class="ti-bar-chart"></i></div>
							<div class="t-content">
								<h6 class="text-uppercase mb-1">@lang('admin.Revenue')</h6>
								<h1 class="mb-1">{{currency($revenue[0]->overall)}}</h1>
								<i class="fa fa-caret-up text-success mr-0-5"></i><span>@lang('admin.from') {{$rides->count()}} @lang('admin.Rides')</span>
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-6 col-xs-12">
						<div class="box box-block bg-white tile tile-1 mb-2">
							<div class="t-icon right"><span class="bg-warning"></span><i class="ti-archive"></i></div>
							<div class="t-content">
								<h6 class="text-uppercase mb-1">@lang('admin.Cancelled_Rides')</h6>
								<h1 class="mb-1">{{$cancel_rides}}</h1>
								<i class="fa fa-caret-down text-danger mr-0-5"></i><span>@lang('admin.for') @if($cancel_rides == 0) 0.00 @else {{round($cancel_rides/$rides->count(),2)}}% @endif @lang('admin.Rides')</span>
							</div>
						</div>
					</div>

						<div class="row row-md mb-2" style="padding: 15px;">
							<div class="col-md-12">
									<div class="box bg-white">
										<div class="box-block clearfix">
											<h5 class="float-xs-left">@lang('admin.Earnings')</h5>
											<div class="float-xs-right">
											</div>
										</div>

										@if(count($rides) != 0)
								            <table class="table table-striped table-bordered dataTable" id="table-2">
								                <thead>
												<tr>
													<td>@lang('admin.Booking_ID')</td>
													<td>@lang('admin.Picked_up')</td>
													<td>@lang('admin.Dropped')</td>
													<td>@lang('admin.Request_Details')</td>
													<td>@lang('admin.Commission')</td>
													<td>@lang('admin.Dated_on')</td>
													<td>@lang('admin.Status')</td>
													<td>@lang('admin.Earned')</td>
												</tr>
								                
								                </thead>
								                <tbody>
								                <?php $diff = ['-success','-info','-warning','-danger']; ?>
														@foreach($rides as $index => $ride)
															<tr>
																<td>{{$ride->booking_id}}</td>
																<td>
																	@if($ride->s_address != '')
																		{{$ride->s_address}}
																	@else
																		Not Provided
																	@endif
																</td>
																<td>
																	@if($ride->d_address != '')
																		{{$ride->d_address}}
																	@else
																		Not Provided
																	@endif
																</td>
																<td>
																	@if($ride->status != "CANCELLED")
																		<a class="text-primary" href="{{route('admin.requests.show',$ride->id)}}"><span class="underline">@lang('admin.View_Ride_Details')</span></a>
																	@else
																		<span>@lang('admin.No_Details_Found') </span>
																	@endif									
																</td>
																<td>{{currency($ride->payment['commision'])}}</td>
																<td>
																	<span class="text-muted">{{date('d M Y',strtotime($ride->created_at))}}</span>
																</td>
																<td>
																	@if($ride->status == "COMPLETED")
																		<span class="tag tag-success">{{$ride->status}}</span>
																	@elseif($ride->status == "CANCELLED")
																		<span class="tag tag-danger">{{$ride->status}}</span>
																	@else
																		<span class="tag tag-info">{{$ride->status}}</span>
																	@endif
																</td>
																<td>{{currency($ride->payment['fixed'] + $ride->payment['distance'])}}</td>

															</tr>
														@endforeach
															
								                <tfoot>
								                   <tr>
														<td>@lang('admin.Booking_ID')</td>
														<td>@lang('admin.Picked_up')</td>
														<td>@lang('admin.Dropped')</td>
														<td>@lang('admin.Request_Details')</td>
														<td>@lang('admin.Commission')</td>
														<td>@lang('admin.Dated_on')</td>
														<td>@lang('admin.Status')</td>
														<td>@lang('admin.Earned')</td>
													</tr>
								                </tfoot>
								            </table>
								            @else
								            <h6 class="no-result">@lang('admin.No_results_found')</h6>
								            @endif 

									</div>
								</div>

							</div>

            	</div>
            </div>
        </div>
    </div>

@endsection
