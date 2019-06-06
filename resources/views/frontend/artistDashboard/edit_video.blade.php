@include('admin.common.header')



<body class="admin deliver_video">

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
                      $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                ->url($fileName);?>
                      <img src="{{$imageUrl}}" alt="">
                      {{--<img src="/{{$artist->profile_path}}" alt=""> --}}
                    </span><span class="admin-name">{{$artist->Name}}</span> <i class="arrow"></i> </span>


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

     <div class="deliver_video_wrap">

      <div  class="col-md-12 ">

        <div id="page-wrapper">

          <div class="graphs">
          <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="#">Edit Video</a> </div>
          
            <h1 class="heading">My Video</h1>

            @if(count($video)>0)

            <div class="inner-wrap">

              <?php //dd($video);?>

              <div class="content clearfix"> @foreach ($video as $video_data) 



                <!-- <div class="row_box_5">-->

                

                <div class="info_box">

                  <div class="content"> @if(Session::has('success'))

                    <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

                    @endif

                    

                    @if(Session::has('error'))

                    <div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

                    @endif

                    

                    {!! Form::open(array('url' =>'edit_video','class'=>'form form-horizontal text-left','id'=>'post_edit_video','method'=>'post' ,'files'=>true)) !!}

                    <input type="hidden" name="requested_video_id" value="{{$video_data->id}}">

                    <div class="form-group">

                      <div class="control-label">

                        <label >Request ID </label>

                      </div>

                      <div class="control-box">
                          <input type="text" class="form-control" readonly="" value="{{$requestID}}">

                      </div>
                    </div>
                    
                    
                     <div class="form-group">

                        <div class="control-label">
 
                          <label > Song Name </label>

                        </div>

                        <div class="control-box">

                          <input type="text" name="request_songName" value="{{$requestedVideos->song_name}}" class="form-control">


                        </div>
                      </div>
                    
                    <div class="form-group">

                      <div class="control-label">

                        <label >Occasion </label>

                      </div>

                      <div class="control-box">

                        <input type="text" name="video_title" value="{{$video_data->title}} " class="form-control">
                        <input type="hidden" name="reqId" value="{{$reqId}} " class="form-control">

                        @if($errors->first('video_title'))

                        <p class="label label-danger" > {{ $errors->first('video_title') }} </p>

                        @endif </div>

                      </div>

                      <div class="form-group">

                        <div class="control-label">

                          <label >Message </label>

                        </div>

                        <div class="control-box">

                          <input type="text" name="video_description" value="{{$video_data->description}}" class="form-control">

                          @if($errors->first('video_description'))

                          <p class="label label-danger" > {{ $errors->first('video_description') }} </p>

                          @endif </div>

                        </div>
                        <!-- 
                            -- Mohamed
                            -- 20 - 12 - 2017
                        -->
                      <div class="form-group">

                        <div class="control-label">

                          <label>Recipient Name </label>

                        </div>

                        <div class="control-box">

                          <input type="text" name="request_name" value="{{$requestedVideos->Name}}" class="form-control">


                        </div>
                      </div>
                    
                         <div class="form-group">

                        <div class="control-label">
 
                          <label >Recipient email <!-- Who will receive the video --></label>

                        </div>

                        <div class="control-box">

                          <input type="text" name="request_requestorEmail" value="{{$requestedVideos->requestor_email}}" class="form-control">


                        </div>
                      </div>
                        
                         <div class="form-group">

                        <div class="control-label">
 
                          <label > Recipient pronunciation</label>

                        </div>

                        <div class="control-box">

                          <input type="text" name="request_recPronunciation" value="{{$requestedVideos->receipient_pronunciation}}" class="form-control">


                        </div>
                      </div>
                        
                        <!-- Sender Data -->
                          <div class="form-group">

                        <div class="control-label">

                          <label >Sender Name </label>

                        </div>

                        <div class="control-box">

                          <input type="text" name="request_senderName" value="{{$requestedVideos->sender_name}}" class="form-control">


                        </div>
                      </div>
                    
                         <div class="form-group">

                        <div class="control-label">
 
                          <label >Sender email <!-- Who will receive the video --></label>

                        </div>

                        <div class="control-box">

                          <input type="text" name="request_senderEmail" value="{{$requestedVideos->sender_email}}" class="form-control">


                        </div>
                      </div>
                        
                         <div class="form-group">

                        <div class="control-label">
 
                          <label > Sender pronunciation</label>

                        </div>

                        <div class="control-box">

                          <input type="text" name="request_sendePronunciation" value="{{$requestedVideos->sender_name_pronunciation}}" class="form-control">


                        </div>
                      </div>
                        
                        <!---------------------------------------->
                    
                      <div class="form-group">

                        <div class="control-label">
 
                          <label > Approval Date  </label>

                        </div>

                        <div class="control-box">

                          <input type="text" name="request_approvalDate" value="{{$requestedVideos->approval_date}}" class="form-control">


                        </div>
                      </div>
                       
                    
                        @if(!empty($requestedVideos->rejected_date))
                         <div class="form-group">

                        <div class="control-label">
 
                          <label > Reject Date  </label>

                        </div>

                        <div class="control-box">

                          <input type="text" name="request_rejectDate" value="{{$requestedVideos->rejected_date}}" class="form-control">


                        </div>
                      </div>
                        @endif
                        @if(!empty($requestedVideos->refund_date))
                         <div class="form-group">

                        <div class="control-label">
 
                          <label > Refund Date  </label>

                        </div>

                        <div class="control-box">

                          <input type="text" name="request_refundDate" value="{{$requestedVideos->refund_date}}" class="form-control">


                        </div>
                      </div>
                        @endif
                    
                       
                        
                        
                        <div class="form-group">

                          <div class="control-label">

                            <label for="video">Change Video </label>

                          </div>

                          <div class="control-box">

                            <input type="file" name="video" id="video" class="form-control">
                            @if($errors->first('video'))
                            <p class="label label-danger" > {{ $errors->first('video') }} </p>
                            @endif </div>

                          </div>

                          <div class="form-group">

                            <div class="control-label"><label></label></div>

                            <div class="control-box">

                              <div style="width:300px;">
                                <?php
                                $rest = '';
                                $fileName = 'requested_video/watermark/'.$video_data->url; ?>
                                @if (\Illuminate\Support\Facades\Storage::disk('s3')->exists($fileName))
                                  <?php $rest = \Illuminate\Support\Facades\Storage::disk('s3')
                                  ->url($fileName);?>
                                @endif
                                <video style="width:100%;max-height:575px;" id="myvideo" controls>

                                  <source src="<?php echo $rest ;?>" type="video/mp4"></source>

                                  <source src="<?php echo $rest ;?>" type="video/webm"></source>

                                  <source src="<?php echo $rest ;?>" type="video/ogg"></source>
                                  <source src="/<?php echo $rest; ?>" type="video/x-msvideo"></source>
                                  <source src="/<?php echo $rest; ?>" type="video/x-flv"></source>
                                  <source src="/<?php echo $rest; ?>" type="application/x-mpegURL"></source>
                                  <source src="/<?php echo $rest; ?>" type="video/quicktime"></source>
                                  <source src="/<?php echo $rest; ?>" type="video/3gpp"></source>

                                  <source src="<?php echo $rest ;?>"></source>

                                </video>

                              </div>

                            </div>

                          </div>

                          <div class="form-group">

                            <div class="control-label"> <label></label> </div>

                            <div class="control-box"><span style="float:left;">{!! Form::submit('Upload',array('class'=>'btn btn-primary center-block'))!!} </span> <a class="btn btn-primary" style="background:#222;" href="{{url('/Dashboard')}}">cancel</a> </div>

                          </div>

                        </div>

                      </div>

                      

                      <!-- </div> --> 

                      

                      @endforeach

                      <div class="pagination"> {!! $video->render()!!} </div>

                    </div>

                    @else <span>

                    <h1>No delivered videos yet</h1>

                  </span><br/>

                  @endif </div>

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