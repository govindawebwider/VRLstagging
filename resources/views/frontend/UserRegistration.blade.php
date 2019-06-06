<!DOCTYPE html>



<html lang="en">

<head>

	@include('frontend.common.head')

</head>



<body class="cb-page register">

	<div class="cb-pagewrap">	

		@include('frontend.common.header')

		

		<section id="mian-content">

			<div class="container">

				<div class="cb-grid">

					<div class="cb-block cb-box-70 main-content">

						<div class="cb-content cb-marginT55 cb-marginB50">

							<div class="border-inner-center reg-inner-block">

						<h1 class="title headding-text">Register</h1>

						@if(Session::has('register_error'))
					<div class="alert alert-danger">
						{{Session::get('login_error') }}
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					</div>
					
					@endif
					@if(Session::has('success'))
					<div class="alert alert-success">
						{{Session::get('success') }}
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					</div>
					@endif
						

						{!! Form::open(array('url' =>'UserSignup','id'=>'signup','method'=>'post')) !!}
					<div class="form-group input-type-section">	
						{!! Form::label('fname', 'First Name* :')!!}  
						{!! Form::text('fname',null,array('class'=>'form-control'))!!}
						@if($errors->first('fname')) 
						<p class="label label-danger" >
							{{ $errors->first('fname') }} 
						</p>
						@endif
					</div>
					<div class="form-group input-type-section">	
						{!! Form::label('lname', 'Last Name* :')!!}
						{!! Form::text('lname',null,array('class'=>'form-control'))!!}
						@if($errors->first('lname')) 
						<p class="label label-danger" >
							{{ $errors->first('lname') }} 
						</p>
						@endif
					</div>
					<div class="form-group input-type-section">
						{!! Form::label('email', 'Email Address* :')!!}
						{!! Form::text('email',null,array('class'=>'form-control'))!!}
						@if($errors->first('email')) 
						<p class="label label-danger" >
							{{ $errors->first('email') }} 
						</p>
						@endif
					</div>

					<div class="form-group input-type-section">
						{!! Form::label('password', 'Password')!!}
						{!! Form::password('password',array('class'=>'form-control'))!!}
						@if($errors->first('password')) 
						<p class="label label-danger" >
							{{ $errors->first('password') }} 
						</p>
						@endif
					</div>
                    
                    
                    
					<div class="form-group form-text-area">
						{!! Form::label('confirm_password', ' Confirm Password')!!}
						{!! Form::password('confirm_password',array('class'=>'form-control'))!!}
						@if($errors->first('confirm_password')) 
						<p class="label label-danger" >
							{{ $errors->first('confirm_password') }} 
						</p>
						@endif
					</div>
					<div class="form-group">
						{!! Form::submit('Submit',array('class'=>'btn btn-primary sbt-btn top-btn'))!!}

					</div>
					{!! Form::close() !!}

							</div>

						</div>

					</div>

				</div>

			</div>

		</section>

		

		<section id="portfolio" class="portfolio">

			<div class="container">

				<div class="col-md-12 col-sm-12 col-xs-12">

					

					

				</div>

                </div>

				<!--/.container-->

			</section>



		@include('frontend.common.footer')	

	</div>

</body>

</html>