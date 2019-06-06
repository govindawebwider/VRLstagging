<section id="top-bar"> 
	<div class="container"> 
		<div class="offcanvas-toggle">
			<i class="fa fa-fw fa-bars"></i>
		</div>
		<div class="cb-grid">
			<div class="cb-block cb-box-30">
				<div class="cb-content">
					<div id="logo"> 
						<a href="/">
							<img class="logo" src="/images/logo.png" alt="VRL logo" width="120" >
						</a> 
					</div>

				</div>			
			</div>			
			<div class="cb-block hidden-phone hidden-mobile  cb-box-70">				
				<div class="cb-content">					
					<nav class="main-menu-wrap">						
						<ul class="menu">							
							<li class="item1 first {{ Request::is('/') ? 'active' : '' }}">
								<a href="/"><span>Home</span></a>
							</li>							
							<li class="{{ Request::is('view-all-artist') ? 'active' : '' }}">
								<a href="{{URL('view-all-artist')}}"><span>Artists</span>
								</a></li> 
<!-- <li class="{{ Request::is('view-all-artist') ? 'active' : '' }}">
	<a href="https://www.videorequestline.com/brianmcknight"><span>Request Video</span></a></li> -->


	@if( Auth::check() ) 
	@if( Auth::user()->type == 'User' || Auth::user()->type == 'Admin')    
	<li class="item4 haschild">
		<a href="{{URL('user_video')}}"><span>My Videos </span></a><i class="fa fa-angle-down arrow_dwn" id="hideshow"></i>
		<ul id="content">
			<li>
				<a href="{{URL('userDashboard')}}">My requested video</a>
			</li>
		</ul>
	</li>                              
	<li class="item4 haschild">
		<a href="javascript:void(0)">
			<span>Hi! {{Auth::user()->user_name}}</span> 
		</a>	<i class="fa fa-angle-down arrow_dwn" id="hideshow1"></i>							
		<ul id="content1">	
			@if(session('current_type') == 'User')								
			<li><a href="{{URL('switch_as_admin')}}">Switch to Admin</a></li>
			@endif	                               
			<li><a href="{{URL('userDashboard')}}">Dashboard</a></li>                                 
			<li><a href="{{URL('profile')}}">Profile</a></li>
			<li><a href="{{URL('user_change_password')}}">Change Password</a></li>
			<!-- <li><a href="{{URL('change_email')}}">change/update email </a></li>  -->                                
			<li><a href="{{ URL::route('getLogout') }}">Signout</a></li>  
		</ul>                                                                      
		@elseif( Auth::user()->type =='Artist')
		<li class="{{ Request::is('view-all-artist') ? 'active' : '' }}">
			<a href="{{URL('view-all-video')}}"><span>Videos</span>
			</a>
		</li>  	
		<li class="item4 haschild"><a href="javascript:void(0)">
			<span>Hi! {{Auth::user()->user_name}}</span> 
			<i class="fa fa-angle-down"></i></a>								
			<ul>									
				<li><a href="{{URL('my_video')}}">My Videos</a></li>	

				@if(session('current_type') == 'Artist')
				<li><a href="{{URL('switch_as_admin')}}">Switch to Admin</a></li>
				@endif		
				
				<li><a href="{{URL('Dashboard')}}">Dashboard</a></li>									
				<li><a href="{{ URL::route('getLogout') }}">Signout</a>  
				</ul>    	
			</li> 									
			@endif								
			@endif															
			
			@if( !Auth::check() ) 	
			<li class="{{ Request::is('view-all-video') ? 'active' : '' }}">
				<a href="{{URL('view-all-video')}}"><span>Videos</span>
				</a></li>					
				<li class="item2 {{--*/ if(Request::is('artist_login') OR Request::is('UserLogin')){echo "active"; }  /*--}}" >
					<a href="{{URL('login')}}">
						<span>Login</span>
					</a>															
				</li>	
				@endif					
				<li class="item3 topsearch last">
					<a href="javascript:void(0)">
						<span><i class="fa fa-search"></i></span>
					</a>
				</li>							
				<div class="search-wrap">								
					<form class="search-form" action="{{URL('search')}}" method="get">
						<input type="text" name="search_query" placeholder="Search">
						<button>Search</button>									
						<i class="fa fa-close"></i>								
					</form>							
				</div>	
			</ul>					
		</nav>				
	</div>			
</div>		
</div>	
</div>
</section>


