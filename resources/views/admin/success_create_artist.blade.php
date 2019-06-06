@include('admin.common.header')

<body class="admin usert_list">

  <section class="main-page-wrapper">

    <div class="main-content">

      <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>

      @include('admin.layouts.header')

      <div class="usert_list_wrap">

        <div  class="col-md-12 ">

          <div id="page-wrapper">

            <div class="graphs">

              <a class="btn btn-primary" href="{{URL('/create_artist')}}">create another artist</a>



              <div class="usert_list_wrap form-horizontal">

               <h1>Thank you!</h1>

               <i class="fa fa-check-circle-o fa-3x" aria-hidden="true"></i>

               <p class="thank-reg">Registering as an artist on VRL.</p>

               <p>Artist login information has been emailed to Artist Email id. We look forward to helping you make money by selling personalized videos to Artist fans.</p>

             </div>

           </div>

         </div>

       </div>

     </div>

   </div>

   <div id="videoContainer" style="display:none;">

    <div class="pop-inner"> <i class="icon icon-cancel-circle close-pop"></i>

      <iframe frameborder="0" allowfullscreen wmode="Opaque" id="play_vid"></iframe>

    </div>

  </div>

</section>

@include('admin.common.footer')

</body>

</html>