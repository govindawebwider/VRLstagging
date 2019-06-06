<!DOCTYPE html>

<html lang="en">

<head>
<?php  $page ='Login'; ?>
	@include('frontend.common.head')


</head>

<body class="cb-page login"> 
	<div class="cb-pagewrap"> @include('frontend.common.header') 
		<section id="main-content"> 
			<div class="row purple thanksbox">
      </div>
			<div class="container"> 
				
				<div class="row"> 
					<div class="col-md-12 col-sm-12 col-lg-12 cb-block cb-box-40 main-content">
						<div class="LoginBox">
                       <div class="panel panel-default cb-content cb-marginT55 cb-marginB50">

							<div class="login-inner-block">

							<!-- 	<h1 class="title log-in-txt">Log In</h1> -->
								<h3 class="text-center yourAccount">
                                    <span class=""> Log In</span>
                                </h3>

								<div class="login_body">
                                 									  
									@if(Session::has('forget_error'))

									<div class="alert alert-danger"> 
										{{Session::pull('forget_error','default') }} 

										<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

									</div>

									@endif

									@if(Session::has('success2'))
							           <div class="col-sm-12">
			                             <div class="alert alert-success"> {{Session::pull('success2','default') }}
										    <button type="button" class="close" data-dismiss="alert" aria-label="close">
												<span aria-hidden="true">&times;</span>
										  </button>
							            </div>
									   </div>
						            @endif

						            @if(Session::has('success3'))
							           <div class="col-sm-12">
			                             <div class="alert alert-success"> {{Session::pull('success3','default') }}
										    <button type="button" class="close" data-dismiss="alert" aria-label="close">
												<span aria-hidden="true">&times;</span>
										  </button>
							            </div>
									   </div>
						            @endif 
									
									@if(Session::has('sucmsg'))
							           <div class="col-sm-12">
			                             <div class="alert alert-success"> {{Session::pull('sucmsg','default') }}
										    <button type="button" class="close" data-dismiss="alert" aria-label="close">
												<span aria-hidden="true">&times;</span>
										  </button>
							            </div>
									   </div>
						            @endif
									
@if(Session::has('login_errore'))
							           <div class="col-sm-12">
			                             <div class="alert alert-danger"> {{Session::pull('login_errore','default') }}
										    <button type="button" class="close" data-dismiss="alert" aria-label="close">
												<span aria-hidden="true">&times;</span>
										  </button>
							            </div>
									   </div>
						            @endif
									{!! Form::open(array('method'=>'post','url' =>'login','id'=>'loginfrm')) !!}

									<div class="form-group"> <span class="label form-text">eMail Address </span> {!! Form::email('email',null,array('id'=>'email','class'=>'form-control first'))!!}

										@if($errors->first('email'))

										<p class="label label-danger" > {{ $errors->first('email') }} </p>

										@endif 

									</div>

									<div class="form-group"> <span class="label form-text">Password</span> {!! Form::password('password',array('id'=>'password','class'=>'form-control first'))!!} 

										@if($errors->first('password'))

										<p class="label label-danger" > {{ $errors->first('password') }} </p>

										@endif </div>
										
										<!--<div class="g-recaptcha" data-sitekey="6LfNxkwUAAAAAHwkbb20uTBF7HGPvn-BbwdO2c9c"></div>-->
										
		      <!--<div class="checkbox space-bottom"> 
                  <input type="checkbox" class="text-section-remeber" id="remeber_me"> 
				  <label for="remeber_me" class="remeber-section">Remember me</label> </div> -->  
		  </div> 
		  <div class="row" >
 
  <div class="col-md-8 loginbutton text-right">
    <input type="submit" class="btn submitbtn" value="Login">
 
  </div> 
  <div class=" forgetpassword">
	   <a href="forget_pass" class="forget-pass-text" >Forgot Password</a> 
  </div></div>

{{--@if($signup_data->status=='show')
          <div class="login_footer">
          	<a  href="artist_register" class="btn-submit text-btn-hover">Artist Sign up</a> 
          </div>
          @endif--}}

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