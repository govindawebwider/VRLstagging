@include('admin.common.header')

<body class="admin change_password">

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

                <div class="dropdown user-menu">
                    <span class="dropdown-toggle">
                        <span class="admin-img">
                            <?php
                            $fileName = 'images/Artist/'.$profileData->profile_path;
                            $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                        ->url($fileName);?>
                            <img src="{{$imageUrl}}" alt="">
                            {{--<img src="{{url('images/Artist/').'/'.$profileData->profile_path}}" alt="">--}}
                        </span>
                        <span class="admin-name">{{$profileData->Name}}</span>
                        <i class="arrow"></i>
                    </span>

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

     <div class="change_password_wrap">

      <div  class="col-md-12 ">

        <div id="page-wrapper">

         

          <div class="graphs">
          <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="#">Change Password</a> </div>
           @if(Session::has('success'))

           <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

           @endif

           @if(Session::has('error'))

           <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>
           @endif
           <h1 class="heading">Change Password</h1>
           
             {!! Form::open(array('url' =>'change-password','class'=>'form form-horizontal text-left','method'=>'post' )) !!}
              <div class="inner-wrapper">
                <div class="change-password">
                  <div class="col-md-12">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-10">
                      <div class="form-group">
                        <div class="">
                          <div class="control-label">
                            <label for="old_password">Old Password</label>
                          </div>
                        </div>
                        <div class="">
                          <div class="control-box">
                            <input type="password" name="old_password" id="old_password" class='form-control' value="{{Request::old('old_password')}}">
                            @if($errors->first('old_password'))
                              <p class="label label-danger" > {{ $errors->first('old_password') }} </p>
                            @endif
                          </div>
                        </div>
                      </div> 
                      <div class="form-group">
                        <div class="">
                          <div class="control-label">
                            <label for="new_password">New Password</label>
                          </div>
                        </div>
                        <div class="">
                          <div class="control-box">
                            <input type="password" name="new_password" id="new_password"  class='form-control' value="{{Request::old('new_password')}}">
                            @if($errors->first('new_password'))
                              <p class="label label-danger" > {{ $errors->first('new_password') }} </p>
                            @endif
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="">
                          <div class="control-label">
                            <label for="confirm_password">Confirm Password</label>
                          </div>
                        </div>
                        <div class="">
                          <div class="control-box">
                            <input type="password" name="confirm_password" id="confirm_password" class='form-control' value="{{Request::old('confirm_password')}}" >
                            @if($errors->first('confirm_password'))
                              <p class="label label-danger" > {{ $errors->first('confirm_password') }} </p>
                            @endif
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="">
                          <div class="control-label"> <label></label>
                          </div>
                        </div>
                        <div class="">
                          <div class="control-box">
                          {!! Form::submit('Change',array('class'=>'btn btn-primary update'))!!}
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              {!! Form::close() !!} 

         <!--   {!! Form::open(array('url' =>'change-password','class'=>'form form-horizontal text-left','method'=>'post' )) !!}

           <div class="inner-wrapper">

            <div class="form-group">

              <div class="control-label"><label for="old_password">Old Password</label></div>

              <div class="control-box">

                <input type="password" name="old_password" id="old_password" class='form-control' value="{{Request::old('old_password')}}">

                @if($errors->first('old_password'))

                <p class="label label-danger" > {{ $errors->first('old_password') }} </p>

                @endif</div>

              </div>

              <div class="form-group">

                <div class="control-label"><label for="new_password">New Password</label></div>

                <div class="control-box">

                  <input type="password" name="new_password" id="new_password"  class='form-control' value="{{Request::old('new_password')}}">

                  @if($errors->first('new_password'))

                  <p class="label label-danger" > {{ $errors->first('new_password') }} </p>

                  @endif</div>

                </div>

                <div class="form-group">

                  <div class="control-label"><label for="confirm_password">Confirm Password</label></div>

                  <div class="control-box">

                    <input type="password" name="confirm_password" id="confirm_password" class='form-control' value="{{Request::old('confirm_password')}}" >

                    @if($errors->first('confirm_password'))

                    <p class="label label-danger" > {{ $errors->first('confirm_password') }} </p>

                    @endif</div>

                  </div>
                  <div class="form-group">

                    <div class="control-label"> <label></label></div>

                    <div class="control-box">

                      {!! Form::submit('Change',array('class'=>'btn btn-primary center-block'))!!}</div>

                    </div>


                  </div>

                  
                  

                  

                  <!-- <button type="button" class="btn btn-primary " data-dismiss="modal" class='btn btn-primary center-block'>Close</button> --> 

                  

<!--                   {!! Form::close() !!} </div>
 --> 
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

      </body>

