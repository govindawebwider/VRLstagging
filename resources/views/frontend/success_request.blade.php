<!DOCTYPE html>
<html lang="en">
<head>
@include('frontend.common.head')
</head>
<body class="cb-page success_payment">
<div class="cb-pagewrap"> @include('frontend.common.header')
  <section id="mian-content">
    <div class="container">
      <div class="cb-grid">
        <div class="cb-block cb-box-50 main-content">
        	<h1>Thank You!</h1>
			
			     @if(Auth::check())
              <!-- <p>Click here to <a href="/userDashboard"> Dashboard</a></p>  -->
              <p>"For requesting video, an Email has been sent to your email Id " Thank you for your request. We've sent the artist your request and you will receive a reply once approved and completed.</p>
              <!-- <p>For requesting video, an Email has been sent to your email Id .</p> -->
              <p>Go to <a href="/userDashboard">  My Requested Videos</a></p> 
            @else
              <p>For requesting video, an Email has been sent to your email Id " Thank you for your request. We've sent the artist your request and you will receive a reply once approved and completed.</p>
              <p>Click here to <a href="/login"> Login</a></p> 
            @endif  
			
        </div>
      </div>
    </div>
  </section>
  @include('frontend.common.footer') </div>
</body>
</html>