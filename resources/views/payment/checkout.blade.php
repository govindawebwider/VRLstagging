<!DOCTYPE html>

<html lang="en">
<head>
@include('frontend.common.head')
</head>

<body class="cb-page payment">
	<div class="cb-pagewrap"> 
	@extends('frontend.common.header')
		<section id="mian-content">
			<div class="container">
				<div class="cb-grid">
					<div class="cb-block cb-box-70 main-content">
						<div class="cb-content nopadding-right nomargin-right">
							<div class="inner-block">
								<div class="col-md-6"> @if(Session::has('register_error'))
									<div class="alert alert-danger"> {{Session::get('register_error') }} </div>
									@endif
									<form action="{{URL('pay')}}" method="post" role="form">
										{!! csrf_field(); !!}
										<div class="billing_form">
											<legend>Billing information</legend>
											<div class="form-group">
												<label for="country">Country / Region</label>
												<input type="text" name="country" id="country" class="form-control">
												@if($errors->first('country'))
												<p class="label label-danger" > {{ $errors->first('country') }} </p>
												@endif </div>
											<div class="form-group">
												<label for="address_1">Address</label>
												<input type="text" name="address_1" id="address_1" class="form-control">
												@if($errors->first('address_1'))
												<p class="label label-danger" > {{ $errors->first('address_1') }} </p>
												@endif </div>
											<div class="form-group">
												<input type="text" name="address_2" id="address_2" class="form-control">
											</div>
											<div class="form-group">
												<label for="city">City</label>
												<input type="text" name="city" id="city" class="form-control">
												@if($errors->first('city'))
												<p class="label label-danger" > {{ $errors->first('city') }} </p>
												@endif </div>
											<div class="form-group">
												<label for="state">State</label>
												<input type="text" name="state" id="state" class="form-control">
												@if($errors->first('state'))
												<p class="label label-danger" > {{ $errors->first('state') }} </p>
												@endif </div>
											<div class="form-group">
												<label for="zip">Zip</label>
												<input type="text" name="zip" id="zip" class="form-control">
												@if($errors->first('zip'))
												<p class="label label-danger" > {{ $errors->first('zip') }} </p>
												@endif </div>
											<div class="form-group">
												<label for="phone">Phone</label>
												<input type="text" name="phone" id="phone" class="form-control">
												@if($errors->first('phone'))
												<p class="label label-danger" > {{ $errors->first('phone') }} </p>
												@endif </div>
											<div class="total_price">
												<legend>Total</legend>
												<h3>$ {{$video->VideoPrice}} USD</h3>
												<input type="submit" class="btn btn-primary btn-block" value="i agree-Pay now">
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
					</div>
					<div class="cb-block cb-box-30 right-bar">
						<div class="cb-content">
							<div class="inner-block">
								<div class="order-summary">
									<legend>Order summary</legend>
									<div class="table-reponsive">
										<table class="table">
											<tr>
												<th>{{$video->Title}}</th>
												<th>${{$video->VideoPrice}}</th>
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
					</div>
				</div>
			</div>
		</section>
		@extends('frontend.common.footer') 
	</div>
</body>
</html>