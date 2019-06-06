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
                               <?php $imageUrl = '';
                              $fileName = 'images/Artist/'.$profileData->profile_path;
                              $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                          ->url($fileName);?>
                              <img src="{{$imageUrl}}" alt="" >
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

                     <li class="{{ Request::is('getLogout') ? 'active' : '' }}"> <a href="{{ URL::route('getLogout') }}"> <i class="icon icon-exit"></i> <span>Logout</span> </a>

                     </li>

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
            <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/get_social_link">Social Links</a> </div> 
              @if(Session::has('success'))

              <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

              @endif

              @if(Session::has('error'))

              <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

              @endif

              <h1 class="heading">Social Links

               <span>

                <a class="btn btn-primary" style="float:right" href="{{ URL('addMore_social_link') }}">Add Link</a>

              </span>

            </h1>
            

            <div class="inner-wrap">

             <div class="content clearfix">

              <div class="info_box">

                <div class="content">

                  <?php //dd($social_data);?>

                  <div class="dataTables_wrapper">

                    <table  class="table1 table-fhr1 dataTable" id="example">

                      <thead>

                       <tr>

                        <th>ID</th>
                        <th>Image</th>

                        <th>Name</th>

                        <th>URL</th>                                                

                        <th>Status</th>

                        <th>Edit</th>

                        <th>Delete</th>

                      </tr>

                    </thead>

                    

                  </thead>

                  @foreach ($social_data as $social_datas)

                  <tr>

                    <td>{{$social_datas->id}}</td>

                    <td>
                        <?php
                        $fileName = 'socialLink/'.$social_datas->social_img;
                        $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                        ->url($fileName);?>
                        <img src="{{$imageUrl}}" style="height: 50px;width: 50px;">
                    </td>

                    <td><div class="name">{{$social_datas->social_name}}</div></td>

                    <td><div class="url">{{$social_datas->social_url}}</div></td>

                    <td>

                      <div class="status">

                        @if($social_datas->is_active=='Disable')

                        <span>

                          <a title="Enable" class="btn btn-success" href="{{ URL('enable_social_link'.'/'.$social_datas->id) }}">Enable</a>

                        </span> 

                        @endif

                        @if($social_datas->is_active=='Enable')

                        

                        <span>

                         <a title="Disable" class="btn btn-danger" href="{{ URL('disable_social_link'.'/'.$social_datas->id) }}">Disable</a>

                       </span>                                               

                       @endif

                     </div>

                   </td>

                   <td>

                    <div class="edit_wrap">                                                        

                      <a class="btn btn-info" href="{{ URL('edit_social_link'.'/'.$social_datas->id) }}">

                        Edit

                      </a>

                    </div>

                  </td>

                  <td>

                    <a class="btn btn-danger" href="{{ URL('delete_social_link'.'/'.$social_datas->id) }}">

                     Delete

                   </a>

                 </td>

               </tr>

               @endforeach

             </table>

           </div>

         </div>

       </div>                                                

     </div>

     <div class="form-group-add">

     </div>

     <input type="hidden" name="ProfileId" value="{{$profileData->ProfileId}}">                        

   </div>

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
