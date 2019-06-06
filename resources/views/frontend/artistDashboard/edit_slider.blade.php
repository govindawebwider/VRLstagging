@include('admin.common.header')



<body class="admin slide">



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
                            <?php
                            $fileName = 'images/Artist/'.$artist->profile_path;
                            $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')->url($fileName);?>
                            <img src="{{$imageUrl}}" alt="">
                            {{--<img src="{{$artist->profile_path}}" alt=""> --}}
                        </span>  <span class="admin-name">{{$artist->Name}}</span><i class="arrow"></i> </span>



                  <ul>

                   @if(session('current_type') == 'Artist')
                   <li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>Switch to Admin</span></a></li>
                   @endif

                   <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{ URL($artist->profile_url)}}"> <i class="icon icon-users"></i> <span>view Profile</span> </a> </li>

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



     <div class="slide_wrap">



      <div  class="col-md-12 ">



        <div id="page-wrapper">



          <div class="graphs">
          <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/my_slider">Slider</a> </div> 


           @if(Session::has('success'))



           <div class="alert alert-info"> {{Session::get('success') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </div>



           @endif



           <h1 class="heading">Slider</h1>
           

        <div class="inner-wrapper">
          


           <div style="width:100%;">



            <div style="width:60%; float:left;">



              <form action="{{URL('update_my_slider')}}" method="POST" enctype="multipart/form-data" role="form" class="form form-horizontal text-left">



                



                {!! csrf_field() !!}



                <div class="form-group">



                  <div class="control-label">



                    <label for="slider_title">Title</label>



                  </div>



                  <div class="control-box">  



                    <input type="text" name="slider_title" value="{{$slider->slider_title}}" class="form-control" id="slider_title">



                    @if($errors->first('slider_title'))



                    <p class="label label-danger" > {{ $errors->first('slider_title') }} </p>



                    @endif



                  </div> 



                </div>



                <div class="form-group">



                  <div class="control-label">



                    <label for="slider_description">Description</label>



                  </div>



                  <div class="control-box">   



                    <textarea name="slider_description" class="form-control" cols="20" rows="10" id="slider_description">{{$slider->slider_description}}</textarea>



                    @if($errors->first('slider_description'))



                    <p class="label label-danger" > {{ $errors->first('slider_description') }} </p>



                    @endif



                  </div> 



                </div>



                



                <div class="form-group">



                  @if(Session::has('img_error'))



                  <div class="alert alert-error"> {{Session::get('img_error') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </div>



                  @endif



                  <div class="control-label">



                    <label for="slider">Choose Slider</label>



                  </div>



                  <div class="control-box"> 



                    <input type="file" name="slider" class="form-control" id="slider">
                    @if($errors->first('slider'))



                    <p class="label label-danger" > {{ $errors->first('slider') }} </p>



                    @endif



                    



                  </div>



                </div>







                <input type="submit" class="btn btn-primary" value="Update">



              </form>



            </div>



            <div style="width:40%; float:left; padding:0 0 0 20px;">



             <div class="img_wrap">
                 <?php
                 $fileName = 'images/Sliders/'.$slider->slider_path;
                 $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')->url($fileName);?>
                 <img src="{{$imageUrl}}" alt="">
                 {{--<img src="/{{$slider->slider_path}}" alt="">--}}
             </div>
             <span>Slider Image must be 400 X 400 px</span>


           </div>



         </div>

       </div>

       </div>



     </div>



   </div>



 </div>



 



</div>



@include('admin.common.footer') 



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