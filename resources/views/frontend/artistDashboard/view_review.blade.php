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
                            <?php $imageUrl = '';
                            $fileName = 'images/Artist/'.$artist->profile_path; ?>
                            @if (\Illuminate\Support\Facades\Storage::disk('s3')->exists($fileName))
                                <?php $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                        ->url($fileName);?>
                            @endif
                            <img src="{{$imageUrl}}" alt="" >
                            {{--<img src="{{url('images/Artist/').'/'.$artist->profile_path}}" alt=""> --}}
                        </span><span class="admin-name">{{$artist->Name}}</span><i class="arrow"></i> </span>

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

     <div class="deliver_video_wrap">

      <div  class="col-md-12 ">

        <div id="page-wrapper">

          <div class="graphs">
          <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="#">My Reviews</a> </div>
            @if(Session::has('success'))
            
            <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

            @endif

            @if(Session::has('error'))

            <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

            @endif

            <h1 class="heading">My Reviews<span> {{-- <a  class="btn btn-primary" style="float:right" href="{{URL('add_testimonial')}}" >Add Reviews</a> --}} </span></h1>
            

            @if(count($testi_data)>0)

            <div class="inner-wrap">

             <?php //dd($testi_data);?>

             <div class="content clearfix">

              <div class="xs">

                <div class="status-table-wrap">

                  <div class="mailbox-content dataTables_wrapper">

                    <table class="table1 table-fhr1 dataTable" id="example">

                      <thead>

                        <tr>

                          <th>ID</th>
                          <th>By User</th>

                          <th>Message</th>

                          <th>Type</th>

                          <th>Action</th>


                        </tr>

                      </thead>

                      <tbody>



                        @foreach ($testi_data as $testimonial_data)

                        <tr>


                          <td>{{$testimonial_data->id}}</td>
                          <td>{{$testimonial_data->user_name}}</td>

                          <td>{{$testimonial_data->Message}}</td>

                          <td>@if($testimonial_data->video_id == 0)
                            <p style="color: green">Profile Comment</p>
                            @else
                            <p style="color: red">Video Comment</p>
                            @endif
                          </td>
                          <td>
                            @if($testimonial_data->AdminApproval == 0)
                            <a class="btn btn-primary" href="{{URL('show_review/'.$testimonial_data->id)}}">Show</a>
                            @else
                            <a class="btn btn-danger" href="{{URL('hide_review/'.$testimonial_data->id)}}">Hide</a>
                            @endif
                            <a class="btn btn-danger" href="{{URL('delete_review/'.$testimonial_data->id)}}">Delete</a>

                          </td>

                        </tr>

                        @endforeach

                      </tbody>



                    </table>

                  </div>

                </div>

              </div>

            </div>

            @else <span>

            <h1>No reviews yet</h1>

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
