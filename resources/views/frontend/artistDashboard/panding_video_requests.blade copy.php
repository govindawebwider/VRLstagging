@include('admin.common.header')

<body class="admin video_request">

  <section class="main-page-wrapper"> 

    <div class="main-content">     

      <div id="left-side-wrap"> 

        @include('frontend.artistDashboard.layouts.lsidebar') 

      </div>

      <div class="header-section">

        <div class="menu-right">        

          <div class="user-panel-top">

            <div class="profile_details">

              <div class="profile_img">

                <div class="dropdown user-menu"> <span class="dropdown-toggle"> <span class="admin-img"> <img src="{{$image_paths->profile_path}}" alt=""> </span> <i class="arrow"></i> </span>

                  <ul>
                   @if(session('current_type') == 'Artist')
                   <li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>Switch to Admin</span></a></li>
                   @endif
                   <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{ URL($image_paths->profile_url)}}"> <i class="icon icon-users"></i> <span>view Profile</span> </a> </li>
                   @if($users->admin_link=='yes')

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

     <div class="video_request_wrap">

      <div  class="col-md-12 ">

        <div id="page-wrapper">

          <div class="graphs">

            @if(Session::has('success'))

            <div class="alert alert-success"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('success') }}</span> </div>

            @endif
            
            @if(Session::has('error'))

            <div class="alert alert-danger"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('error') }}</span> </div>

            @endif
            
            

            <h1 class="heading">Requests </h1>

                <!-- <a href="{{URL('/req_video_csv/'.$users->profile_id)}}">

                    <input class="btn btn-primary" type="button" name="artist_csv" value="Export Panding Request Video List">

                  </a> -->



                  <div class="xs">

                    <div class="status-table-wrap">

                      <div class="mailbox-content dataTables_wrapper"> 



                       <table class="table1 table-fhr1 dataTable" id="example">

                        <thead>

                            <tr>
                                <tH>Action</tH>
                                <tH>Status</tH>
                                <tH>ID</tH>
                                <th>Song</th>
                                <tH>Description</tH>
                                <th>Occasion</th>

                                <tH>From</tH>

                                <th>TO</th>

                                <tH>Requested</tH>

                                <tH>Deliver</tH>



                            

                          </tr>

                        </thead>

                        <tbody>

                          @foreach($video_requests as $video_request)

                          <tr>
        <td> 

                             @if($video_request->RequestStatus!='Approved' AND $video_request->RequestStatus!='Completed')

                             <a href="{{URL('approve_request/'.$video_request->VideoReqId)}}" class="btn btn-primary">Approve</a>

                             @endif

                             @if($video_request->paid=='Paid' AND $video_request->RequestStatus == 'Approved')

                             @if($video_request->refund_date==$current_date)

                             <span class="btn btn-primary">time elapsed</span>   

                             @else

                             <a href="{{URL('upload_requested_video/'.$video_request->VideoReqId)}}" class="btn btn-primary">Upload Video</a>

                             @endif

                             @endif



                             @if($video_request->RequestStatus == 'Completed')

                             <a href="" class="btn btn-primary">Completed</a>

                             @else

                             <a class="btn btn-danger" href="{{URL('reject_request/'.$video_request->VideoReqId)}}">Reject</a>

                             @endif



                                                                </td>
                                     <td>{{ $video_request->RequestStatus }}</td>
                                     <td>{{ $video_request->VideoReqId }}</td>
                                     <td>{{ $video_request->song_name }}</td>
                                     <td>{{ $video_request->Description }}</td>
                                     <td>{{ $video_request->Title }}</td>
                                     <td>{{ $video_request->sender_name }}<br />
                                     {{ $video_request->sender_name_pronunciation }}<br />
                                  
                                     {{ $video_request->sender_email }} <br>
                                      @if(isset($video_request->sender_record))
                             <audio controls src="{{asset('usersRecords')}}/{{$video_request->sender_record}}"></audio>

                            @endif</td>

                                     <td>{{ $video_request->Name }}<br />
                                     {{ $video_request->receipient_pronunciation }}<br />
                                     {{ $video_request->requestor_email }}<br>
                                     
                                      @if(isset($video_request->recipient_record))
                             <audio controls src="{{asset('usersRecords')}}/{{$video_request->recipient_record}}"></audio>

                            @endif
                                     
                                     </td>




                            <td data-order>{{ $video_request->request_date }}</td>

                            <td>{{ $video_request->complitionDate }}</td>

                           

                    

                         </tr>                                            

                         @endforeach

                       </tbody>                                            



                     </table>

                   </div>                        

                 </div>



               </div>

             </div>

           </div>

         </div>

       </div>

     </div>

     @include('admin.common.footer') 

   </section>



   <script type="text/javascript">

    $( document ).ready(function() {
      $('#example_paginate').show();

      $( ".dropdown.user-menu" ).click(function() {

        $( '.dropdown.user-menu ul' ).toggle();

      });

    });

  </script>

</body>

</html>