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
                            $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')->url($fileName);?>
                            <img src="{{$imageUrl}}" alt="">
                        </span> <span class="admin-name">{{$artist->Name}}</span><i class="arrow"></i> </span>

                  <ul>
                   @if(session('current_type') == 'Artist')
                   <li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>{{\Lang::get('views.switch_to_admin')}}</span></a></li>
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
          <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/deliver_video">Edit Sample video</a> </div>
            <h1 class="heading">Edit Sample video</h1>

            <?php //dd($video_data);?>

            <div class="inner-wrap">

              <div class="content clearfix">

                <div class="#">

                  <div class="info_box">

                    <div class="content"> @if(Session::has('success'))

                      <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

                      @endif

                      @if(Session::has('error'))

                      <div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

                      @endif

                      <form action="{{URL('edit_sample_video/'.$video_data->VideoId)}}" method="POST" enctype="multipart/form-data" role="form" class="form form-horizontal text-left">

                        {!! csrf_field() !!}

                        <div class="form-group">

                          <div class="control-label">

                            <label for="video">Video Title </label>

                          </div>

                          <div class="control-box">

                            <input class="form-control" type="text" name="video_title" value="{{$video_data->Title}}">
                            <input class="form-control" type="hidden" name="video_id" value="{{$video_data->VideoId}}">

                            @if($errors->first('video_title'))

                            <p class="label label-danger" > {{ $errors->first('video_title') }} </p>

                            @endif </div>

                          </div>
                          <?php //dd($video_data);?>
                          <div class="form-group">

                            <div class="control-label">

                              <label for="video">Video Description </label>

                            </div>

                            <div class="control-box">

                              <textarea name="video_description" class="form-control" cols="30" rows="10" id="video_description" autocomplete="off">{{$video_data->Description}}</textarea>

                              @if($errors->first('video_description'))

                              <p class="label label-danger" > {{ $errors->first('video_description') }} </p>

                              @endif </div>

                            </div>

                            <div class="form-group">

                              <div class="control-label">

                                <label for="video">Download status</label>

                              </div>

                              <div class="control-box">

                                <select name="download_status">

                                  <option value="Disable" <?php if($video_data->download_status=='Disable') echo "Selected"?>>Disable</option>

                                  <option value="Enable"<?php if($video_data->download_status=='Enable') echo "Selected"?>>Enable</option>

                                </select>

                              </div>

                            </div>

                              <!--   <div class="form-group">
                               
                               <div class="control-label">
                                 
                                 <label for="video">Home AutoPlay status</label>
                                 
                               </div>
                               
                               <div class="control-box">
                                 
                                 <select name="autoPlay_status">
                                   
                                   <option value="Disable" <?php //if($video_data->home_auto_play_status=='Disable') echo "Selected"?>>Disable</option>
                                   
                                   <option value="Enable" <?php //if($video_data->home_auto_play_status=='Enable') echo "Selected"?>>Enable</option>
                                   
                                 </select>
                                 
                               </div>
                               
                             </div> -->

                             <div class="form-group">
                              
                              <div class="control-label">
                                
                                <label for="video">Profile AutoPlay status</label>
                                
                              </div>
                              
                              <div class="control-box">
                                
                                <select name="profile_autoPlay_status">
                                  
                                  <option value="Disable" <?php if($video_data->profile_auto_play_status=='Disable') echo "Selected"?>>Disable</option>
                                  
                                  <option value="Enable" <?php if($video_data->profile_auto_play_status=='Enable') echo "Selected"?>>Enable</option>
                                  
                                </select>
                                
                              </div>
                              
                            </div>

                             <!--  <div class="form-group">
                             
                              <div class="control-label">
                             
                                <label for="video">Video AutoPlay status</label>
                             
                              </div>
                             
                              <div class="control-box">
                                
                                <select name="video_autoPlay_status">
                             
                                  <option value="Disable" <?php //if($video_data->video_auto_play_status=='Disable') echo "Selected"?>>Disable</option>
                             
                                  <option value="Enable" <?php //if($video_data->video_auto_play_status=='Enable') echo "Selected"?>>Enable</option>
                             
                                </select>
                             
                              </div>
                             
                            </div> -->
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

                                <div>
                                  <?php
                                    $fileName = 'video/watermark/'.$video_data->VideoURL;
                                    $rest = \Illuminate\Support\Facades\Storage::disk('s3')->url($fileName);?>
                                  <video style="width:300px;min-height:212px;" id="myvideo" controls autoplay>

                                    <source src="<?php echo $rest ;?>" type="video/mp4"></source>
                                    
                                    <source src="<?php echo $rest ;?>" type="video/webm"></source>
                                    
                                    <source src="<?php echo $rest ;?>" type="video/ogg"></source>
                                    <source src="<?php echo $rest ;?>" type="video/quicktime"></source>

                                    <source src="<?php echo $rest ;?>"></source>
                                    
                                  </video>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              
                              <div class="control-label"><label></label> </div>
                              
                              <div class="control-box"><span style="float:left;">{!! Form::submit('Upload',array('class'=>'btn btn-primary center-block'))!!} </span> <a class="btn btn-primary" style="background:#222;" href="{{url('/Dashboard')}}">cancel</a> </div>
                              
                            </div>   
                            
                            

                          </form>

                        </div>

                      </div>

                    </div>

                  </div>

                </div>

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