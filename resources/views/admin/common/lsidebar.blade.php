<!-- left side start-->
<div class="left-side sticky-left-side main_admin_menu">	
	<div class="left-side-inner">		
	<!--sidebar nav start-->		
		<ul class="nav nav-pills nav-stacked custom-nav">	
			<li class="{{ Request::is('admin_dashboard') ? 'active' : '' }}">                
			<a href="{{ URL('admin_dashboard') }}"> 
				<i class="icon icon-meter"></i><span>Dashboard </span>
			</a>
			</li>			
			
			<li class="{{ Request::is('change_pass') ? 'active' : ''}}">
			<a href="{{ URL('change_pass') }}">
				<i class="lnr lnr-picture"></i> <span>Change Password</span>
			</a>
			</li>			

			<li class="{{ Request::is('artists') ? 'active' : '' }}">
			<a href="{{ URL('artists') }}">                
				<i class="lnr lnr-picture"></i> <span>Artists</span>
			</a>
			</li>
			
			<li class="{{ Request::is('users') ? 'active' : '' }}">    <a href="{{ URL('users') }}">
				<i class="lnr lnr-picture"></i> <span>Users</span>
			</a>
			</li>	
			
			<li class="{{ Request::is('get_video_requests') ? 'active' : '' }}">
			<a href="{{ URL('get_video_requests') }}">
				<i class="lnr lnr-picture"></i> <span>Video Requests</span>
			</a>
			</li>
			
			<li class="{{ Request::is('reviews') ? 'active' : '' }}">
			<a href="{{ URL('reviews') }}">
				<i class="lnr lnr-picture"></i> <span>Reviews</span>
			</a>
			</li>			

			<li class="{{ Request::is('sliders') ? 'active' : '' }}">
			<a href="{{ URL('sliders') }}">
				<i class="lnr lnr-picture"></i> <span>Sliders</span>
			</a>
			</li>			

			<li class="{{ Request::is('get_payments') ? 'active' : '' }}">
			<a href="{{ URL('get_payments') }}">
				<i class="lnr lnr-picture"></i> <span>Payments</span>
			</a>
			</li>			

			<li class="{{ Request::is('getLogout') ? 'active' : '' }}">
				<a href="{{ URL::route('getLogout') }}">
					<i class="lnr lnr-exit"></i><span>Logout</span>
				</a>
			</li>
		</ul>		<!--sidebar nav end-->	
	</div>
</div>    
<!-- left side end-->