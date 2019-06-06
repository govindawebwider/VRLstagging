@include('admin.common.header')

<body class="admin change_password">

  <section class="main-page-wrapper">

    <div class="main-content">

      <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>

      @include('admin.layouts.header')

      <div class="change_password_wrap">

        <div  class="col-md-12 ">

          <div id="page-wrapper">

            <div class="change_pass_form"> @if(Session::has('error'))

              <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

              @endif

              <div class="graphs">
              <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="#">Invite Artist</a> </div>
                <h1 class="heading">Invite Artist</h1>
                <p class="desc-title"></p>

                {!! Form::open(array('url' =>'invite_artist','class'=>'form form-horizontal text-left','method'=>'post' )) !!}

                <div class="inner-wrapper"> @if(Session::has('register_error'))

                  <div class="alert alert-danger"> {{Session::get('login_error') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </div>

                  @endif

                  @if(Session::has('success'))

                  <div class="alert alert-success"> {{Session::get('success') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </div>

                  @endif



                  {!! Form::open(array('url' =>'invite_artist','class'=>'form form-horizontal text-left','id'=>'regfrm','method'=>'post','files' => true)) !!}

                  <div class="form-group">

                    <div class="control-label">{!! Form::label('username', 'Artist Name')!!} </div>

                    <div class="control-box"> {!! Form::text('username',null,array('class'=>'form-control','name'=>'username'))!!}

                      @if($errors->first('username'))

                      <p class="label label-danger" > {{ $errors->first('username') }} </p>

                      @endif</div>

                    </div>

                    <div class="form-group">

                      <div class="control-label">{!! Form::label('email', 'Email')!!}</div>

                      <div class="control-box"> {!! Form::email('artistEmail',null,array('class'=>'form-control','id'=>'artistEmail'))!!}

                        @if($errors->first('artistEmail'))

                        <p class="label label-danger" > {{ $errors->first('artistEmail') }} </p>

                        @endif</div>

                      </div>

                      <div class="form-group">

                        <div class="control-label">{!! Form::label('gender', ' Gender')!!}</div>



                        <div class="control-box">

                          <select name="gender" id="gender" class="">

                            <option value="">Please Select Gender</option>

                            <option value="male">Male</option>

                            <option value="female">Female</option>

                          </select>



                          @if($errors->first('gender'))

                          <p class="label label-danger" > {{ $errors->first('gender') }}</p>

                          @endif</div>

                        </div>

                        <div class="form-group">

                          <div class="control-label"><label></label></div>

                          <div class="control-box"> {!! Form::submit('Invite',array('class'=>'btn btn-primary sbt-btn','id'=>'regBtn'))!!} </div>

                        </div>

                      </div>

                      {!! Form::close() !!} </div>

                    </div>

                  </div>

                </div>

              </div>

              @extends('admin.common.footer')

            </body>

            </html>