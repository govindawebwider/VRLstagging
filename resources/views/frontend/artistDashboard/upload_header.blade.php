@include('admin.common.header')

<body class="sticky-header left-side-collapsed"  onload="initMap()">

  <section class="main-page-wrapper"> <!-- main content start-->

    <div class="main-content">

      <div id="left-side-wrap"> @include('frontend.artistDashboard.layouts.lsidebar') </div>

      <div class="header-section">

        <div class="menu-right">

          <div class="user-panel-top">

            <div class="profile_details">

              <div class="profile_img">

                <div class="dropdown user-menu"> <span class="dropdown-toggle"> <span class="admin-img"> <img src="{{$artist->profile_path}}" alt=""> </span> <i class="arrow"></i> </span>

                  <ul>
                   @if(session('current_type') == 'Artist')
                   <li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>Switch to Admin</span></a></li>
                   @endif
                   <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{ URL($artist->profile_url)}}"> <i class="icon icon-users"></i> <span>view Profile</span> </a> </li>

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

     <!-- //header-ends -->

     <div id="page-wrapper" class="header-img-wrap">

      <div class="graphs"> @if(Session::has('message'))

        <div class="alert alert-success"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('message') }}</span> </div>

        @endif

        <div class="xs">

          <div class="col-md-8 inbox_left">

            <h1 class="heading">Choose BackGround Image</h1>

            <div class="mailbox-content">

              <form action="/upload_header" method="post" enctype="multipart/form-data">

                {!! csrf_field () !!}

                <div class="form-group">

                  <input type="file" name="header" id="header" class="form-control">

                </div>

                <div class="form-group">

                  <input type="submit" value="Upload" class="btn btn-primary">

                </div>

              </form>

            </div>

          </div>

          <div class="col-md-4 inbox_right"> <img src="{{$artist->BannerImg}}" alt=""> </div>

          <div class="clearfix"> </div>

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