<!DOCTYPE html>
<html lang="en">
<?php $page = 'ARTIST PROFILE'; ?>
    <script language="JavaScript">
      function disableCtrlKeyCombination(e)
      {
                //list all CTRL + key combinations you want to disable
                var forbiddenKeys = new Array("a", "s", "c");
                var key;
                var isCtrl;
                if(window.event)
                {
                        key = window.event.keyCode;     //IE
                        if(window.event.ctrlKey)
                          isCtrl = true;
                        else
                          isCtrl = false;
                      }
                      else
                      {
                        key = e.which;     //firefox
                        if(e.ctrlKey)
                          isCtrl = true;
                        else
                          isCtrl = false;
                      }
                //if ctrl is pressed check if other key is in forbidenKeys array
                if(isCtrl)
                {
                  for (i = 0; i < forbiddenKeys.length; i++)
                  {
                                //case-insensitive comparation
                                if (forbiddenKeys[i].toLowerCase() == String.fromCharCode(key).toLowerCase())
                                {
//                                    alert("Key combination CTRL + "
//                                                + String.fromCharCode(key)
//                                                + " has been disabled.");                                    
return false;
}
}
}
return true;
}
</script>
<head>
  @include('frontend.common.head')
  <style type="text/css">
     
  </style>

  <script language=JavaScript> 
    var message="Function Disabled!";
    function clickIE4()
    { 
      if (event.button==2)
      { 
          //alert(message); return false;
        }
      } 
      function clickNS4(e){ 
        if (document.layers||document.getElementById&&!document.all){
         if (e.which==2||e.which==3){
          //alert(message); return false; 
        } 
      } 
    } 
    if (document.layers){ 
      document.captureEvents(Event.MOUSEDOWN); 
      document.onmousedown=clickNS4;
    } else if (document.all&&!document.getElementById){
     document.onmousedown=clickIE4;
   } 
   document.oncontextmenu=new Function("return false") 
 </script>
