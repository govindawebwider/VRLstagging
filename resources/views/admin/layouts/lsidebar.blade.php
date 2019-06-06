<style type="text/css">
  #left-side-wrap .left-side-inner ul.custom-nav > li {
    position: relative;
    margin-top: 10px;
}
#left-side-wrap .left-side-inner ul.custom-nav > li > a{
  padding-left: 0px;
}
</style>
<!-- left side start-->
<div class="left-side sticky-left-side main_admin_menu">
	<div class="left-side-inner">
		<!--sidebar nav start-->
		<ul class="nav nav-pills nav-stacked custom-nav">
			<li class="{{ Request::is('admin_dashboard') ? 'active' : '' }}">
				<a href="{{ URL('admin_dashboard') }}">
				<i class="fa fa-home" aria-hidden="true"></i>
				<span>Dashboard</span>
				</a>
			</li>
     

			<li class="{{ Request::is('pages') ? 'active' : ''}}">
				<a href="#">
				<i class="fa fa-lock" aria-hidden="true"></i><span>Pages</span>
				</a>
				<span class="cbicon-arrow"><i class="icon icon-ctrl"> </i></span>
				  <ul class="sub-child  ">
					<li class="{{ Request::is('add_help') ? 'active' : '' }}">
					  <a href="{{ URL('add_help') }}">
					   <span>Help</span>
					 </a>
				   </li>
				   <li class="{{ Request::is('add_about') ? 'active' : '' }}">
					<a href="{{ URL('add_about') }}">
					 <span>About</span>
				   </a>
				 </li>
				 <li class="{{ Request::is('add_terms') ? 'active' : '' }}">
				  <a href="{{ URL('add_terms') }}">
					<span>Terms</span>
				  </a>
				</li>
				<li class="{{ Request::is('add_privacy') ? 'active' : '' }}">
				  <a href="{{ URL('add_privacy') }}">
				   <span>Privacy</span>
				 </a>
			   </li>
			 </ul>
			</li>

			<!-- category link added by sandeep -->
			<li class="{{ Request::is('category') ? 'active' : ''}}">
			  <a href="#">
				<i class="fa fa-lock" aria-hidden="true"></i><span>Category</span>
			  </a><span class="cbicon-arrow"> <i class="icon icon-ctrl"></i></span>
			  <ul class="sub-child  ">
				<li class="{{ Request::is('create_category') ? 'active' : '' }}">
				  <a href="{{ URL('create_category') }}">
				   <span>Add Category</span>
				  </a>
				</li>
				<li class="{{ Request::is('categories') ? 'active' : '' }}">
				  <a href="{{ URL('categories') }}">
					<span>View Category</span>
				  </a>
				</li>
			  </ul>
			</li>

			<li class="{{ Request::is('add_artist') ? 'active' : '' }}">
			  <a href="#">
				<i class="fa fa-lock" aria-hidden="true"></i><span>Artist</span>
			  </a><span class="cbicon-arrow"> <i class="icon icon-ctrl"></i></span>
			  <ul class="sub-child">
				<li class="{{ Request::is('artists') ? 'active' : '' }}">
				  <a href="{{ URL('artists') }}">
				   <span>View Artists</span>
				 </a>
			   </li>
			   <li class="{{ Request::is('create_artist') ? 'active' : '' }}">
				<a href="{{ URL('create_artist') }}">
				  <span>Create Artist</span>
				</a>
			  </li>
			  <li class="{{ Request::is('invite_artist') ? 'active' : '' }}">
				<a href="{{ URL('invite_artist') }}">
				  <span>Invite Artist</span>
				</a>
			  </li>
			   <li class="{{ Request::is('joinus-artist') ? 'active' : '' }}">
				<a href="{{ URL('joinus-artist') }}">
				  <span>Join Request</span>
				</a>
			  </li>
			</ul>
			</li>
			
			<li class="{{ Request::is('users') ? 'active' : '' }}">
			  <a href="{{ URL('users') }}">
			   <i class="icon icon-user"></i> <span>Users</span>
			 </a>
			</li>
			
			<li class="{{ Request::is('videos') ? 'active' : '' }}">
			  <a href="{{ URL('videos') }}">
			   <i class="fa fa-video-camera" aria-hidden="true"></i><span>Sample Videos</span>
			 </a>
			</li>
			
			<li class="{{ Request::is('get_video_requests') ? 'active' : '' }}">
			  <a href="{{ URL('get_video_requests') }}">
			   <i class="fa fa-video-camera" aria-hidden="true"></i><span>Video Requests</span>
			 </a>
			</li>
			
			<li class="{{ Request::is('ReactionVideo') ? 'active' : '' }}">
			  <a href="#">
				<i class="fa fa-lock" aria-hidden="true"></i><span>Reaction Videos</span>
			  </a><span class="cbicon-arrow"> <i class="icon icon-ctrl"></i></span>
			  <ul class="sub-child">
				<li class="{{ Request::is('getReactionVideoRequests') ? 'active' : '' }}">
				  <a href="{{ URL('getReactionVideoRequests') }}">
					<span>Reaction Video Requests</span>
				  </a>
				</li>
				<li class="{{ Request::is('SocialMediaVideos') ? 'active' : '' }}}">
				  <a href="{{ URL('SocialMediaVideos') }}">
				   <span>Social Media Videos</span>
				 </a>
			   </li>
			 </ul>
			</li>
			
			<li class="{{ Request::is('report') ? 'active' : '' }}">
			  <a href="{{ URL('report') }}">
			   <i class="fa fa-flag" aria-hidden="true"></i><span>Report</span>
			 </a>
			</li>
			
			<li class="{{ Request::is('review') ? 'active' : '' }}">
			  <a href="#">
				<i class="fa fa-lock" aria-hidden="true"></i><span>Reviews</span>
			  </a><span class="cbicon-arrow"> <i class="icon icon-ctrl"></i></span>
			  <ul class="sub-child">
				<li class="{{ Request::is('reviews') ? 'active' : '' }}">
				  <a href="{{ URL('reviews') }}">
					<span>View Reviews</span>
				  </a>
				</li>
				<li class="{{ Request::is('add_admin_review') ? 'active' : '' }}">
				  <a href="{{ URL('add_admin_review') }}">
				   <span>Add Review</span>
				 </a>
			   </li>
			 </ul>
			</li>
			
			<li style="display:none" class="{{ Request::is('slider') ? 'active' : '' }}">
			  <a href="#">
				<i class="fa fa-lock" aria-hidden="true"></i><span>Sliders</span>
			  </a><span class="cbicon-arrow"> <i class="icon icon-ctrl"></i></span>
			  <ul class="sub-child">
				<li class="{{ Request::is('sliders') ? 'active' : '' }}">
				  <a href="{{ URL('sliders') }}">
					<span>View Sliders</span>
				  </a>
				</li>
				<li class="{{ Request::is('add_slider') ? 'active' : '' }}">
				  <a href="{{ URL('add_admin_slider') }}">
					<span>Add Slider</span>
				  </a>
				</li>
			  </ul>
			</li>
			
			<li class="{{ Request::is('payments') ? 'active' : '' }}">
			  <a href="#">
				<i class="fa fa-lock" aria-hidden="true"></i><span>Payments</span>
			  </a><span class="cbicon-arrow"> <i class="icon icon-ctrl"></i></span>
			  <ul class="sub-child">
				<li class="{{ Request::is('get_payments') ? 'active' : '' }}">
				  <a href="{{ URL('artist_payments') }}">
					<i class="icon icon-coin-dollar"></i> <span>Artist Payments</span>
				  </a>
				</li>
				<li class="{{ Request::is('get_payments') ? 'active' : '' }}">
				  <a href="{{ URL('get_payments') }}">
				   <i class="icon icon-coin-dollar"></i> <span>User Payments</span>
				 </a>
			   </li>
			  <li class="{{ Request::is('share-percentage') ? 'active' : '' }}">
				  <a href="{{ URL('share-percentage') }}">
					  <i class="icon icon-coin-dollar"></i> <span>Share Percentage</span>
				  </a>
			  </li>
			 </ul>
			</li>
			
			<li class="{{ Request::is('settings') ? 'active' : '' }}">
			  <a href="#">
				<i class="fa fa-lock" aria-hidden="true"></i><span>Settings</span>
			  </a><span class="cbicon-arrow"> <i class="icon icon-ctrl"></i></span>
			  <ul class="sub-child">
				<li class="{{ Request::is('signup') ? 'active' : '' }}">
				  <a href="{{ URL('admin_videoPrice') }}">
					<span>Default Video Price</span>
				  </a>
				</li>
				<li class="{{ Request::is('signup') ? 'active' : '' }}">
				  <a href="{{ URL('signup_setting') }}">
				   <span>Signup Setting</span>
				 </a>
			   </li>
			   <li class="{{ Request::is('threshold') ? 'active' : '' }}">
				<a href="{{ URL('/threshold_setting') }}">
				 <span>Threshold Duration</span>
			   </a>
			 </li>
			 <li class="{{ Request::is('extend_price') ? 'active' : '' }}">
			   <a href="{{ URL('/extend_price') }}">
				 <span>Extend Storage Price</span>
			   </a>
			 </li>
			 <li class="{{ Request::is('video_purge') ? 'active' : '' }}">                
			  <a href="{{ URL('video_purge') }}">                 
				<span>Video Purge</span>                
			  </a>            
			</li>
			<li class="{{ Request::is('purge') ? 'active' : '' }}">
			  <a href="{{ URL('/set_purge') }}">
			   <span>Purge Duration</span>
			 </a>
			</li>
			</ul>
			</li>
			
			<li class="{{ Request::is('getLogout') ? 'active' : '' }}">
			  <a href="{{ URL::route('getLogout') }}">
			   <i class="icon icon-exit"></i><span>Logout</span>
			 </a>
			</li>
		</ul>
<!--sidebar nav end-->
	</div>
</div>
    <!-- left side end-->