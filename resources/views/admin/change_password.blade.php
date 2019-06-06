@include('admin.common.header')
<body class="admin change_password">
    <section class="main-page-wrapper">
		<div class="main-content">
 
			<div id="left-side-wrap"> 
            @include('admin.layouts.lsidebar') </div>
			@include('admin.layouts.header')
            <div class="change_password_wrap">
				<div  class="col-md-12 ">
					<div id="page-wrapper">
						<div class="change_pass_form"> 
                            <div class="graphs">
                                <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="#">Change Password</a> </div>
                                <h1 class="heading">Change Password</h1>
                                <p class="desc-title"></p>
                                @if(Session::has('success'))
                            <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>
                            @endif   
                            @if(Session::has('error'))
                            <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>
                            @endif
                                {!! Form::open(array('url' =>'change_pass','class'=>'form form-horizontal text-left','method'=>'post' )) !!}
                                    <div class="inner-wrapper">
                                      <div class="form-group">
                                        <div class="control-label"><label for="old_password">Old Password</label></div>
                                        <div class="control-box">
                                          <input type="password" name="old_password" id="old_password" class='form-control' value="{{Request::old('old_password')}}">
                                          @if($errors->first('old_password'))
                                          <p class="label label-danger" > {{ $errors->first('old_password') }} </p>
                                          @endif</div>
                                      </div>
                                      <div class="form-group">
                                        <div class="control-label"><label for="new_password">New Password</label></div>
                                        <div class="control-box">
                                          <input type="password" name="new_password" id="new_password"  class='form-control' value="{{Request::old('new_password')}}">
                                          @if($errors->first('new_password'))
                                          <p class="label label-danger" > {{ $errors->first('new_password') }} </p>
                                          @endif</div>
                                      </div>
                                      <div class="form-group">
                                        <div class="control-label"><label for="confirm_password">Confirm Password</label></div>
                                        <div class="control-box">
                                          <input type="password" name="confirm_password" id="confirm_password" class='form-control' value="{{Request::old('confirm_password')}}" >
                                          @if($errors->first('confirm_password'))
                                          <p class="label label-danger" > {{ $errors->first('confirm_password') }} </p>
                                          @endif</div>
                                      </div>
                                      
                                      <div class="form-group">
                                        <div class="control-label"> <label></label></div>
                                        <div class="control-box">
                                         {!! Form::submit('Change',array('class'=>'btn btn-primary center-block'))!!}
                                         </div>
                                      </div>
                                      
                                    </div>
                                    
                                
                                {!! Form::close() !!} 
							</div>
						</div>
            		</div>
            	</div>
            </div>
		</div>
  </section>
  @extends('admin.common.footer')
</body>
</html>