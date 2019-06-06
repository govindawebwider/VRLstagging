@include('admin.common.header')

<body class="admin deliver_video">

  <section class="main-page-wrapper">

    <div class="main-content">

      <div id="left-side-wrap"> @include('frontend.artistDashboard.layouts.lsidebar') </div>

      <div class="header-section">

        <div class="menu-right">

          <div class="user-panel-top">

            <div class="profile_details">

              <div class="profile_img">

                <div class="dropdown user-menu"> <span class="dropdown-toggle"> <span class="admin-img"> <img src="/{{$artist->profile_path}}" alt=""> </span> <i class="arrow"></i> </span>

                  <ul>

                   <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{ URL($artist->profile_url)}}"> <i class="icon icon-users"></i> <span>view Profile</span> </a> </li>

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

     <div class="deliver_video_wrap">

      <div  class="col-md-12 ">

        <div id="page-wrapper">

          <div class="graphs">

            @if(Session::has('success'))

            <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

            @endif

            @if(Session::has('error'))

            <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

            @endif

            <h1 class="heading">Add Review</h1>

            <div class="inner-wrap">

              <?php //dd($testi_data);?>

              <div class="content clearfix">

                <div class="xs">

                  <div class="status-table-wrap">

                    <div class="mailbox-content ">

                      <form action="/add_testimonial" method="POST" role="form">

                        {!! csrf_field(); !!}

                        <div class="form-group">

                          <div class="control-label">

                            <label for="ssn_number">Message</label>

                          </div>

                          <div class="control-box">

                            <textarea name="message" rows="5" style="width:100%; max-width:100%;"></textarea>

                            @if($errors->first('message'))

                            <p class="label label-danger" > {{ $errors->first('message') }} </p>

                            @endif </div>

                          </div>

                          <div class="form-group">

                            <div class="control-label">

                              <label for="ssn_number"></label>

                            </div>

                            <div class="control-box">

                              <div class="edit_testimonial-btn" style="float:left;">

                                <input type="submit" class="btn btn-primary" value="Add">

                              </div>

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

      </div>

      @include('admin.common.footer') </section>

      

      <script type="text/javascript">

        $( document ).ready(function() {

          $( ".dropdown.user-menu" ).click(function() {

            $( '.dropdown.user-menu ul' ).toggle();

          });

        });

      </script>

    </body>

    </html>