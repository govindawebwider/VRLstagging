<!DOCTYPE html>
<html>
	<head>
		<script>
			var reqid,
			    reqto,
			    reqby,
			    price,
			    email;
			var token,
			    flag = 0;
			var mydata;
			Ti.App.addEventListener("app:fromTitanium", function(e) {
				reqid = e.reqid;
				reqto = e.reqto;
				reqby = e.reqby;
				price = e.price;
				email = e.email;
			});
			
		</script>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=yes">
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.3/jquery.min.js"></script>
		<script src="jquery.payment.js"></script>
		<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
		<style type="text/css" media="screen">
					
		@font-face {
			font-family: 'gothamregular';
			src: url('fonts/gotham-light.woff2') format('woff2'),
				url('fonts/gotham-light.woff') format('woff');
			font-weight: normal;
			font-style: normal;

		}
		body{height:100%; width:100%; background-color: #000 no-repeat scroll 0 0;}
		.container {width: 400px;}
		.form_sec{background-color: #000; padding:49px 29px;}
		.label-block span {font-weight: normal; color: #fff; font-family: 'gothamregular'; font-size: 18px;}
		.full_header{background: rgba(0, 0, 0, 0) url("image/vrl_bg.png") no-repeat scroll 0 0; background-size:cover;background-position:center center;background-repeat:no-repeat;}
			.payment-errors {color: white;}
			
			.has-error input {
				border-width: 2px;
			}
			.validation.text-danger:after {
				content: 'Validation failed';
			}
			.validation.text-success:after {
				content: 'Validation passed';
			}
			#loading {
				width: 100%;
				height: 100%;
				top: 0;
				left: 0;
				position: fixed;
				display: block;
				opacity: 0.7;
				background-color: #fff;
				z-index: 99;
				text-align: center;
			}

			#loading-image {
				top: 150px;
				z-index: 100;
			}
			#success {
				width: 100%;
				height: 100%;
				top: 0;
				left: 0;
				position: fixed;
				background-color: #fff;
				z-index: 99;
				text-align: center;
			}

			#success-image {
				z-index: 100;
			}
			#error {
				width: 100%;
				height: 100%;
				top: 0;
				left: 0;
				position: fixed;
				display: block;
				background-color: #fff;
				z-index: 99;
				text-align: center;
			}

			#error-image {
				position: absolute;
				top: 150px;
				left: 30px;
				z-index: 100;
			}
			#success .inner-wrap{position: absolute;left: 50%;top: 50%;-webkit-transform: translateX(-50%) translateY(-50%) ;-moz-transform: translateX(-50%) translateY(-50%) ;-ms-transform: translateX(-50%) translateY(-50%) ;-o-transform: translateX(-50%) translateY(-50%) ;transform: translateX(-50%) translateY(-50%) ;}
			@media (max-width: 767px){
				nav {background-color: #000;  text-align: center;}
				.navbar {border: 0 none !important;min-height: 48px !important;}
				.form_sec {height: 470px;}
				
				.container {width: 70%;}
				.logo > img {padding: 5px;width: 21%;}
				.form_sec {padding: 33px 15px;}
				.form-group {width: 100% !important;}
				.input-block > input {border: medium none;border-radius: 5px; height: 36px;}
				.input-block > span {color: #fff;font-size: 20px;}
				h2 > span {background-color: #a60000; border: 1px solid; color: #a60000; margin-right: 10px;  padding: 2px 4px;}	
				h2 {color: white;font-family: gothamregular;font-size: 20px;font-weight: 600; margin-bottom: 25px; margin-top: 0; text-transform: uppercase;}
				.submit {background-color: #a60000 !important;border: 2px solid #222;border-radius: 7px !important; font-family: gothamregular; font-size: 15px; padding: 9px !important;  width: 180px;}
				.back {font-family: gothamregular; text-align: center;}
				.back {background: #313561 none repeat scroll 0 0;color: #fff;font-family: gothamregular;font-size: 20px;padding: 17px;text-align: center;width: 100% !important;}
				p {color: #fff;font-family: gothamregular; font-size: 17px;}
				.nav.navbar.navbar-footer.footer {margin: 0; padding-top: 14px;}
				.exp-date > input {width: 48%;}
				.form-group {margin-bottom: 8px;}
							
			}
			@media(max-width:640px){
				.container {width: 100%;}
			}
			@media(max-width:480px){
				.exp-date > input {width: 48%;}
			}
			@media(max-width:414px){
				.exp-date > input {width: 47%;}
			}
			@media(max-width:320px){
			.exp-date > input {width: 46%;}
			}
			
		
		</style>
		<script language="javascript" type="text/javascript">
			$(window).load(function() {
				$('#loading').hide();
				$('#success').hide();
				//$('#error').css('display','none');

			});
		</script>


	</head>
	<body>
	<div class="full_header" style="background-color: #000 no-repeat scroll 0 0">
		<nav class="nav navbar logo">
			<img src="image/logo.png">
		</nav>
		<div class="container">
		
		<div class="form_sec">
		<div class="heading"><h2> <span></span> Payment Page</h2></div>
			<form action="" method="POST" id="payment-form">
				<span class="payment-errors"></span>

				<div class="form-group" style="width: 320px">
					<div class="label-block" style="width:100%">
						<label> <span>Card Number</span></label>
					</div>
					<div class="input-block" style="width:100%">
						<input type="number" size="20" data-stripe="number" style="width:100%">
					</div>
				</div>

				<div class="form-group" style="width: 320px">
					<div class="label-block" style="width:100%">
						<label> <span>Expiration (MM/YY)</span></label>
					</div>
					<div class="input-block exp-date" style="width:100%">
						<input type="number" size="2" data-stripe="exp_month">
						<span> / </span>
						<input type="number" size="2" data-stripe="exp_year">
					</div>
				</div>

				<div class="form-group" style="width: 320px">
					<div class="label-block" style="width:100%">
						<label> <span>CVC</span></label>
					</div>
					<div class="input-block" style="width:100%">
						<input type="number" size="4" data-stripe="cvc" style="width:100%">
					</div>
				</div>

				<div class="form-group" style="width: 320px">
					<div class="label-block" style="width:100%">
						<label> <span>Billing Zip</span></label>
					</div>
					<div class="input-block" style="width:100%">
						<input type="number" size="6" data-stripe="address_zip" style="width:100%">
					</div>
				</div>
				 <div class="form-group" style="width: 320px">
					<input type="submit" class="submit" value="Submit Payment" style="float:right;margin-top:10px;border-radius:3px;background:#222;color:#fff;padding:5px 10px;" >
				</div>
			</form>
			<div id="loading">
				<img id="loading-image" src="loading.gif" />
			</div>
			<div id="success">
				<div class="inner-wrap">
				<p>
					Payment Successful!
				</p>
				<img id="success-image" src="image/ok.png" />
				
				<p>
					Click on back to exit this window.
				</p>
			</div>
			</div>
		<div id="error" style="display:none;">
				<img id="error-image" src="image/tryagain.jpg" />
			</div>
			<div>
		</div>
		</div>	
		</div>
		
	</div>
	<!--<div class="back">Back</div>
	<nav class="nav navbar navbar-footer footer">
			<p>&copy 2017 Video Request Line</p>
	</nav>-->
	<script>
			$(function() {
				var $form = $('#payment-form');
				$form.submit(function(event) {
				
					$('#loading').show();
					// Disable the submit button to prevent repeated clicks:
					//$form.find('.submit').prop('disabled', true);
					// Request a token from Stripe:
					Stripe.card.createToken($form, stripeResponseHandler);
					// Prevent the form from being submitted:
					return false;
				});
			});
			function stripeResponseHandler(status, response) {
				//preventDefault();
				// Grab the form:
				var $form = $('#payment-form');
				if (response.error) {
					// Show the errors on the form:`
					$form.find('.payment-errors').text(response.error.message);
					$form.find('.submit').prop('disabled', false);
					$('#loading').hide();
					// Re-enable submission

				} else {
					$('#loading').hide();
					// Get the token ID:
					var token = response.id;
					var mydata = {
						"stripeTokenType" : "card",
						"stripeEmail" : '',
						"status" : "Approved",
						"amount" : '229',
						"VideoReqId" : 'null',
						"requestToProfileId" : '973',
						"requestByProfileId" : '',
						"stripeToken" : token
					};
					
					//Ti.API.info("json----"+JSON.stringify(mydata));
					
					//alert(response.preventDefault());
					$.ajax({
						url : "{{ url('/Payment') }}",
						type : 'post',
						dataType: "JSON",
						//headers: {'X-Requested-With': 'XMLHttpRequest'},
						 //crossDomain: true,
						data : mydata,
						success : function(response) {
							alert('hi');
							var data=JSON.parse(response);
							alert(JSON.stringify(data));
							if (data.return_message == "Payment succesfully") {
								$('#success').show();
							} else {
								$('#error').css('display','block');
							}
							return false;
						},
						error : function(response, ajaxOptions, thrownError) {
							console.log( 'StringError: ' + ajaxOptions + '\n\nthrownError: ' + JSON.stringify(thrownError) + '\n\nResponse: ' + JSON.stringify(response));
						}
						
					});
					flag = 1;
					// Insert the token ID into the form so it gets submitted to the server:
					//$form.append($('<input type="hidden" name="stripeToken">').val(token));
					// Submit the form:
					//$form.get(0).submit();
					//setTimeout(alert(token), 10000);
				}
			};
		</script>
		<script type="text/javascript">
			Stripe.setPublishableKey('pk_live_ibbVEpwDbfWJAboByQ6Kvygy');
		</script>
	</body>
</html>