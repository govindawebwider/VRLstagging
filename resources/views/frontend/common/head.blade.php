<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="description" content="">
<title>VRL | </title>
<!-- core CSS --> 
{!! Html::style('css/font.css') !!}
{!! Html::style('css/bootstrap.css') !!}
{!! Html::style('css/style.css') !!}
{!! Html::style('css/newstyle.css?ver=2') !!}
<!-- {!! Html::style('css/custom.css') !!} -->
{!! Html::style('css/sweetalert.css') !!}
{!! Html::style('css/owl.carousel.css') !!}
{!! Html::style('css/font-awesome.min.css') !!}
{!! Html::style('css/responsive.css') !!}
{!! Html::style('css/fonts.css') !!}
{!! Html::style('css/user-style.css') !!}
{!! Html::style('https://code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css') !!}
{!! Html::style('css/bs-rating.css') !!}
{{ Html::favicon( 'images/favicon.ico' ) }}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<script type="text/javascript" async>
	var onloadCallback = function() {
		grecaptcha.render('html_element', {
			'sitekey' : '6LdERAwUAAAAAHIIBfu8WK8u7SR3gCkdvTp-SjES' 
		});
	};
</script>
<!-- {!! Html::style('/resources/demos/style.css') !!} -->
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="https://js.stripe.com/v3/"></script>

{{--<!-- Abhishek Tandon includes polyfill library for Safari -->--}}
{{--<script src="{{ URL::asset('audio-recorder-polyfill-master/polyfill.min.js') }}"></script>--}}

<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<!--
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
-->
<script type="text/javascript">
/*$.noConflict();
jQuery(document).ready(function() {
 setmyVideo();
});
function setmyVideo() {   
var myVideoPlayer = document.getElementById("myVideo");
 myVideoPlayer.addEventListener('durationchange', function() {
       console.log('Duration change', myVideoPlayer.duration);
       myVideoPlayer.duration
       timeout=myVideoPlayer.duration;
  });  
var myVar = setInterval(setmyVideoTimer, 2300);
function setmyVideoTimer() {  
    if(Math.round(myVideoPlayer.currentTime * 1000)>=23646)
    {    document.getElementById("bannerAnimation").style.display="none"; 
         myVideoPlayer.pause();
        myStopFunction();
    }  
}   myVideoPlayer.play();
     
    myVideoPlayer.onended = function() { 
     document.getElementById("bannerAnimation").style.display="none";
     myVideoPlayer.pause(); 
    };
function myStopFunction() {
    clearInterval(myVar);

});}*/ 
</script>
<script type="text/javascript" src="https://js.stripe.com/v1/"></script>

<script type="text/javascript" async>
	Stripe.setPublishableKey('pk_live_ibbVEpwDbfWJAboByQ6Kvygy');
	$(document).ready(function() {
		function addInputNames() {
                    // Not ideal, but jQuery's validate plugin requires fields to have names
                    // so we add them at the last possible minute, in case any javascript 
                    // exceptions have caused other parts of the script to fail.
                    $(".card-number").attr("name", "card-number")
                    $(".card-cvc").attr("name", "card-cvc")
                    $(".card-expiry-year").attr("name", "card-expiry-year")
                }
                function removeInputNames() {
                	$(".card-number").removeAttr("name")
                	$(".card-cvc").removeAttr("name")
                	$(".card-expiry-year").removeAttr("name")
                }
                function submit(form) {
                    // remove the input field names for security
                    // we do this *before* anything else which might throw an exception
                    removeInputNames(); // THIS IS IMPORTANT!
                    //$ given a valid form, submit the payment details to stripe
                    $(form['submit-button']).attr("disabled", "disabled")
                    Stripe.createToken({
                    	number: $('.card-number').val(),
                    	cvc: $('.card-cvc').val(),
                    	exp_month: $('.card-expiry-month').val(), 
                    	exp_year: $('.card-expiry-year').val()
                    }, function(status, response) {
                    	if (response.error) {
                            // re-enable the submit button
                            $(form['submit-button']).removeAttr("disabled")
                            
                            // show the error
                            $(".payment-errors").html(response.error.message);
                            // we add these names back in so we can revalidate properly
                            addInputNames();
                        } else {
                            // token contains id, last4, and card type
                            var token = response['id'];
                            // insert the stripe token
                            var input = $("<input name='stripeToken' value='" + token + "' style='display:none;' />");
                            form.appendChild(input[0])
                            // and submit
                            form.submit();
                        }
                    });
                    
                    return false;
                }
                
                // add custom rules for credit card validating
                jQuery.validator.addMethod("cardNumber", Stripe.validateCardNumber, "Please enter a valid card number");
                jQuery.validator.addMethod("cardCVC", Stripe.validateCVC, "Please enter a valid security code");
                jQuery.validator.addMethod("cardExpiry", function() {
                	return Stripe.validateExpiry($(".card-expiry-month").val(), 
                		$(".card-expiry-year").val())
                }, "Please enter a valid expiration");
                // We use the jQuery validate plugin to validate required params on submit
                $("#example-form").validate({
                	submitHandler: submit,
                	rules: {
                		"card-cvc" : {
                			cardCVC: true,
                			required: true
                		},
                		"card-number" : {
                			cardNumber: true,
                			required: true
                		},
                        "card-expiry-year" : "cardExpiry" // we don't validate month separately
                    }
                });
                // adding the input field names is the last step, in case an earlier step errors                
                addInputNames();
            });
        </script>



