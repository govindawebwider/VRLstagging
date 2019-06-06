@include('admin.common.header')

<body class="admin deliver_video">

  <section class="main-page-wrapper">

    <div class="main-content">

      <div id="left-side-wrap"> 

        @include('frontend.artistDashboard.layouts.lsidebar') </div>

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
                              $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                          ->url($fileName);?>
                              <img src="{{$imageUrl}}" alt="">
                              {{--<img src="{{url('images/Artist/').'/'.$artist->profile_path}}" alt="">--}}
                          </span><span class="admin-name">{{$artist->Name}}</span><i class="arrow"></i> </span>

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

       <div class="deliver_video_wrap">

        <div  class="col-md-12 ">

          <div id="page-wrapper">

            <div class="graphs">
            <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/deliver_video">Delivered Requested Videos</a> </div>
              @if(Session::has('success'))

              <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

              @endif

              @if(Session::has('error'))

              <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

              @endif



              <h1 class="heading">Delivered Videos</h1>
              

            <div class="content grid_bottom clearfix">
              <div class="col-sm-12">
                <div class="box table-info">
                 
                  @if(count($video)>0)

                  <div class="inner-wrap">

                    <?php //dd($video);?>

                    <div class="content clearfix">

                      @foreach ($video as $video_data)

                      <div class="row_box_5 col-sm-3">

                        <div class="info_box">

                          <div class="content">

                            <div class="img_wrap">
                                <?php $imageUrl = '';
                                $fileName = 'images/thumbnails/'.$video_data->thumbnail; ?>
                                @if (\Illuminate\Support\Facades\Storage::disk('s3')->exists($fileName))
                                    <?php $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                    ->url($fileName);?>
                                @endif
                                <img src="{{$imageUrl}}">
                                {{--<img src="{{$pic}}">--}}
                            </div>
                            <h6>{{$video_data->title}}</h6>
                            <h6>Request Number : {{$video_data->request_id}}</h6>
                            <h6>By : {{$video_data->sender_name}}</h6>
                            <ul>

                              <li><a href="{{URL('view_video/'.$video_data->id)}}"><i class="fa fa-eye"></i><span>view video</span></a></li>                                                        

                              <li><a href="{{URL('edit_video/'.$video_data->id.'/'.$video_data->request_id)}}"><i class="fa fa-video-camera"></i><span>edit video </span></a></li>

                              <li><a href="{{URL('artist_resend_video/'.$video_data->requestby.'/'.$video_data->id)}}"><i class="fa fa-reply"></i><span>resend video</span></a></li>
                           
                              <li><a href="{{route('artist.deleteCompletedRequests',$video_data->id)}}"><i class="fa fa-trash"></i><span>Delete</span></a></li>

                            </ul>

                          </div>

                        </div>

                      </div> 

                      @endforeach 

                                       

                    </div>

                    @else

                    <span><h1>No delivered videos yet</h1></span><br/>

                    @endif
                  </div>
                    <div class="pagination"> {!! $video->render()!!} </div>  
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
    <script>
      $(document).ready(function() {
        $('#example_paginate').show();
      });
    </script>
  </body>

  </html>
