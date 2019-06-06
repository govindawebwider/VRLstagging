@include('admin.common.header')
<body class="admin video_request">
<style>
    .out_mess_del_video .model-width {
        max-width: 450px !important;
    }
    .select2-container {
        z-index: 99999;
    }
</style>
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
              <div class="dropdown user-menu"> <span class="dropdown-toggle"> <span class="admin-img">
                          <?php
                          $fileName = 'images/Artist/'.$image_paths->profile_path;
                          $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                      ->url($fileName);?>
                          <img src="{{$imageUrl}}" alt="">
                          {{--<img src="{{url('images/Artist/').'/'.$image_paths->profile_path}}" alt="">--}}
                      </span> <span class="admin-name">{{$image_paths->Name}}</span> <i class="arrow"></i> </span>
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
      <div  class="col-md-12">
        <div id="page-wrapper">
          <div class="graphs">
          <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/video_requests">Waiting to be Fulfilled</a> </div>
            @if(Session::has('success'))
            <div class="alert alert-success"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('success') }}</span> </div>
            @endif
            <h1 class="heading">Waiting to be Fulfilled</h1>
            
              <!-- <a href="{{URL('/req_video_csv/'.$users->profile_id)}}" >   
                <input class="btn btn-primary" type="button" name="artist_csv" value="Export Request Video List">
              </a>  -->
            <div class="content grid_bottom clearfix">
              <div class="col-sm-12 info-grid">
                <div class="box table-info">
                  
                  <form class="col-sm-12" style="padding-left: 0px;">
                    <div class="col-sm-5" style="padding-left: 0px;">
                      <div class="input-group">
                        <input type="search" class="light-table-filter" data-table="order-table" placeholder="Search..." />
                        <span class="input-group-addon ques"><img src="/images/question.png"></span>
                      </div>
                    </div>
                    <div class="col-sm-3 stat">
                      <label>Status</label><br>
                      <select name="status" type="search" class="select_status select-table-filter" data-table="order-table">
                        <option value="">All</option>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                      </select>
                    </div>
                  </form>
                  <table class="order-table table table-responsive table-striped" id="example">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Email</th>
                        <th>Who is this video for</th>
                        <th>Song</th>
                        <th>Occasion</th>
                        <th>Message</th>
                        <th>Delivery Date</th>
                        <th>Status </th>
                        <th>Action </th>
                      </tr>
                    </thead>
                    <tbody>

                      @foreach($video_requests as $video_request)
                      <tr class="content">
                          <?php
                          $requestDate = Carbon\Carbon::createFromFormat('m/d/Y', $video_request->request_date, 'UTC')
                                  ->setTimezone(session('timezone'))
                                  ->format('m/d/Y');
                          ?>
                        <td>{{ $requestDate }}</td>
                        <td>{{ $video_request->sender_email }} </td>
                        <td>{{ $video_request->Name }}</td>
                        <td>{{ $video_request->song_name }}</td>
                        <td>{{ $video_request->Title }}</td>
                        <td>{{ $video_request->Description }}</td>
                          <?php
                              $completionDate = date('m/d/Y', strtotime($video_request->complitionDate));
                          ?>
                        <td>{{ $completionDate }}</td>
                        <td class="status"><span>
                       <?php  if($video_request->RequestStatus == 'Pending'){?> <i class="fa fa-circle yellow" aria-hidden="true"></i> Pending <?php } elseif($video_request->RequestStatus == 'Approved') {?> <i class="fa fa-circle purple" aria-hidden="true"></i> Approved <?php } elseif($video_request->RequestStatus == 'Reject') {?>  <i class="fa fa-circle red" aria-hidden="true"></i> Rejected <?php } else {?>  <i class="fa fa-circle green" aria-hidden="true"></i> Completed <?php }?>
                         </span></td>
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
                                      <a onClick="rejectRequest({{$video_request->VideoReqId}})" class="btn btn-danger req_video_del" href="javascript:void(0)">Reject</a>
                                  {{--<a class="btn btn-danger" href="{{URL('reject_request/'.$video_request->VideoReqId)}}">Reject</a>--}}
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
</div>
</div>
@include('admin.common.footer') 
</section>
  <div class="out_mess_del_video" id="myModal">
      <div class="model-width inner_message" style="transition: all 0s ease-out;">
          <div class="head_wrap">Reject Request</div>
          <span class="ms_close"><i></i></span>
          <div>
              <form action="/reject_request" method="post">
                  <div class="main_body message">
                      <strong>{{\Lang::get('messages.reject_request')}}</strong>
                      <br><br>
                      {!! Form::select('rejected_reason', [''=>'Select Reason']+Config::get('constants.REJECTED_REASONS'), null, ['style'=>'width: 50%;', 'id'=>'rejected_reason']) !!}
                      <br><br>
                      {!! Form::textarea('rejected_comment', null, ['rows' => 4, 'cols' => 48, 'id'=>'rejected_comment']) !!}
                      {{ csrf_field() }}
                      {!! Form::hidden('request_id', null, ['id' => 'request_id']) !!}
                  </div>
                  <div class="footer_wrap"><a href="javascript:void(0)" class="btn_no ms_close">No</a>
                      <input type="submit" class="btn_yes" value="Yes"></div>
              </form>
          </div>
      </div>
  </div>
<script type="text/javascript">
  $( document ).ready(function() {
    $( ".dropdown.user-menu" ).click(function() {
      $( '.dropdown.user-menu ul' ).toggle();
    });
  });
</script>
<script>
  $(document).ready(function() {
    $('#example_paginate').show();
  });

</script>
<script>
(function(document) {
  'use strict';

  var LightTableFilter = (function(Arr) {

    var _input;
    var _select;

    function _onInputEvent(e) {
      _input = e.target;
      var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
      Arr.forEach.call(tables, function(table) {
        Arr.forEach.call(table.tBodies, function(tbody) {
          Arr.forEach.call(tbody.rows, _filter);
        });
      });
    }
    
    function _onSelectEvent(e) {

      _select = e.target;
      var tables = document.getElementsByClassName(_select.getAttribute('data-table'));

      Arr.forEach.call(tables, function(table) {
        Arr.forEach.call(table.tBodies, function(tbody) {
          Arr.forEach.call(tbody.rows, _filterSelect);
        });
      });
    }

    function _filter(row) {
      
      var text = row.textContent.toLowerCase(), val = _input.value.toLowerCase();
      row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';

    }
    
    function _filterSelect(row) {
     
      var text_select = row.textContent.toLowerCase(), val_select = _select.options[_select.selectedIndex].value.toLowerCase();
      
      row.style.display = text_select.indexOf(val_select) === -1 ? 'none' : 'table-row';

    }

    return {
      init: function() {
        var inputs = document.getElementsByClassName('light-table-filter');
        var selects = document.getElementsByClassName('select-table-filter');

        Arr.forEach.call(inputs, function(input) {
          input.oninput = _onInputEvent;
        });
        Arr.forEach.call(selects, function(select) {

         select.onchange  = _onSelectEvent;
        });
      }
    };
  })(Array.prototype);

  document.addEventListener('readystatechange', function() {
    if (document.readyState === 'complete') {
      LightTableFilter.init();
    }
  });

})(document);
$('.out_mess_del_video .ms_close').on('click',function () {
    $('.out_mess_del_video').removeClass('delete_video');
});
function rejectRequest(id) {
    $('#request_id').val(id);
    $('#rejected_comment').value = '';
}
$('#rejected_reason').select2({
    dropdownParent: $('#myModal')
});
</script>
<style type="text/css">
  div#example_filter{
  display: none;
}
</style>
</body>
</html>
