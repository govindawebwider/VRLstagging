@extends('admin.common.header')

<body class="sticky-header left-side-collapsed"  onload="initMap()">
<section class="main-page-wrapper"> 
  
  <!-- main content start-->
  
  <div class="main-content"> 
    
    <!-- header-starts -->
    
    <div id="left-side-wrap"> @include('admin.common.lsidebar') </div>
    <div class="header-section">
      <div class="logo">
        <h1>VRL<span class="tag-line"><span class="txt1">Video</span><span class="txt2">Request</span><span class="txt3">Line</span></span></h1>
      </div>
      <div class="menu-right">
        <div class="notice-bar">
          <ul>
            <li><a href="{{ URL('notifications')}}"><i class="lnr lnr-alarm"></i><span class="no">10</span></a><span class="tooltip">notifications</span></li>
            <li><a href="{{ URL('activite')}}"><i class="lnr lnr-file-empty"></i><span class="no">10</span></a><span class="tooltip">activite</span></li>
          </ul>
        </div>
        <div class="user-panel-top">
          <div class="profile_details">
            <div class="profile_img">
              <div class="dropdown user-menu"> <span class="dropdown-toggle"> <span class="admin-img" style="background:url(images/1.jpg) no-repeat center"> </span> <span class="admin-name">{{ $users->user_name }}</span> <i class="arrow"></i> </span> </div>
              
              <!--<div class="dropdown-menu">



                                	<ul>



                                    	



                                    </ul>



                                </div>--> 
              
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- //header-ends -->
    
    <div id="page-wrapper">
      <div class="graphs main-status-wrap"> @if(Session::has('message'))
        <div class="alert alert-success"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('message') }}</span> </div>
        @endif
        <h1 class="heading">Pending Request Video</h1>
        <div class="xs">
          <div class="status-table-wrap">
            <div class="mailbox-content">
              <table class="table table-fhr">
                <thead>
                  <tr>
                    <td>{!! Form::label('SetStatus', 'Set Status')!!}</td>
                    <td>{!! Form::label('CurrentStatus', 'Current Status')!!}</td>
                    <td>{!! Form::label('VideoDescription', 'Video Description')!!}</td>
                    <td>{!! Form::label('RequestedBy', 'Request By') !!}</td>
                    <td>{!! Form::label('Description', 'About Video') !!}</td>
                    <td>{!! Form::label('RequestedDate', 'Requested Date')!!}</td>
                    <td>{!! Form::label('delivery', 'Delivery Date') !!}</td>
                    <td>{!! Form::label('action', 'Action') !!}</td>
                    <td></td>
                  </tr>
                </thead>
                <tbody>
                
                @foreach ($video_requests as $video_request)
                
                
                
                {!! Form::open(array('url' =>'update_video_request','class'=>'form form-horizontal text-left','id'=>'regfrm','method'=>'post')) !!}
                <tr class="unread checked">
                  <td><select name="SetStatus" id="" class="form-control">
                      <option value="approve">Approve</option>
                      <option value="reject">Reject</option>
                    </select></td>
                  <td class="hidden-xs">{{ $video_request->RequestStatus }}</td>
                  <td>{{$video_request->Description}}</td>
                  <td>{{$video_request->Name}}</td>
                  <td>{{$video_request->Description}}</td>
                  <td>{{$video_request->RequestDate}}</td>
                  <td>{{$video_request->complitionDate}}</td>
                  <td><a href="/upload_video/{{$video_request->requestByProfileId}}">Upload Video</a></td>
                  <input type="hidden" name="VideoReqId" value="{{ $video_request->VideoReqId}}">
                  <input type="hidden" name="requester" value="{{ $video_request->requestByProfileId}}">
                  <td> {!! Form::submit('Update',array('class'=>'btn btn-primary center-block', 'id'=>'videosend'))!!} </td>
                </tr>
                {!! Form::close() !!}
                
                
                
                @endforeach
                  </tbody>
                
              </table>
            </div>
          </div>
          <div class="clearfix"> </div>
        </div>
      </div>
    </div>
  </div>
  @include('admin.common.footer') </section>
</body>
</html>