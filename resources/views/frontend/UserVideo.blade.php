<!DOCTYPE html>

<html lang="en">

<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	@include('frontend.common.head')
        <style>
            .popup{
               
                    height: auto;
            }
            input{
                    border: 1px solid #cacaca !important;
            }
            
            .control-label {
                color:#666;
            }
            #paymentAlertMessage{
                display: none;
            }
            .customVideoHide{
                display: none;
            }
            .customALertDanger{
                padding: 15px;
                margin-bottom: 20px;
                border: 1px solid transparent;
                border-radius: 4px;
                color: #a94442;
                background-color: #f2dede;
                    border-color: #ebccd1;
            }
            .artist-video .inner-artists-video .dutation_time{
                top:0 !important;
            }
            .extendStorege{
                cursor: pointer;
            }
        
        </style>
</head>

<body class="cb-page user_profile user_video notranslate">
<div id="popup1" class="overlay" style="display:none">

<div class="popup">
				
	<a class="close"  id="hide">&times;</a>
	<div class="form-group content">
						<form action="/RePayment" method="post"  id="payment-form">
								<div class="col-md-12">
									 <div class="form-group">
									<div class="control-label">
											<label for="description">Number of expanded Months 5$/month</label>
									</div>

									<div class="control-box">
											<input type="hidden" value="{{$storage_data->status}}" name="storage_data" id="storage_data">
											<select class="form-control" id="numberOfMonths" name="requestExtendMonth">
												<option value="1" selected="">1 month</option>
												@for($i=2; $i<13 ; $i++)
												<option value="{{$i}}">{{$i}} months</option>
												@endfor
											</select>
										</div>
									</div>
								</div>
							<input type="hidden" name="requestID" id="extendRequestID">
						 </form>
						
						
						<div class="col-md-12">
							<div class="form-group">
								<div class="control-label">
											<label for="">Card Holder's Name</label>
								</div>
								<div class="control-box">
									<input type="text" name="cart_holder_name" value="Mohamed Mamdouh" placeholder="Card Holder Name" maxlength="70" autocomplete="off" >

								</div>
								
							</div>
							<div class="form-group">
								<div class="control-label">
								  <label for="">Card Number</label>
								</div>
								<div class="control-box">
									<input type="text" maxlength="16" placeholder="Card Number" value="5555555555554444" class="card-number form-control" >

								</div>
								
							</div>
							
						  
								<div class="form-group">
									<div class="control-label">
												<label class="col-sm-12 control-label" for="textinput">Card Expiry Date</label>
												</div>
												 <div class="control-box">
												  <select name="select2" data-stripe="exp-month" class="card-expiry-month stripe-sensitive required form-control selectnew">
												   <option value="00" selected="selected">Enter Month</option>
												   <option value="01" selected="">01</option>
												   <option value="02">02</option>
												   <option value="03">03</option>
												   <option value="04">04</option>
												   <option value="05">05</option>
												   <option value="06">06</option>
												   <option value="07">07</option>
												   <option value="08">08</option>
												   <option value="09">09</option>
												   <option value="10">10</option>
												   <option value="11">11</option>
												   <option value="12">12</option>
												 </select>
												 <select name="select2" data-stripe="exp-year" class="card-expiry-year stripe-sensitive required form-control selectnew1">
												   <option value="02" selected>Enter Year</option>
												 </select>

											   </div>
											 
								   </div>
						 
									<div class="form-group">
										<div class="control-box">
										<label class="col-sm-12 control-label"  for="textinput">CVV/CVV2</label>
										</div>
										<div class="control-box">
											<input type="text" id="cvv" placeholder="CVV" value="1234" maxlength="4" class="card-cvc form-control">
									   </div>
									 </div>
							
							

								</div>
						
						<div class="col-md-12">
							<button class="btn btn-success pull-right btn-sm" id="extendStorage">Extend Now <span id="finlPriceToPay">5</span>$</button>
						</div>
				   
</div>
					<div class="customALertDanger customVideoHide" id="paymentAlertMessage"></div>
