@include('admin.common.header')
<style>
  #videoContainer {
   position: fixed;
   top: 0;
   right: 0;
   left: 0;
   bottom: 0;
   overflow: hidden;
   background: rgba(0,0,0,.6);
   z-index: 99999999;
 }
 .pop-inner {
   display: none;
   position: absolute;
   left: 0;
   right: 0;
   width: 500px;
   background-color: #333;
   border-radius: 5px;
   padding: 5px;
   height: 400px;
   margin: 50px auto;
 }
 .pop-inner iframe {
   width: 100%;
   height: 100%;
   border-radius: 5px;
 }
 .close-pop {
   color: #fff;
   right: -10px;
   position: absolute;
   top: -10px;
   font-size: 24px;
   cursor: pointer;
 }
</style>
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
<body class="admin dashboard">
  <section class="main-page-wrapper col-lg">
    <div class="main-content">
      <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>
      @include('admin.layouts.header')
      <div class="dashboard_password_wrap">
        <div  class="col-md-12 no-padding">
          <div id="page-wrapper">
            <div class="change_pass_form">
              <div class="graphs">
              <div id="breadcrumb"> <a class="tip-bottom" href="/" data-original-title="Go to Home" style="margin-left: 20px;"><i class="lnr lnr-home"></i> Home</a></div>
                <h1 class="heading">Dashboard </h1>
                <p class="desc-title"></p>
                <div class="inner_wrapper">
                  <div class="content info_box_wrap clearfix">
                    <div class="row_3">
                     <a class="javascript:void(0)" href="{{URL('/artists')}}">
                      <div class="info_box green">
                        <div class="content">
                          <h2> {{count($total_artist)}} <span> All Artist </span> </h2>
                          <i class="icon icon-user-tie"></i><span class="more-wrap">More info <i class="icon icon-circle-right"></i></span>
                          
                        </div>
                      </div>
                    </a> 
                  </div>
                  <div class="row_3">
                   <a class="javascript:void(0)" href="{{URL('/get_video_requests')}}">
                    <div class="info_box yellow">
                      <div class="content">
                        <h2> 
                          <?php $video_requests = DB::table('requestvideos')
                          ->leftJoin('requested_videos','requested_videos.request_id','=','requestvideos.VideoReqId')
                          ->join('profiles','profiles.ProfileId','=','requestvideos.requestToProfileId')
                          ->leftJoin('admin_payments', 'admin_payments.video_request_id', '=', 'requestvideos.requestToProfileId')
                          ->select('requestvideos.*','requested_videos.id AS Rid','profiles.*','admin_payments.*')
                          ->orderBy('requestvideos.VideoReqId', 'desc')
                          ->get();
                          ?>

                          {{count($video_requests)}} <span> Video Requests </span> </h2>
                          <i class="icon icon-play3"></i><span class="more-wrap">More info <i class="icon icon-circle-right"></i></span> </div>
                        </div>
                        
                        
                      </a> 
                      
                    </div>
                    <div class="row_3">
                      <a class="javascript:void(0)" href="{{URL('/reviews')}}">
                        <div class="info_box red">
                          <div class="content">
                            <h2> {{count($total_testimonial)}} <span> All Reviews </span> </h2>
                            <i class="icon icon-bubbles2"></i><span class="more-wrap">More info <i class="icon icon-circle-right"></i></span> </div>
                          </div>
                          
                        </a> 
                        
                      </div>
                      <div class="row_3">
                        <a class="javascript:void(0)" href="{{URL('/users')}}">
                          <div class="info_box blue">
                            <div class="content">
                              <h2> 
                                <?php 
                                $total_user = DB::table('profiles')->select('profiles.*','users.*')
                                ->join('users',function($join){
                                  $join->on('profiles.ProfileId', '=', 'users.profile_id')
                                  ->where('users.type', '=','user');
                                })->orderBy('ProfileId', 'desc')->get();
                                ?>
                                {{count($total_user)}} 

                                <span> All User </span> </h2>
                                <i class="icon icon-user"></i><span class="more-wrap">More info <i class="icon icon-circle-right"></i></span></div>
                              </div>

                            </a>
                          </div>
                        </div>
                        <div class="content grid_bottom clearfix">
                          <div class="row_4 all_user_list">
                            <div class="info_box">
                              <div class="box box-warning">
                                <div class="box-header with-border">
                                  <h3 class="box-title">All Video</h3>
                                  <div class="box-tools pull-right"> <span class="label label-warning"><a class="uppercase" href="{{ URL('/videos') }}">{{count($total_video)}} Video</a></span> </div>
                                </div>

                                <!-- /.box-header -->

                                <div class="box-body no-padding">
                                  <ul class="users-list clearfix">
                                    {{--*/ $i = 0; /*--}}
                                    <?php $kk=0; ?> 
                                    @foreach($videos as $video)
                                    <li>
                                      <?php $fileName = 'images/thumbnails/'. $video->VideoThumbnail;
                                      $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')->url($fileName);?>
                                      <a href="javascript::void()" id="myBtn<?php echo $kk; ?>" >
                                        <span class="img"> <img alt="User Image" src="{{ $imageUrl }}"> <i class="icon icon-play3"></i> </span> </a> <a href="#" class="users-list-name">{{$video->Title}}</a> 

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
                                              <source src="https://videorequestline-staging.s3.us-west-2.amazonaws.com/video/watermark/<?php echo $video->VideoURL; ?>" type="video/mp4">
                                             </video>
                                            </div>

                                          </div>

                                      </li>
                                    {{--*/ $i++; /*--}}



                                   <!--  -->

                                    <?php $kk++; ?>
                                    @endforeach
                                  </ul>

                                  <!-- /.users-list --> 

                                </div>

                                <!-- /.box-body -->

                                <div class="box-footer text-center"> <a class="uppercase" href="{{ URL('/videos') }}">View All Video</a> </div>

                                <!-- /.box-footer --> 

                              </div>
                            </div>
                          </div>
                          <div class="row_4 all_artist_list">
                            <div class="info_box">
                              <div class="box box-danger">
                                <div class="box-header with-border">
                                  <h3 class="box-title">All Artist</h3>
                                  <div class="box-tools pull-right"> <span class="label label-danger"><a class="uppercase" style="color:#fff;" href="{{URL('/artists')}}">{{count($total_artist)}} Artist</a></span> </div>
                                </div>

                                <!-- /.box-header -->

                                <div class="box-body no-padding">
                                  <ul class="users-list clearfix">
                                    @foreach($artists as $artist)
                                    <li>
                                      <?php $fileName = 'images/Artist/'.$artist->profile_path;
                                      $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')->url($fileName);?>
                                      <a href="{{ URL('view_artist/'.$artist->ProfileId) }}">
                                        <img alt="User Image" src="{{$imageUrl}}">
                                        <a href="{{ URL('view_artist/'.$artist->ProfileId) }}" class="users-list-name">{{$artist->Name}}</a> </a> </li>
                                    @endforeach
                                  </ul>

                                  <!-- /.users-list --> 

                                </div>

                                <!-- /.box-body -->

                                <div class="box-footer text-center"> <a class="uppercase" href="{{URL('/artists')}}">View All Artist</a> </div>

                                <!-- /.box-footer --> 

                              </div>
                            </div>
                          </div>
                          <div class="row_4 all_testimonial_list">
                            <div class="info_box">
                              <div class="box box-info">
                                <div class="box-header with-border">
                                  <h3 class="box-title">All Reviews</h3>
                                  <div class="box-tools pull-right"> <span class="label label-info"><a class="uppercase" href="{{URL('/reviews')}}">{{count($total_testimonial)}} Review</a></span> </div>
                                </div>

                                <!-- /.box-header -->

                                <div class="box-body no-padding">
                                  <ul class="users-list clearfix">
                                    @foreach($show_testimonial as $testimonial)
                                    <li> <span class="txt">{{$testimonial->Message}} </span> <span class="name">{{$testimonial->Name}}</span> </li>
                                    @endforeach
                                  </ul>
                                </div>
                             
                                
                                <!-- /.box-body -->

                                <div class="box-footer text-center"> <a class="uppercase" href="{{URL('/reviews')}}">View All Reviews</a> </div>
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
          </div>
          <div id="videoContainer" style="display:none;">
            <div class="pop-inner"> <i class="icon icon-cancel-circle close-pop"></i>
              <iframe frameborder="0" allowfullscreen wmode="Opaque" id="play_vid"></iframe>
            </div>
          </div>
        </section>
        @include('admin.common.footer') 
        <script>
         $(document).ready(function(){
          $('a[id*="play_btn_"]').on('click', function(){
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
      </script>


      <script type="text/javascript">
        $( document ).ready(function() {
          $( ".dropdown.user-menu" ).click(function() {
            $( '.dropdown.user-menu ul' ).slideToggle();
          });
        });
      </script>

    </body>
    </html>