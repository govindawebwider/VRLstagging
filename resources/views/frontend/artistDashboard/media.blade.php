@include('admin.common.header')

<body class="admin custom_css">

  <section class="main-page-wrapper">

    <div class="main-content">

      <div id="left-side-wrap"> @include('frontend.artistDashboard.layouts.lsidebar') </div>

      <div class="header-section">
          <div class="top-main-left">
              <a href="{{URL('Dashboard')}}"><span class="logo1 white"><img src="/images/vrl_logo_nav.png" class="img img-responsive"></span> </a>
              <a href="javascript:void(0)" class="toggle menu-toggle"><i class="lnr lnr-menu"></i></a>
          </div>
        <div class="menu-right">

          <div class="user-panel-top">

            <div class="profile_details">

              <div class="profile_img">

                <div class="dropdown user-menu"> <span class="dropdown-toggle"> <span class="admin-img">
                            <?php
                            $fileName = 'images/Artist/'.$profileData->profile_path;
                            $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                        ->url($fileName);?>
                            <img src="{{$imageUrl}}" alt="" >
                            {{--<img src="{{url('images/Artist/').'/'.$profileData->profile_path}}" alt="">--}}
                        </span> <span class="admin-name">{{$profileData->Name}}</span> <i class="arrow"></i> </span>

                  <ul>
                   @if(session('current_type') == 'Artist')
                   <li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>Switch to Admin</span></a></li>
                   @endif
                   <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{ URL($profileData->profile_url)}}"> <i class="icon icon-users"></i> <span>view Profile</span> </a> </li>
                   @if($user->admin_link=='yes')

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

     <div class="custom_css_wrap">

      <div  class="col-md-12 ">

        <div id="page-wrapper">

          <div class="graphs">

          <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/media">Media Css</a> </div> 
            @if(Session::has('success'))

            <div class="alert alert-success"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('success') }}</span> </div>

            @endif

            <h1 class="heading">Custom Css</h1>
            

            {!! Form::open(array('url' =>'media','class'=>'form form-horizontal text-left','id'=>'regfrm','method'=>'post')) !!}


            <div class="inner-wrapper">
             
              <div class="form-group">

                <div class="control-label">

                  <label for="account_number">Artist Name </label>

                </div>

                <div class="control-box">

                  <input type="text" class="jscolor {valueElement:'chosen-value', onFineChange:'setTextColor(this)'} form-control" id="chosen-value" value="{{$text_color->text_color}}" name="text_color">

                  <script>

                    function setTextColor(picker) {

                                        //document.getElementsByTagName('body')[1].style.color = '#' + picker.toString()

                                      }

                                    </script> 

                                  </div>

                                </div>

                                <!-- <div class="form-group">

                                  <div class="control-label">

                                    <label for="ssn_number">Heading</label>

                                  </div>

                                  <div class="control-box">

                                    <input type="text" id="chosen-value1" value="{{$profileData->title_color}}" class="jscolor {valueElement:'chosen-value1', onFineChange:'setTextColor(this)'} for, form-control" name="title_color">

                                  </div>

                                </div> -->

                                

            <!--  <tr>

                                    <td>

                                      <button class="jscolor {valueElement:'chosen-value2', onFineChange:'setTextColor(this)'}">

                                          Change Description text color

                                      </button>

                                       <input type="hidden" id="chosen-value2" value="{{$profileData->description_color}}" name="description_color">

                                    </td>

                                  </tr> -->

                                  

                                  <div class="form-group">

                                    <div class="control-label">

                                      <label for="ssn_number">Set Name text Size</label>

                                    </div>

                                    <div class="control-box">

                                      <input type="text" name="name_heading_size" id="size" placeholder="change name heading size" value="{{$profileData->name_heading_size}}" class="form-control">

                                    </div>

                                  </div>

                                  <div class="form-group">

                                    <div class="control-label">

                                      <label for="ssn_number">Note : Write CSS Like as <span>a { color:red; }</span></label>

                                    </div>

                                    <div class="control-box">

                                      <input type="text" name="custom_css2" id="size" placeholder="Css" value="{{$profileData->custom_css}}" class="form-control">

                                    </div>

                                  </div>

                                  <div class="form-group">

                                    <div class="control-label">

                                      <label for="ssn_number">Custom CSS</label>

                                    </div>

                                    <div class="control-box">

                                      <textarea rows="10" cols="50" name="custom_css" class="form-control">

                                        {{{ $profileData->custom_css or '' }}}

                                      </textarea>

                                    </div>

                                  </div>

                                  

            <!--  <tr>

                                      <td>Note : Write CSS Like as <span>a { color:red; }</span></td>

                                      

                                    </tr>

                                    <tr>

                                      <td>Custom CSS</td>

                                      <td><textarea rows="10" cols="50" name="custom_css">

                                      {{{ $profileData->custom_css or '' }}}

                                      </textarea></td>

                                    </tr>

                                

                                  -->

                                  

                                  <div class="form-group">

                                    <div class="control-label"><label></label></div>

                                    <div class="control-box">{!! Form::submit('Update',array('class'=>'btn btn-primary center-block', 'id'=>'videosend'))!!} </div>

                                  </div>
                                  
                                </div>

                                {!! Form::close() !!} </div>

                              </div>

                            </div>

                          </div>

                        </div>

                        @extends('admin.common.footer') </section>

                        

                        <script type="text/javascript">

                          $( document ).ready(function() {

                            $( ".dropdown.user-menu" ).click(function() {

                              $( '.dropdown.user-menu ul' ).toggle();

                            });

                          });

                        </script>

                        <script src="js/jscolor.js"></script>

                      </body>

                      </html>
