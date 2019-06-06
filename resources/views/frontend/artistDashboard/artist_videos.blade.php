@include('admin.common.header')
<body class="admin artist_video">
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
                      {{--<img src="{{url('images/Artist/').'/'.$artist->profile_path}}" alt=""> --}}
                    </span> <span class="admin-name">{{$artist->Name}}</span><i class="arrow"></i> </span>
                  <ul>
                    <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{ URL($artist->profile_url)}}"> <i class="icon icon-users"></i> <span>view Profile</span> </a> </li>
                    <?php //dd($user);?>
                    @if(session('current_type') == 'Artist')
                    <li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>Switch to Admin</span></a></li>
                    @endif
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
      <div class="artist_video_wrap">
        <div  class="col-md-12 ">
          <div id="page-wrapper">
            <div class="graphs">
            <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/artist_video">My Promo Video</a> </div>
              @if(Session::has('success'))
              <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>
              @endif
              @if(Session::has('error'))
              <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>
              @endif
              <h1 class="heading">My Promo Video <!-- <span><a  class="btn btn-primary" style="float:right" href="http://www.videorequestline.com/record_video" >Add Video</a> </span> -->
              </h1>
              
              <div class="content grid_bottom clearfix">
                <div class="col-sm-12">
                  <div class="box table-info">
                    <h4 class="info-box-head">My Promo Videos</h4>
                    @if(count($video)>0)
                    <div class="inner_wrapper">
                      <div class="content clearfix">
                           <?php //dd($video);?>
                        @foreach ($video as $key=>$video_data)

                        <div class="row_box_5">
                          <div class="info_box">
                            <div class="content">                               
                              <div class="img_wrap">
                                <!-- <?php $str = $video_data->VideoThumbnail;
                                $pic = "images/thumbnails/".$str?>
                                <img src="{{$pic}}"> -->
                                <?php $str = $video_data->VideoURL;
                                $pic = \Illuminate\Support\Facades\Storage::disk('s3')->url("video/watermark/".$str);?>
                               
                                <video class="sample-video" id="myVideo{{$key}}" controls preload="none">
                                  <source src="{{ $pic }}" type="video/mp4">
                                  <source src="{{ $pic }}" type="video/ogg">
                                  Your browser does not support HTML5 video.
                                </video>
                                <div class="playsample" onclick="play({{ $key }})" id="{{ $key }}"></div>
                                <div class="pausesample" onclick="pause({{$key}})" id="pause{{ $key }}" style="display: none;"></div>
                              </div>
                              <h6>{{$video_data->Title}}</h6>
                              <ul>
                                <li><a href="{{URL('delete_sample_video/'.$video_data->VideoId)}}"><i class="icon icon-bin"></i><span>delete video </span></a></li>
                                <li><a href="{{URL('edit_sample_video/'.$video_data->VideoId)}}"><i class="icon icon-pencil2"></i><span>edit video </span></a></li>
                              </ul>
                            </div>
                          </div>
                        </div> 
                       @endforeach 
                      </div>
                      <div class="pagination_wrap" style="width: 100%;display: inline-block; text-align: center;"> {!! $video->render()!!} </div>                   
                    </div>
                   @else
                   <span style="width:100%; text-align:center; text-transform:uppercase; font-size:22px;margin-top:100px;display: inline-block;"> <h2>No video found</h2></span>
                   @endif
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
</body>

<style>
.sample-video{
    width: 253px;
    height: 150px;
    object-fit: fill;
}

.playsample {
  cursor: pointer;
  background-image: url(/images/btn_video_play.png);
  background-repeat: no-repeat;
  width: 13%;
  position: absolute;
  left: 40%;
  cursor: pointer;
  right: 0%;
  top: 30%;
  bottom: 0%;
  background-size: contain;
  background-position: center;
  height: 39px;
}


.pausesample {
  background: transparent;
  width: 100%;
  height: 100%;
  position: absolute;
  left: 0%;
  right: 0%;
  top: 0%;
  bottom: 0%;
  margin: auto;
  background-size: contain;
  background-position: center;
}


</style>

<script type="text/javascript" charset="utf-8">
  function play(id){
    document.getElementById("myVideo"+id).play()
    $("#pause"+id).show();
    $("#"+id).hide(); 
    $("#overl"+id).hide();
  }
  function pause(id) {
    document.getElementById("myVideo"+id).pause();
    $("#pause"+id).hide();
    $("#"+id).show();
    $("#overl"+id).show();
  } 
  
  </script>
</html>
