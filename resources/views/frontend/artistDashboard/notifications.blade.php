@extends('admin.common.header')

<body class="sticky-header left-side-collapsed"  onload="initMap()">

  <section class="main-page-wrapper"> <!-- main content start-->

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

                <div class="dropdown user-menu"> <span class="dropdown-toggle"> <span class="admin-img"> <img src="{{$profileData->profile_path}}" alt=""> </span>  <i class="arrow"></i> </span>

                  <ul>
                   @if(session('current_type') == 'Artist')
                   <li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>Switch to Admin</span></a></li>
                   @endif
                   <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{ URL($profileData->profile_url)}}"> <i class="icon icon-users"></i> <span>view Profile</span> </a> </li>

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

     <div id="page-wrapper" class="notification-wrap">

      <ul>

        @foreach ($notifications as $notification)

        <li class="{{ $notification->type }}">

          <div class="inner-wrap"> <img src="{{$notification->profile_path}}" alt="" height="100" width="100">

            <h3>{{$notification->Name}}</h3>

            @foreach(explode(' ', $notification->created_at) as $info) <span>{{$info}}</span> @endforeach </div>

          </li>

          @endforeach

        </ul>

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