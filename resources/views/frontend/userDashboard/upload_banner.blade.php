@extends('admin.common.header')

<body class="sticky-header left-side-collapsed"  onload="initMap()">
	<section>



		<!-- main content start-->
		<div class="main-content">

			<div id="left-side-wrap">
				@include('frontend.userDashboard.layouts.lsidebar')
			</div>
			<!-- header-starts -->
			<div class="header-section">
				<div class="logo">
					<h1>VRL<span class="tag-line"><span class="txt1">Video</span><span class="txt2">Request</span><span class="txt3">Line</span></span></h1>
				</div>
				<!--notification menu start -->
				<div class="menu-right">
					<div class="notice-bar">
						<ul>
							<li><a href="{{ URL('notifications')}}"><i class="lnr lnr-alarm"></i><span class="no">10</span></a><span class="tooltip">notifications</span></li>
							<li><a href="{{ URL('activite')}}"><i class="lnr lnr-file-empty"></i><span class="no">10</span></a><span class="tooltip">activite</span></li>
						</ul>
					</div>
					<div class="user-panel-top">  	
						
						<div class="profile_details">		
							<div class="profile_img">

								<div class="dropdown user-menu">
									<span class="dropdown-toggle">
										<span class="admin-img" style="background:url(images/1.jpg) no-repeat center"> </span>
										<span class="admin-name">{{ $user->Name }}</span>	
										<i class="arrow"></i>
									</span>
								</div>								 
							</div>
						</div>		
						
					</div>
				</div>
				<!--notification menu end -->
			</div>
			<!-- //header-ends -->
			<div id="page-wrapper" class="header-img-wrap">
				<div class="graphs">
					@if(Session::has('message'))
					<div class="alert alert-success">
						<span class="close" data-dismiss="alert">&times;</span>
						<span class="text-center">  {{Session::get('message') }}</span>
					</div>
					@endif
					
					<div class="xs">
						<div class="col-md-8 inbox_left">	
							<h1 class="heading">Choose Header Image</h1>							
							<div class="mailbox-content">
								<form action="upload_banner" method="post" enctype="multipart/form-data">
									{!! csrf_field () !!}
									
									
									<div class="form-group">
										<input type="file" name="header" id="header" class="form-control">
									</div>
									<div class="form-group">
										<input type="submit" value="upload" class="btn btn-primary">
									</div>
								</form>
							</div>
						</div>
						<div class="col-md-4 inbox_right">
							<img src="{{$user->BannerImg}}" alt="">
						</div>
						<div class="clearfix"> </div>
					</div>

				</div>
			</div>
		</div>
		@extends('admin.common.footer')
	</section>
	
</body>
</html>