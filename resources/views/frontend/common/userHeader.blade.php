<section class="header user_header">	
	<div class="">
		<div class="row">
			<nav class="navbar">
			  <div class="">
			    <!-- Brand and toggle get grouped for better mobile display -->
			    <div class="navbar-header">

				  <a class="navbar-brand" href="{{URL('/')}}"><img class="logo" src="/images/vrl_logo_nav.png" alt="VRL Logo"></a>
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
							data-target="#bs-example-navbar-collapse-2" aria-expanded="false">
						<i class="fa fa-bars fa-3x color-white" aria-hidden="true"></i>
						<span class="navbar-toggler-icon"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
			    <!-- Collect the nav links, forms, and other content for toggling -->
			    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					@if( Auth::check() )
					<ul class="navbar-nav mr-auto notranslate">
					<li class="nav-item active">
						<a class="nav-link" href="/">Home</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/view-all-artist">Artists</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">My Videos</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="/user_video">My requested video</a>
						</div>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Hi! {{ucfirst(Auth::user()->user_name)}}</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							@if ((Auth::user()->type=="Admin" || session('current_type') == "User"))
							<a class="dropdown-item" href="/switch_as_admin">Switch to Admin</a>
					        @endif
							<a class="dropdown-item" href="/user_dashboard">Dashboard</a>
							<a class="dropdown-item" href="/profile">Profile</a>
							<a class="dropdown-item" href="/user_change_password">Change Password</a>
							<a class="dropdown-item" href="/getLogout">Signout</a>
						</div>
					</li>
					</ul>
					@endif
					{{--<form class="form-inline my-2 my-lg-0">
						<div class="search-box"><input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search"></div>
						<button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fa fa-search"></i></button>
					</form>--}}

     				 <ul class="nav navbar-nav navbar-right pull-right">

			      	@if( !Auth::check() ) 	

			        <li class="item2 {{--*/ if(Request::is('artist_login') OR Request::is('UserLogin')){echo "active"; }  /*--}}" ><a class="logintext  navbar-btn purple-text" data-toggle="modal" data-target="#exampleModalCenter" id="login"><span>Login</span></a></li>	
			        <li><a data-toggle="modal" data-target="#exampleModalCenterJOIN" class="btn loginshadow navbar-btn joinus">Join Us</a></li>

			        @endif	
			        </ul>
			    </div><!-- /.navbar-collapse -->
				  <div class=" hidden-md hidden-lg notranslate">
					  <div class="collapse navbar-collaps" id="bs-example-navbar-collapse-2">
						  <div class="right-side sticky-right-side" id="right-side-wrap" >
							  <div class="left-side-inner">
								  <ul class="nav  mr-auto head-main">
									  <li class="{{ Request::is('/') ? 'active1' : '' }}">
										  <a href="/" class="right-menu navbar-btn purple-text">
											  <span>Home</span>
										  </a>
									  </li>
									  <li class="nav-hover">
										  <a class="right-menu navbar-btn purple-text" href="/view-all-artist">
											  <span>Artists</span></a>
									  </li>
									  <li class="nav-hover">
										  <a class="right-menu nav-link navbar-btn purple-text dropdown-toggle" href="#" id="navbarDropdown"
											 role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">My Videos</a>
										  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
											  <a class="dropdown-item" href="/user_video">
												  <span>My requested video</span>
											  </a>
										  </div>
									  </li>
									  <li class="nav-hover nav-item dropdown">
										  <a class="right-menu nav-link navbar-btn purple-text dropdown-toggle" href="#"
											 id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
											 aria-expanded="false">Hi! {{ Auth::user()->user_name }}</a>
										  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
											  @if ((Auth::user()->type=="Admin" || session('current_type') == "User"))
												  <a class="dropdown-item" href="/switch_as_admin">Switch to Admin</a>
											  @endif
											  <a class="dropdown-item" href="/user_dashboard">Dashboard</a>
											  <a class="dropdown-item" href="/profile">Profile</a>
											  <a class="dropdown-item" href="/user_change_password">Change Password</a>
											  <a class="dropdown-item" href="/getLogout">Signout</a>
										  </div>
									  </li>

								  </ul>

							  </div>

						  </div>
					  </div>
				  </div>

			  </div><!-- /.container-fluid -->
			</nav>

	


		</div>
	</div>
