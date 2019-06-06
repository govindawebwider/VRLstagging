@include('admin.common.header')

<body class="admin dashboard">

  <section class="main-page-wrapper col-lg">

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

                            $fileName = 'images/Artist/'.$image_paths->profile_path;
                            $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                        ->url($fileName);?>
                            <img src="{{$imageUrl}}" alt="">
                            {{--<img src="{{url('images/Artist/').'/'.$image_paths->profile_path}}" alt=""> --}}
                        </span> <span class="admin-name">{{$image_paths->Name}}
                        </span> <i class="arrow"></i> </span>

            			<ul>
                    @if(session('current_type') == 'Artist')
                    <li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>Switch to Admin</span></a></li>
                    @endif
                    <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}">
                        <a href="{{ URL($image_paths->profile_url)}}"> <i class="icon icon-users"></i>
                            <span>view Profile</span> </a> </li>

                    <?php //dd($users);?>

                    @if($users->admin_link=='yes')

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

      <div class="dashboard_url_wrap">

        <div  class="col-md-12 ">

          <div id="page-wrapper">

            <div class="graphs">
            <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Dashboard</a></div>
              <h1 class="heading">Dashboard</h1>
              <div class="inner_wrapper">

                <div class="content info_box_wrap clearfix">

                <div class="row_3">

                   <a class="javascript:void(0)" href="{{URL('/video_requests')}}">

                    <div class="info_box green">

                      <div class="content">

                        <h2 class="content"> {{$video_requests}} <span> All Requests </span> </h2>
                        <i class="icon icon-play3"></i> <span class="more-wrap">More info <i class="icon icon-circle-right"></i></span>
                      </div>

                      </div>

                    </a>

                  </div>


                  <div class="row_3">
                   <a class="javascript:void(0)" href="{{URL('/pending_requests')}}">
                    <div class="info_box blue">
                      <div class="content">
                        <h2> {{count($panding_video_requests)}} <span> Pending Requests </span> </h2>
                        <i class="icon icon-price-tags"></i> <span class="more-wrap">More info <i class="icon icon-circle-right"></i></span>
                      </div>
                      </div>
                    </a> 
                  </div>


                  <div class="row_3">

                   <a class="javascript:void(0)" href="{{URL('/deliver_video')}}">

                    <div class="info_box yellow">

                      <div class="content">

                        <?php //dd($deliver_videos);?>

                        <h2> {{count($deliver_videos)}} <span> Completed </span> </h2>
                        <i class="icon icon-video-camera"></i> <span class="more-wrap">More info <i class="icon icon-circle-right"></i></span>
                      </div>

                      </div>

                    </a>

                  </div>



                <div class="row_3">

                  <a class="javascript:void(0)" href="{{URL('/artist_video')}}">

                    <div class="info_box red">

                      <div class="content">

                        <h2> {{count($my_videos)}} <span> Videos </span> </h2>
                        <i class="icon icon-film"></i> <span class="more-wrap">More info <i class="icon icon-circle-right"></i></span>
                      </div>

                    </div>

                  </a>

                </div>



                </div>

                <div class="content grid_bottom clearfix">

                  <div class="col-sm-8 all_user_list">

                    <div class="info_box">

                      <div class="box box-warning">

                        <div class="box-header with-border">

                          <h3 class="box-title"> @if(count($my_videos) > 1)My Videos @else My Video @endif  </h3>
<style>
body {font-family: Arial, Helvetica, sans-serif;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background:none;
  margin: auto;
  padding: 00px;
  border:0px;
  width:400px;
}
.modal-content video{
	display:block;
}

