<!DOCTYPE html>
<html lang="en">
<head>
  @include('frontend.common.head')
</head>
<body class="cb-page user_profile change-password-page">
  <div class="cb-pagewrap"> @include('frontend.common.header')
    <section id="mian-content">
      <div class="container">
        <div class="cb-grid">
          <div class="cb-block cb-box-60 main-content">
            <div class="cb-content">
              <div class="cb-content cb-marginT55 cb-marginB50">
                <div class="login-inner-block">
                  <h1 class="heading"> <span class="txt1">Dashboard</span> 
                    <span class="txt2">Change Password</span> </h1>
                    
                    @if(Session::has('pass_success'))
                     <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('pass_success') }} 
                     </div>
                  
                    @endif
                    @if(Session::has('error'))
                    <div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>
                    @endif
                    <div class="request_video_listing_wrap">
                      <form action="{{URL('/user_change_password')}}" class="form" method="post">
                        {{csrf_field()}}
                        <input type="hidden" name="user_email" value="" id="user_email">
                        <div class="form-group">
                          <div class="control-label">
                            <label>Old Password</label>
                          </div>
                          <div class="control-box">
                            <input type="password" name="old_pass" value="" id="old_pass">
                            @if($errors->first('old_pass'))
                            <p class="label label-danger" > {{ $errors->first('old_pass') }} </p>
                            @endif </div>
                          </div>
                          <div class="form-group">
                            <div class="control-label">
                              <label>New Password</label>
                            </div>
                            <div class="control-box">
                              <input type="Password" name="new_pass" value="" id="new_pass">
                              @if($errors->first('new_pass'))
                              <p class="label label-danger" > {{ $errors->first('new_pass') }} </p>
                              @endif </div>
                            </div>
                            <div class="form-group">
                              <div class="control-label">
                                <label>Confirm Password</label>
                              </div>
                              <div class="control-box">
                                <input type="Password" name="conf_pass" value="" id="conf_pass">
                                @if($errors->first('conf_pass'))
                                <p class="label label-danger" > {{ $errors->first('conf_pass') }} </p>
                                @endif </div>
                              </div>
                              <div class="cb-box-100">
                                <div class="control-box">
                                  <input type="submit" class="btn btn-default login change-btn" value="Change">
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
            @include('frontend.common.footer') </div>
          </body>
          </html>