@include('admin.common.header')
<body class="admin video_request">
	<section class="main-page-wrapper"> 
		<div class="main-content">     
			<div id="left-side-wrap"> 
				@include('frontend.artistDashboard.layouts.lsidebar') 
			</div>
			<div class="header-section">
				<div class="top-main-left">
					<a href="{{URL('Dashboard')}}"><span class="logo1 white"><img src="/images/vrl_logo_nav.png" class="img img-responsive"></span> </a>
					<a href="javascript:void(0)" class="toggle menu-toggle"><i class="lnr lnr-menu"></i></a>
				</div>
				<?php //dd($payment);?>
				<div class="menu-right">
					<div class="user-panel-top">
						<div class="profile_details">
							<div class="profile_img"><?php //dd($artist);?>
								<div class="dropdown user-menu"> <span class="dropdown-toggle"> <span class="admin-img">
											<?php
											$fileName = 'images/Artist/'.$artist->profile_path;
											$imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
														->url($fileName);?>
											<img src="{{$imageUrl}}" alt="">
											{{--<img src="{{url('images/Artist/').'/'.$artist->profile_path}}" alt=""> --}}
										</span> <span class="admin-name">{{$artist->Name}}</span><i class="arrow"></i> </span>
									<ul>
										@if(session('current_type') == 'Artist')
										<li><a href="{{URL('switch_as_admin')}}">Switch to Admin</a></li>
										@endif
										<li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{ URL($artist->profile_url)}}"> <i class="icon icon-users"></i> <span>view Profile</span> </a> </li>
										@if($user->admin_link=='yes')

										<li class="{{ Request::is('Switch to Admin') ? 'active' : '' }}"> <a href="{{URL('/login_admin')}}"> <i class="icon icon-users"></i> <span>Switch to Admin</span> </a>

										</li>

										@endif
										<li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{URL('ProfileUpdate')}}"> <i class="icon icon-users"></i> <span>Edit Profile</span> </a> </li>
										<li class="{{ Request::is('change-password') ? 'active' : '' }}"> <a href="{{URL('change-password')}}"> <i class="icon icon-lock"></i> <span>Change Password</span> </a> </li>
										<li class="{{ Request::is('getLogout') ? 'active' : '' }}"> <a href="{{ URL::route('getLogout') }}"> <i class="icon icon-exit"></i> <span>Logout</span> </a> </li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="video_request_wrap">
				<div  class="col-md-12 ">
					<div id="page-wrapper">
						<div class="graphs">
						<div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/payment_dtl">Payment Details</a> </div>            
							@if(Session::has('success'))
							<div class="alert alert-success"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('success') }}</span> </div>
							@endif
			            	<h1 class="heading">Payment Details</h1>
			            	
							<div class="content grid_bottom clearfix">
			              		<div class="col-sm-12 info-grid">
			                		<div class="box table-info">                            
									<div class="mailbox-content dataTables_wrapper"> 
                                                                            <a href="{{route('export-payments')}}" class="pull-right btn btn-success">Export</a>
										<table class="table1 table-fhr1 dataTable table table-responsive table-striped" id="example">
											<thead>
												<tr>
													<tH>Payment Id</tH>
													<tH>Payment Date</tH>
													<tH>User</tH>
													<tH>Video Request id </tH>
													<tH>video Title</tH>
													<tH>Video Price</tH>
													<tH>Payment Status</tH>
													<tH>Refund</tH>
													<tH>System Share Amt.</tH>
													<tH>Artist Share Amt.</tH>
													<tH>Completion Date</tH>
												</tr>
											</thead>
											<tbody>
												@foreach ($payment as $payments)
												<tr>
													<td> 
														{{$payments->id}}
													</td>
													<?php
													$dateTime = Carbon\Carbon::createFromFormat('m/d/Y',
															date('m/d/Y', strtotime($payments->payment_date)), session('timezone'));
													$paymentDate = date('m/d/Y', strtotime($dateTime->toDateString()));
													?>
													<td> 
														{{$paymentDate}}
													</td>
													<td> 
														<?php $users = \App\Profile::find($payments->profile_id); ?>
														{{$payments->Name}}
													</td>
													<td> 
														{{$payments->video_request_id}}
													</td>
													<td>
														{{$payments->Title}}
													</td>
													<td> 
														$ {{$payments->videoPrice}}
													</td>
													<td> 
														{{$payments->status}}
													</td>
													<td> @if($payments->is_refunded==0)
														Not
														@else
														Yes
														@endif
													</td>
													<td> 
														$ {{$payments->system_share}} <!-- ${{$system_sh = ($payments->videoPrice*30)/100}} -->
													</td>
													<td> 
														$ {{$payments->artist_share}} <!-- ${{$artist_sh = ($payments->videoPrice*70)/100}} -->
													</td>
													<?php
													$dateTime = Carbon\Carbon::createFromFormat('m/d/Y',
															date('m/d/Y', strtotime($payments->complitionDate)), session('timezone'));
													$completionDate = date('m/d/Y', strtotime($dateTime->toDateString()));
													?>
													<td>{{$completionDate}}</td>
												</tr>                                            
												@endforeach

											</tbody>
										</table>

									</div>                        
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@include('admin.common.footer') 
	</section>
	<script type="text/javascript">
		$( document ).ready(function() {
			$('#example_paginate').show();
			$( ".dropdown.user-menu" ).click(function() {
				$( '.dropdown.user-menu ul' ).toggle();
			});
		});
	</script>
</body>
</html>
