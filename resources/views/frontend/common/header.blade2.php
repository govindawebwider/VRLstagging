<style type="text/css">
	.eye_pas {
	position: relative;
	width: 100%;
}
.eye_pas input {
	width: 100%;
}
.eye_pas .field-icon {
	position: absolute;
	right: 0;
	bottom: 10px;
}
</style>
<section class="header">	
	<div class="container">
		<div class="row">
			<nav class="navbar">
			  <div class="container-fluid">
			    <!-- Brand and toggle get grouped for better mobile display -->
			    <div class="navbar-header">
					<?php $url= URL('/');?>
				  <a class="navbar-brand" href="{{$url}}"><img class="logo" src="/images/vrl_logo_nav.png" alt="VRL Logo"></a>
					
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
							data-target="#bs-example-navbar-collapse-2" aria-expanded="false">
						<i class="fa fa-bars fa-3x purple-text" aria-hidden="true"></i>
						<span class="navbar-toggler-icon"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					
			    </div>
				  <!-- Collect the nav links, forms, and other content for toggling -->
				  @if(!Auth::check())
			    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
     				 <ul class="nav navbar-nav navbar-right pull-right head-main">

			      	@if( !Auth::check() )
							 {{--<li class="item2">
								 <a class="logintext navbar-btn purple-text locale-translate" style='display:none;' id="google_translate_element">
									 {!! Form::open(['method' => 'POST', 'route' => 'changelocale', 'class' => '']) !!}
									 {!! Form::select(
                                             'local',
                                             [en' => 'English'] +\Config::get('translation.locales'),
                                             \Stevebauman\Translation\Facades\Translation::getRoutePrefix(),
                                             [
                                                 'id'       => 'locale',
                                                 'class'    => '',
                                                 'required' => 'required',
                                                 'onchange' => 'this.form.submit()',
                                             ]
                                         ) !!}
									 <div class="btn-group pull-right sr-only">
										 {!! Form::submit("Change", ['class' => 'btn btn-success']) !!}
									 </div>
									 {!! Form::close() !!}
								 </a>
							 </li>--}}
					 <li class="item2">
						 <div class="logintext  navbar-btn" style='display:none;z-index:5;padding: 11px 12px !important;' id='google_translate_element'></div>
					 </li>
				    <li class="item2">
						<a class="logintext  navbar-btn purple-text" href="javascript:void(0)" onclick="open_translate(this)"
						   title='Translate VRL' id="login"><i class="fa fa-globe fa-lg"></i></a>
					</li>
			        <li class="item2 {{--*/ if(Request::is('artist_login') OR Request::is('UserLogin')){echo "active"; }  /*--}}" >
						<a class="logintext  navbar-btn purple-text" data-toggle="modal" data-target="#exampleModalCenter" id="login">
							<span>Login</span>
						</a>
					</li>
					<!-- Here i need to run something like 
select group_name from group where id = $user->id -->


					
						<!-- @if($setting->status == 'show' ) -->
				        <li><a data-toggle="modal" data-target="#exampleModalCenterJOIN" class="btn loginshadow navbar-btn joinus">
							Join Us
						</a></li>
						<!-- @endif -->
			        @endif
			      </ul>
			    </div>
				@else

				<div class="collapse navbar-collapse notranslate">
					<ul class="nav navbar-nav navbar-right pull-right mr-auto head-main">
					<li class="nav-hover active">
						<a class="logintext navbar-btn purple-text" href="/">
							<span>Home</span></a>
					</li>
					<li class="nav-hover">
						<a class="logintext  navbar-btn purple-text" href="/view-all-artist">
							<span>Artists</span></a>
					</li>
					<li class="nav-hover dropdown">
						<a class="logintext nav-link purple-text dropdown-toggle" href="#" id="navbarDropdown"
						   role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">My Videos</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							@if ((Auth::user()->type=="User" || Auth::user()->type=="Admin"|| session('current_type') == "User"))
								<a class="dropdown-item" href="/user_video">
									<span>My requested video</span>
								</a>
							@else
								<a class="dropdown-item" href="/pending_requests">
									<span>My requested video</span>
								</a>
							@endif
						</div>
					</li>
					<li class="nav-hover nav-item dropdown">
						<a class="logintext  nav-link purple-text dropdown-toggle" href="#"
						   id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
						   aria-expanded="false">Hi! {{ Auth::user()->profile->Name }}</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							@if ((Auth::user()->type=="Admin" || session('current_type') == "User" || session('current_type') == "Artist"))
								<a class="dropdown-item" href="/switch_as_admin">Switch to Admin</a>
							@endif
							@if ((Auth::user()->type=="User" || session('current_type') == "User"))
								<a class="dropdown-item" href="/user_dashboard">Dashboard</a>
								<a class="dropdown-item" href="/profile">Profile</a>
								<a class="dropdown-item" href="/user_change_password">Change Password</a>
							@else
								<a class="dropdown-item" href="/Dashboard">Dashboard</a>
								<a class="dropdown-item" href="/ProfileUpdate">Profile</a>
								<a class="dropdown-item" href="/change-password">Change Password</a>
							@endif
							<a class="dropdown-item" href="/getLogout">Signout</a>
						</div>
					</li>
					</ul>
				</div>
				  <div class="hidden-md hidden-lg notranslate">
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
											  @if ((Auth::user()->type=="User" || Auth::user()->type=="Admin"|| session('current_type') == "User"))
											  <a class="dropdown-item" href="/user_video">
												  <span>My requested video</span>
											  </a>
											  @else
												  <a class="dropdown-item" href="/pending_requests">
													  <span>My requested video</span>
												  </a>
											  @endif
										  </div>
									  </li>
									  <li class="nav-hover nav-item dropdown">
										  <a class="right-menu nav-link navbar-btn purple-text dropdown-toggle" href="#"
											 id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
											 aria-expanded="false">Hi! {{ Auth::user()->user_name }}</a>
										  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
											  @if ((Auth::user()->type=="Admin" || session('current_type') == "User" || session('current_type') == "Artist"))
												  <a class="dropdown-item" href="/switch_as_admin">Switch to Admin</a>
											  @endif
											  @if ((Auth::user()->type=="User" || session('current_type') == "User"))
											  <a class="dropdown-item" href="/user_dashboard">Dashboard</a>
											  <a class="dropdown-item" href="/profile">Profile</a>
											  <a class="dropdown-item" href="/user_change_password">Change Password</a>
											  @else
												  <a class="dropdown-item" href="/Dashboard">Dashboard</a>
												  <a class="dropdown-item" href="/ProfileUpdate">Profile</a>
												  <a class="dropdown-item" href="/change-password">Change Password</a>
											  @endif
											  <a class="dropdown-item" href="/getLogout">Signout</a>
										  </div>
									  </li>

								  </ul>

							  </div>

						  </div>
					  </div>
				  </div>

			    @endif		  <!-- /.navbar-collapse -->
			  </div><!-- /.container-fluid -->
			</nav>

	


		</div>
	</div>
</section>
<style>
	.main-category .select2-container--default .select2-selection--single {
		height: 37px;outline: none;
		border-radius: 0;
		border: none;
		border-bottom: solid 1px #cccccc;
	}.main-category .select2-container--default .select2-selection--single .select2-selection__rendered {
			 line-height: 37px;
			 border-top: none;
	 }.main-category .select2-container--default .select2-selection--single .select2-selection__arrow {
				   /*display: none;*/
	  }
	.main-category .select2-container--default.select2-container--open.select2-container--below .select2-selection--single{
		outline: none!important;
	}
	.select2-container--default .select2-selection--multiple{
		outline: none!important;
		border: none;
		border-top: none;
		border-right: none;
		border-bottom: solid 1px #cccccc;
		border-radius: 0;
	}
	.select2-container--default.select2-container--focus .select2-selection--multiple{
		border: none;border-bottom: solid 1px #cccccc;
	}
	.select2-container--default .select2-selection--multiple:before {
		content: ' ';
		display: block;
		position: absolute;
		border-color: #888 transparent transparent transparent;
		border-style: solid;
		border-width: 5px 4px 0 4px;
		height: 0;
		right: 6px;
		margin-left: -4px;
		margin-top: -2px;top: 50%;
		width: 0;cursor: pointer
	}
.select2-container--open .select2-dropdown--above{
	border-bottom: solid 1px #aaa;
}
	.select2-container--open .select2-selection--multiple:before {
		content: ' ';
		display: block;
		position: absolute;
		border-color: transparent transparent #888 transparent;
		border-width: 0 4px 5px 4px;
		height: 0;
		right: 6px;
		margin-left: -4px;
		margin-top: -2px;top: 50%;
		width: 0;cursor: pointer
	}
</style>
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
                        <h3 class="text-center yourAccount"><span class="">
						  Login to your account
							</span>
						</h3>
                    </div>
	            </div>
	            <div class="row">
	                <div class="login">
	                    @if(Session::has('login_error'))
	                    	<div id="show-login-popup" data-showlogin="1"></div>

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

			            {!! Form::open(array('method'=>'post','url' =>'login','id'=>'loginfrm','onsubmit'=>'return submit_login();')) !!}

			            <div class="login-body">
				            <div class="row">
				                <div class="col-sm-1"></div>
				                <div class="col-sm-10 paddingtop">
				                    <form action="">
									    <div class="field">
										    <!-- {!!Form::email('email',null,array('id'=>'email','placeholder' => 'tony.stark@avengers.com'))!!} -->
										    <input type="text" placeholder="example@domain.com"
										    name="email" id="email"
										    onfocus="this.placeholder = ''"
                                            onblur="this.placeholder = 'example@domain.com'" />
										    <label for="fullname" id="login-text">
												Username/Email
											</label>
										</div>
										<div class="error">
						                    <p class="error-danger" id="err-email"></p>
									    </div>
										<?php if (isset($errors)) { ?>
										 @if($errors->first('email'))

										<div class="error">
							                    <p class="error-danger" > {{ ($errors->first('email')) }} </p>
										   </div>
										 @endif
										<?php } ?>
									    <div class="field">
										       <!--  {!! Form::password('password',array('id'=>'password','placeholder' => '&#9679;&#9679;&#9679;&#9679;&#9679;'))!!} -->
									        <div class="eye_pas">
									        	<input data-toggle="password" type="password" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;"
									        name="password" id="password"
									        onfocus="this.placeholder = ''"
                                            onblur="this.placeholder = '&#9679;&#9679;&#9679;&#9679;&#9679;'" />
                                            <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
									        </div>
									        
										    <label for="password" id="login-text-1" >
												Password
											</label>
										</div>
										<div class="error">
						                    <p class="error-danger" id="err-password"></p>
									    </div>
										<?php if (isset($errors)) { ?>
										@if($errors->first('password'))
										   <div class="error">
												<p class="error-danger" > {{ ($errors->first('password')) }} </p>
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
										<?php $url = 'forget_pass'?>
										<a href="{{$url}}" for="signed" class="forgot">
											Forgot Password?
										</a>
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
							<input type="hidden" name="timezone" id="timezone">
							{{ Form::token() }}
				         {!! Form::close() !!}
		            </div>
	            </div>

		</div>

    </div>
  </div>
</div>

<?php
    $categoryData = DB::table('category')->where('status', 1)->pluck('title','id');
    $catdata = $categoryData;
?>


<!-- Modal -->
<div class="modal fade" id="exampleModalCenterJOIN" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                        <h3 class="text-center yourAccount"><span>
								Request to join
							</span></h3>
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

			            {!! Form::open(array('method'=>'post','url' =>'joinus-request', 'id'=>'joinfrm', 'onsubmit'=>'return submit_join_us();')) !!}
			            <div class="login-body">
				            <div class="row">
				                <div class="col-sm-1"></div>
				                <div class="col-sm-10 paddingtop">
									    <div class="field">
										    <!-- {!!Form::email('fullname',null,array('id'=>'email','placeholder' => 'Tony Stark'))!!} -->

										    <input type="text" placeholder="Tony Stark"
										    name="fullname" id="fullname"
										    onfocus="this.placeholder = ''"
                                            onblur="this.placeholder = 'Tony Stark'" />

											<label for="fullname" id="login-text">
												Full name

											</label>
									    </div>
									    <div class="error">
						                    <p class="error-danger" id="err-fullname"></p>
									    </div>
								    	@if(isset($errors) && $errors->first('fullname'))
										    <div class="error">
							                    <p class="error-danger" >  {{ $errors->first('fullname') }} </p>
										    </div>
									    @endif
									    <div class="field">
										    <input type="name" name="email" id="r_email"  placeholder="example@domain.com"
										    onfocus="this.placeholder = ''"
                                            onblur="this.placeholder = 'example@domain.com'"
										    >
										    <label for="fullname" id="login-text-3">
												Your Email
											</label>
									    </div>
									    <div class="error">
						                    <p class="error-danger" id="err-r-email"></p>
									    </div>

									    @if(isset($errors) && $errors->first('email'))
										    <div class="error">
							                    <p class="error-danger" >
													{{ $errors->first('email') }} </p>
										    </div>
									    @endif

										<div class="field">
											<input type="text" name="date_of_birth" id="date_of_birth"  placeholder="mm/dd/yyyy"
												   onfocus="this.placeholder = ''"
												   onblur="this.placeholder = 'mm/dd/yyyy'"
											>
											<label for="date_of_birth" id="date_of_birth-text-3">
												Date of Birth
											</label>
										</div>
										<div class="error">
											<p class="error-danger" id="err-dob"></p>
										</div>

										@if(isset($errors) && $errors->first('date_of_birth'))
											<div class="error">
												<p class="error-danger" >  {{ $errors->first('date_of_birth') }} </p>
											</div>
										@endif

									    <div class="field">
										    <input type="name" name="phone" id="phone" placeholder="512 345 9876"  onfocus="this.placeholder = ''"
                                            onblur="this.placeholder = '512 345 9876'">
										    <label for="fullname" id="login-text-3">
												Your Phone
											</label>
									    </div>
									    <div class="error">
						                    <p class="error-danger" id="err-phone"></p>
									    </div>
									    @if(isset($errors) && $errors->first('phone'))
										    <div class="error">
							                    <p class="error-danger" >  {{ $errors->first('phone') }} </p>
										    </div>
									    @endif

									    <div class="field">
										    <input type="name" name="finduser" id="find" placeholder="Twitter"
										    onfocus="this.placeholder = ''"
                                            onblur="this.placeholder = 'Twitter'">
										    <label for="fullname" id="login-text-2">
												Where can we find you?</label>
									    </div>
										<div class="error">
											<p class="error-danger"></p>
										</div>
									    <div class="field">
										    <input type="name" name="handle" id="handle" placeholder="@ironman"
										    onfocus="this.placeholder = ''"
                                            onblur="this.placeholder = '@ironman'"
										    >
										    <label for="fullname" id="login-text-3">
												Your handle
											</label>
									    </div>
										<div class="error">
											<p class="error-danger"></p>
										</div>
										<div class="field main-category">
											<select id="main-category" name="main_cat_id">
												@foreach($catdata as $key => $value)
												<option value="{{$key}}">{{ ($value) }}</option>
												@endforeach
											</select>

											<label for="work" id="login-text-3">
												{{ (\Lang::get('views.what_you_do'))
											}}</label>
										</div>
										<div class="error">
											<p class="error-danger" id="err-r-maincat"></p>
										</div>
										@if(isset($errors) && $errors->first('main_cat_id'))
											<div class="error">
												<p class="error-danger" >  {{ $errors->first('main_cat_id') }} </p>
											</div>
										@endif
				                        <div class="field">
											<select id="category" name="cat_id[]" multiple>
												@foreach($catdata as $key => $value)
													<option value="{{$key}}">{{ ($value) }}</option>
												@endforeach
											</select>
											<label for="category" id="login-text-3">{{(\Lang::get('views.add_tags'))

											}}</label>
				                        </div>
				                        <div class="error">
						                    <p class="error-danger" id="err-r-cat"></p>
									    </div>
									    @if(isset($errors) && $errors->first('cat_id'))
										    <div class="error">
							                    <p class="error-danger" >
													{{ $errors->first('cat_id') }}
												</p>
										    </div>
									    @endif
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
				                    <label for="signed" class="account-join">
										I have already joined.
										<a data-dismiss="modal" href="#" data-toggle="modal" data-target="#exampleModalCenter">
											Login
										</a>
									</label>
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





