<!DOCTYPE html>

<html lang="en">

<head>

  @include('frontend.common.head')

</head>

<body class="cb-page payment_success">

  <div class="cb-pagewrap">
      @include('frontend.common.header')

    <section id="mian-content">

      <div class="container">

        <div class="cb-grid">

         <div class="request_video_listing_wrap">

           <div class="cb-block cb-box-50 main-content"> 

             <h1>Thank You!</h1>
             @if(Session::has('msg'))
             {{Session::get('msg') }} 
             @endif

             @if(Session::has('error'))
             {{Session::get('error') }}
             @endif

  

          
           <p>Click here to <a href="/user_dashboard"> Dashboard</a></p>

           <!-- <p>Click here to <a href="/login"> Login</a></p> -->

         </div>

       </div>

     </div>

   </div>

 </section>



 @include('frontend.common.footer') 

</div>



</body>

</html>