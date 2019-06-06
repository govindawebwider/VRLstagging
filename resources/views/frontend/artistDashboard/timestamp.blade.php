@include('admin.common.header')

<body class="turn_arround_time">

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
                               <?php $imageUrl = '';
                              $fileName = 'images/Artist/'.$artist->profile_path; ?>
                              @if (\Illuminate\Support\Facades\Storage::disk('s3')->exists($fileName))
                                  <?php $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                          ->url($fileName);?>
                              @endif
                              <img src="{{$imageUrl}}" alt="" >
                              {{--<img src="{{url('images/Artist/').'/'.$artist->profile_path}}" alt="">--}}
                          </span><span class="admin-name">{{$artist->Name}}</span> <i class="arrow"></i> </span>

                    <ul>
                     @if(session('current_type') == 'Artist')
                     <li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>Switch to Admin</span></a></li>
                     @endif
                     <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{ URL($artist->profile_url)}}"> <i class="icon icon-users"></i> <span>view Profile</span> </a> </li>
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

       <div class="video_price_wrap">

        <div  class="col-md-12 ">

          <div id="page-wrapper">

            <div class="graphs">
            <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/turnaround_time">My Fulfillment Duration</a> </div> 

              @if(Session::has('error'))

              <div class="alert alert-danger"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('error') }}</span> </div>

              @endif

              

              @if(Session::has('success'))

              <div class="alert alert-success"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('success') }}</span> </div>

              @endif

              <h1 class="heading">Fulfillment Duration</h1>
            

              <div class="note">How long will it take you on average to complete personalized video requests?</div>

              {!! Form::open(array('url' =>'turnaround_time','class'=>'form form-horizontal text-left','id'=>'regfrm','method'=>'post')) !!}
              
              <div class="inner-wrapper">
                
                <div class="form-group">

                  <div class="control-label">

                    <label for="timestamp">Fulfillment Duration ( in Days )</label> 

                  </div>

                  <div class="control-box">

                    <input type="text" name="timestamp" id="timestamp" class='form-control' value="{{ $artist->timestamp }}" ><span class="doller"></span>

                    

                    @if($errors->first('timestamp'))

                    <p class="label label-danger" > {{ $errors->first('timestamp') }} </p>

                    @endif

                    

                  </div>

                </div>

                <div class="form-group">

                  <div class="control-label">

                   <label></label>

                 </div>

                 <div class="control-box">

                  {!! Form::submit('Save',array('class'=>'btn btn-primary center-block', ))!!}</div>

                </div>

                
              </div>
              

              {!! Form::close() !!} </div>

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
