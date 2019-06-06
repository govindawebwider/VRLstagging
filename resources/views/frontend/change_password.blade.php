<!DOCTYPE html>
<html lang="en">
<head>
 @include('frontend.common.head')
</head>
<body class="cb-page user_profile change-password-page notranslate">
  <div class="cb-pagewrap"> 
    @include('frontend.common.userHeader')
    <section id="mian-content">
        <div class="container">
            <div class="cb-grid">
                <div class="cb-block cb-box-60 main-content user_data">
                    <div class="cb-content">
                            <div class="inner-block">
                                <h1 class="heading">
                                    <span class="txt1">Dashboard</span>
                                    <span class="txt2 new_txt">Change Password</span>
                                </h1>
                                <div class="request_video_listing_wrap">
                                    <div class="cb-block cb-box-100">

                                    @if(Session::has('pass_success'))

                                    <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('pass_success') }} 
                                    </div>
                                        
                                    @endif
                                    @if(Session::has('error'))
                                        <div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>
                                    @endif
                                    <div class=" cb-content video-wrap artist-video">
                                    <form action="{{URL('/user_change_password')}}" id='changePassword' class="form form-horizontal" method="post">
                                        {{csrf_field()}}
                                        <input type="hidden" name="user_email" value="" id="user_email">
                                        <div class="inner-wrap"> </div>
                                        <div class="form-group">
                                            <div class="control-label">
                                                <label>Old Password</label>
                                            </div>
                                            <div class="control-box">
                                                <input type="password" name="old_pass" value="" id="old_pass" class='form-control'>
                                                @if($errors->first('old_pass'))
                                                <p class="label label-danger" > {{ $errors->first('old_pass') }} </p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="control-label">
                                                <label>New Password</label>
                                            </div>
                                            <div class="control-box">
                                            <input type="Password" name="new_pass" value="" id="new_pass" class='form-control'>
                                                @if($errors->first('new_pass'))
                                                <p class="label label-danger" > {{ $errors->first('new_pass') }} </p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="control-label">
                                                <label>Confirm Password</label>
                                            </div>
                                            <div class="control-box">
                                                <input type="Password" name="conf_pass" value="" id="conf_pass" class='form-control'>
                                                @if($errors->first('conf_pass'))
                                                <p class="label label-danger" > {{ $errors->first('conf_pass') }} </p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="control-box">
                                                <input type="submit" class="btn btn-primary profile-update-btn" value="Change">
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
        </div>
    </section>
            <!--section id="footer">
              <div class="container">
                <div class="cb-grid">
                  <div class="cb-block cb-box-50">
                    <div class="cb-content  nomargin-top nopadding-bottom nomargin-bottom footer-nav">
                      <ul>
   <li><a href="http://www.videorequestline.com/about-us"><span>About</span></a></li>                       
 <li><a href="http://www.videorequestline.com/help"><span>Help</span></a></li>
                        <li><a href="http://www.videorequestline.com/about-us"><span>About</span></a></li>
                        <li><a href="http://www.videorequestline.com/terms"><span>Terms</span></a></li>
                        <li><a href="http://www.videorequestline.com/privacy"><span>Privacy</span></a></li>
                      </ul>
                    </div>
                  </div>
                  <div class="cb-block cb-box-50">
                    <div class="cb-content social-icon nopadding-bottom  nopadding-top">
                      <ul>
                        <li><a href="https://www.facebook.com/vid.req.9" target="_blank"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="https://twitter.com/requestvideo" target="_blank"><i class="fa fa-twitter"></i></a></li>
                    
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div id="copyright">
                <div class="container">
                  <div class="cb-grid">
                    <div class="cb-block">
                      <div class="cb-content copyright  nopadding-bottom  nopadding-top">
                        <p>&copy; {{date('Y')}} Video Request Line - All Rights Reserved</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section-->
			 @include('frontend.common.footer')
          </div>
        </body>
        </html>