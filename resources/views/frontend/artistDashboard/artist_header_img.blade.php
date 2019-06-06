@include('admin.common.header')

<body class="admin artist_header_img">

  <section class="main-page-wrapper">

    <div class="main-content">

      <div id="left-side-wrap"> @include('frontend.artistDashboard.layouts.lsidebar') </div>

      <div class="header-section">
          <div class="top-main-left">
              <a href="{{URL('Dashboard')}}"><span class="logo1 white"><img src="/images/vrl_logo_nav.png" class="img img-responsive"></span> </a>
              <a href="javascript:void(0)" class="toggle menu-toggle"><i class="lnr lnr-menu"></i></a>
          </div>

        <div class="menu-right">

          <div class="user-panel-top">

            <div class="profile_details">

              <div class="profile_img">

                <div class="dropdown user-menu"> <span class="dropdown-toggle"> <span class="admin-img">
                            <?php $imageUrl = '';
                            $fileName = 'images/Artist/'.$artist_data->profile_path; ?>
                            @if (\Illuminate\Support\Facades\Storage::disk('s3')->exists($fileName))
                                <?php $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                        ->url($fileName);?>
                            @endif
                            <img src="{{$imageUrl}}" alt="">
                            {{--<img src="{{url('images/Artist/').'/'.$artist_data->profile_path}}" alt=""> --}}
                        </span> <span class="admin-name">{{$artist_data->Name}}</span> <i class="arrow"></i> </span>

                  <ul>
                   @if(session('current_type') == 'Artist')
                   <li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>Switch to Admin</span></a></li>
                   @endif
                   <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{ URL($artist_data->profile_url)}}"> <i class="icon icon-users"></i> <span>view Profile</span> </a> </li>
                   @if($user->admin_link=='yes')

                   <li class="{{ Request::is('Switch to Admin') ? 'active' : '' }}"> <a href="{{URL('/login_admin')}}"> <i class="icon icon-users"></i> <span>Switch to Admin</span> </a>

                   </li>

                   @endif

                   <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{URL('ProfileUpdate')}}"> <i class="icon icon-users"></i> <span>Edit Profile</span> </a> </li>

                   <li class="{{ Request::is('change-password') ? 'active' : '' }}"> <a href="{{URL('change-password')}}"> <i class="icon icon-lock"></i> <span>Change Password</span> </a> </li>

                   <li class="{{ Request::is('getLogout') ? 'active' : '' }}"> <a href="{{ URL::route('getLogout') }}"> <i class="icon icon-exit"></i> <span>Logout</span> </a> </li>

                 </ul>

               </div>

             </div>

           </div>

         </div>

       </div>

     </div>

     <div class="video_background_img_wrap">

      <div  class="col-md-12 ">

        <div id="page-wrapper">

          <div class="graphs">
          <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/artist_header_img">Header Image</a> </div> 
            @if(Session::has('message'))

            <div class="alert alert-success"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('message') }}</span> </div>

            @endif

            <div class="xs">

              <h1 class="heading">Header Image</h1>
              
              <div class="mailbox-content"> @if(Session::has('success'))

                <div class="alert alert-success"> {{Session::get('success') }} </div>

                @endif                                                                   
                @if(Session::has('error'))

                <div class="alert alert-danger"> {{Session::get('error') }} </div>

                @endif
                <div class="inner-wrapper">
                  <div class="img_wrap">
                      <?php
                      $fileName = 'images/Artist/'.$artist_data->header_image;
                      $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                  ->url($fileName);?>
                      <img src="{{$imageUrl}}" alt="" alt="" class="img img-responsive">
                      {{--<img src="{{url('/images/Artist/').'/'.$artist_data->header_image}}" alt="" class="img img-responsive">--}}
                  </div>
                  <span class="msg"> Header Image must be 1316 X 250 px and  jpeg, png format.</span>
                
                  <form action="/artist_header_img" method="post" enctype="multipart/form-data">

                    {!! csrf_field () !!}

                    <div class="form-group">

                      <input type="file" name="header_img" id="header_img" class="form-control">

                      @if($errors->first('header_img'))

                      <p class="label label-danger" > {{ $errors->first('header_img') }} </p>

                      @endif </div>

                      <div class="form-group">

                        <input type="submit" value="Upload" class="btn btn-primary">

                      </div>

                    </form>
                  </div>
                </div>

              </div>

            </div>

          </div>

        </div>

      </div>

    </div>
    <!--footer section start-->



    <footer>

      <!-- <p>&copy 2015 Easy Admin Panel. All Rights Reserved | Design by <a href="https://w3layouts.com/" target="_blank">w3layouts.</a></p> -->

    </footer>

    <!-- Include external JS libs. -->

    <script>

      initSample();

    </script>

    <!--footer section end-->

    {!! Html::script('https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js') !!}

    <!--{!! Html::script('https://code.jquery.com/jquery-1.12.3.min.js') !!}-->

    {!! Html::script('js/scripts.js') !!}

    {!! Html::script('js/bootstrap.min.js') !!}

    {!! Html::script('js/jquery.date-dropdowns.js') !!}

    <script type="text/javascript">



     $("#profile_date_ofbirth").dateDropdowns({

      submitFieldName: 'date_ofbirth',

      displayFormat: 'mdy',

      submitFormat: "mm-dd-yyyy",

  //yearRange: "18",

  minAge: 18,



});

     $('#profile_date_ofbirth').attr('readonly', 'readonly');

   </script>



   <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.js"></script>

   <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.2.0/js/dataTables.buttons.min.js"></script>

   <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.2.0/js/buttons.print.min.js"></script>







   <!-- html editer script  -->



   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>

   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>



   <!-- Include JS files. -->

   <script type="text/javascript" src="editor_js/froala_editor.pkgd.min.js"></script>



   <!-- Include Language file if we want to use it. -->

   <script type="text/javascript" src="editor_js/languages/ro.js"></script>



   <script src="http://www.videorequestline.com/js/jquery.inputmask.bundle.min.js"></script>





   <script>

    $(document).ready( function () {

     $("input[name=phone]").inputmask("mask", {

      "mask": "(999) 999-9999"

    }); 



     $("#add_more").click(function(){

      var len=$('.form-control').length;

      $(".form-group-add").append('<div class="form-group"><div class="control-label"><label for="twitter_link">Twitter</label></div><div class="control-box"><input type="text" name="twitter_link'+len+'" id="twitter_link'+len+'" value="" class="form-control" ></div></div>');



    });

     $('#testimonialsss').DataTable( {

      "paging":   false,

      "ordering": false,

      "info":     false

    } );

     $('#testimonial').DataTable( {

      "lengthMenu": [[5,6,7,8,9,10,15,20,25], [5,6,7,8,9,10,15,20,25]],

      "order": [[ 2, "desc" ]],

      buttons: ['print'],

      "paging":   true,
         "language": {
             "search": "Search:",
             "searchPlaceholder": ""
         }

    } );

     $('#table_id').DataTable({

       dom: 'lBfrtip',

       buttons: ['print'],

       "order": [[ 0, "desc" ]],
         "language": {
             "search": "Search:",
             "searchPlaceholder": ""
         }

     });



     $('#example').DataTable( {

      "lengthMenu": [[5,6,7,8,9,10,15,20,25], [5,6,7,8,9,10,15,20,25]],

      "order": [[ 0, "desc" ]],
         "language": {
             "search": "Search:",
             "searchPlaceholder": ""
         }

    } );



     $('#admin_dob').DataTable( {

      "lengthMenu": [[5,6,7,8,9,10,15,20,25], [5,6,7,8,9,10,15,20,25]],

      "order": [[ 2, "desc" ]],
         "language": {
             "search": "Search:",
             "searchPlaceholder": ""
         }

    } );

     jQuery(".modal_wrap").hide();    

     jQuery(".payment #table_id_wrapper .btn.btn-info").click(function() { 

       jQuery(".modal_wrap").fadeIn("slow");

     });

     jQuery(".modal_wrap .inner_modal_wrap .close_btn").click(function() { 

       jQuery(".modal_wrap").fadeOut("slow");



     });

   });  



 </script>

 <script>

  jQuery(document).ready( function () {

   jQuery(function(){

     jQuery('.toggle.menu-toggle').click(function(){

      jQuery('#left-side-wrap, #page-wrapper').toggleClass('active');

    });     

   });

 });  

