@include('admin.common.header')

<body class="admin video_record">

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
                        $fileName = 'images/Artist/'.$profileData->profile_path;
                        $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')->url($fileName);?>
                        <img src="{{$imageUrl}}" alt="">
                        {{--<img src="{{url('images/Artist/').'/'.$profileData->profile_path}}" alt="">--}}
                      </span><span class="admin-name">{{$profileData->Name}}</span> <i class="arrow"></i> </span>

                    <ul>
                     @if(session('current_type') == 'Artist')
                     <li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>Switch to Admin</span></a></li>
                     @endif
                     <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{ URL($profileData->profile_url)}}"> <i class="icon icon-users"></i> <span>view Profile</span> </a> </li>
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

       <div class="video_record_wrap">

         <div  class="col-md-12 ">

          <div id="page-wrapper"> 



            <div class="graphs">
            <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/record_video">Upload Promo Video</a> </div>
              @if(Session::has('success'))

              <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

              @endif

              @if(Session::has('error'))

              <div class="alert alert-danger"> {{Session::get('error') }} </div>

              @endif
              <h1 class="heading">
                Upload Promo Video 
                <span>
                  <a class="btn btn-primary" style="float:right; height:25px; line-height:20px; text-transform:uppercase;" href="{{ URL('sample_webcame')}}">
                    <i style="padding:4px;" class="icon icon-video-camera" aria-hidden="true"></i>
                    webcam
                  </a> 
                </span> 
              </h1>
              

              {!! Form::open(array('url' =>'record_video','class'=>'form form-horizontal text-left','id'=>'upProfile','method'=>'post' ,'files'=>true)) !!}
              
              <div class="inner-wrapper">

                <div class="form-group">

                  <div class="control-label">

                    <label for="video_title">Video title</label>

                  </div>

                  <div class="control-box">

                    <input type="text" name="video_title" id="video_title" class='form-control' value="{{Request::old('video_title')}}">

                    @if($errors->first('video_title'))

                    <p class="label label-danger" > {{ $errors->first('video_title') }} </p>

                    @endif</div>

                  </div>

                  <div class="form-group">

                    <div class="control-label">

                      <label for="video_description">Video Description</label>

                    </div>

                    <div class="control-box">

                      <textarea name="video_description" id="video_description"  class='form-control' value=""> {{Request::old('video_description')}}</textarea>

                      @if($errors->first('video_description'))

                      <p class="label label-danger" > {{ $errors->first('video_description') }} </p>

                      @endif</div>

                    </div>

                    {{-- <div class="form-group">

                    <div class="control-label">

                      <label for="video_price">Video Price</label>

                    </div>

                    <div class="control-box">

                      <input type="text" name="video_price" id="video_price" class='form-control' value="{{Request::old('video_price')}}" >

                      ($)

                      

                      @if($errors->first('video_price'))

                      <p class="label label-danger" > {{ $errors->first('video_price') }} </p>

                      @endif</div>

                    </div> --}}

                    <div class="form-group">

                      <div class="control-label">

                        <label for="video">Choose video</label>

                      </div>
                      <!--  <span>mp4,mpeg,webm,mkv,flv,vob,ogv,ogg,drc,avi,mov,qt,wmv,yuv,asf,amv,m4p,m4v,mpg,mp2,mpe,mpv,m2v,svi,3gp,3g2,mxf,roq,nsv,flv,f4v,f4p,f4a,f4b</span> -->

                      <div class="control-box">

                        <input type="file" name="video" id="video"  class='form-control'  value="{{Request::old('video')}}">

                        @if($errors->first('video'))

                        <p class="label label-danger" > {{ $errors->first('video') }} </p>

                        @endif</div>

                      </div>

                      <div class="form-group">

                        <div class="control-label">

                          <label for="video">Download status</label>

                        </div>

                        <div class="control-box">

                          <select name="download_status">

                            <option value="Disable"<?php if(Request::old('download_status')=='Disable') echo "Selected";?>>Disable</option>

                            <option value="Enable" <?php if(Request::old('download_status')=='Enable') echo "Selected";?>>Enable</option>

                          </select>

                        </div>

                      </div>

                      <!-- <div class="form-group">
                      
                        <div class="control-label">
                      
                          <label for="video">Home AutoPlay status</label>
                      
                        </div>
                      
                        <div class="control-box">
                      
                          <select name="autoPlay_status">
                      
                            <option value="Disable" <?php //if(Request::old('autoPlay_status')=='Disable') echo "Selected";?>>Disable</option>
                      
                            <option value="Enable" <?php //if(Request::old('autoPlay_status')=='Enable') echo "Selected";?>>Enable</option>
                      
                          </select>
                      
                        </div>
                      
                      </div> -->

                      <div class="form-group">

                        <div class="control-label">

                          <label for="video">Profile AutoPlay status</label>

                        </div>

                        <div class="control-box">

                          <select name="profile_autoPlay_status">

                            <option value="Disable" <?php if(Request::old('profile_autoPlay_status')=='Disable') echo "Selected";?>>Disable</option>

                            <option value="Enable" <?php if(Request::old('profile_autoPlay_status')=='Enable') echo "Selected";?>>Enable</option>

                          </select>

                        </div>

                      </div>

                      <!-- <div class="form-group">
                      
                        <div class="control-label">
                      
                          <label for="video">Video AutoPlay status</label>
                      
                        </div>
                      
                        <div class="control-box">
                      
                          <select name="video_autoPlay_status">
                      
                            <option value="Disable" <?php //if(Request::old('video_autoPlay_status')=='Disable') echo "Selected";?>>Disable</option>
                      
                            <option value="Enable" <?php //if(Request::old('video_autoPlay_status')=='Enable') echo "Selected";?>>Enable</option>
                      
                          </select>
                      
                        </div>
                      
                      </div> -->
                      
                      
                      <div class="form-group">

                        <div class="control-label">
                          <label></label>
                        </div>
                        
                        <div class="control-box">
                          
                          {!! Form::submit('Upload',array('class'=>'btn btn-primary center-block'))!!} </div>

                        </div>
                        
                      </div>



                      

                      

                      

                      

                      <!-- <button type="button" class="btn btn-primary " data-dismiss="modal" class='btn btn-primary center-block'>Close</button> --> 

                      

                      {!! Form::close() !!} </div>

                    </div>

                  </div>

                </div>

              </div>

              @extends('admin.common.footer')

            </section>

            

            <script type="text/javascript">

              $( document ).ready(function() {

                $( ".dropdown.user-menu" ).click(function() {

                  $( '.dropdown.user-menu ul' ).toggle();

                });

              });

            </script>

          </body>

          </html>
