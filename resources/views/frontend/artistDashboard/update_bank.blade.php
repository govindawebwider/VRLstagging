
@include('admin.common.header')


<body class="admin change_password">

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
                            <?php $imageUrl = '';
                            $fileName = 'images/Artist/'.$profileData->profile_path; ?>
                            @if (\Illuminate\Support\Facades\Storage::disk('s3')->exists($fileName))
                                <?php $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                        ->url($fileName);?>
                            @endif
                            <img src="{{$imageUrl}}" alt="" >
                            {{--<img src="{{url('images/Artist/').'/'.$profileData->profile_path}}" alt="">--}}
                        </span> <span class="admin-name">{{$profileData->Name}}</span> <i class="arrow"></i> </span>
                  <ul> @if(session('current_type') == 'Artist')
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

      <div class="change_password_wrap">

        <div  class="col-md-12 ">

          <div id="page-wrapper"> 

            <div class="graphs">
            <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/bank_details">Bank Detail</a> </div> 
              @if(Session::has('success'))

              <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

              @endif

              

              

              

              @if(Session::has('error'))

              <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

              @endif

              <h1 class="heading">Bank Detail</h1>
             

              {!! Form::open(array('url' =>'bank_details','class'=>'form form-horizontal text-left','method'=>'post','enctype'=>'multipart/form-data' )) !!}

              <div class="inner-wrapper">

                <div class="form-group">

                  <div class="control-label">

                    <label for="currency">Currency</label>

                  </div>

                  <div class="control-box">

                    <select name="currency" id="currency" class='form-control'>

                      <option value="USD" selected>USD</option>

                    </select>

                  </div>

                </div>

                <div class="form-group">

                  <div class="control-label">

                    <label for="country"> Bank Country</label>

                  </div>

                  <div class="control-box">

                    <select name="country" id="country" class='form-control'>

                      <option value="United_states" selected>United States</option>

                    </select>

                  </div>

                </div>

                <div class="form-group">

                  <div class="control-label">

                    <label for="routing_number">Routing Number</label>

                  </div>
                  <div class="control-box">
                    @if($profileData->routing_number =="" && $profileData->routing_number ==0)
                    <!-- <input type="text" name="routing_number" id="routing_number" class='form-control' value="{{$profileData->routing_number}}" > -->
                    <input type="Password" name="routing_number" id="routing_number" class='form-control' value="{{$profileData->routing_number ? $profileData->routing_number : Request::old('routing_number')}}" >
                    @else
                        <?php try {
                              $routingNumber = Crypt::decrypt($profileData->routing_number);
                          } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                              $routingNumber = "";
                          }?>
                    <input type="Password" name="routing_number" id="routing_number" class='form-control'
                           value="{{$routingNumber}}"
                            >

                    @endif
                    @if($errors->first('routing_number'))

                    <p class="label label-danger" > {{ $errors->first('routing_number') }} </p>

                    @endif</div>

                  </div>

                  <div class="form-group">

                    <div class="control-label">

                      <label for="account_number">Account Number</label>

                    </div>

                    <div class="control-box">
                     @if($profileData->account_number =="" && $profileData->account_number ==0)
                     <input type="Password" name="account_number" id="account_number" class='form-control' value="{{$profileData->account_number ? $profileData->account_number : Request::old('account_number')}}" >
                     @else
                            <?php try {
                                $accountNumber = Crypt::decrypt($profileData->account_number);
                            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                                $accountNumber = "";
                            }?>
                     <input type="Password" name="account_number" id="account_number" class='form-control'
                            value="{{$accountNumber}}" >
                     

                     @endif

                     @if($errors->first('account_number'))

                     <p class="label label-danger" > {{ $errors->first('account_number') }} </p>

                     @endif</div>

                   </div>

                   <div class="form-group">

                    <div class="control-label">

                      <label for="account_number"> Confirm Account Number</label>

                    </div>

                    <div class="control-box">

                      <input type="Password" name="confirm_account_number" id="confirm_account_number" class='form-control' >

                      @if($errors->first('confirm_account_number'))

                      <p class="label label-danger" > {{ $errors->first('confirm_account_number') }} </p>

                      @endif</div>

                    </div>

                    <div class="form-group">

                      <div class="control-label">


                       <label for="ssn_number"> Last 4 SSN</label>

                     </div>

                     <div class="control-box">
                       @if($profileData->ssn_number =="" && $profileData->ssn_number ==0)
                       <input type="Password" name="ssn_number" id="ssn_numbers" class='form-control'
                              value="{{$profileData->ssn_number ? $profileData->ssn_number : Request::old('ssn_number')}}">
                       @else
                             <?php try {
                                 $ssnNumber = Crypt::decrypt($profileData->ssn_number);
                             } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                                 $ssnNumber = "";
                             }?>
                       <input type="Password" name="ssn_number" id="ssn_numbers" class='form-control'
                              value="{{$ssnNumber}}">
                       

                       @endif
                       @if($errors->first('ssn_number'))

                       <p class="label label-danger" > {{ $errors->first('ssn_number') }} </p>

                       @endif</div>
                     </div>

                     <div class="form-group">

                      <div class="control-label">


                        <label for="pin"> Confirm Last 4 SSN</label>

                      </div>

                      <div class="control-box">
                        @if($profileData->pin =="" && $profileData->pin ==0)
                        <!-- <input type="text" name="pin" id="pin" class='form-control' value="{{$profileData->pin}}" > -->
                        
                        <input type="Password" name="pin" class='form-control' value="{{$profileData->pin ? $profileData->pin : Request::old('pin')}}" >
                        @else
                          <?php try {
                              $pin = Crypt::decrypt($profileData->pin);
                          } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                              $pin = "";
                          }?>
                        <input type="Password" name="pin" class='form-control' value="{{$pin}}" >

                        @endif
                        @if($errors->first('pin'))

                        <p class="label label-danger" > {{ $errors->first('pin') }} </p>

                        @endif</div>

                      </div>

                      <!-- <div class="form-group">
                      
                        <div class="control-label">
                      
                        
                          <label for="id_pic">Driver's License Image</label> 
                      
                        </div>
                      
                        <div class="control-box">
                      
                          <input type="file" name="id_pic" id="id_pic" class='form-control' >
                      
                          @if($errors->first('id_pic'))
                      
                          <p class="label label-danger" > {{ $errors->first('id_pic') }} </p>
                      
                          @endif</div>
                      
                        </div> -->
                        <div class="form-group">

                          <div class="control-label"><label></label></div>
                          <?php $artist = \App\Profile::find(\Auth::user()->profile_id); ?>
                          @if($artist->is_bank_updated=='0')
                          <div class="control-box"> 
                            {!! Form::submit('Save',array('class'=>'btn btn-primary center-block'))!!}
                          </div>
                          @elseif($artist->bank_id=="" AND $artist->is_bank_updated==1)
                          <div class="control-box"> 
                            {!! Form::submit('Update',array('class'=>'btn btn-primary center-block'))!!}
                          </div>
                          @endif 
                        </div>

                      </div>

                      





                      {!! Form::close() !!} </div>

                    </div>

                  </div>

                </div>

              </div>

              @include('admin.common.footer')

            </section>

          </body>

          </html>
