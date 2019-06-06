  <div class="header-section">
    <div class="top-main-left">
        <a href="{{URL('admin_dashboard')}}"><span class="logo1 white"><img src="/images/vrl_logo_nav.png" class="img img-responsive"></span> </a>
          <a href="javascript:void(0)" class="toggle menu-toggle"><i class="lnr lnr-menu"></i></a>
    </div>
    {{--<div class="col-md-1 span-icon">
      <i class="lnr lnr-menu" onclick="contentMove();"></i>
    </div>--}}
    
    <div class="menu-right">
    

      <div class="user-panel-top">

        <div class="profile_details">

          <div class="profile_img">

            <div class="dropdown user-menu"> <span class="dropdown-toggle"> 

              <span class="admin-img"> <img src="{{URL('/')}}/images/Artist/1494304967.jpg" alt=""> </span> 

              <span class="admin-name">Admin</span> 

              <i class="arrow"></i> </span>

              <ul>

                           <!-- <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="#"> <i class="icon icon-users"></i> <span>View Profile</span> </a> </li>

                           <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="#"> <i class="icon icon-users"></i> <span>Edit Profile</span> </a> </li>-->

                            <!-- <li class="{{ Request::is('login_artist') ? 'active' : '' }}"> 

                              <a href="{{ URL('login_artist') }}"> 

                                <i class="icon icon-lock"></i> 

                                <span>Login as Artist</span> 

                              </a> 

                            </li>

                            <li class="{{ Request::is('login_user') ? 'active' : '' }}"> 

                              <a href="{{ URL('login_user') }}"> 

                                <i class="icon icon-lock"></i> 

                                <span>Login as User</span> 

                              </a> 

                            </li> -->

                            <li class="{{ Request::is('change-password') ? 'active' : '' }}"> 

                              <a href="{{ URL('change_pass') }}"> 

                                <i class="icon icon-lock"></i> 

                                <span>Change Password</span> 

                              </a> 

                            </li>

                            <li class="{{ Request::is('getLogout') ? 'active' : '' }}"> <a href="{{ URL::route('getLogout') }}"> <i class="icon icon-exit"></i> <span>Logout</span> </a> </li>                    

                          </ul> 

                        </div>                      	

                      </div>

                    </div>

                  </div>  

                </div>

              </div>

              

