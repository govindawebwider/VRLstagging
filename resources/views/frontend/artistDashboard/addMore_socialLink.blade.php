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
                              $fileName = 'images/Artist/'.$artist->profile_path;
                              $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')->url($fileName);?>
                              <img src="{{$imageUrl}}" alt=""> </span>
                          <span class="admin-name">{{$artist->Name}}</span>
                          <i class="arrow"></i> </span>

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

       <div class="social_link_wrap">

        <div  class="col-md-12 ">

          <div id="page-wrapper">

            

            <div class="graphs">
                <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="#">Social Links</a> </div>

              @if(Session::has('success'))

              <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

              @endif

              @if(Session::has('error'))

              <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

              @endif

              <h1 class="heading">Social Links</h1>

              {!! Form::open(array('url' =>'addMore_socialLink','class'=>'form form-horizontal text-left','method'=>'post','files'=>'true')) !!}

              <div class="inner-wrap">

                <div class="form-group">

                  <div class="control-label">

                    <label for="name">Name</label>

                  </div>

                  <div class="control-box">

                    <input type="text" name="name" id="name" value="" class='form-control' >

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

                    <input type="file" name="social_img" id="social_img" class='form-control' placeholder=""> 
                    <span class="msg">Image Size must be (25*25 px)</span>

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

                    <input type="text" name="social_url" id="social_url" value="{{$artist->social_url}}" class='form-control' >

                    @if($errors->first('social_url'))

                    <p class="label label-danger" > {{ $errors->first('social_url') }} </p>

                    @endif 

                  </div>

                  

                </div>

                <div class="form-group-add">

                </div>

                <input type="hidden" name="ProfileId" value="{{$artist->ProfileId}}">                        

                <div class="form-group">

                 

                  <div class="control-label">

                    <label></label>

                  </div>

                  <!-- <a class="btn btn-primary" href="{{ URL('addMore_social_link') }}">Add </a> -->

                  <div class="control-box">

                   {!! Form::submit('Add',array('class'=>'btn btn-primary center-block'))!!} 

                 </div>

               </div>

               

             </div>

             

           </div>

                       <!-- 

                       <button type="button" class="btn btn-primary " data-dismiss="modal" class='btn btn-primary center-block'>Add</button> --> 

                       {!! Form::close() !!} </div>

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