</head>
@if (count($errors) > 0)
<body class="cb-page home active video-request-form-active" ondragstart="return false" onselectstart="return false" onkeypress="return disableCtrlKeyCombination(event);" onkeydown="return disableCtrlKeyCombination(event);" >
  @elseif(Session::has('error'))
  <body class="cb-page home active video-request-form-active" ondragstart="return false" onselectstart="return false" onkeypress="return disableCtrlKeyCombination(event);" onkeydown="return disableCtrlKeyCombination(event);" >
    @else
    <body class="cb-page home" onkeypress="return disableCtrlKeyCombination(event);" onkeydown="return disableCtrlKeyCombination(event);" >
      @endif
 @include('frontend.common.header')
    <section id="main-content">
      <div class="row bookingcheckoutbg paddings">
        <div class="container">
          <div class="col-md-12 col-sm-12 col-lg-12  "> 
          <h4 class="white-text  book-checkout">Booking Checkout</h4>
          </div>
        </div> 
      </div>
 <div class="container">
    <div class="row"> 
  <div class="col-md-12 col-sm-12 col-lg-12  "> 
        <!--   <div class="col-md-8 col-sm-12 col-lg-8">
             <div class="col-md-8 col-sm-12 col-lg-12">
                <h3 class="Bookingheading">Gift Shipping details</h3>
                <div class="panel panel-default gift-details"> 
                   <div class="panel-body">
                    <div class="col-sm-12  ">  
                        <div class="col-sm-9 padd-rm">
                           <div class="col-sm-8  ">
                            <div class="form-group">
                            <label>RECIPIENT’S NAME</label>
                              <input type="" name="" placeholder="" class="form-control">
                            </div>
                           </div>
                            <div class="col-sm-4  ">
                              <div class="form-group padd-r">
                                <label>DELIVERY DATE</label>
                                <input type="" name="" placeholder="" class="form-control">
                              </div>
                            </div>
                            <div class="col-sm-5  ">
                              <div class="form-group padd-r">
                                <label>RECIPIENT’S ZIP CODE</label>
                                <input type="" name="" placeholder="" class="form-control">
                              </div>
                            </div>
                        </div>
                       <div class="col-sm-3 padd-rm">
                         <div class="Arrangement"> 
                             <div class="col-sm-12">
                               <h4 class="ArrangementPrice">Arrangement/Price</h4> 
                                <div class="radio radio-primary">
                                    <input type="radio" name="radio1" id="radio1" value="option1" checked="">
                                    <label for="radio1">
                                        As Shown
                                    </label>
                                </div>
                                <div class="radio radio-primary">
                                    <input type="radio" name="radio1" id="radio2" value="option2">
                                    <label for="radio2">
                                        Deluxe + $20
                                    </label>
                                </div>
                                <div class="radio radio-primary">
                                    <input type="radio" name="radio1" id="radio3" value="option2">
                                    <label for="radio3">
                                        Premium + $30
                                    </label>
                                </div> 
                              </div>
                           </div>
                        </div> 
                        </div> 
                  </div>  
                </div> 
              </div>-->
     <div class="hide turnaroundtimevalue" data-turnaroundtime="{{$user_detail->timestamp}}"></div>
     <div class="col-md-8 col-sm-12 col-lg-8">
          <h3 class="Bookingheading">Payment</h3>
        <div class="panel panel-default paddings Paymentdetails gift-details">
           <div class="panel-body payment"> 
                @if(Session::has('success'))
                <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>{{ (Session::get('success')) }} </div>
                @endif
                @if(Session::has('error'))
                <div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ (Session::get('error')) }} </div>
                @endif
                <form action="/stripe_payment" method="POST" id="payment-form">
                    <span class="payment-errors"></span>
                    <input type="hidden" name="artist_id" value="{{$user_detail->ProfileId}}" id="artist_profile_id">
                    <input type="hidden" name="artist_vidoe_price" value="{{Session::get('post_video_price')}}" id="video_price">
                    <div class="row-centered">
                      <input type="hidden" name="user_id" value="<?php echo Session::get('post_user_id');?>">
                      <input type="hidden" name="artist" value="<?php echo Session::get('post_artist');?>">
                     <!--  <input type="hidden" name="song_name"  value="<?php echo Session::get('post_song_name');?>">
                     
                      <input type="hidden" name="user_name" id="user_name" value="<?php echo Session::get('post_myusername');?>" >
                      <input type="hidden" name="pronun_name" id="pronun_name" value="<?php echo Session::get('post_pronun_name');?>"> 
                      <input type="hidden" name="user_email" id="user_email" value="<?php echo Session::get('post_useremail');?>" > -->
                      <input type="hidden" name="recei_email" id="recei_email" value="<?php echo Session::get('post_recei_email');?>">
                       
                    <!--   <input type="hidden" name="sender_name" id="sender_name" value="<?php echo Session::get('post_sender_name');?>"> 
                      <input type="hidden" name="sender_name_pronun" id="sender_name_pronun" value="<?php echo Session::get('post_sender_name_pronun');?>"> 
                      <input type="hidden" name="sender_email" id="sender_email" value="<?php echo Session::get('post_sender_email');?>">

                      <input type="hidden" name="delivery_date" id="datepicker" value="<?php echo Session::get('post_delivery_date');?>">
                      <input type="hidden" name="Occassion" id="Occassion" value="<?php echo Session::get('post_Occassion');?>">
                      <input type="hidden" name="person_message" id="person_message" value="<?php echo Session::get('post_person_message');?>"> -->
                      <input type="hidden" name="artist_id" id="artist_id" value="<?php echo Session::get('post_artist_id');?>">
                      <input type="hidden" name="post_type" id="post_type" value="<?php echo Session::get('post_type','request_page');?>">
                      <input type="hidden" name="recipient-record" id="post_type" value="<?php echo Session::get('recipient-record');?>">
                      <input type="hidden" name="sender-record" id="post_type" value="<?php echo Session::get('sender-record');?>">
                      <input type="hidden" name="is_hidden" value="<?php echo Session::get('is_hidden');?>">
                       <!-- <input type="text" name="video_price" id="video_price" value="<?php echo Session::get('video_price');?>"> -->

                      <noscript>
                        <div class="bs-callout bs-callout-danger">
                          <h4>JavaScript is not enabled!</h4>
                          <p>This payment form requires your browser to have JavaScript enabled. Please activate JavaScript and reload this page. Check <a href="http://enable-javascript.com" target="_blank">enable-javascript.com</a> for more informations.</p>
                        </div>
                      </noscript>

                      <div class="alert alert-danger" id="a_x200" style="display: none;"> <strong>Error!</strong>
                        <span class="payment-errors"></span> 
                      </div>
                      <span class="payment-success">
                      </span>
                    </div>


                    <div class="col-sm-12 paddings-n">
                      <div class="col-sm-8">
                       <!--  <div class="radio radio-primary">
                            <input type="radio" name="Credit" id="Credit" value="CreditCard" checked="">
                            <label for="Credit">
                                Credit Card
                            </label>
                         </div> -->
                          <label class="credit-card" for="Credit">
                                Credit Card
                          </label>
                          <!-- <p class="safemoney">Safe money transfer using your bank account. Visa, Maestro, Discover, American Express.</p> -->
                      </div>
                      <div class="col-sm-4 right">
                          <img src="/images/credit-cards_amex.png" class="img">
                          <img src="/images/credit-cards_visa.png" class="img">
                          <img src="/images/credit-cards_mastercard.png" class="img">
                      </div>
                      <div class="col-xs-12">
                        <p class="safemoney">Safe money transfer using your bank account. Visa, Maestro, Discover, American Express.</p>
                      </div>
                      <div class="col-sm-12 payment-form-padd">
                        <div class="form-group">
                          <label>Card Number</label>
                          <div class="input-group">
                            <input type="tel" id="cardnumber" maxlength="16" placeholder="0000 0000 0000 0000" class="card-number form-control" autocomplete="cc-number" required autofocus name="number" onfocus="this.placeholder = ''" 
                             onblur="this.placeholder = '0000 0000 0000 0000'">
                            <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                          </div>
                        </div>
                        <div class="col-sm-12 padd-rm">
                          <div class="col-sm-6 padd-rm">
                            <div class="form-group padd-r">
                              <label>Name on Card</label>
                              <input type="" name="cardholdername" placeholder="" class="card-holder-name form-control">
                            </div>
                          </div>
                          <div class="col-sm-3 padd-rm">
                            <div class="form-group padd-r">
                              <label>Expiry Date</label>
                              <input type="tel" id="date" maxlength="5"  name="" placeholder="MM / YY" autocomplete="cc-exp" required  class="exp-date form-control" onfocus="this.placeholder = ''" 
                              onblur="this.placeholder = 'MM / YY'">
                            </div>
                          </div>
                          <div class="col-sm-3 padd-rm">
                            <div class="form-group">
                              <label>CVV</label>
                              <div class="input-group">
                                <input type="tel" name="" maxlength="3" placeholder=""  autocomplete="cc-csc" required class="card-cvc form-control">
                                <span class="input-group-addon"><i class="fa fa-question-circle-o"></i></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <hr class="grey-text">
                    <!-- <div class="form-group">
                      <div class="col-sm-12 paddings-n">
                        <div class="col-sm-8">
                          <div class="radio radio-primary">
                            <input type="radio" name="pay" id="pay" value="CreditCard"  >
                            <label for="pay">
                                PayPal
                            </label>
                           </div>
                            <p class="grey-text padd">You will be redirected to PayPal website to complete your purchase securely.</p>
                         </div>
                       
                        <div class="col-sm-4">
                          <img src="images/paypal.png" class="img pull-right">
                        </div>
                      </div>
                    </div>
                    <hr class="grey-text"> -->
                    
                    <div class="form-group">
                      <div class="col-sm-12 paddings-n">
                        <div class="col-sm-7">
                          <a href="{{ $user_detail->profile_url}}" class="artist-name">
                            <i class="fa fa-arrow-left"></i> 
                            <span class="ReturnProfile">Return to Actors Profile</span>
                          </a>
                        </div>
                        <div class="col-sm-5 text-right">
                          <input type="hidden" name="_token" value="{{csrf_token()}}">
                          <button class="btn btn-submit-payment" type="submit" id="checkStripeTokenAccount"> COMPLETE ORDER </button>
                        </div>
                      </div>
                    </div>
                    <div class="paddings"></div>
                 
                </div> 
             </div>
        </div> 
    
      <div class="col-md-4 col-sm-12 col-lg-4">
              <h3 class="Bookingheading">Booking Summary</h3>
                <div class="panel panel-default BookingSummary">
                   <div class="panel-body">
                      <h3 class="Wondeetext"><?php echo (Session::get('post_artist_name'));?></h3>
                      <div class="form col-sm-10 paddingoffset-0">
                        <div class="col-sm-12  ">
                          <div class="form-group padd-r">
                            <label class="user-video-text">Who is this video for?</label>
                            <input type="text" name="user_name" class="user-input" value="<?php echo Session::get('post_myusername');?>" onblur="textCounter(this, 50, 'for-whom');" onkeyup="textCounter(this, 50, 'for-whom');">
                              <p class="for-whom limit-text-align"></p>
                            @if($errors->first('user_name'))
                              <p class="label label-danger" >
                                {{ ($errors->first('user_name')) }}
                              </p>
                            @endif
                          </div>
                        </div> 
                       <!--  <div class="col-sm-12  ">
                          <div class="form-group padd-r">
                            <label class="Whovideofor">Instructions</label> 
                            <textarea type="text" name=""  class="forminput" placeholder="It's James 36th Birthday" onfocus="this.placeholder = ''" 
                             onblur="this.placeholder = 'It's James 36th Birthday'"></textarea>
                          </div>
                        </div>  -->

                        <div class="col-sm-12  ">
                          <div class="form-group padd-r">
                            <label class="user-video-text">Recipient Name Pronunciation  </label>
                            <input type="text" size="23" name="pronun_name"  
                            value="<?php echo Session::get('post_pronun_name');?>"
                            class="user-input" > 
                             @if($errors->first('pronun_name'))
                              <p class="label label-danger" >
                                {{ ($errors->first('pronun_name')) }}
                              </p>
                              @endif 
                          </div>
                        </div>
                        <div class="col-sm-12  ">
                          <div class="form-group padd-r">
                            <label class="user-video-text">Recipient Email Address</label>
                            <input type="text" name="user_email" size="23" id="user_email"  
                            value="<?php echo Session::get('post_useremail');?>" class="user-input" >
                            @if($errors->first('user_email'))
                              <p class="label label-danger" >
                                {{ ($errors->first('user_email')) }}
                              </p>
                            @endif     
                          </div>
                        </div>
                        <div class="col-sm-12  ">
                          <div class="form-group padd-r">
                            <label class="user-video-text">Your Name</label>
                            <input type="text" size="23" name="sender_name" id="sender_name" value="<?php echo Session::get('post_sender_name');?>" class="user-input" {{!is_null(Auth::user()) ? 'readonly' : ''}}
                            onblur="textCounter(this, 50, 'your-name');" onkeyup="textCounter(this, 50, 'your-name');">
                              <p class="your-name limit-text-align"></p>
                            @if($errors->first('sender_name'))
                              <p class="label label-danger" >
                                {{ ($errors->first('sender_name')) }}
                              </p>
                            @endif   
                          </div>
                        </div>
                        <div class="col-sm-12  ">
                          <div class="form-group padd-r">
                            <label class="user-video-text">Your Name Pronunciation  </label>
                            <input type="text" size="23" name="sender_name_pronun" 
                            value="<?php echo Session::get('post_sender_name_pronun');?>"  class="user-input" > 
                            @if($errors->first('sender_name_pronun'))
                            <p class="label label-danger" >
                              {{ ($errors->first('sender_name_pronun')) }}
                            </p>
                            @endif  
                          </div>
                        </div>
                        <div class="col-sm-12  ">
                          <div class="form-group padd-r">
                            <label class="user-video-text"> Your Email</label>
                            <input type="text" size="23" name="sender_email" id="sender_email" value="<?php echo Session::get('post_sender_email');?>" class="user-input" {{!is_null(Auth::user()) ? 'readonly' : ''}}>
                            @if($errors->first('sender_email'))
                            <p class="label label-danger" >
                              {{ ($errors->first('sender_email')) }}
                            </p>
                            @endif   
                          </div>
                        </div>
                        <div class="col-sm-12  ">
                          <div class="form-group padd-r">
                            <label class="user-video-text">Desired Song Name</label>
                            <input type="text" size="23" name="song_name" value="<?php echo Session::get('post_song_name');?>" id="song_name" class="user-input"
                                   onblur="this.placeholder = 'Desired Song Name'" onblur="textCounter(this, 70, 'song-name');" onkeyup="textCounter(this, 70, 'song-name');">
                              <p class="song-name limit-text-align"></p>
                            @if($errors->first('song_name'))
                            <p class="label label-danger" >
                              {{ ($errors->first('song_name')) }}
                            </p>
                            @endif 
                          </div>
                        </div>
                        @if(count($occasions)>0)
                          <div class="form-group">
                              <label class="user-video-text">Occasion(s)</label>
                              {!! Form::select('occasion_id', [0=>'Select Occasion']+$occasions, Session::get('post_occasion_id'), ['class' => 'form-control', 'id'=>"occasion_id", 'data-live-search'=>"true"]) !!}
                              @if($errors->first('occasion'))
                                  <p class="label label-danger" >
                                      {{ $errors->first('occasion') }}
                                  </p>
                              @endif
                          </div>
                          @endif
                          <input type="hidden" name="Occassion" id="Occassion" value="{{Session::get('post_Occassion')}}">
                        <div class="col-sm-12  ">
                          <div class="form-group padd-r">
                            <label class="user-video-text">Price</label>
                              <input type="text" size="23" name="price" id="price" value="<?php echo Session::get('post_video_price');?>" class="user-input">
                          </div>
                        </div>
                        <div class="col-sm-12  ">  
                          <div class="form-group padd-r">
                            <label class="user-video-text">Phone (Optional)</label>
                            <input type="text" name="phone" size="23" id="phone" value="<?php echo Session::get('post_phone');?>" class="user-input" >
                          </div>
                        </div>
                        <div class="col-sm-12  ">  
                          <div class="form-group padd-r">
                            <label class="user-video-text">Personalized Message</label>
                            <textarea type="text" size="23" name="person_message" id="person_message" class="user-input" onblur="this.placeholder = 'It`s James 36th Birthday'"
                                   onblur="textCounter(this, 200, 'message_limit');" onkeyup="textCounter(this, 200, 'message_limit');"><?php echo Session::get('post_person_message');?></textarea>
                              <p class="message_limit limit-text-align"></p>
                            @if($errors->first('person_message'))
                            <p class="label label-danger" >
                              {{ ($errors->first('person_message')) }}
                            </p>
                            @endif
                          </div>
                        </div>
                        <div class="col-sm-12  ">   
                          <div class="form-group padd-r">
                            <label class="user-video-text">Delivery Date</label>
                            <input type="text" size="23" name="delivery_date" id="datepicker" value="<?php echo Session::get('post_delivery_date');?>" class="user-input delivery_datepicker" placeholder="mm/dd/yyyy" >
                              @if($errors->first('delivery_date'))
                                <p class="label label-danger" >
                                  {{ ($errors->first('delivery_date')) }}
                                </p>
                              @endif
                          </div> 
                        </div>
                      </div>
                  </div>
              </div>
              </form>
        </div>
     </div> 
     </div>
   </div>
  </div>

 
    <!--  <div class="row paddings join-us-bg">
        <h4 class="amazingtalents text-center  ">We are looking for amazing talents like you</h1>
        <div class="col-md-offset-5 paddingupbottom">
          <a href="" data-toggle="modal" data-target="#exampleModalCenterJOIN" class="JOINUS">JOIN US NOW!</a>
        </div>
      </div>  -->
    </section>
