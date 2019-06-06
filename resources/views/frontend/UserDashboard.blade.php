<!DOCTYPE html>
<html lang="en">
<head>
  @include('frontend.common.head')
</head>
<body class="cb-page user_profile notranslate">
  <div class="cb-pagewrap"> @include('frontend.common.userHeader')
    <section id="mian-content">
      <div class="container">
        <div class="cb-grid">
          <div class="cb-block cb-box-100 main-content video_request">
            <div class="heading cb-content">
              <div class="inner-block">
                <h1 class="heading"> <span class="txt1">Dashboard</span> <span class="txt2">My Video Requests </span> </h1>
                <div class="video_list_filter">
                  <ul>
                    <li>Filter by : </li>
                    <li><a href="javascript:void(0)" id="all">All</a></li>
                    <li><a href="javascript:void(0)" id="completed">Completed</a></li>
                    <li><a href="javascript:void(0)" id="approved">Approved</a></li>
                    <li><a href="javascript:void(0)" id="pending">Pending</a></li>
                    <li><a href="javascript:void(0)" id="reject">Rejected</a></li>
                  </ul>
                </div>
                <div class="request_video_listing_wrap"> @if (count($request_details) > 0)
                  <table style="width:100%;">
                    <thead>
                      <tr>
                        <th>Id</th>
                        <th>Artist Name</th>
                        <th>Title / Description</th>
                        <th>Status</th>
                        <th>Requested Date</th>
                        <th>Delivery Date</th>
                        <th>Price</th>
                        <th>Delete</th>
                        <th>Payment Option</th>
                      </tr>
                    </thead>

                  <tbody>

                    @foreach ($request_details as $request_detail)
                        <?php
                        $requestDate = Carbon\Carbon::createFromFormat('m/d/Y', $request_detail->request_date, 'UTC')
                                ->setTimezone(session('timezone'))
                                ->format('m/d/Y');

                        $completionDate = date('m/d/Y', strtotime($request_detail->complitionDate));
                        ?>
                    @if($request_detail->RequestStatus=='Approved' AND $request_detail->paid != ' ' )
                    <tr class="approve_block">
                      <td data-th="Id">{{ $request_detail->VideoReqId }}</td>
                      <td data-th="Artist Name">{{ $request_detail->Name }}</td>
                      <td data-th="Title"><span class="title">{{ $request_detail->Title }}</span><span class="content">{{ $request_detail->Description }}</span> </td>
                      <td data-th="Status"><span class="content appr_btn">{{ $request_detail->RequestStatus }}</span></td>
                      <td data-th="Requested Date">{{ $requestDate }}</td>
                      <td data-th="Completion Date">{{ $completionDate }}</td>
                      <td data-th="Price">
                        ${{$request_detail->ReqVideoPrice}}
                      </td>
                      <td> <span class="btn_wrap req_video_del">
                              <a onclick="delete_req({{$request_detail->VideoReqId}},{{$request_detail->requestByProfileId}})" href="javascript:void(0)"> <i class="fa fa-trash red"></i> </a> </span>
                        <td>
                          <span class="">
                            @if ($request_detail->paid!='Paid')
                            <form action="{{URL('/testdata')}}" method="POST">
                              {{ csrf_field() }}
                              <input type="hidden" name="amount" value="{{$request_detail->ReqVideoPrice}}">
                              <input type="hidden" name="VideoReqId" value="{{$request_detail->VideoReqId}}">
                              <input type="hidden" name="requestToProfileId" value="{{$request_detail->requestToProfileId}}">
                              <input type="hidden" name="requestByProfileId" value="{{$request_detail->requestByProfileId}}">
                              <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                              data-key="pk_live_ibbVEpwDbfWJAboByQ6Kvygy"
                              data-amount="{{($request_detail->ReqVideoPrice)*100}}"
                              data-name="VRL"
                              data-description="Video Request Live"
                              data-label="Proceed to payment"
                              data-image="/images/logo2.png"
                              data-locale="auto">
                            </script>
                          </form>
                        </span>
                      </td>
                      @else
                      <span class="btn_wrap comp_btn">Paid</span>
                      @endif
                   </td>
                  </tr>
                  @elseif($request_detail->RequestStatus=='Pending')
                  <tr class="pending_block">
                    <td data-th="Id">{{ $request_detail->VideoReqId }}</td>
                    <td data-th="Artist Name">{{ $request_detail->Name }}</td>
                    <td data-th="Title / Description"> <span class="content">{{ $request_detail->Title }}</span><span class="content">{{ $request_detail->Description }}</span> </td>
                    <td data-th="Status"> <span class="content pend_btn">{{ $request_detail->RequestStatus }}</span> </td>
                    <td data-th="Requested Date">{{ $requestDate }}</td>
                    <td data-th="Completion Date"> {{ $completionDate }}</td>
                    <td data-th="Price">${{$request_detail->ReqVideoPrice}}</td>
                    <td> <span class="btn_wrap req_video_del"> <a onclick="delete_req({{$request_detail->VideoReqId}},{{$request_detail->requestByProfileId}})" href="javascript:void(0)"> <i class="fa fa-trash red"></i> </a> </span>
                    </td>
                    <td><span class="content pend_btn"> Not Approved </span></td>
                  </tr>
                  @elseif($request_detail->RequestStatus=='Reject')
                  <tr class="reject_block">
                   <td data-th="Id">{{ $request_detail->VideoReqId }}</td>
                   <td data-th="Artist Name">{{ $request_detail->Name }}</td>
                   <td data-th="Title_Description"><span class="title">{{ $request_detail->Title }}</span><span class="content">{{ $request_detail->Description }}</span> </td>
                   <td data-th="Status"><span class="content rejs_btn">{{ $request_detail->RequestStatus }}</span> </td>
                   <td data-th="Requested Date"> {{ $requestDate }}</li>
                    <td data-th="Completion Date">{{ $completionDate }}</td>
                    <td data-th="Price">${{$request_detail->ReqVideoPrice}}</td>
                    <td> <span class="btn_wrap req_video_del"> <a onclick="delete_req({{$request_detail->VideoReqId}},{{$request_detail->requestByProfileId}})" href="javascript:void(0)"> <i class="fa fa-trash red"></i> </a> </span>
                    </td>
                    <td>
                     @if ($request_detail->paid =='Paid' AND $request_detail->is_refunded == '1')
                     <span class="content rejs_btn">Rejected and Payment refunded</span>
                     @else
                     <span class="content rejs_btn">Request Rejected </span>
                     @endif
                   </td>
                 </tr>
                 @elseif($request_detail->RequestStatus=='Completed')
                 <tr class="complete_block">
                  <td data-th="Id">{{ $request_detail->VideoReqId }}</td>
                   <td data-th="Artist Name">{{ $request_detail->Name }}</td>
                   <td data-th="Title / Description"><span class="title">{{ $request_detail->Title }}</span><span class="content">{{ $request_detail->Description }}</span> </td>
                   <td data-th="Status"><span class="content comp_btn">{{ $request_detail->RequestStatus }}</span> </td>
                   <td data-th="Requested Date">{{  $requestDate }}</td>
                   <td data-th="Completion Date">{{ $completionDate }}</td>
                   <td data-th="Price">${{$request_detail->ReqVideoPrice}}</td>
                   <td><span class="btn_wrap req_video_del"> <a onclick="delete_req({{$request_detail->VideoReqId}},{{$request_detail->requestByProfileId}})" href="javascript:void(0)"> <i class="fa fa-trash red"></i> </a> </span>
                   </td>
                   <td><span class="content comp_btn"> Video Delivered </span></td>
                 </tr>
                 @endif
                 @endforeach
               </tbody>
             </table>
             @else
             <h1 class="title">No Requested Video Details found</h1>
             @endif 
           </div>
         </div>
       </div>
     </div>
   </div>

 </div>

