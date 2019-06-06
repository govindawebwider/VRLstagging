@include('admin.common.header')

<body class="sticky-header left-side-collapsed"  onload="initMap()">

  <section class="main-page-wrapper"> <!-- main content start-->

    <div class="main-content"> <!-- header-starts -->

      <div id="left-side-wrap"> @include('frontend.artistDashboard.layouts.lsidebar') </div>

      <div class="header-section">

        <div class="menu-right">

          <div class="user-panel-top">

            <div class="profile_details">

              <div class="profile_img">

                <div class="dropdown user-menu"> <span class="dropdown-toggle"> <span class="admin-img"> <img src="{{$image_paths->profile_path}}" alt=""> </span>  <span class="admin-name">{{$image_paths->Name}}</span><i class="arrow"></i> </span>

                  <ul>
                   @if(session('current_type') == 'Artist')
                   <li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>Switch to Admin</span></a></li>
                   @endif
                   <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{ URL($users->profile_url)}}"> <i class="icon icon-users"></i> <span>view Profile</span> </a> </li>

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

     <div id="page-wrapper">

      <div class="graphs main-status-wrap">

        <h1 class="heading">sold Videos</h1>

      </div>

      <div class="table">

        <table class="table table-hover" id="table_id">

          <thead>

            <tr>

              <th>Video ID</th>

              <th>Title</th>

              <th>Description</th>

              <th>Price</th>

              <th>Purchased By</th>

            </tr>

          </thead>

          <tbody>

            

            @foreach ($sold_videos as $sold_video)

            <tr>

              <td>{{$sold_video->VideoId}}</td>

              <td>{{$sold_video->Title}}</td>

              <td>{{$sold_video->Description}}</td>

              <td>{{$sold_video->VideoPrice}}</td>

              <td>{{$sold_video->profile_id}}</td>

            </tr>

            @endforeach

          </tbody>

          

        </table>

      </div>

    </div>

    <div class="clearfix"> </div>

  </div>

</div>

</div>

</div>

@include('admin.common.footer') </section>



<script type="text/javascript">

  $( document ).ready(function() {

    $( ".dropdown.user-menu" ).click(function() {

      $( '.dropdown.user-menu ul' ).toggle();

    });

  });

</script>

</body>

</html>