</script>





<script>

  jQuery(document).ready(function() {

   function close_accordion_section() {

    jQuery('#left-side-wrap .left-side-inner .cbicon-arrow').removeClass('show');

    jQuery('#left-side-wrap .left-side-inner .sub-child').slideUp(300).removeClass('open');

  }



  jQuery('#left-side-wrap .left-side-inner .cbicon-arrow').click(function(e) {

    var currentAttrValue = jQuery(this).attr('href');

    if(jQuery(e.target).is('.show')) {

     close_accordion_section();

   }else {

     close_accordion_section();

     jQuery(this).addClass('show');

     jQuery('#left-side-wrap .left-side-inner .custom-nav ' + currentAttrValue).slideDown(300).addClass('open'); 

   }

   e.preventDefault();

 });

});

</script>





<script>

  $('.req_video_del').on('click',function () {

    $('.out_mess_del_video').addClass('delete_video');

  })

  $('.out_mess_del_video .ms_close').on('click',function () {

    $('.out_mess_del_video').removeClass('delete_video');

  })

  jQuery(document).ready(function() {







   $('#left-side-wrap > div > div > ul ul').hide();

   $('#left-side-wrap > div > div > ul > li > span').on('click',function(){

     if($(this).parent().hasClass('active')){

      $(this).parent().removeClass('active');

      $(this).next().slideUp();

    }else{

      $('#left-side-wrap > div > div > ul > li').removeClass('active');

      $(this).next().slideToggle();

      $(this).parent().addClass('active');

    }

  });

 });

</script>

<script>

  $(function() {

    $('#edit').froalaEditor()

  });

  $(function() {

    $("#table_id > tbody > tr:nth-child(1) > td:nth-child(2) > div > div:nth-child(4)").children().remove().detach(); 

  });

</script>









<!--END html editer script  -->







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

        window.location.href="http://www.videorequestline.com/getLogout";

      }

    }

  });

},3000);

</script>

@endif  

@endif








</section>



<script type="text/javascript">

  $( document ).ready(function() {

    $( ".dropdown.user-menu" ).click(function() {

      $( '.dropdown.user-menu ul' ).toggle();

    });

  });

</script>

</body>

</html>