@include('frontend.common.footer')

<script> 
$('#play-pause-button').click(function () {
   if ($("#myVideo").get(0).paused) {
       $("#myVideo").get(0).play();
   } else {
       $("#myVideo").get(0).pause();
  }
});

  function valueChanged()
  {
    if($('.showdiv').is(":checked"))   
        $(".gift-details").show();
    else
        $(".gift-details").hide();
  }

</script> 


<script type="text/javascript" src="https://js.stripe.com/v2/"></script> 
<script type="text/javascript">
    <?php
    if (App::environment('local') || App::environment('testing')) {
        // The environment is local or testing
       $key =  Config::get('constants.STRIPE_KEYS.test.publishable_key');
    } else {
        // The environment is production
        $key = Config::get('constants.STRIPE_KEYS.live.publishable_key');
    }
    ?>
Stripe.setPublishableKey('{{$key}}');
</script>
<script type="text/javascript">
 var select = $(".card-expiry-year"),
 year = new Date().getFullYear();

 for (var i = 0; i < 12; i++) {
  select.append($("<option value='"+(i + year)+"' "+(i === 0 ? "" : "")+">"+(i + year)+"</option>"))
}
</script>

<script>
$( document ).ready(function() {
  $('#date').bind('keyup','keydown', function(event) {
    var inputLength = event.target.value.length;
    if (event.keyCode != 8){
      if(inputLength === 2 ){
        var thisVal = event.target.value;
        thisVal += '/';
        $(event.target).val(thisVal);
      }
    }
  })
});
</script>

