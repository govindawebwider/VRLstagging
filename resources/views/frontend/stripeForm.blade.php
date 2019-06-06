
<?php //dd($req_detail);?>
<!DOCTYPE html>
<html lang="en">
<head>
  @include('frontend.common.head')

</head>
<body class="cb-page request_new_video">
  <div class="cb-pagewrap"> @include('frontend.common.header')
    <section id="mian-content">
      <div class="container">
        <div class="cb-grid">
          <div class="cb-block cb-box-35 main-content ">
            <div class="cb-content">
              <div class="inner-block">
                <div class="artist_content"> <a href="{{URL($user_detail->profile_url)}}"> <img src="/{{$user_detail->profile_path}}"/>
                  <h4>{{$user_detail->Name}}</h4>
                </a> </div>
              </div>
            </div>
          </div>
          <div class="cb-block cb-box-65 main-content ">
            <div class="cb-content">
              <div class="inner-block">
                <div class="request_new_video_form">
                  <input type="hidden" name="artist_id" value="{{$user_detail->ProfileId}}" id="artist_id" class="artist_id">

                  <h2 class="heading"><span>Payment</span></h2>
                  <?php //dd($detail);?>
                  @if(Session::has('success'))
                  <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>{{Session::get('success') }} </div>
                  @endif
                  @if(Session::has('error'))
                  <div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>
                  @endif
                  <div class="request-form">

                    <!-- Stripe  custome form -->
                    <form action="/stripe_payment" method="POST" id="payment-form" class="form-horizontal">
                      <span class="payment-errors"></span>
                      <input type="hidden" name="artist_id" value="{{$user_detail->ProfileId}}">
                      <input type="hidden" name="artist_vidoe_price" value="<?php echo Session::get('post_video_price'); ?>">
                      <div class="row-centered">
                       <div class="col-md-12">
                        <div class="page-header">
                         <input type="hidden" name="user_id" value="<?php echo Session::get('post_user_id');?>">
                         <input type="hidden" name="artist" value="<?php echo Session::get('post_artist');?>">
                         <input type="hidden" name="song_name1" id="ddselect" value="<?php echo Session::get('post_song_name1');?>">
                         <input type="hidden" name="song_name2" value="<?php echo Session::get('post_song_name2');?>"> 
                         
                         <input type="hidden" id="new_song_name" name="song_name3" value="<?php echo Session::get('post_song_name3');?>" id="song_name">
                         <input type="hidden" name="user_name" id="user_name" value="<?php echo Session::get('post_myusername');?>" >
                         <input type="hidden" name="pronun_name" id="pronun_name" value="<?php echo Session::get('post_pronun_name');?>"> 
                         <input type="hidden" name="user_email" id="user_email" value="<?php echo Session::get('post_useremail');?>" >
                         <input type="hidden" name="recei_email" id="recei_email" value="<?php echo Session::get('post_recei_email');?>">
                         
                         <input type="hidden" name="sender_name" id="sender_name" value="<?php echo Session::get('post_sender_name');?>"> 
                         <input type="hidden" name="sender_name_pronun" id="sender_name_pronun" value="<?php echo Session::get('post_sender_name_pronun');?>"> 
                         <input type="hidden" name="sender_email" id="sender_email" value="<?php echo Session::get('post_sender_email');?>">
                         
                         <input type="hidden" name="delivery_date" id="datepicker" value="<?php echo Session::get('post_delivery_date');?>">
                         <input type="hidden" name="Occassion" id="Occassion" value="<?php echo Session::get('post_Occassion');?>">
                         <input type="hidden" name="person_message" id="person_message" value="<?php echo Session::get('post_person_message');?>">
                         <input type="hidden" name="artist_id" id="artist_id" value="<?php echo Session::get('post_artist_id');?>">
                         <input type="hidden" name="post_type" id="post_type" value="<?php echo Session::get('post_type','request_page');?>">
                        <input type="hidden" name="recipient-record" id="post_type" value="<?php echo Session::get('recipient-record');?>">
                         <input type="hidden" name="sender-record" id="post_type" value="<?php echo Session::get('sender-record');?>">

                         <!-- <input type="text" name="video_price" id="video_price" value="<?php echo Session::get('video_price');?>"> -->

                       </div>
                       <noscript>
                         <div class="bs-callout bs-callout-danger">
                          <h4>JavaScript is not enabled!</h4>
                          <p>This payment form requires your browser to have JavaScript enabled. Please activate JavaScript and reload this page. Check <a href="http://enable-javascript.com" target="_blank">enable-javascript.com</a> for more informations.</p>
                        </div>
                      </noscript>

                      <div class="alert alert-danger" id="a_x200" style="display: none;"> <strong>Error!</strong> <span class="payment-errors"></span> </div>
                      <span class="payment-success">
                      </span>
                      
                      <fieldset class="payment_form">
                       <legend>Please enter your Credit Card info below</legend>
                       <div class="form-group">
                        <label class="col-sm-4 control-label"  for="textinput">Card Holder's Name</label>
                        <div class="col-sm-7">
                         <input type="text" name="cardholdername" maxlength="70" placeholder="Card Holder Name" class="card-holder-name form-control">
                       </div>
                     </div>

                     <div class="form-group">
                      <label class="col-sm-4 control-label" for="textinput">Card Number</label>
                      <div class="col-sm-7">
                       <input type="text" id="cardnumber" maxlength="16" placeholder="Card Number" class="card-number form-control">
                     </div>
                   </div>

                   <div class="form-group">
                    <label class="col-sm-4 control-label" for="textinput">Card Expiry Date</label>
                    <div class="col-sm-7">
                     <div class="form-inline">
                      <select name="select2" data-stripe="exp-month" class="card-expiry-month stripe-sensitive required form-control selectnew">
                       <option value="00" selected="selected">Enter Month</option>
                       <option value="01">01</option>
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
                       <option value="00" selected="selected">Enter Year</option>
                     </select>

                   </div>
                 </div>
               </div>

               <div class="form-group">
                <label class="col-sm-4 control-label" for="textinput">CVV/CVV2</label>
                <div class="col-sm-7">
                 <input type="text" id="cvv" placeholder="CVV" maxlength="4" class="card-cvc form-control">
               </div>
             </div>
             <input type="hidden" name="_token" value="{{csrf_token()}}">
             <div class="form-group">
               <div class="control-group">
                <label class="col-sm-4 control-label" for="textinput"></label>
                <div class="col-sm-7">
                    <button class="btn btn-success" type="submit" id="checkStripeTokenAccount">  Process Payment $<?php echo Session::get('post_video_price');?></button>
               </div>
             </div>
           </div>
         </fieldset>
       </form> 
     </div>
   </div>
 </div>
