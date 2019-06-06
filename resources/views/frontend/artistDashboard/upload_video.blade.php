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

                <div class="dropdown user-menu"> <span class="dropdown-toggle"> <span class="admin-img"> <img src="{{url('images/Artist/').'/'.$profileData->profile_path}}" alt=""> </span> <span class="admin-name">{{$profileData->Name}}</span>< <i class="arrow"></i> </span>

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

     <div id="page-wrapper">

      <div class="graphs"> @if(Session::has('message'))

        <div class="alert alert-success"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('message') }}</span> </div>

        @endif

        <div class="xs">

          <div class="col-md-8 inbox_right">

            <div class="mailbox-content">

              <div class="mail-toolbar clearfix">

                <div class="float-left">

                  <div class="btn btn_1 btn-default mrg5R"> <i class="fa fa-refresh"> </i> </div>

                  <div class="dropdown"> <a href="#" title="" class="btn btn-default" data-toggle="dropdown" aria-expanded="false"> <i class="fa fa-cog icon_8"></i> <i class="fa fa-chevron-down icon_8"></i>

                    <div class="ripple-wrapper"></div>

                  </a>

                  <ul class="dropdown-menu float-right">

                    <li> <a href="#" title=""> <i class="fa fa-pencil-square-o icon_9"></i> Edit </a> </li>

                    <li> <a href="#" title=""> <i class="fa fa-calendar icon_9"></i> Schedule </a> </li>

                    <li> <a href="#" title=""> <i class="fa fa-download icon_9"></i> Download </a> </li>

                    <li class="divider"></li>

                    <li> <a href="#" class="font-red" title=""> <i class="fa fa-times" icon_9=""></i> Delete </a> </li>

                  </ul>

                </div>

                <div class="clearfix"> </div>

              </div>

              <div class="float-right"> <span class="text-muted m-r-sm">Showing 20 of 346 </span>

                <div class="btn-group m-r-sm mail-hidden-options" style="display: inline-block;">

                  <div class="btn-group"> <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-folder"></i> <span class="caret"></span></a>

                    <ul class="dropdown-menu dropdown-menu-right" role="menu">

                      <li><a href="#">Social</a></li>

                      <li><a href="#">Forums</a></li>

                      <li><a href="#">Updates</a></li>

                      <li class="divider"></li>

                      <li><a href="#">Spam</a></li>

                      <li><a href="#">Trash</a></li>

                      <li class="divider"></li>

                      <li><a href="#">New</a></li>

                    </ul>

                  </div>

                  <div class="btn-group"> <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-tags"></i> <span class="caret"></span></a>

                    <ul class="dropdown-menu dropdown-menu-right" role="menu">

                      <li><a href="#">Work</a></li>

                      <li><a href="#">Family</a></li>

                      <li><a href="#">Social</a></li>

                      <li class="divider"></li>

                      <li><a href="#">Primary</a></li>

                      <li><a href="#">Promotions</a></li>

                      <li><a href="#">Forums</a></li>

                    </ul>

                  </div>

                </div>

                <div class="btn-group"> <a class="btn btn-default"><i class="fa fa-angle-left"></i></a> <a class="btn btn-default"><i class="fa fa-angle-right"></i></a> </div>

              </div>

            </div>

            <table class="table table-fhr">

              <form action="/upload_video/" method="post" enctype="multipart/form-data">

                <label for="video_title">Video Title</label>

                <div class="form-group">

                  <input type="text" name="video_title" id="video_title" class="form-control">

                </div>

                <div class="form-group">

                  <input type="hidden" name="artist_id" id="" class="form-control">

                </div>

                <label for="video_description">Video Description</label>

                <div class="form-group">

                  <input type="text" name="video_description" id="video_description" class="form-control">

                </div>

                <label for="video_price">Video price:</label>

                <div class="form-group">

                  <input type="number" name="video_price" id="video_price" class="form-control">

                  <span>$</span> </div>

                  <label for="video">Choose Video: </label>

                  <div class="form-group">

                    <input type="file" name="video" id="video" class="form-control">

                  </div>

                </form>

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

  {!! Html::script('https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js') !!}		{!! Html::script('js/jquery.nicescroll.js') !!}		{!! Html::script('js/scripts.js') !!} <!-- Bootstrap Core JavaScript --> {!! Html::script('js/bootstrap.min.js') !!}

</body>

</html>