</div>
</div>
	

<div class="cb-pagewrap"> @include('frontend.common.userHeader')
<div class="user_dashboard">
	<section id="mian-content">

		<div class="container">

			<div class="cb-grid">

				<div class="cb-block cb-box-100 main-content">

					<div class="cb-content">

						<div class="inner-block">

							<h1 class="heading"> <span class="txt1">Dashboard</span> <span class="txt2">My Video Requests </span> </h1>

						</div>

					</div>

				</div>

			</div>

		</div>

	</section>
            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif
	<?php $s3 = \Illuminate\Support\Facades\Storage::disk('s3')?>
	<section id="grid-botttom">

		<div class="container">

			<div class="cb-grid">

				<div class="cb-block cb-box-100">

					@if(Session::has('success'))

					<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

					@endif

					@if(Session::has('error'))

					<div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

					@endif
					<?php //dd($my_videos);?>
					<div class="cb-content video-wrap artist-video"> @if ($my_videos!=Null)

						<div class="inner-block">

							<h2 class="heading"><span>My  Videos</span></h2>

							<?php //dd($my_videos);?>

							@foreach($my_videos as $my_video)

							<div class="artists-video wi-33-3">

								<div class="artists-video-block">

									<div class="inner-artists-video">

										<span>
											<?php $now = new \DateTime();

											$date1=date_create($my_video->purchase_date);

											$diff=date_diff($date1,$now);

											$diff_date=$diff->format("%a");

											//echo $my_video->remain_storage_duration-$diff_date;

											?>
											@if($my_video->remain_storage_duration-$diff_date > 0)

											<?php $str = $my_video->thumbnail;$rest = substr($str,5);
													?>

											<a href="video_detail/{{$my_video->id}}">

												<img src="{{ $s3->url('images/thumbnails/'.$str) }}" alt="Mountain View" style="width:360px;height:225px;">

											</a>

											@else

											

											<img src="/images/thumbnails/default-video.png" alt="Mountain View" style="width:360px;height:225px;">

											

											@endif                 


										</span>

										<div class="video-content"> <span class="title" style="white-space:normal;">{{$my_video->title}}</span> </div>

										<?php $now = new \DateTime();

										$date1=date_create($my_video->purchase_date);

										$diff=date_diff($date1,$now);

										$diff_date=$diff->format("%a");


										?>
                                        <div class="video-content">
										@if($my_video->remain_storage_duration-$diff_date > 0)
                                            <div class="col-md-6">
                                                <span class="title">
													<a class="btn res-more-btn btn-sm" href="{{URL('resend_video/'.$my_video->requestby.'/'.$my_video->id)}}">resend video</a>
												</span>  
                                            </div>
											<div class="col-md-6">
												<?php $sub_str=substr($my_video->url,5); ?>
												<span class="title">
													<a class="btn res-more-btn btn-sm" href="{{ URL('download_video/'.$my_video->url) }}">Download</a>
												</span> 
                                            </div>
                                        @endif


										<div class="col-md-12" style="margin:12px 0px">
												<span class="title">
												<a class="extendStorege res-more-btn btn-sm btn-block" data-requestid="{{$my_video->id}}"  >Extend storage</a>
												</span>
											</div>
										</div>
										@if($my_video->remain_storage_duration-$diff_date <= 0)

										<div class="dutation_time col-md-12">

                                            <span>Your video storage duration is ended,please pay to extend storage of video.</span>

										</div>

										@else

											<div class="dutation_time col-md-12">
												<span> Video Storage Expires in {{$my_video->remain_storage_duration-$diff_date}} days.</span>

											</div>

										@endif

										<div class="artists-video-img">

										</div>

										<!-- <a href="/move_file/.''">move</a> -->

									</div>

								</div>

							</div>

							@endforeach </div>

							@else

							<div class="inner-block">

								<div class="cb-box-50">

									<h1 class="title not-found">No Requested Video found</h1>

								</div>

								<div class="cb-box-50">

									<div class="user_video">

										<img src="/images/not-found-vdo.jpg" alt="" class="fr-fic fr-dii">

									</div>

								</div>

							</div>

							@endif </div>

						</div>

					</div>

				</div>

			</section>
            </div>
			@include('frontend.common.footer') </div>

