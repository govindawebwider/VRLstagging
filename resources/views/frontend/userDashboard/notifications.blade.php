@extends('admin.common.header')


<body class="sticky-header left-side-collapsed"  onload="initMap()">
	<section>
		<!-- main content start-->
		<div class="main-content">
        <div id="left-side-wrap">
				@include('admin.common.lsidebar')
			</div>
			<!-- header-starts -->
			<div class="header-section">
            	<div class="logo">
                	<h1>VRL<span class="tag-line"><span class="txt1">Video</span><span class="txt2">Request</span><span class="txt3">Line</span></span></h1>
                </div>
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
			<div id="page-wrapper" class="notification-wrap">
				<ul>
					@foreach ($notifications as $notification)
					
					<li class="{{ $notification->type }}">
                    	<div class="inner-wrap">
                            <img src="{{$notification->profile_path}}" alt="" height="100" width="100">
                            <h3>{{$notification->Name}}</h3>
                            @foreach(explode(' ', $notification->created_at) as $info) 
    						<span>{{$info}}</span>
  								@endforeach
                            
                        </div>
					</li>

					@endforeach
				</ul>
				
			</div>
		</div>
		@extends('admin.common.footer')
	</section>
	
</body>
</html>