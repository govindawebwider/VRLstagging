@extends('admin.common.header')

<body class="sticky-header left-side-collapsed"  onload="initMap()">
	<section>
		@extends('admin.common.lsidebar') 


		<!-- main content start-->
		<div class="main-content">
			<!-- header-starts -->
			<div class="header-section">

				<!--toggle button start-->
				<a class="toggle-btn  menu-collapsed"><i class="fa fa-bars"></i></a>
				<!--toggle button end-->

				<!--notification menu start -->
				<div class="menu-right">
					<div class="user-panel-top">  	
						<div class="profile_details_left">
							<ul class="nofitications-dropdown">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-envelope"></i><span class="badge">3</span></a>
									
									<ul class="dropdown-menu">
										<li>
											<div class="notification_header">
												<h3>You have 3 new messages</h3>
											</div>
										</li>
										<li><a href="#">
											<div class="user_img"><img src="images/1.png" alt=""></div>
											<div class="notification_desc">
												<p>Lorem ipsum dolor sit amet</p>
												<p><span>1 hour ago</span></p>
											</div>
											<div class="clearfix"></div>	
										</a></li>
										<li class="odd"><a href="#">
											<div class="user_img"><img src="images/1.png" alt=""></div>
											<div class="notification_desc">
												<p>Lorem ipsum dolor sit amet </p>
												<p><span>1 hour ago</span></p>
											</div>
											<div class="clearfix"></div>	
										</a></li>
										<li><a href="#">
											<div class="user_img"><img src="images/1.png" alt=""></div>
											<div class="notification_desc">
												<p>Lorem ipsum dolor sit amet </p>
												<p><span>1 hour ago</span></p>
											</div>
											<div class="clearfix"></div>	
										</a></li>
										<li>
											<div class="notification_bottom">
												<a href="#">See all messages</a>
											</div> 
										</li>
									</ul>
								</li>
								<li class="login_box" id="loginContainer">
									<div class="search-box">
										<div id="sb-search" class="sb-search">
											<form>
												<input class="sb-search-input" placeholder="Enter your search term..." type="search" id="search">
												<input class="sb-search-submit" type="submit" value="">
												<span class="sb-icon-search"> </span>
											</form>
										</div>
									</div>
									<!-- search-scripts -->
									{!! Html::script('js/classie.js') !!}
									{!! Html::script('js/uisearch.js') !!}
									<script>
										new UISearch( document.getElementById( 'sb-search' ) );
									</script>
									<!-- //search-scripts -->
								</li>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-bell"></i><span class="badge blue">3</span></a>
									<ul class="dropdown-menu">
										<li>
											<div class="notification_header">
												<h3>You have 3 new notification</h3>
											</div>
										</li>
										<li><a href="#">
											<div class="user_img"><img src="images/1.png" alt=""></div>
											<div class="notification_desc">
												<p>Lorem ipsum dolor sit amet</p>
												<p><span>1 hour ago</span></p>
											</div>
											<div class="clearfix"></div>	
										</a></li>
										<li class="odd"><a href="#">
											<div class="user_img"><img src="images/1.png" alt=""></div>
											<div class="notification_desc">
												<p>Lorem ipsum dolor sit amet </p>
												<p><span>1 hour ago</span></p>
											</div>
											<div class="clearfix"></div>	
										</a></li>
										<li><a href="#">
											<div class="user_img"><img src="images/1.png" alt=""></div>
											<div class="notification_desc">
												<p>Lorem ipsum dolor sit amet </p>
												<p><span>1 hour ago</span></p>
											</div>
											<div class="clearfix"></div>	
										</a></li>
										<li>
											<div class="notification_bottom">
												<a href="#">See all notification</a>
											</div> 
										</li>
									</ul>
								</li>	
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-tasks"></i><span class="badge blue1">22</span></a>
									<ul class="dropdown-menu">
										<li>
											<div class="notification_header">
												<h3>You have 8 pending task</h3>
											</div>
										</li>
										<li><a href="#">
											<div class="task-info">
												<span class="task-desc">Database update</span><span class="percentage">40%</span>
												<div class="clearfix"></div>	
											</div>
											<div class="progress progress-striped active">
												<div class="bar yellow" style="width:40%;"></div>
											</div>
										</a></li>
										<li><a href="#">
											<div class="task-info">
												<span class="task-desc">Dashboard done</span><span class="percentage">90%</span>
												<div class="clearfix"></div>	
											</div>

											<div class="progress progress-striped active">
												<div class="bar green" style="width:90%;"></div>
											</div>
										</a></li>
										<li><a href="#">
											<div class="task-info">
												<span class="task-desc">Mobile App</span><span class="percentage">33%</span>
												<div class="clearfix"></div>	
											</div>
											<div class="progress progress-striped active">
												<div class="bar red" style="width: 33%;"></div>
											</div>
										</a></li>
										<li><a href="#">
											<div class="task-info">
												<span class="task-desc">Issues fixed</span><span class="percentage">80%</span>
												<div class="clearfix"></div>	
											</div>
											<div class="progress progress-striped active">
												<div class="bar  blue" style="width: 80%;"></div>
											</div>
										</a></li>
										<li>
											<div class="notification_bottom">
												<a href="#">See all pending task</a>
											</div> 
										</li>
									</ul>
								</li>		   							   		
								<div class="clearfix"></div>	
							</ul>
						</div>
						<div class="profile_details">		
							<ul>
								<li class="dropdown profile_details_drop">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<div class="profile_img">	
											<span style="background:url('{{$baseurl}}{{$artist->profile_path}}') no-repeat center"> </span> 
											<div class="user-name">
												<p>{{ $artist->Name }}<span>Administrator</span></p>
											</div>
											<?php 
						                   // Session::forget('name');
											?>
											<i class="lnr lnr-chevron-down"></i>
											<i class="lnr lnr-chevron-up"></i>
											<div class="clearfix"></div>	
										</div>	
									</a>
									<ul class="dropdown-menu drp-mnu">
										<li> <a href="#"><i class="fa fa-cog"></i> Settings</a> </li> 
										<li> <a href="{{ URL::route('ArtistProfile') }}"><i class="fa fa-user"></i>Profile</a> </li> 
										<li> <a href="{{ URL::route('getLogout') }}"><i class="fa fa-sign-out"></i> Logout</a> </li>
									</ul>
								</li>
								<div class="clearfix"> </div>
							</ul>
						</div>		
						<div class="social_icons">
							<div class="col-md-4 social_icons-left">
								<a href="#" class="yui"><i class="fa fa-facebook i1"></i><span>300<sup>+</sup> Likes</span></a>
							</div>
							<div class="col-md-4 social_icons-left pinterest">
								<a href="#"><i class="fa fa-google-plus i1"></i><span>500<sup>+</sup> Shares</span></a>
							</div>
							<div class="col-md-4 social_icons-left twi">
								<a href="#"><i class="fa fa-twitter i1"></i><span>500<sup>+</sup> Tweets</span></a>
							</div>
							<div class="clearfix"> </div>
						</div>			             	
						<div class="clearfix"></div>
					</div>
				</div>
				<!--notification menu end -->
			</div>
			<!-- //header-ends -->
			<div id="page-wrapper">
				<div class="graphs">
					@if(Session::has('message'))
					<div class="alert alert-success">
						<span class="close" data-dismiss="alert">&times;</span>
						<span class="text-center">  {{Session::get('message') }}</span>
					</div>
					@endif
					
					<div class="xs">
						<div class="col-md-8 inbox_right">
							
							<div class="mailbox-content">
								<div class="mail-toolbar clearfix">
									<div class="float-left">
										<div class="btn btn_1 btn-default mrg5R">
											<i class="fa fa-refresh"> </i>
										</div>
										<div class="dropdown">
											<a href="#" title="" class="btn btn-default" data-toggle="dropdown" aria-expanded="false">
												<i class="fa fa-cog icon_8"></i>
												<i class="fa fa-chevron-down icon_8"></i>
												<div class="ripple-wrapper"></div></a>
												<ul class="dropdown-menu float-right">
													<li>
														<a href="#" title="">
															<i class="fa fa-pencil-square-o icon_9"></i>
															Edit
														</a>
													</li>
													<li>
														<a href="#" title="">
															<i class="fa fa-calendar icon_9"></i>
															Schedule
														</a>
													</li>
													<li>
														<a href="#" title="">
															<i class="fa fa-download icon_9"></i>
															Download
														</a>
													</li>
													<li class="divider"></li>
													<li>
														<a href="#" class="font-red" title="">
															<i class="fa fa-times" icon_9=""></i>
															Delete
														</a>
													</li>
												</ul>
											</div>
											<div class="clearfix"> </div>
										</div>
										<div class="float-right">


											<span class="text-muted m-r-sm">Showing 20 of 346 </span>
											<div class="btn-group m-r-sm mail-hidden-options" style="display: inline-block;">
												<div class="btn-group">
													<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-folder"></i> <span class="caret"></span></a>
													<ul class="dropdown-menu dropdown-menu-right" role="menu">
														<li><a href="#">Social</a></li>
														<li><a href="#">Forums</a></li>
														<li><a href="#">Updates</a></li>
														<li class="divider"></li>
														<li><a href="#">Spam</a></li>
														<li><a href="#">Trash</a></li>
														<li class="divider"></li>
														<li><a href="#">New</a></li>
													</ul>
												</div>
												<div class="btn-group">
													<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-tags"></i> <span class="caret"></span></a>
													<ul class="dropdown-menu dropdown-menu-right" role="menu">
														<li><a href="#">Work</a></li>
														<li><a href="#">Family</a></li>
														<li><a href="#">Social</a></li>
														<li class="divider"></li>
														<li><a href="#">Primary</a></li>
														<li><a href="#">Promotions</a></li>
														<li><a href="#">Forums</a></li>
													</ul>
												</div>
											</div>
											<div class="btn-group">
												<a class="btn btn-default"><i class="fa fa-angle-left"></i></a>
												<a class="btn btn-default"><i class="fa fa-angle-right"></i></a>
											</div>

										</div>
									</div>

									<table class="table table-fhr">
										<form action="/upload_video/" method="post" enctype="multipart/form-data">
											<label for="video_title">Video Title</label>
											<div class="form-group">
												<input type="text" name="video_title" id="video_title" class="form-control">
											</div>
											<div class="form-group">
												<input type="hidden" name="artist_id" id="" class="form-control">
											</div>

											<label for="video_description">Video Description</label>
											<div class="form-group">
												<input type="text" name="video_description" id="video_description" class="form-control">
											</div>

											<label for="video_price">Video price:</label>
											<div class="form-group">
												<input type="number" name="video_price" id="video_price" class="form-control"><span>$</span>
											</div>
											<label for="video">Choose Video: </label>
											<div class="form-group">
												<input type="file" name="video" id="video" class="form-control">
											</div>
										</form>
									</table>
								</div>
							</div>
							<div class="clearfix"> </div>
						</div>

					</div>
				</div>
			</div>
			@extends('admin.common.footer')
		</section>
		{!! Html::script('https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js') !!}
		{!! Html::script('js/jquery.nicescroll.js') !!}
		{!! Html::script('js/scripts.js') !!}
		<!-- Bootstrap Core JavaScript -->
		{!! Html::script('js/bootstrap.min.js') !!}
	</body>
	</html>