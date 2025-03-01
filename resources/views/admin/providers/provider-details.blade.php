@extends('admin.layout.base')

@section('title', 'Provider Details ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
            	<h4>@lang('admin.Provider_Details')</h4>
                {{-- <h1>alo</h1> --}}
            	<div class="row">
            		<div class="col-md-12">
						<div class="box bg-white user-1">
						<?php $background = asset('admin/assets/img/photos-1/4.jpg'); ?>
							<div class="u-img img-cover" style="background-image: url({{$background}});"></div>
							<div class="u-content">
								<div class="avatar box-64">
									<img class="b-a-radius-circle shadow-white" src="{{img($provider->picture)}}" alt="">
									<i class="status bg-success bottom right"></i>
								</div>
								<p class="text-muted">
									{{-- @if($provider->is_approved == 1) --}}
                                    @if($provider->status == "approved")
										<span class="tag tag-success">@lang('admin.Approved')</span>
									@else
										<span class="tag tag-success">@lang('admin.Not_Approved')</span>
									@endif
								</p>
								<h5><a class="text-black" href="#">{{base64_decode( $provider->first_name) }} {{base64_decode( $provider->last_name) }}</a></h5>
								<p class="text-muted">@lang('admin.Email') : {{$provider->email}}</p>
								<p class="text-muted">@lang('admin.Mobile') : {{$provider->mobile}}</p>
								<p class="text-muted">@lang('admin.Wallet_Amount') : {{$provider->wallet_balance}}</p>
								{{-- <p class="text-muted">@lang('admin.Address') : {{$provider->latitude}} - {{$provider->longitude}}</p> --}}

                                <p class="text-muted">@lang('admin.Accepted_Requests') : {{ \App\UserRequests::where('provider_id',$provider->id)->where('status', 'COMPLETED')->count() }}</p>
                                <p class="text-muted">@lang('admin.Cancelled_Requests_User') :{{ \App\UserRequests::where('provider_id',$provider->id)->where('status', 'CANCELLED')->count() }}</p>
                                <p class="text-muted">@lang('admin.Cancelled_Requests') : {{ \App\ProviderUserRequests::where('provider_id', $provider->id)->count() }}</p>
                                <p class="text-muted">@lang('admin.Company Name') :
                                    @if($provider->fleet == null)
                                        <td>لا توجد</td>
                                    @else
                                        <td>{{ $provider->fleets->name }}</td>
                                    @endif
                                </p>

                                <a class="btn btn-success "
                                    href="{{route('admin.send_message', $provider->id )}}">@lang('admin.Send Message')</a>

								<p class="text-muted">

									{{-- @if($provider->is_activated == 1)
										<span class="tag tag-warning">@lang('admin.Activated')</span>
									@else
										<span class="tag tag-warning">@lang('admin.Not_Activated')</span>
									@endif --}}

                                    @if($provider->service)
                                        @if($provider->service->status == 'active')
                                            <span class="tag tag-primary">@lang('admin.Online')</span>


                                        @else
                                            <span class="tag tag-warning">@lang('admin.Offline')</span>


                                        @endif
                                    @else
                                        <span class="tag tag-danger">@lang('admin.N/A')</span>

                                    @endif

								</p>
							</div>
						</div>
					</div>
            	</div>

            </div>
        </div>
    </div>

@endsection