</div>
</div>
</div>
</div>
</section>

<section id="footer">
  <div class="container">
    <div class="cb-grid">
      <div class="cb-block cb-box-50">
        <div class="cb-content  nomargin-top nopadding-bottom nomargin-bottom footer-nav">
          <ul>
            <li><a href="{{URL('help')}}"><span>Help</span></i></a></li>
            <li><a href="{{URL('about-us')}}"><span>About</span></i></a></li>
            <li><a href="{{URL('terms')}}"><span>Terms</span></i></a></li>
            <li><a href="{{URL('privacy')}}"><span>Privacy</span></i></a></li>
          </ul>
        </div>
      </div>
      <div class="cb-block cb-box-50">
        <div class="cb-content social-icon nopadding-bottom  nopadding-top">
          <ul>
            <!-- <li><a href="#"><i class="fa fa-facebook"></i></a></li>
            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
            <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
            <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
            <li><a href="#"><i class="fa fa-pinterest"></i></a></li> -->

            <li><a href="https://www.facebook.com/vid.req.9" target="_blank"><i class="fa fa-facebook"></i></a></li>
            <li><a href="https://www.twitter.com/videorequestline" target="_blank"><i class="fa fa-twitter"></i></a></li>
            <!-- <li><a href="https://twitter.com/CBAccount" target="_blank"><i class="fa fa-twitter"></i></a></li> -->
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div id="copyright">
    <div class="container">
      <div class="cb-grid">
        <div class="cb-block">
          <div class="cb-content copyright  nopadding-bottom  nopadding-top">

            <p>© {{date('Y')}} @ Video Request Line All Rights Reserved</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


