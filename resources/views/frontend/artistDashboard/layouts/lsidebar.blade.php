<!-- left side start-->
<div class="left-side sticky-left-side">
	<div class="left-side-inner">
		<ul class="nav nav-pills nav-stacked custom-nav">
			<li class="{{ Request::is('Dashboard') ? 'active1' : '' }}">
				<a href="javascript:void(0)"> <i class="fa fa-home" aria-hidden="true"></i> <span>Requests</span>  </a> <span class="cbicon-arrow"> <i class="icon icon-ctrl"></i></span>
				<ul class="sub-child">
					<li class="{{ Request::is('pending_requests') ? 'active' : '' }}"> 
						<a href="{{URL('pending_requests')}}"> <i class="icon icon-play3"></i>
						<span>1. Pending Requests</span> 
						</a> 
					</li>

				<li class="{{ Request::is('video_requests') ? 'active' : '' }}"> <a href="{{URL('video_requests')}}"> <i class="icon icon-play3"></i> 
						
						<span>2. Pending Fulfillment</span>

						<!-- <span>Requested Videos</span> --> 
					</a> 
				</li>

	<li class="{{ Request::is('deliver_video') ? 'active' : '' }}"> <a href="{{URL('deliver_video')}}"> <i class="icon icon-play3"></i> <span>3. Completed Requests</span> </a> </li>
			
	

			

		</ul>

	</li>


    <li>
		<a href="javascript:void(0)"> <i class="icon icon-cogs"></i> <span>Sample Videos</span> </a><span class="cbicon-arrow"> <i class="icon icon-ctrl"></i></span>
		<ul class="sub-child">
			<li class="{{ Request::is('artist_video') ? 'active' : '' }}"> <a href="{{URL('artist_video')}}"> <i class="icon icon-film"></i> <span>View Sample Videos</span> </a> </li>
			<li class="{{ Request::is('record_video') ? 'active' : '' }}"> <a href="{{URL('record_video')}}"> <i class="icon icon-film"></i> <span>Upload Sample Video</span> </a> </li>
        </ul>
    </li> 


<li class="{{ Request::is('Dashboard') ? 'active2' : '' }}">
<a href="{{URL('payment_dtl')}}"> <i class="icon icon-play"></i> <span>Payment Details</span> </a>
</li>

<!-- <li class="{{ Request::is('ProfileUpdate') ? 'active2' : '' }}">
<a href="{{URL('ProfileUpdate')}}"><i class="fa fa-file-text"></i> <span>Settings</span> </a>
</li> -->


	<li >

		<a href="javascript:void(0)"> <i class="icon icon-cogs"></i> <span>Settings</span> </a><span class="cbicon-arrow"> <i class="icon icon-ctrl"></i></span>

		<ul class="sub-child">

			<li class="{{ Request::is('edit_url') ? 'active' : '' }}"> <a href="{{URL('edit_url')}}"> <i class="icon icon-link"></i> <span>My URL</span> </a> </li>

			<li class="{{ Request::is('addPrice') ? 'active' : '' }}"> <a href="{{URL('addPrice')}}"> <i class="icon icon-play"></i> <span>Video Request Price</span> </a> </li>

			<li class="{{ Request::is('background_img') ? 'active' : '' }}"> <a href="{{URL('background_img')}}"> <i class="icon icon-camera"></i> <span>Background Image</span> </a> </li>
			<li class="{{ Request::is('artist_header_img') ? 'active' : '' }}"> <a href="{{URL('artist_header_img')}}"> <i class="icon icon-images"></i> <span>Header Image</span> </a> </li>

			<li class="{{ Request::is('upload_video_background') ? 'active' : '' }}"> <a href="{{URL('upload_video_background')}}"> <i class="icon icon-video-camera"></i> <span>Video Background</span> </a> </li>

			<li class="{{ Request::is('my_slider') ? 'active' : '' }}"> <a href="{{ URL('my_slider') }}"> <i class="icon icon-equalizer"></i> <span>Homepage Slider</span> </a> </li>
			<!-- <li class="{{ Request::is('media') ? 'active' : '' }}"> <a href="media"> <i class="icon icon-cogs"></i> <span>Custom CSS</span> </a> </li> -->

			<li class="{{ Request::is('turnaround_time') ? 'active' : '' }}"> <a href="{{URL('turnaround_time')}}"> <i class="icon icon-clock"></i> <span>Fulfillment Duration</span> </a> </li>

			<li class="{{ Request::is('get_social_link') ? 'active' : '' }}"> <a href="{{ URL('get_social_link') }}"> <i class="icon icon-share2"></i> <span>Social Links</span> </a> </li>



			<li class="{{ Request::is('view_review') ? 'active' : '' }}"><a href="{{ URL('view_review') }}"> <i class="icon icon-equalizer"></i> <span>Reviews</span> </a></li>

			<li class="{{ Request::is('bank_details') ? 'active' : '' }}"> <a href="{{URL('bank_details')}}"> <i class="icon icon-link"></i> <span>Bank Details</span> </a> </li>
			<!-- <li class="{{ Request::is('occasions') ? 'active' : '' }}"> <a href="{{URL('occasions/index')}}"> <i class="fa fa-gift"></i> <span>{{ \Lang::get('views.occasion')}}</span> </a> </li> -->





		</ul>

	</li>         

</ul>

</div>

</div>

<!-- left side end-->
