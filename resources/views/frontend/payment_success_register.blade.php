<!DOCTYPE html>
<html lang="en">
<head>
  @include('frontend.common.head')
</head>
<body class="cb-page payment_success">
  <div class=" ">
     @include('frontend.common.header')
  </div>
  <section id="mian-content">
    <div class="row purple thanksbox">
      </div>
      <div class="container">
        <div class="row">
           <div class="col-xs-12 ">
            <div class="col-md-8 col-sm-12 col-md-offset-2">
                <div class="panel panel-default  Thank_You">
                  <h4 class="head">Thank You!</h4>
                  <hr>
                  <p>Your payment and registration has been successfully done. Now you can track your video status from dashboard, rest of time you can also request another video.</p>
                   <div class="visitloginpage">Click here to <a href="/login"> Login</a></div>
                </div>
            </div>
         </div>
      </div>
        <!--<div class="cb-grid">
         <div class="request_video_listing_wrap">
           <div class="cb-block cb-box-50 main-content"> 
             <h1>Thank You!</h1>
             <p>Your payment and registration has been successfully done. Now you can track your video status from dashboard, rest of time you can also request another video.</p> 
             <p>Click here to <a href="/login"> Login</a></p> 
           </div>
         </div>
       </div>-->
     </div>
      @include('frontend.common.footer')
  </section>
</body>
</html>