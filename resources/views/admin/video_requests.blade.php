@include('admin.common.header')

<body class="admin video_request">

  <section class="main-page-wrapper">

    <div class="main-content">

     <div id="left-side-wrap"> 

      @include('admin.layouts.lsidebar') </div>

      @include('admin.layouts.header')



      <div class="video_request_wrap">

        <div  class="col-md-12 ">

         <div id="page-wrapper">

           <div class="graphs">

              <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="#">Video Request</a> </div>
             @if(Session::has('success'))

             <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

             @endif

             @if(Session::has('error'))

             <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

             @endif
             <h1 class="heading">Video Request

               <?php //dd($videos);?>

               <span>
                 <a href="{{URL('/video_purge')}}"><input class="btn btn-primary" type="button" name="artist_csv" value="Video Purge" style="float:right;"></a>
                 <a href="{{URL('/video_req_csv')}}">

                  <input class="btn btn-primary" type="button" name="artist_csv" value="Export Video Request List" style="float:right;">

                </a> 
              </span>

            </h1> 
            <p class="desc-title"></p>

            <div class="users">

              <div class="table-responsive dataTables_wrapper">

               <table id="table_video_request" class="display" cellspacing="0" width="100%">

                <thead>

                  <tr>

                    <th>ID</th>
                    <th>Artist ID</th>
                    <th>Request By</th>

                    <th>Artist Name</th>

                    <th>Video Price</th>

                    <th>Completion Date</th>

                    <th>Request Status</th>

                    <th>System Share</th>

                    <th>Artist Share</th>
                    <th>Reminder</th>

                  </tr>

                </thead>



                <tbody>

                  <?php //dd($videos);?>

                  @foreach ($videos as $video)

                  <tr>

                    <td>{{$video->VideoReqId}}</td>
                    <td>{{ $video->ProfileId }}</td>
                    <td>{{$video->requestor_email}}</td>

                    <td>{{$video->Name}}</td>
                    
                    <td>$ {{$video->ReqVideoPrice}}</td>
                      <?php
                          $dateTime = Carbon\Carbon::createFromFormat('m/d/Y',
                                  date('m/d/Y', strtotime($video->complitionDate)), session('timezone'));
                          $completionDate = date('m/d/Y', strtotime($dateTime->toDateString()));
                      ?>
                    {{--<td> {{date('m/d/Y', strtotime($video->complitionDate))}} </td>--}}
                    <td> {{$completionDate}} </td>

                      <td>@if($video->RequestStatus == 'Approved')

                        <span style="color: #00b4ff;">

                         {{$video->RequestStatus}}

                       </span>

                       @endif

                       @if($video->RequestStatus == 'Reject')

                       <span style="color: #cc2121;">

                         {{$video->RequestStatus}}

                       </span>

                       @endif

                       @if($video->RequestStatus == 'Refund')

                       <span style="color: #cc2121;">

                         {{$video->RequestStatus}}

                       </span>

                       @endif

                       @if($video->RequestStatus == 'Pending')  

                       <span style="color: #ec971f;">

                         {{$video->RequestStatus}}

                       </span>

                       @endif

                       @if($video->RequestStatus == 'Completed')  

                       <span class="btn btn-primary">

                        <a href="{{URL('/admin_view_video/'.$video->Rid)}}">

                         {{$video->RequestStatus}}

                       </a>

                     </span>



                     <span class="btn btn-primary"> 

                       <a href="{{URL('admin_resend_video/'.$video->requestByProfileId.'/'.$video->Rid)}}">Resend video</a>

                     </span>

                     @endif

                   </td>

                   <td>

                   ${{$video->system_percent}}  <!-- ${{$pay_price = ($video->ReqVideoPrice*30)/100}} -->
                     
                   </td>

                   <td>
                    
                   ${{$video->artist_percent}} <!-- ${{$pay_prices = ($video->ReqVideoPrice*70)/100}} -->
                    
                  </td>
                  <td>
                    @if($video->RequestStatus == 'Approved' and $video->paid != 'Paid' )  
                    <span class="btn btn-warning">
                      <a href="{{URL('/user_payment_reminder/'.$video->requestByProfileId)}}">
                        Send Reminder to User
                      </a>
                    </span>

                    @elseif($video->RequestStatus == 'Pending')
                    <span class="btn btn-warning">
                      <a href="{{URL('/artist_reminder/'.$video->requestToProfileId.'/'.$video->VideoReqId)}}">
                        Send Reminder to Artist
                      </a>
                    </span>
                    @elseif($video->paid == 'Paid')
                    <span class="btn btn-warning">
                      Paid
                    </span>
                    <span class="btn btn-warning">
                      <a href="{{URL('/fulfil_reminder_to_artist/'.$video->requestToProfileId.'/'.$video->VideoReqId)}}">
                        Full-fillment Reminder
                      </a>
                    </span>
                    @else
                    <span class="btn btn-warning">
                      Paid
                    </span>  
                    @endif
                  </td>
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

</section>

@include('admin.common.footer')

</body>
<script>
  $(document).ready(function() {
    $('#example_paginate').show();
  });


 
</script>
</html>