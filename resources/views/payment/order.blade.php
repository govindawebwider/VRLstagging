<!DOCTYPE html>

<html lang="en">
<head>
@include('frontend.common.head')
</head>

<body class="cb-page order">
	<div class="cb-pagewrap"> 
		@include('frontend.common.header')
		<section id="mian-content">
			<div class="container">
				<div class="cb-grid">
					<div class="cb-block cb-box-70 main-content">
						<div class="cb-content nopadding-right nomargin-right">
							<div class="inner-block">
								@if(Session::has('register_error'))
								<div class="alert alert-danger">
									{{Session::get('register_error') }}
								</div>								
								@endif				
								<form action="{{URL('store')}}" method="post" role="form">
				
									<div class="signup_form clearfix">
										<h2 class="heading"><span>Create an account</span></h2>
										{!! csrf_field(); !!}
										<div class="inner_wrap">
                                           <div class="form-group ">
                                                <label for="email">Email</label>
                                                <input type="text" name="user_email" id="email" class="form-control">
                                                @if($errors->first('user_email')) 
                                                <p class="label label-danger" >
                                                    {{ $errors->first('user_email') }}                     
                                                </p>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" name="password" id="password" class="form-control">
                                                @if($errors->first('password')) 
                                                <p class="label label-danger" >
                                                    {{ $errors->first('password') }}                     
                                                </p>
                                                @endif
                                            </div>	
                                        </div>									
									</div>
									
									<div class="billing_form <!--cb-marginT50-->">
										<h3>Billing information</h3>
                                        <div class="inner_wrap">
                                            <div class="form-group">
                                                <label for="country">Country / Region</label>
                                                <input type="text" name="country" id="country" class="form-control">
                                                @if($errors->first('country')) 
                                                <p class="label label-danger" >
                                                    {{ $errors->first('country') }} 
                    
                                                </p>
                                                @endif
                                            </div>				
                                            <div class="form-group">
                                                <label for="first_name">First Name</label>
                                                <input type="text" name="first_name" id="first_name" class="form-control">
                                                @if($errors->first('first_name')) 
                                                <p class="label label-danger" >
                                                    {{ $errors->first('first_name') }} 
                    
                                                </p>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="last_name">Last Name</label>
                                                <input type="text" name="last_name" id="last_name" class="form-control">
                                                @if($errors->first('last_name')) 
                                                <p class="label label-danger" >
                                                    {{ $errors->first('last_name') }} 
                    
                                                </p>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="address_1">Address</label>
                                                <input type="text" name="address_1" id="address_1" class="form-control">
                                                @if($errors->first('address_1')) 
                                                <p class="label label-danger" >
                                                    {{ $errors->first('address_1') }} 
                    
                                                </p>
                                                @endif
                                            </div>
                                            				
                                            <div class="form-group">
                                                <label for="city">City</label>
                                                <input type="text" name="city" id="city" class="form-control">
                                                @if($errors->first('city')) 
                                                <p class="label label-danger" >
                                                    {{ $errors->first('city') }} 
                    
                                                </p>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="state">State</label>
                                                <input type="text" name="state" id="state" class="form-control">
                                                @if($errors->first('state')) 
                                                <p class="label label-danger" >
                                                    {{ $errors->first('state') }} 
                    
                                                </p>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="zip">Zip</label>
                                                <input type="text" name="zip" id="zip" class="form-control">
                                                @if($errors->first('zip')) 
                                                <p class="label label-danger" >
                                                    {{ $errors->first('zip') }} 
                    
                                                </p>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="phone">Phone</label>
                                                <input type="text" name="phone" id="phone" class="form-control">
                                                @if($errors->first('phone')) 
                                                <p class="label label-danger" >
                                                    {{ $errors->first('phone') }} 
                    
                                                </p>
                                                @endif
                                            </div>
                                        </div>
										<div class="total_price">
											<input type="submit" class="btn btn-primary btn-block" value="Pay now">
										</div>
				
										
										<input type="hidden" name="business" value="codingbrains18@gmail.com">
										<input type="hidden" name="item_name" value="{{$video->Title}}">
										<input type="hidden" name="item_number" value="{{$video->VideoId}}">
										<input type="hidden" name="amount" value="{{$video->VideoPrice}}">
										<input type="hidden" name="tax" value="1">
										<input type="hidden" name="quantity" value="1">
										<input type="hidden" name="video_id" value="{{$video->VideoId}}">
				
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="cb-block cb-box-30 right-bar">
                    
						<div class="cb-content">
							<div class="inner-block">
								<div class="order-summary">
									<h2 class="heading"><span>Order summary</span></h2>
									<div class="table-reponsive">
										<table class="table">
											<tr>
												<th>{{$video->Title}}</th>
												<th align="right">${{$video->VideoPrice}}</th>
											</tr>
											<tr>
												<th>Subtotal</th>
												<th>$ {{$video->VideoPrice}}</th>
											</tr>
											<tr>
												<th>Total</th>
												<th>$ {{$video->VideoPrice}}</th>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div>
                        
                        <div class="cb-content">
							<div class="inner-block">
								<div class="order-summary">
									<h2 class="heading"><span>Have An Account ?</span></h2>
									<div class="login">
                                    	<p>We are happy to see you return! Please log in to continue.</p>
										<a  href="{{URL('UserLogin')}}" class="btn ">Login Now</a>
									</div>
								</div>
							</div>
						</div>
                        
					</div>
				</div>
			</div>
		</section>
		@include('frontend.common.footer') 
	</div>
</body>
</html>