<script>
$(document).ready(function() {
	$("#numberOfMonths").on('change',function(){
		var numberOfMonths = this.value;
		var totalPrice = this.value * 5;
		$("#finlPriceToPay").text(totalPrice);						
});
                     
$('#completed').on('click',function () {

	$('.complete_block').show();

	$('.approve_block').hide();

	$('.pending_block').hide();

	$('.reject_block').hide();

})

$('#approved').on('click',function () {

	$('.approve_block').show();

	$('.complete_block').hide();

	$('.pending_block').hide();

	$('.reject_block').hide();

})

$('#pending').on('click',function () {

	$('.pending_block').show();

	$('.complete_block').hide();

	$('.approve_block').hide();

	$('.reject_block').hide();

})

$('#reject').on('click',function () {

	$('.reject_block').show();

	$('.complete_block').hide();

	$('.approve_block').hide();

	$('.pending_block').hide();

})

$('#all').on('click',function () {

	$('.reject_block').show();

	$('.complete_block').show();

	$('.approve_block').show();

	$('.pending_block').show();

})

});

$('.extendStorege').click(function(){

	$('#popup1').addClass('overlay').show();
	$(".popup").show();  
	
	$('#extendRequestID').val($(this).data('requestid'));
			
});

$('#calc_price').click(function(){

var days=parseInt($('#days').val());					
	if(!isNaN(days)){
			if(days != 0 && days != null ){

				var amount = parseFloat($('#storage_data').val());
				var da = days*amount;
				$('#request_day').val(days);
				$("#tot_price").val(da);
				$("#amountfinal").val(da);
				$(".video-content").show();

				}

		}else{

			alert('please add only number in day field.');

		}

});
</script>
<script>
jQuery(document).ready(function(){

		jQuery(".close").click(function(){
			$('#popup1').removeClass('overlay').hide();
			$(".popup").hide();                               
		});
});
</script>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script> 
<script type="text/javascript">
Stripe.setPublishableKey('pk_test_u2EpaiGskW20KXn5Nw7MMJta');
//Stripe.setPublishableKey('pk_live_ibbVEpwDbfWJAboByQ6Kvygy');
</script>
<script type="text/javascript">
 var select = $(".card-expiry-year"),
 year = new Date().getFullYear();

 for (var i = 0; i < 12; i++) {
  select.append($("<option value='"+(i + year)+"' "+(i === 0 ? "" : "")+">"+(i + year)+"</option>"))
}
</script>


 <script>
 function stripeResponseHandler(status, response) {
  var $form = $('#payment-form');

  if (response.error) { 
    $("#paymentAlertMessage").text(response.error.message);
    $("#paymentAlertMessage").show();
            
            
            $('#extendStorage').prop('disabled', false); // Re-enable submission

  }
  else { 
   var token = response.id;
   $form.append($('<input type="hidden" name="stripeToken">').val(token));
   $form.get(0).submit();
 }
};


function submitFormDetails(){
            var $form = $('#payment-form');
                    Stripe.card.createToken({
                     number: $('.card-number').val(),
                     cvc: $('.card-cvc').val(),
                     exp_month: $('.card-expiry-month').val(),
                     exp_year: $('.card-expiry-year').val(),
                     name: $('.card-holder-name').val(),
                     address_line1: $('.address').val(),
                     address_city: $('.city').val(),
                     address_zip: $('.zip').val(),
                     address_state: $('.state').val(),
                     address_country: $('.country').val()
                   }, stripeResponseHandler);
             return false;
            
            

}
            
            
            $("#extendStorage").click(function(){
                $('#extendStorage').prop('disabled', true); // Re-enable submission
                  submitFormDetails();
            });

</script>

		</body>

		</html>