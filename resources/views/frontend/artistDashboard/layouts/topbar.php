
      <div class="header-section">

        <div class="top-main-left"></div>

        <div class="menu-right">

          <div class="user-panel-top">

            <div class="profile_details">

              <div class="profile_img">



                <div class="dropdown user-menu"> <span class="dropdown-toggle"> <span class="admin-img"> <img src="{{$image_paths->profile_path}}" alt=""> </span> <span class="admin-name">{{$image_paths->Name}}</span> <i class="arrow"></i> </span>

                  <ul>
                    @if(session('current_type') == 'Artist')
                    <li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>Switch to Admin</span></a></li>
                    @endif

                    <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{ URL($image_paths->profile_url)}}"> <i class="icon icon-users"></i> <span>view Profile</span> </a> </li>

                    <?php //dd($users);?>

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