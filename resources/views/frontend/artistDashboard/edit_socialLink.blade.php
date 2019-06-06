@include('admin.common.header')

<body class="admin social_link">

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
                              $fileName = 'images/Artist'.$artist->profile_path;
                              $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                      ->url($fileName);?>
                              <img src="{{$fileName}}" alt="">
                          </span><span class="admin-name">{{$artist->Name}}</span> <i class="arrow"></i> </span>

                    <ul>

                     <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}">

                      <a href="{{ URL($artist->profile_url)}}"> <i class="icon icon-users"></i> <span>view Profile</span> </a> </li>
                      @if(session('current_type') == 'Artist')
                      <li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>Switch to Admin</span></a></li>
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

        <div class="social_link_wrap">

          <div  class="col-md-12 ">

            <div id="page-wrapper">

              <?php //dd($social_data);?>

              <div class="graphs">
              <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="#">Edit Social Links</a> </div>
                @if(Session::has('success'))

                <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

                @endif

                @if(Session::has('error'))

                <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

                @endif

                <h1 class="heading">Edit Social Links</h1>

                <form action="{{URL('edit_social_link/'.$social_data->id)}}" method="POST" enctype="multipart/form-data" role="form" class="form form-horizontal text-left">

                  

                  {!! csrf_field() !!}

                  

                  <div class="inner-wrap">

                    <div class="form-group">

                      <div class="control-label">

                        <label for="name">Name</label>

                      </div>

                      <div class="control-box">

                        <input type="text" name="name" id="name" value="{{$social_data->social_name}}" class='form-control' >

                        @if($errors->first('name'))

                        <p class="label label-danger" > {{ $errors->first('name') }} </p>

                        @endif 

                      </div>



                    </div>

                    <div class="form-group">

                      <div class="control-label">

                        <label for="social_img">Image</label>

                      </div>

                      <div class="control-box">

                        <input type="hidden" name="image_path" value="{{$social_data->social_img}}">
                          <?php
                          $fileName = 'socialLink/'.$social_data->social_img;
                          $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                          ->url($fileName);?>
                        <img src="{{$imageUrl}}" width="25px" height="25px">

                        <input type="file" name="social_img" id="social_img" class='form-control' >

                        @if($errors->first('social_img'))

                        <p class="label label-danger" > {{ $errors->first('social_img') }} </p>

                        @endif 

                      </div>

                      

                    </div>

                    <div class="form-group">

                      <div class="control-label">

                        <label for="social_url">URL</label>

                      </div>

                      <div class="control-box">

                        <input type="text" name="social_url" id="social_url" value="{{$social_data->social_url}}" class='form-control' >

                        @if($errors->first('social_url'))

                        <p class="label label-danger" > {{ $errors->first('social_url') }} </p>

                        @endif 

                      </div>

                      

                    </div>

                    <div class="form-group-add">

                    </div>

                    <input type="hidden" name="SocialId" value="{{$social_data->id}}">                        

                    <div class="form-group">

                      

                      <div class="control-label"> <label></label>  </div>

                      <!-- <a class="btn btn-primary" href="{{ URL('addMore_social_link') }}">Add </a> -->

                      <div class="control-box">

                       {!! Form::submit('Update',array('class'=>'btn btn-primary center-block'))!!} 

                     </div>

                     

                     

                   </div>

                   

                 </div>

                       <!-- 

                       <button type="button" class="btn btn-primary " data-dismiss="modal" class='btn btn-primary center-block'>Add</button> --> 

                     </form> </div>

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

      </body>

      </html>