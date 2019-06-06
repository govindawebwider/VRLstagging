@include('admin.common.header')



<body class="admin video_record">

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
                            <?php $fileName = 'images/Artist/'.$profileData->profile_path;
                            $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                        ->url($fileName);?>
                            <img src="{{$imageUrl}}" alt="">
                            {{--<img src="{{url('images/Artist/').'/'.$profileData->profile_path}}" alt=""> --}}
                        </span> <span class="admin-name">{{$profileData->Name}}</span><i class="arrow"></i> </span>

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

     <div class="video_record_wrap">

      <div  class="col-md-12 ">

        <div id="page-wrapper">

          <div class="graphs">
              <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="#">Upload Video</a> </div>
            @if(Session::has('success'))

            <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

            @endif



            @if(Session::has('error'))

            <div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

            @endif

            <h1 class="heading">Upload Video <span><a class="btn btn-primary" style="float:right; height:25px; line-height:20px; text-transform:uppercase;" href="/webcame/{{$requested_video->VideoReqId}}"><i style="padding:4px;" class="icon icon-video-camera" aria-hidden="true"></i>webcam</a> </span> </h1>

            {!! Form::open(array('url' =>'upload_requested_video','class'=>'form form-horizontal text-left','id'=>'upProfile','method'=>'post' ,'files'=>true)) !!}
            <?php //dd($requested_video);?>
            <div class="inner-wrapper">
              <?php //dd($profileData->EmailId);?>

              <input type="hidden" id="requested_video_id" name="requested_video_id" value="{{$requested_video->VideoReqId}}">

              <input type="hidden" id="requested_email" name="requested_email" value="{{$requested_video->requestor_email}}">

              <input type="hidden" id="requested_video_title" name="requested_video_title" value="{{$requested_video->Title}}">

              <input type="hidden" id="requested_video_description" name="requested_video_description" value="{{$requested_video->Description}}">

              <input type="hidden" id="requestedby" name="requestedby" value="{{$requested_video->requestByProfileId}}">

              <input type="hidden" id="user_email" name="user_email" value="{{$profileData->EmailId}}">

              <input type="hidden" id="uploadedby" name="uploadedby" value="{{$profileData->ProfileId}}">

              <div class="form-group">

                <div class="control-label">

                  <label for="video">Choose video</label>

                </div>

                <div class="control-box">

                  <input type="file" name="video" id="video"  class='form-control' >

                  @if($errors->first('video'))

                  <p class="label label-danger" > {{ $errors->first('video') }} </p>

                  @endif</div>

                </div>

              </div>

              <div class="form-group">

                <div class="control-label"> </div>

                <div class="control-box"> {!! Form::submit('Upload',array('class'=>'btn btn-primary center-block'))!!} </div>

              </div>



              <!-- <button type="button" class="btn btn-primary " data-dismiss="modal" class='btn btn-primary center-block'>Close</button> --> 



              {!! Form::close() !!} </div>

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