/* The Close Button */
.close {
    position: absolute;
    z-index: 9999999;
    width: 30px;
    top: 8px;
    right: 8px;
    opacity: 1;
    text-shadow: 0px 0px 0px;
    height: 30px;
    text-align: center;
    display: inline-block;
    background: rgba(00,00,00,0.4);
    border-radius: 50%;
    color: #fff;
    line-height: 30px;
}
.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
</style>


                        </div>

                        <!-- /.box-header -->


                        <div class="box-body no-padding">
                          @if(count($my_videos)>0)
                          <ul class="users-list clearfix">

                            <?php $kk=0; ?> 
                            @foreach($my_videos as $my_video)
                            <?php $str = 'images/thumbnails/'. $my_video->VideoThumbnail;
                                  $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')->url($str);
								  
                            ?>
                            <li> 
							    <a id="myBtn<?php echo $kk; ?>" href="javascript::void(0)" >
								<span class="img"> <img alt="User Image" src="{{$imageUrl}}">
									<i class="icon icon-play3"></i>
								</span> </a> <a href="#" class="users-list-name">
								{{$my_video->Title}}</a> 
									
								<!-- Modal -->
								<div id="myModal<?php echo $kk; ?>" class="modal" >
                                 <script>
								
								var btn = document.getElementById("myBtn<?php echo $kk; ?>");

								btn.onclick = function() {
									
								  $('#myModal<?php echo $kk; ?>').css('display','block');
								}

								function closefun<?php echo $kk; ?>(i) {
								  $('#myModal'+i).css('display','none');
								  var vid = document.getElementById("vid"+i); 
								  vid.pause(); 
								  
								} 

								</script>
								  <!-- Modal content -->
								  <div class="modal-content">
									<span class="close" onclick="closefun<?php echo $kk; ?>(<?php echo $kk; ?>);">&times;</span>
									 <video id="vid<?php echo $kk; ?>" width="400" controls controlsList="nodownload">
										<source src="https://videorequestline-staging.s3.us-west-2.amazonaws.com/video/watermark/<?php echo $my_video->VideoURL; ?>" type="video/mp4">
									 </video>
								  </div>

								</div>

								
								</li>
                            <?php $kk++; ?>

                            @endforeach
                          </ul>
                          @endif
                          

                          <!-- /.users-list -->                          

                        </div>

                      
                        <!-- /.box-body --> 

                      </div>

                    </div>

                  </div>

                  <div class="col-sm-4 all_artist_list">

                    <div class="info_box">

                      <div class="box box-danger">

                        <div class="box-header with-border">

                          <h3 class="box-title">Notifications</h3>

                          <div class="box-tools pull-right"> 

                            <!-- <span class="label label-danger">13 Notifications</span> --> 

                          </div>

                        </div>
                        <!-- /.box-header -->

                        <div class="box-body no-padding notification-msg">

                          <section>

                            <ul class="list-group">

                              @if($image_paths->timestamp <= 0)

                              <li class="list-group-item">

                                <div class="media">

                                  <div class="media-left">

                                    <a href="{{URL('/turnaround_time')}}"><span class="media-object media-icon bg-danger">

                                      <i class="fa fa-ban"></i></span></a>

                                    </div>


                                    <div class="media-body">

                                      <span class="ng-binding">You have not set your video fulfillment duration</span> 

                                      <!-- <small class="ng-binding">6 minutes ago</small> -->

                                    </div>

                                  </div>

                                </li>


                                @endif

                                @if($image_paths->VideoPrice <= 0 )

                                <li class="list-group-item ng-scope">

                                  <div class="media">

                                    <div class="media-left">

                                      <a href="{{URL('/addPrice')}}"><span class="media-object media-icon bg-primary">

                                        <i class="fa fa-user" aria-hidden="true"></i></span></a>

                                      </div>


                                      <div class="media-body">

                                        <span class="ng-binding">You have not set your video price </span> 

                                        <!-- <small class="ng-binding">12 minutes ago</small> -->

                                      </div>

                                    </div>

                                  </li>


                                  @endif

                                  @if($image_paths->is_bank_updated==0)

                                  <li class="list-group-item ng-scope">

                                    <div class="media">

                                      <div class="media-left">

                                        <a href="{{URL('/bank_details')}}"><span class="media-object media-icon bg-primary">

                                          <i class="fa fa-user" aria-hidden="true"></i></span></a>

                                        </div>


                                        <div class="media-body">

                                          <span class="ng-binding">You have not set your Bank details</span> 

                                          <!-- <small class="ng-binding">12 minutes ago</small> -->

                                        </div>

                                      </div>

                                    </li>


                                    @endif


                                    <!--review notification-->
                                    @if(count($review_notification)>0)
                                   


                                      @foreach($review_notification as $review)
                                      
                                      <li class="list-group-item ng-scope">

                                      <div class="media">

                                        <div class="media-left">

                                          <a href="{{ URL('view_review') }}"><span class="media-object media-icon bg-primary">

                                            <i class="fa fa-bell" aria-hidden="true"></i></span></a>

                                          </div>


                                          <div class="media-body">

                                            <span class="ng-binding">{{$review->Message}}</span> 

                                            <!-- <small class="ng-binding">12 minutes ago</small> -->

                                          </div>

                                        </div>

                                      </li>

                                      @endforeach
                                    
                                    @endif
                                    <!--review notification-->

                                  </ul>

                                </section>

                              </div>

                              <!-- /.box-body -->
<!-- 
                              <div class="box-footer text-center"> 
                                  <a class="uppercase" href="http://www.videorequestline.com/artists">
                                      View All Notifications</a> 
                              </div> -->

                              <!-- /.box-footer --> 

                            </div>

                          </div>

                        </div>

                      </div>

                    </div>

                  </div>

                </div>

              </div>

            </div>

          </div>
      <div id="videoContainer" style="display:none;">
          <div class="pop-inner"> <i class="icon icon-cancel-circle close-pop"></i>
              <iframe frameborder="0" 
  allow="autoplay; fullscreen" allowfullscreen wmode="Opaque" id="play_vid"></iframe>
          </div>
      </div>
  </section>
  @include('admin.common.footer')



          <script type="text/javascript">
          $(document).ready(function(){
              $('a[id*="play_btn_0"]').on('click', function(){
                  $('#videoContainer').show();
                  $('.pop-inner').slideDown(500);
                  var url = "{{ \Illuminate\Support\Facades\Storage::disk('s3')->url('video/watermark/') }}"+ $(this).data('url');
                  $('#play_vid').attr({'src':url});
              });
              $('.close-pop').on('click', function(){
                  $('.pop-inner').slideUp(500);
                  $(this).delay(500).parents('#videoContainer').hide();
              });
          });

            $( document ).ready(function() {

              $( ".dropdown.user-menu" ).click(function() {

                $( '.dropdown.user-menu ul' ).toggle();

              });

            });

          </script>

        </body>

        </html>
   