{!! Html::script('js/jquery-1.10.2.min.js') !!}
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

{!! Html::script('js/jquery.inputmask.bundle.min.js') !!}
{!! Html::script('js/jquery.date-dropdowns.js ') !!}
<script type="text/javascript">


  $('#date_ofbirth').attr('readonly', 'readonly');
  $( document ).ready(function() {
    $('#new_song').click(function(){
      $('#new_song_name').show();
      $('#ddselect').hide();
      //alert('test');
      $('#new_song').hide();

    });
    $('#more').hide();
    $('#less').click(function(){
      $('#more').show();
      ///$('#less').hide();
      $(this).hide();
    });
    $('#hide_less').click(function(){
      $('#more').hide();
      $('#less').show();
    });


    $('#new_song_name').hide();

    $('#newsongs').click(function(){
      alert();
      $('#ddselect').hide();
      $('#new_song_name').show();
    });
    $('#completed').on('click',function () {
      //alert('test');
      $('#completed').addClass('active');

      $('#approved').removeClass('active');
      $('#pending').removeClass('active');
      $('#reject').removeClass('active');
      $('#all').removeClass('active');

      $('.complete_block').show();

      $('.approve_block').hide();

      $('.pending_block').hide();

      $('.reject_block').hide();

    })







    $('.req_video_del').on('click',function () {
      //alert('test');

      $('.out_mess_del_video').addClass('delete_video');

    })

    $('.out_mess_del_video .ms_close').on('click',function () {

      $('.out_mess_del_video').removeClass('delete_video');

    })


  });
</script>
@if(Auth::check())
@if(Auth::user()->type =="Artist" OR Auth::user()->type =="Admin")

<script type="text/javascript">
  setInterval(function() {
    var url = "{{URL('/')}}" ;
    $.ajax({
      url: url+"/check_user_auth",
      cache: false, 
      success: function(result){ 
        if(result === "false")
        {
          window.location.href="https://www.videorequestline.com/getLogout";
        }
      }
    });
  },1000);
</script>
@endif  
<!-- @if(Auth::user()->type == "User")
<script type="text/javascript">
  setInterval(function() {
    $.ajax({
      url: "/check_user_auth",
      cache: false, 
      success: function(result){ 
        if(result === "false"){
          window.location.href="https://www.videorequestline.com/getLogout";
        }
      }
    });
  },1000);
</script>
@endif   -->
@endif
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{{--<script>
  $( function() {
    $(document).ready(function(){
      var myData = $('#artist_id').val();
      $.ajax({
        url: "https://www.videorequestline.com/testby/"+myData,
        type: "GET",
        dataType: "html",
        success: function(result){
          da= parseInt(result);
          $("#datepicker").datepicker({ maxDate: "+30Y",
            minDate: da});
        },
        error: function (jqXHR, status, err) {
            //alert("Local error callback.");
          }
        });
    });
  });
</script>--}}


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
   $form.find('.payment-errors').text(response.error.message);
    $form.find('#checkStripeTokenAccount').prop('disabled', false); // Re-enable submission

  }
  else { 
   var token = response.id;
   $form.append($('<input type="hidden" name="stripeToken">').val(token));
   $form.get(0).submit();
 }
};

var $form = $('#payment-form');
$form.submit(function(event) {
 $form.find('#checkStripeTokenAccount').prop('disabled', true);
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
});


</script>

</div>
</body>
</html>