@extends('admin.common.header')



<body class="sticky-header left-side-collapsed"  onload="initMap()">

<section> 

  

  <!-- main content start-->

  

  <div class="main-content">

  <div id="left-side-wrap"> @include('frontend.userDashboard.layouts.lsidebar') </div>

  

  <!-- header-starts -->

  

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

            <div class="dropdown user-menu"> <span class="dropdown-toggle"> <span class="admin-img" style="background:url(images/1.jpg) no-repeat center"> </span> <span class="admin-name">{{ $profileData->Name}}</span> <i class="arrow"></i> </span> </div>

            

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

  

  <div  class="col-md-10 profile-update-wrap">

    <div id="page-wrapper"> @if(Session::has('success'))

      <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

      @endif

      

      

      

      

      

      

      

      @if(Session::has('error'))

      <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

      @endif

      <div class="graphs">

        <h1 class="heading">Change Password</h1>

        {!! Form::open(array('url' =>'change_password','class'=>'form form-horizontal text-left','method'=>'post' )) !!}

        <div class="inner-wrapper">

          <div class="form-group">

            <div class="control-label">

              <label for="old_password">Old Password</label>

            </div>

            <div class="control-box">

              <input type="password" name="old_password" id="old_password" class='form-control' value="{{Request::old('old_password')}}">

              @if($errors->first('old_password'))

              <p class="label label-danger" > {{ $errors->first('old_password') }} </p>

              @endif</div>

          </div>

          <div class="form-group">

            <div class="control-label">

              <label for="new_password">New Password</label>

            </div>

            <div class="control-box">

              <input type="password" name="new_password" id="new_password"  class='form-control' value="{{Request::old('new_password')}}">

              @if($errors->first('new_password'))

              <p class="label label-danger" > {{ $errors->first('new_password') }} </p>

              @endif</div>

          </div>

          <div class="form-group">

            <div class="control-label">

              <label for="confirm_password">Confirm Password</label>

            </div>

            <div class="control-box">

              <input type="password" name="confirm_password" id="confirm_password" class='form-control' value="{{Request::old('confirm_password')}}" >

              @if($errors->first('confirm_password'))

              <p class="label label-danger" > {{ $errors->first('confirm_password') }} </p>

              @endif</div>

          </div>

        </div>

        <br>

        {!! Form::submit('Change',array('class'=>'btn btn-primary center-block'))!!} 

        

        <!-- <button type="button" class="btn btn-primary " data-dismiss="modal" class='btn btn-primary center-block'>Close</button> --> 

        

        {!! Form::close() !!} </div>

    </div>

  </div>
</div>
  @extends('admin.common.footer') </section>

</body>

</html>