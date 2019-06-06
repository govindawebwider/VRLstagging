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

                            <div class="alert alert-success"> {{Session::get('success') }} </div>

                            @endif

                            

                            

                            

                            

                            

                            

                            

                            @if(Session::has('error'))

                            <div class="alert alert-danger"> {{Session::get('error') }} </div>

                            @endif

                            <div class="graphs">

                              <h1 class="heading">Update Profiles</h1>

                              {!! Form::open(array('url' =>'profile_update','class'=>'form form-horizontal text-left','id'=>'upProfile','method'=>'post' ,'files'=>true)) !!}

                              <div class="inner-wrap">

                                <div class="form-group">

                                  <div class="control-label">

                                    <label for="username">Name</label>

                                  </div>

                                  <div class="control-box">

                                    <input type="text" name="username" id="username" value="{{$profileData->Name}}" class='form-control' >

                                    @if($errors->first('username'))

                                    <p class="label label-danger" > {{ $errors->first('username') }} </p>

                                    @endif</div>

                                  </div>

                                  <div class="form-group">

                                    <div class="control-label">

                                      <label for="email">Email</label>

                                    </div>

                                    <div class="control-box">

                                      <input type="text" name="email" id="email" value="{{$profileData->EmailId}}" class='form-control' disabled="disabled">

                                    </div>

                                  </div>

                                  <div class="form-group">

                                    <div class="control-label">

                                      <label for="dob">Date Of Birth</label>

                                    </div>

                                    <div class="control-box">

                                      <input type="date" name="dob" id="dob" value="{{$profileData->DateOfBirth}}" class='form-control' >

                                      @if($errors->first('dob'))

                                      <p class="label label-danger" > {{ $errors->first('dob') }} </p>

                                      @endif</div>

                                    </div>

                                    <div class="form-group">

                                      <div class="control-label">

                                        <label for="phone">Phone No.</label>

                                      </div>

                                      <div class="control-box">

                                        <input type="text" name="phone" id="phone" value="{{$profileData->MobileNo}}" class='form-control' >

                                        @if($errors->first('phone'))

                                        <p class="label label-danger" > {{ $errors->first('phone') }} </p>

                                        @endif</div>

                                      </div>

                                      <div class="form-group">

                                        <div class="control-label">

                                          <label for="nickName">Nick Name:</label>

                                        </div>

                                        <div class="control-box">

                                          <input type="text" name="nickName" id="nickName" value="{{$profileData->NickName}}" class='form-control' >

                                          @if($errors->first('nickName'))

                                          <p class="label label-danger" > {{ $errors->first('nickName') }} </p>

                                          @endif</div>

                                        </div>

                                        <div class="form-group">

                                          <div class="control-label">

                                            <label for="address">Address</label>

                                          </div>

                                          <div class="control-box">

                                            <input type="text" name="address" id="address" value="{{$profileData->Address}}" class='form-control' >

                                            @if($errors->first('address'))

                                            <p class="label label-danger" > {{ $errors->first('address') }} </p>

                                            @endif</div>

                                          </div>

                                          <div class="form-group">

                                            <div class="control-label">

                                              <label for="city">City:</label>

                                            </div>

                                            <div class="control-box">

                                              <input type="text" name="city" id="city" value="{{$profileData->City}}" class='form-control' 







                                              @if($errors->

                                              first('city'))

                                              <p class="label label-danger" > {{ $errors->first('city') }} </p>

                                              @endif></div>

                                            </div>

                                            <div class="form-group">

                                              <div class="control-label">

                                                <label for="state">State:</label>

                                              </div>

                                              <div class="control-box">

                                                <input type="text" name="state" id="state" value="{{$profileData->State}}" class='form-control' >

                                                @if($errors->first('state'))

                                                <p class="label label-danger" > {{ $errors->first('state') }} </p>

                                                @endif</div>

                                              </div>

                                              <div class="form-group">

                                                <div class="control-label">

                                                  <label for="paypal_id">Paypal Id</label>

                                                </div>

                                                <div class="control-box">

                                                  <input type="number" name="paypal_id" id="paypal_id" value="{{$profileData->PaypalId}}" class='form-control' >

                                                  @if($errors->first('paypal_id'))

                                                  <p class="label label-danger" > {{ $errors->first('paypal_id') }} </p>

                                                  @endif</div>

                                                </div>

                                                <div class="form-group">

                                                  <div class="control-label">

                                                    <label for="country">Country</label>

                                                  </div>

                                                  <div class="control-box">

                                                    <input type="text" name="country" id="country" value="{{$profileData->country}}" class='form-control' >

                                                    @if($errors->first('country'))

                                                    <p class="label label-danger" > {{ $errors->first('country') }} </p>

                                                    @endif</div>

                                                  </div>

                                                  <div class="form-group">

                                                    <div class="control-label">

                                                      <label for="zip">Zip</label>

                                                    </div>

                                                    <div class="control-box">

                                                      <input type="number" name="zip" id="zip" value="{{$profileData->Zip}}" class='form-control' >

                                                      @if($errors->first('zip'))

                                                      <p class="label label-danger" > {{ $errors->first('zip') }} </p>

                                                      @endif</div>

                                                    </div>

                                                    <div class="form-group">

                                                      <div class="control-label">

                                                        <label for="profile">Profile :</label>

                                                      </div>

                                                      <div class="control-box">

                                                        <input type="file" name="profile" id="profile" class='form-control' >

                                                        <img src="{{$profileData->profile_path}}" alt=""> </div>

                                                      </div>

                                                    </div>

                                                    <input type="hidden" name="ProfileId" value="{{$profileData->ProfileId}}">

                                                    <br>

                                                    {!! Form::submit('Update',array('class'=>'btn btn-primary center-block'))!!} 

                                                    

                                                    <!-- <button type="button" class="btn btn-primary " data-dismiss="modal" class='btn btn-primary center-block'>Close</button> --> 

                                                    

                                                    {!! Form::close() !!} </div>

                                                  </div>

                                                </div>

                                                @extends('admin.common.footer') </section>

                                                {!! Html::script('js/jquery.nicescroll.js') !!}







                                                {!! Html::script('js/scripts.js') !!} 



                                                <!-- Bootstrap Core JavaScript --> 



                                                {!! Html::script('js/bootstrap.min.js') !!}

                                              </body>

                                              </html>