<script>
 function stripeResponseHandler(status, response) {
  var $form = $('#payment-form');
  if (response.error) { 
   $form.find('.payment-errors').text(response.error.message);
    $form.find('#checkStripeTokenAccount').prop('disabled', false); // Re-enable submission
  } else {
   var token = response.id;
   $form.append($('<input type="hidden" name="stripeToken">').val(token));
   $form.get(0).submit();
 }
};

var $form = $('#payment-form');
$form.submit(function(event) {
 
var exp = $('.exp-date').val();
var month = exp.substring(0, exp.lastIndexOf("/"));
var year = exp.substring(exp.lastIndexOf("/") + 1, exp.length);

$form.find('#checkStripeTokenAccount').prop('disabled', true);
 Stripe.card.createToken({
  number: $('.card-number').val(),
  cvc: $('.card-cvc').val(),
  exp_month: month ,
  exp_year: year,
  name: $('.card-holder-name').val(),
  address_line1: $('.address').val(),
  address_city: $('.city').val(),
  address_zip: $('.zip').val(),
  address_state: $('.state').val(),
  address_country: $('.country').val()
}, stripeResponseHandler);
 return false;
});
 $("#occasion_id").change(function() {
     var id = $(this).val();
     var artist_profile_id = $('#artist_profile_id').val();
     var optionText = $('option:selected', this).text();
     $('#Occassion').val(optionText);
     $.get("occasions/get-price?id="+id+"&artist_profile_id="+artist_profile_id, function(data){
         $('#price').val(data);
         $('#video_price').val(data);
     });
 });
</script>

</body>
</html>
