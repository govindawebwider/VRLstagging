@extends('admin.common.header')



<body class="sticky-header left-side-collapsed"  onload="initMap()">



	<section class="main-page-wrapper">		 



		<!-- main content start-->



		<div class="main-content">



			<!-- header-starts -->



			<div id="left-side-wrap">



				@include('admin.common.lsidebar')



			</div>



			<div class="header-section">



            	<div class="logo">



                	<h1>VRL<span class="tag-line"><span class="txt1">Video</span><span class="txt2">Request</span><span class="txt3">Line</span></span></h1>



                </div>



				<div class="menu-right">



                	<div class="notice-bar">



                        <ul>



                            <li><a href="javascript:void(0)"><i class="lnr lnr-alarm"></i><span class="no">10</span></a><a href="{{ URL('notifications')}}"><span class="tooltip">notifications</span></a></li>



                            <li><a href="javascript:void(0)"><i class="lnr lnr-file-empty"></i><span class="no">10</span></a><a href="{{ URL('activite')}}"><span class="tooltip">activite</span></a></li>



                        </ul>



                    </div>



					<div class="user-panel-top">  	



						<div class="profile_details">		



							<div class="profile_img">



                            	<div class="dropdown user-menu">



                                	<span class="dropdown-toggle">



                                        <span class="admin-img" style="background:url(images/1.jpg) no-repeat center"> </span>



                                        <span class="admin-name">{{ $users->user_name }}</span>	



                                        <i class="arrow"></i>



                                    </span>



                                </div>



                                <!--<div class="dropdown-menu">



                                	<ul>



                                    	



                                    </ul>



                                </div>-->								 



							</div>



						</div>		



					</div>



				</div>



			</div>



			<!-- //header-ends -->



			<div id="page-wrapper">



				<div class="graphs main-status-wrap">



					@if(Session::has('message'))



					<div class="alert alert-success">



						<span class="close" data-dismiss="alert">&times;</span>



						<span class="text-center">  {{Session::get('message') }}</span>



					</div>



					@endif



					<h1 class="heading">Dashboard</h1>



					



				</div>



			</div>



		</div>



		@include('admin.common.footer')



	</section>



</body>



</html>