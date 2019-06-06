@include('admin.common.header')

<body class="admin video_background_img">

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
                            $fileName = 'images/Artist/'.$artist->profile_path; ?>
                            @if (\Illuminate\Support\Facades\Storage::disk('s3')->exists($fileName))
                                <?php $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                        ->url($fileName);?>
                            @endif
                            <img src="{{$imageUrl}}" alt="" >
                            {{--<img src="{{url('images/Artist/').'/'.$artist->profile_path}}" alt=""> --}}
                        </span>
                        <span class="admin-name">{{$artist->Name}}</span> <i class="arrow"></i> </span>

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

     <div class="video_background_img_wrap">

      <div  class="col-md-12 ">

        <div id="page-wrapper">

          <div class="graphs">
          <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/upload_video_background">Video Background Image</a> </div> 
            @if(Session::has('message'))

            <div class="alert alert-success"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('message') }}</span> </div>

            @endif

            <div class="xs">

              <h1 class="heading">Video Background Image</h1>
              

              <div class="mailbox-content"> @if(Session::has('success'))

                <div class="alert alert-success"> {{Session::get('success') }} </div>

                @endif                                                                                                        @if(Session::has('error'))

                <div class="alert alert-danger"> {{Session::get('error') }} </div>

                @endif
                  <div class="inner-wrapper">
                    

                    <div class="img_wrap">
                        <?php $imageUrl = '';
                        $fileName = 'images/Artist/'.$artist->video_background; ?>
                        @if (\Illuminate\Support\Facades\Storage::disk('s3')->exists($fileName))
                            <?php $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                    ->url($fileName);?>
                        @endif
                        <img src="{{$imageUrl}}" alt="" >
                        {{--<img src="{{$artist->video_background}}" alt="">--}}
                    </div>

                    <form action="/upload_video_background" method="post" enctype="multipart/form-data">

                    {!! csrf_field () !!}

                      <div class="form-group">
                      <span class="msg"> Image must be 400 X 400 px and  jpeg, png format.</span>
                      <input type="file" name="video_background" id="video_background" class="form-control">

                      @if($errors->first('video_background'))

                      <p class="label label-danger" > {{ $errors->first('video_background') }} </p>

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

  </div>

  @extends('admin.common.footer') </section>

  

  <script type="text/javascript">

    $( document ).ready(function() {

      $( ".dropdown.user-menu" ).click(function() {

        $( '.dropdown.user-menu ul' ).toggle();

      });

    });

  </script>

</body>

</html>