</section>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content login-modal">
            <div class="modal-body">
	            <div class="row">
	                <div class="col-xs-12">
	                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img class="img-responsive center-block" src="/images/Popup_close.png" alt="VRL close">
                        </button>
	                </div>
	            </div>
	            <div class="row" id="image">
		            <div class="col-sm-12">
		                <img class="img-responsive center-block" src="/images/vrl_logo_login.png" alt="VRL Logo" id="logo-img"></a>
		            </div>
	            </div>
	            <div class="row-login-account">
		            <div class="col-sm-12">
                        <h3 class="text-center yourAccount"><span class=""> Login to your account </span></h3>
                    </div>
	            </div>
	            <div class="row">
	                <div class="login">
	                    @if(Session::has('login_error'))
				            <div class="col-sm-12">
				            <div class="alert alert-danger"> 
									{{Session::pull('login_error','default') }} 
								<button type="button" class="close"  data-dismiss="alert" aria-label="close">
									<span aria-hidden="true">&times;</span>
								</button>	
								</div>
						 </div>
		                @endif
						@if(Session::has('success'))
						 <div class="col-sm-12">
				            <div class="alert alert-primary"> {{Session::pull('success','default') }}  
							<button type="button" class="close"  data-dismiss="alert" aria-label="close">
									<span aria-hidden="true">&times;</span>
								</button>	
							</div>
					    </div>
			            @endif

			            {!! Form::open(array('method'=>'post','url' =>'login','id'=>'loginfrm')) !!}
			            <div class="login-body">
				            <div class="row">
				                <div class="col-sm-1"></div>
				                <div class="col-sm-10 paddingtop">
				                    <form action="">
									    <div class="field">
										    <!-- {!!Form::email('email',null,array('id'=>'email','placeholder' => 'tony.stark@avengers.com'))!!} -->
										    <input type="text" placeholder="tony.stark@avengers.com"
										    name="email" id="email"
										    onfocus="this.placeholder = ''" 
                                            onblur="this.placeholder = 'tony.stark@avengers.com'" />
										    <label for="fullname" id="login-text">Username/Email</label>
										</div>
										<?php if (isset($errors)) { ?>
										 @if($errors->first('email'))
										
										<div class="error"> 
							                    <p class="error-danger" > {{ $errors->first('email') }} </p>
										   </div>
										 @endif
										
									    <div class="field">
										       <!--  {!! Form::password('password',array('id'=>'password','placeholder' => '&#9679;&#9679;&#9679;&#9679;&#9679;'))!!} -->

									        <input type="password" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;"
									        name="password" id="password"
									        onfocus="this.placeholder = ''" 
                                            onblur="this.placeholder = '&#9679;&#9679;&#9679;&#9679;&#9679;'" />
										    <label for="password" id="login-text-1">Password</label>
										</div>
										@if($errors->first('password'))
										   <div class="error"> 
											<p class="error-danger" > {{ $errors->first('password') }} </p>
										   </div>
										@endif 
									    <?php } ?>
				                    </div>
				                <div class="col-sm-1"></div>
				            </div>
				            <div class="row" id="check-sign">
				                 
				                 <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
								  <div class="Keepsigned">
				                    <div class="checkbox checkbox-primary">
										<input id="checkbox" type="checkbox">
										<label for="checkbox">
											Keep me signed in
										</label>
									</div>
								</div>
									<div class="forgotpass">
										<a href="forget_pass" for="signed" class="forgot">
										Forgot Password?</a>
									</div>
				                </div>  
				            </div>
				            <div class="row"> 
				                <div class="col-xs-12 text-center">
				                     <input type="submit" class="login-submit-button"  value="LOGIN" 
				                     align="middle">
				                </div> 
				            </div>
				           
				           <!--  <div class="row"> 
				                <div class="col-sm-12 col-md-12 text-center">
				                    <label for="signed" class="account-sign">Don’t have an account? <a data-dismiss="modal" href="#" data-toggle="modal" data-target="#exampleModalCenterJOIN">Signup Now.</a></label>
				                </div> 
				            </div> -->
				        </div>
				         {!! Form::close() !!} 
		            </div>
	            </div>
            
		</div>
      
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModalCenterJOIN" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content join-us">
            <div class="modal-body ">
	            <div class="row">
	                <div class="col-xs-12">
	                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img class="img-responsive center-block" src="/images/Popup_close.png" alt="VRL close">
                        </button>
	                </div>
	            </div>
	            <div class="row" id="image">
		            <div class="col-sm-12">
		                <img class="img-responsive center-block" src="/images/vrl_logo_login.png" alt="VRL Logo" id="logo-img"></a>
		            </div>
	            </div>
	            <div class="row-login-account">
		            <div class="col-sm-12">
                        <h3 class="text-center yourAccount"><span > Request to join </span></h3>
                    </div>
	            </div>
	            <div class="row">
	                <div class="login">
						@if(Session::has('register_error'))
						<div class="col-sm-12">
				            <div class="alert alert-danger"> 
				            {{Session::pull('register_error','default') }} 
						  <button type="button" class="close"  data-dismiss="alert" aria-label="close">
							 <span aria-hidden="true">&times;</span>
						  </button>	
						</div>
						</div>	
		                @endif
			            @if(Session::has('success'))
				           <div class="col-sm-12">
                             <div class="alert alert-primary"> {{Session::pull('success','default') }} 
							    <button type="button" class="close" data-dismiss="alert" aria-label="close">
									<span aria-hidden="true">&times;</span>
							  </button>
				            </div>
						   </div> 
			            @endif

			            {!! Form::open(array('method'=>'post','url' =>'joinus-request','id'=>'joinfrm')) !!}
			            <div class="login-body">
				            <div class="row">
				                <div class="col-sm-1"></div>
				                <div class="col-sm-10 paddingtop">
				                    <form action="">
									    <div class="field">
										    <!-- {!!Form::email('fullname',null,array('id'=>'email','placeholder' => 'Tony Stark'))!!} -->

										    <input type="text" placeholder="Tony Stark" 
										    name="fullname" id="Tony Stark"
										    onfocus="this.placeholder = ''" 
                                            onblur="this.placeholder = 'Tony Stark'" />
										   
											<label for="fullname" id="login-text">Full name</label>
									    </div>
									        <?php if (isset($errors)) { ?>
									    	@if($errors->first('fullname'))
											    <div class="error"> 
								                    <p class="error-danger" >  {{ $errors->first('fullname') }} </p>
											    </div>
										    @endif
									    <div class="field">
										    <input type="name" name="email" id="email"  placeholder="tony.stark@avengers.com" 
										    onfocus="this.placeholder = ''" 
                                            onblur="this.placeholder = 'tony.stark@avengers.com'"
										    >
										    <label for="fullname" id="login-text-3">Your Email</label>
									    </div>
									        @if($errors->first('email'))
											    <div class="error"> 
								                    <p class="error-danger" >  {{ $errors->first('email') }} </p>
											    </div>
										    @endif
									    <div class="field">
										    <input type="name" name="phone" id="Phone" placeholder="512 345 9876"  onfocus="this.placeholder = ''" 
                                            onblur="this.placeholder = '512 345 9876'">
										    <label for="fullname" id="login-text-3">Your Phone</label>
									    </div>
									        @if($errors->first('phone'))
											    <div class="error"> 
								                    <p class="error-danger" >  {{ $errors->first('phone') }} </p>
											    </div>
										    @endif
										    <?php } ?>
									    <div class="field">
										    <input type="name" name="finduser" id="find" placeholder="Twitter"
										    onfocus="this.placeholder = ''" 
                                            onblur="this.placeholder = 'Twitter'">
										    <label for="fullname" id="login-text-2">Where can we find you?</label>
									    </div>
									    <div class="field">
										    <input type="name" name="handle" id="handle" placeholder="@ironman"
										    onfocus="this.placeholder = ''" 
                                            onblur="this.placeholder = '@ironman'"
										    >
										    <label for="fullname" id="login-text-3">Your handle?</label>
									    </div>
									    <!-- <div class="field">
										    <input type="name" id="handle" placeholder="450"
										    onfocus="this.placeholder = ''" 
                                            onblur="this.placeholder = '450'"
										    >
										    <label for="fullname" id="login-text-2">How many followers do you have?</label>
									    </div> -->
									   
				                    </div>
				                <div class="col-sm-1"></div>
				            </div>
				            <!-- <div class="row" id="check-sign">
				                <div class="col-sm-10 col-sm-offset-1">
								  <div class="Keepsigned">
				                    <div class="checkbox checkbox-primary">
										<input id="checkbox1" type="checkbox">
										<label for="checkbox1">
											Keep me signed in
										</label>
									</div>
								</div>
									<div class="forgotpass">
										<label for="signed" class="forgot">Forgot Password?</label>
									</div>
				                </div> 
				            </div> -->
				            <div class="row" id="check-sign"> 
				                <div class="col-xs-12 text-center">
				                     <input type="submit" class="login-submit-button"  value="SEND REQUEST" 
				                     align="middle">
				                </div> 
				            </div>
				           
				            <div class="row"> 
				                <div class="col-xs-12 text-center">
				                    <label for="signed" class="account-join">I have already joined. <a data-dismiss="modal" href="#" data-toggle="modal" data-target="#exampleModalCenter">Login</a></label>
				                </div> 
				            </div>
				        </div>
				        {!! Form::close() !!} 
		            </div>
	            </div>
		    </div>
        </div>
    </div>
</div>















