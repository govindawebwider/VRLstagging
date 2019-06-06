<!DOCTYPE html>
<html lang="en">
<head>
@include('frontend.common.head')
</head>
<body class="cb-page login">
<div class="cb-pagewrap"> 
@include('frontend.common.header')
	<section id="mian-content">
		<div class="container">
			<div class="cb-grid">
				<div class="cb-block cb-box-40 main-content">
					<div class="cb-content cb-marginT55 cb-marginB50">
						<div class="login-inner-block">
							<h1 class="title log-in-txt">Log In</h1>
							@if(Session::has('verify_email'))									
                                <div class="alert alert-primary"> {{Session::get('verify_email') }}
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
								</div>						
							@endif
							<div class="login_body"> @if(Session::has('login_error'))
							
							
								<div class="alert alert-danger"> {{Session::get('login_error') }}
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
								</div>
								@endif
								{!! Form::open(array('method'=>'post','url' =>'UserLogin','id'=>'loginfrm')) !!}
								<div class="form-group">
									<span class="label form-text">Email id</span>
									{!! Form::email('email',null,array('id'=>'email','class'=>'form-control first'))!!}
									@if($errors->first('email')) 
									<p class="label label-danger" >
										{{ $errors->first('email') }} 
									</p>
									@endif
								</div>
								<div class="form-group">
									<span class="label form-text">Passwordsss</span>
									{!! Form::password('password',array('id'=>'password','class'=>'form-control first'))!!} 
									@if($errors->first('password')) 
									<p class="label label-danger" >
										{{ $errors->first('password') }} 
									</p>
									@endif             
								</div>
								<div class="checkbox space-bottom">
									<!--<input type="checkbox" class="text-section-remeber" id="remeber_me">
									<label for="remeber_me" class="remeber-section">Remember me</label>-->
									<a href="forget_pass" class="btn-submit forget-pass-text">Forget Password</a> 
								</div>
			                    <div class="login_footer">
									{{--<a  href="UserSignup" class="btn-submit text-btn-hover" >SignUp</a> --}}
                                    <input type="submit" class="btn btn-default login log-btn" value="Login">
                                    									
								</div>
								{!! Form::close() !!}
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		@include('frontend.common.footer') </div>
	</body>
</html>