</section>

<div class="out_mess_del_video" style="display:none">

  <div class="inner_message">

    <div class="head_wrap">Delete Video</div>

    <span class="ms_close"><i></i></span>

    <div>

      <div class="main_body message">Are you sure, you want to remove this request.</div>

      <div class="footer_wrap"><a href="javascript:void(0)" class="btn_no ms_close">No</a><a href="" class="btn_yes delete_url">Yes</a></div>

    </div>

  </div>

</div>

@include('frontend.common.footer') </div>

<script>

  function delete_req(req_id,user_id){

    var baseurl = "{{URL('/')}}";
    var url = baseurl+'/delete/'+req_id+'/'+user_id;
    $('.delete_url').attr('href',url);
  }
  $(document).ready(function() {

      $('#completed').on('click',function () {

          $('.complete_block').show();

          $('.approve_block').hide();

          $('.pending_block').hide();

          $('.reject_block').hide();

      })

      $('#approved').on('click',function () {

          $('.approve_block').show();

          $('.complete_block').hide();

          $('.pending_block').hide();

          $('.reject_block').hide();

      })

      $('#pending').on('click',function () {

          $('.pending_block').show();

          $('.complete_block').hide();

          $('.approve_block').hide();

          $('.reject_block').hide();

      })

      $('#reject').on('click',function () {

          $('.reject_block').show();

          $('.complete_block').hide();

          $('.approve_block').hide();

          $('.pending_block').hide();

      })

      $('#all').on('click',function () {

          $('.reject_block').show();

          $('.complete_block').show();

          $('.approve_block').show();

          $('.pending_block').show();

      })



      $('.req_video_del').on('click',function () {
          $('.out_mess_del_video').css("display","block");
          $('.out_mess_del_video').addClass('delete_video');

      })

      $('.out_mess_del_video .ms_close').on('click',function () {
          $('.out_mess_del_video').css("display","none");
          $('.out_mess_del_video').removeClass('delete_video');

      })

  });

</script>

</body>

</html>
