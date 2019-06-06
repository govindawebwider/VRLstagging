@include('admin.common.header')

<body class="admin background_img">    

  <section class="main-page-wrapper">        

    <div class="main-content">            

      <div id="left-side-wrap">                 

        @include('frontend.artistDashboard.layouts.lsidebar') 

      </div>

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
                      <?php $imageUrl = '';
                      $fileName = 'images/Artist/'.$artist_data->profile_path; ?>
                      @if (\Illuminate\Support\Facades\Storage::disk('s3')->exists($fileName))
                        <?php $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                ->url($fileName);?>
                      @endif
                      <img src="{{$imageUrl}}" alt="">
                      {{--<img src="{{url('images/Artist/').'/'.$artist_data->profile_path}}" alt=""> --}}
                        <span class="admin-name">{{$artist_data->Name}}</span></span>

                      <i class="arrow"></i>

                    </span>

                    <ul>
                     @if(session('current_type') == 'Artist')
                     <li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>Switch to Admin</span></a></li>
                     @endif

                     <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}">

                      <a href="{{ $artist_data->profile_url}}"> <i class="icon icon-users"></i>

                        <span>view Profile</span> </a> </li>
                        @if($user->admin_link=='yes')

                        <li class="{{ Request::is('Switch to Admin') ? 'active' : '' }}"> <a href="{{URL('/login_admin')}}"> <i class="icon icon-users"></i> <span>Switch to Admin</span> </a>

                        </li>

                        @endif



                        <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}">

                          <a href="{{URL('ProfileUpdate')}}">

                            <i class="icon icon-users"></i>

                            <span>Edit Profile</span> </a> </li>

                            <li class="{{ Request::is('change-password') ? 'active' : '' }}">

                              <a href="{{URL('change-password')}}">

                                <i class="icon icon-lock"></i>

                                <span>Change Password</span> </a>

                              </li>

                              <li class="{{ Request::is('getLogout') ? 'active' : '' }}">

                                <a href="{{ URL::route('getLogout') }}">

                                  <i class="icon icon-exit"></i>

                                  <span>Logout</span> </a> </li>

                                </ul>

                              </div>

                            </div>

                          </div>

                        </div>

                      </div>

                    </div>

                    <div class="background_img_wrap">                        

                      <div  class="col-md-12 ">                            

                        <div id="page-wrapper">								

                          <div class="graphs">                                
                          <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/background_img">Background Image</a> </div>   

                              @if(Session::has('message'))                                

                              <div class="alert alert-success"> 

                                <span class="close" data-dismiss="alert">&times;</span> 

                                <span class="text-center"> {{Session::get('message') }}</span> 

                              </div>                                

                              @endif                                

                            <div class="xs">        

                                                                                                         

                                  @if(Session::has('success')) 

                                  <div class="alert alert-info"> {{Session::get('success') }} 

                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                                  </div>                                     

                                  @endif                                                                           

                                  @if(Session::has('error'))                                      

                                  <div class="alert alert-danger"> {{Session::get('error') }} 

                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 

                                  </div>                                      @endif                                                                

                                <h1 class="heading">Background Image</h1>    
                               

                              <div class="inner-wrapper">

                                <div class="mailbox-content">                                                                          

                                  <div class="img_wrap">
                                    <?php $imageUrl = '';
                                    $fileName = 'images/Artist/'.$artist_data->BannerImg; ?>
                                    @if (\Illuminate\Support\Facades\Storage::disk('s3')->exists($fileName))
                                      <?php $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                              ->url($fileName);?>
                                    @endif
                                    <img src="{{$imageUrl}}" alt="">

                                    {{--<img src="{{$artist_data->BannerImg}}" alt="">--}}

                                  </div> 
                                  <span class="msg">on the background image, recommended size is 1600x1200 72dpi rgb jpeg.</span>                                     
                                  
                                  <form action="/upload_background_img" method="post" enctype="multipart/form-data">               

                                    {!! csrf_field () !!}                                        

                                    <div class="form-group">                                          

                                      <input type="hidden" name="profile_id" value="{{$artist_data->ProfileId}}"> 

                                      <input type="file" name="background" id="background" class="form-control">                                        

                                    </div>                                        

                                    <div class="form-group">                                          

                                      <input type="submit" value="Upload" class="btn btn-primary">                                           @if($errors->first('background'))                                          

                                      <p class="label label-danger" >                                           

                                        {{ $errors->first('background') }}                                                                  

                                      </p>                                         

                                      @endif                                        

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
