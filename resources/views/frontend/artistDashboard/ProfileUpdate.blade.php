@include('admin.common.header')
<?php
$dob=$profileData->DateOfBirth;
$dob=explode("-",$dob);

?>

<body class="admin profile_update">



    <section id="app" class="main-page-wrapper">



    <div class="main-content">



      <div id="left-side-wrap"> 



        @include('frontend.artistDashboard.layouts.lsidebar') </div>



        <div class="header-section">

            <div class="top-main-left">
                <a href="{{URL('Dashboard')}}"><span class="logo1 white"><img src="/images/vrl_logo_nav.png" class="img img-responsive"></span> </a>
                <a href="javascript:void(0)" class="toggle menu-toggle"><i class="lnr lnr-menu"></i></a>
            </div>
          <div class="menu-right">



            <div class="user-panel-top">



              <div class="profile_details">



                <div class="profile_img">



                  <div class="dropdown user-menu">
                    <span class="dropdown-toggle">
                        <span class="admin-img">
                            <?php
                            $fileName = 'images/Artist/'.$profileData->profile_path;
                            $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                        ->url($fileName);?>
                            <img src="{{$imageUrl}}" alt="">
                        </span>
                        <span class="admin-name">{{$profileData->Name}}</span>
                        <i class="arrow"></i>
                    </span>
                    <ul>



                    <?php //dd($profileData);?>
                    @if(session('current_type') == 'Artist')
                    <li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>Switch to Admin</span></a></li>
                    @endif


                    <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{ URL($profileData->profile_url)}}"> <i class="icon icon-users"></i> <span>view Profile</span> </a> </li>
                    @if($userData->admin_link=='yes')

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

      <div class="profile_update_wrap">
        <div  class="col-md-12 ">
          <div id="page-wrapper"> 
            <div class="graphs">
              <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="#">Update Profile</a> </div>
              @if(Session::has('success'))
              <div class="alert alert-success"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('success') }}</span> </div>
              @endif
              @if(Session::has('error'))
              <div class="alert alert-danger"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center">
              {{Session::get('error') }}</span> </div>
              @endif
              <h1 class="heading">Update Profile</h1>
             <!--  <h1 class="heading-profile">Settings</h1>
              <p class="head-Text">Some Page description subtitle</p> 
              <div class="user-profile" id="setting">
                <div class="row" id="user-setting">
                  <div class="col-sm-4">
                    <div class="row">
                      <div class="col-sm-4 active" id="user">
                        <span class="profile-text">My Profile </span>
                      </div>
                      <div class="col-sm-6" id="password">
                        <span class="profile-text-1">Change Password</span>
                      </div>
                    </div>
                  </div>
                </div> -->
                
               

                
               {!! Form::open(array('url' =>'ProfileUpdate','class'=>'form form-horizontal text-left','id'=>'upProfile','method'=>'post' ,'files'=>true)) !!}
              <div class="inner-wrapper">
                <div class="change-password">
                  <div class="col-md-12 ">
                      <div class="form-group">
                        <div class="control-label">
                          <label for="profile">Profile Image </label>
                        </div>
                        <div class="control-box" >
                          <input type="file" name="profile" id="profile" class='form-control upload-img-1' style="width: 91%;">
                            <?php
                             $fileName = 'images/Artist/'.$profileData->profile_path;
                            $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                ->url($fileName);?>
                          <img src="{{$imageUrl}}" alt="" class="image-circle">
						   <span class="msg"> Profile Image must be 400 X 400 px and  jpeg, png format.</span>
                            @if($errors->first('profile'))
                            <p class="label label-danger" > 
							
                            Profile Image must be 400 X 400 px and  jpeg, png format. 
							</p>
                          @endif
                         
                        </div>
                        <img id="previewId" src="#" alt="preview image" style="width:400px;height:400px;display:none;margin-left:26%;" />
                      </div>
                      <div class="form-group">
                        <div class="control-label">
                          <label for="username">Artist Name</label>
                        </div>
                        <div class="control-box">
                          <input type="text" name="username" id="username" value="{{$profileData->Name}}" class='form-control' >
                          @if($errors->first('username'))
                          <p class="label label-danger" > {{ $errors->first('username') }} </p>
                          @endif
                        </div>
                      </div>
                      <div class="form-group">
                          <div class="control-label">
                            <label for="nickName">FULL Name </label>
                          </div>
                          <div class="control-box">
                            <input type="text" name="nickName" id="nickName" value="{{$profileData->NickName}}" class='form-control' >
                            @if($errors->first('nickName'))
                              <p class="label label-danger" > {{ $errors->first('nickName') }} </p>
                            @endif 
                          </div>
                      </div>
                      <div class="form-group">
                        <div class="control-label">
                          <label for="description">Description</label>
                        </div>
                        <div class="control-box">
                          <input type="text" name="description" id="description" value="{{$profileData->profile_description}}" class='form-control' >
                          @if($errors->first('description'))
                            <p class="label label-danger" > {{ $errors->first('description') }} </p>
                          @endif </div>
                      </div>
                      <div class="form-group">
                        <div class="control-label">
                          <label for="dob">Date Of Birth</label></div>
                        <div class="control-box">
                          <div class="artist_register_date_section">
                            <div class="artist_select-date mobile-date">
                              <input type="text" name="dateofbirth" class="artist-date-so" value="{{$profileData->DateOfBirth}}" disabled>
                            </div>
                            <div class="artist_select-date">
                              <input type="hidden" id="profile_date_ofbirth" name="profile_date_ofbirth" >
                            </div>
                            <input type="hidden" name="profile_date_ofbirth1" id="profile_date_ofbirth1" value="{{$profileData->DateOfBirth}}" class='form-control' >
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                          <div class="control-label">
                            <label for="dob">CATEGORY</label>
                          </div>
                          <div class="control-box">
                             <select name="category_id[]" multiple="multiple">
                              <?php 
                              foreach ($catData as $catDataValue) {
                                $selected = in_array($catDataValue->id, $artistCategory) ? 'selected' : '' ;
                              ?>
                                <option value="{{$catDataValue->id}}" {{$selected}}>{{$catDataValue->title}}</option>
                              
                              <?php } ?>
                            </select>
                              @if($errors->first('category_id'))
                                  <p class="label label-danger" > {{ $errors->first('category_id') }} </p>
                              @endif
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="control-label">
                            <label for="dob">MAIN CATEGORY</label>
                          </div>
                          <div class="control-box">
                             <select name="main_category_id">
                              <?php 
                              foreach ($catData as $catDataValue) {
                                $selected = $main_category_id==$catDataValue->id ? 'selected' : '' ;
                              ?>
                                <option value="{{$catDataValue->id}}" {{$selected}}>{{$catDataValue->title}}</option>
                              
                              <?php } ?>
                            </select>
                              @if($errors->first('main_category_id'))
                                  <p class="label label-danger" > {{ $errors->first('main_category_id') }} </p>
                              @endif
                          </div>
                      </div>

                      <div class="form-group">
                          <div class="control-label">
                            <label for="email">EMAIL</label>
                          </div>
                          <div class="control-box">
                           <?php
                           header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
                           header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                           header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
                           header("Cache-Control: post-check=0, pre-check=0", false);
                           header("Pragma: no-cache");

                           header ("Expires: ".gmdate("D, d M Y H:i:s", time())." GMT");  
                           header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  
                           header ("Cache-Control: no-cache, must-revalidate");  
                           header ("Pragma: no-cache");
                           ?>
                             <input type="text" name="email" readonly id="email" value="{{$profileData->EmailId}}" class='form-control'>

                             <a class="popup_btn" data-toggle="modal" data-target="#myModal"> Update Current Email</a>

                             @if($errors->first('email'))
                               <p class="label label-danger" > {{ $errors->first('email') }} </p>
                             @endif
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="control-label">
                            <label for="phone">PHONE</label>
                          </div>
                          <div class="control-box">
                            <input type="text" name="phone" id="phone" value="{{$profileData->MobileNo}}" class='form-control' >
                            @if($errors->first('phone'))
                              <p class="label label-danger" > {{ $errors->first('phone') }} </p>
                            @endif 
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="control-label">
                            <label for="address">ADDRESS</label>
                          </div>
                          <div class="control-box">
                            <input type="text" name="address" id="address" value="{{$profileData->Address}}" class='form-control' >
                            @if($errors->first('address'))
                              <p class="label label-danger" > {{ $errors->first('address') }} </p>
                            @endif 
                          </div>
                      </div>
                      <div class="form-group">
                            <div class="control-label">
                              <label for="city" id="city">CITY</label>

                            </div>
                            <div class="control-box">
                              <input type="text" name="city" id="city-1"  value="{{$profileData->City}}" class='form-control' >
                              @if($errors->first('city'))
                              <p class="label label-danger" > {{ $errors->first('city') }} </p>
                              @endif 
                            </div>
                      </div>

                      <div class="form-group">
                          <div class="control-label">
                            <label for="state">STATE</label>
                          </div>
                          <div class="control-box">
                            <input type="text" name="state" id="state" value="{{$profileData->State}}" class='form-control' >
                            @if($errors->first('state'))
                              <p class="label label-danger" > {{ $errors->first('state') }} </p>
                            @endif 
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="control-label">
                            <label for="country">COUNTRY</label>
                          </div>
                          <div class="control-box">
                            <input type="text" name="country" id="country" value="{{$profileData->country}}" class='form-control' >
                            @if($errors->first('country'))
                              <p class="label label-danger" > {{ $errors->first('country') }} </p>
                            @endif 
                          </div>
                      </div>
                    <div class="form-group">
                          <div class="control-label">
                            <label for="city" id="zip" >ZIP</label>
                          </div>

                          <div class="control-box">
                            <input type="text" name="zip" id="zip-1" value="{{$profileData->Zip}}" class='form-control' >
                            @if($errors->first('zip'))
                              <p class="label label-danger" > {{ $errors->first('zip') }} </p>
                            @endif
                          </div>
                    </div>
                    <div class="form-group">

                                      <div class="control-label">{!! Form::label('gender', ' Choose Your Gender')!!}</div>

                                      

                                      <div class="control-box">

                                        <select name="gender" id="gender" class="">

                                          <option value="">Please Select you Gender</option>

                                          <option value="male" id="male" <?php if(trim($profileData->Gender)=='male' or trim($profileData->Gender)=='Male' ) echo "Selected";?>>Male</option>

                                          <option value="female" id="female" <?php if($profileData->Gender=='female' or $profileData->Gender=='Female') echo "Selected";?>>Female</option>

                                        </select>

                                        

                                        @if($errors->first('gender'))

                                        <p class="label label-danger" > {{ $errors->first('gender') }}</p>

                                        @endif

                                      </div>

                                    </div>
                    <div class="form-group">
                      <div class="control-label">
                        <label for="SSN">SSN </label>
                      </div>
                      <div class="control-box">
                        <input type="text" name="ssn_number" id="SSN" value="{{$hashedSSN}}" class='form-control' >
                      </div>
                      <input type="hidden" name="ProfileId" value="{{$profileData->ProfileId}}">
                      <div class="form-group">
                        <div class="col-sm-3">
                          <div class="control-label">
                            <label></label>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="control-box">
                            {!! Form::submit('Update',array('class'=>'btn btn-primary update'))!!} 
                          </div>
                        </div>
                        </div>
                      {!! Form::close() !!}
                    </div>
                  </div>
                </div>
              </div>
            </div>
<!-- 
      <div class="profile_update_wrap">



        <div  class="col-md-12 ">



          <div id="page-wrapper"> 







            <div class="graphs">


             @if(Session::has('success'))



             <div class="alert alert-success"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('success') }}</span> </div>







             @endif



             @if(Session::has('error'))



             <div class="alert alert-danger"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center">



               {{Session::get('error') }}</span> </div>



               @endif



               <h1 class="heading">Update Profile</h1>
               <p class="desc-title">Artist Dashboard</p>



               {!! Form::open(array('url' =>'ProfileUpdate','class'=>'form form-horizontal text-left','id'=>'upProfile','method'=>'post' ,'files'=>true)) !!}



               <div class="inner-wrap">



                 <div class="form-group">



                  <div class="control-label">



                    <label for="profile">Profile Image </label>



                  </div>



                  <div class="control-box">



                    <input type="file" name="profile" id="profile" class='form-control' >



                    <img src="{{url('images/Artist/').'/'.$profileData->profile_path}}" alt=""> @if($errors->first('profile'))
                    


                    <p class="label label-danger" > {{ $errors->first('profile') }} </p>



                    @endif 

                    <span class="msg"> Profile Image must be 400 X 400 px and  jpeg, png format.</span>


                  </div>



                </div>



                <div class="form-group">



                  <div class="control-label">



                    <label for="username">Artist Name</label>



                  </div>



                  <div class="control-box">



                    <input type="text" name="username" id="username" value="{{$profileData->Name}}" class='form-control' >



                    @if($errors->first('username'))



                    <p class="label label-danger" > {{ $errors->first('username') }} </p>



                    @endif </div>



                  </div>



                  <div class="form-group">



                    <div class="control-label">



                      <label for="description">Description</label>



                    </div>



                    <div class="control-box">



                      <input type="text" name="description" id="description" value="{{$profileData->profile_description}}" class='form-control' >



                      @if($errors->first('description'))



                      <p class="label label-danger" > {{ $errors->first('description') }} </p>



                      @endif </div>



                    </div>



                    <div class="form-group">



                      <div class="control-label">



                        <label for="email">Email</label>



                      </div>



                      <div class="control-box">

                       <?php
                       header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
                       header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                       header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
                       header("Cache-Control: post-check=0, pre-check=0", false);
                       header("Pragma: no-cache");

                       header ("Expires: ".gmdate("D, d M Y H:i:s", time())." GMT");  
                       header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  
                       header ("Cache-Control: no-cache, must-revalidate");  
                       header ("Pragma: no-cache");
                       ?>


                       <input type="text" name="email" readonly id="email" value="{{$profileData->EmailId}}" class='form-control'>

                       <a class="popup_btn" data-toggle="modal" data-target="#myModal"> Update Current Email</a>

                       @if($errors->first('email'))



                       <p class="label label-danger" > {{ $errors->first('email') }} </p>



                       @endif </div>



                     </div>















                     <div class="form-group">

                      <div class="control-label">

                        <label for="dob">Date Of Birth</label></div>

                        <div class="control-box">

                          <div class="artist_register_date_section">

                            <div class="artist_select-date mobile-date">

                              <input type="text" name="dateofbirth" class="artist-date-so" value="{{$profileData->DateOfBirth}}" disabled>

                            </div>

                            <div class="artist_select-date">

                              <input type="hidden" id="profile_date_ofbirth" name="profile_date_ofbirth" >

                            </div>

                            <input type="hidden" name="profile_date_ofbirth1" id="profile_date_ofbirth1" value="{{$profileData->DateOfBirth}}" class='form-control' >

                          </div>

                        </div>

                      </div>









                      <div class="form-group">



                        <div class="control-label">



                          <label for="phone">Phone No.</label>



                        </div>



                        <div class="control-box">



                          <input type="text" name="phone" id="phone" value="{{$profileData->MobileNo}}" class='form-control' >



                          @if($errors->first('phone'))



                          <p class="label label-danger" > {{ $errors->first('phone') }} </p>



                          @endif </div>



                        </div>



                        <div class="form-group">



                          <div class="control-label">



                            <label for="nickName">Real Name:</label>



                          </div>



                          <div class="control-box">



                            <input type="text" name="nickName" id="nickName" value="{{$profileData->NickName}}" class='form-control' >



                            @if($errors->first('nickName'))



                            <p class="label label-danger" > {{ $errors->first('nickName') }} </p>



                            @endif </div>



                          </div>



                          <div class="form-group">



                            <div class="control-label">



                              <label for="address">Address</label>



                            </div>



                            <div class="control-box">



                              <input type="text" name="address" id="address" value="{{$profileData->Address}}" class='form-control' >



                              @if($errors->first('address'))



                              <p class="label label-danger" > {{ $errors->first('address') }} </p>



                              @endif </div>



                            </div>



                            <div class="form-group">



                              <div class="control-label">



                                <label for="city">City:</label>



                              </div>



                              <div class="control-box">



                                <input type="text" name="city" id="city" value="{{$profileData->City}}" class='form-control' >



                                @if($errors->first('city'))



                                <p class="label label-danger" > {{ $errors->first('city') }} </p>



                                @endif </div>



                              </div>



                              <div class="form-group">



                                <div class="control-label">



                                  <label for="state">State:</label>



                                </div>



                                <div class="control-box">



                                  <input type="text" name="state" id="state" value="{{$profileData->State}}" class='form-control' >



                                  @if($errors->first('state'))



                                  <p class="label label-danger" > {{ $errors->first('state') }} </p>



                                  @endif </div>



                                </div>



                                <div class="form-group">



                                  <div class="control-label">



                                    <label for="country">Country</label>



                                  </div>



                                  <div class="control-box">



                                    <input type="text" name="country" id="country" value="{{$profileData->country}}" class='form-control' >



                                    @if($errors->first('country'))



                                    <p class="label label-danger" > {{ $errors->first('country') }} </p>



                                    @endif </div>



                                  </div>



                                  <div class="form-group">



                                    <div class="control-label">



                                      <label for="zip">Zip</label>



                                    </div>



                                    <div class="control-box">



                                      <input type="text" name="zip" id="zip" value="{{$profileData->Zip}}" class='form-control' >



                                      @if($errors->first('zip'))



                                      <p class="label label-danger" > {{ $errors->first('zip') }} </p>



                                      @endif </div>



                                    </div>


                      <div class="form-group">



                                    <div class="control-label">



                                      <label for="SSN">SSN </label>



                                    </div>



                                    <div class="control-box">



                                      <input type="text" name="ssn_number" id="SSN" value="{{$hashedSSN}}" class='form-control' >


                                    </div>
 -->
                                <!-- Modal -->
                                <div id="myModal" class="modal fade" role="dialog">
                                  <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <form class="modal-content" onsubmit="return false;" >
                                      {{csrf_field()}}
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <!--  <h4 class="modal-title">Form for Email Update</h4> -->
                                        <h4 class="modal-title">Update your Email Address</h4> 
                                      </div>
                                      <div class="modal-body">
                                          <div class="errors" v-show="emailErrors.length > 0" style="
                                                    padding: 15px;
                                                    margin-bottom: 20px;
                                                    border: 1px solid transparent;
                                                    border-radius: 4px;
                                                    color: #a94442;
                                                    background-color: #f2dede;
                                                    border-color: #ebccd1;
                                                    ">
                                              <ul>
                                                  <li v-for="error in emailErrors">@{{error}}</li>
                                              </ul>
                                          </div>
                                          <div class="alert alert-success" v-show="emailValidation == true">
                                              @{{success}}
                                          </div>
                                          <div class="form-group">
                                          <div class="control-label new_label"><label>New Email Address</label></div>
                                          
                                          <div class="control-box new_box">
                                            <input type="email" name="newEmail"
                                                   class="form-control" 
                                                   placeholder="Enter New Email Address"
                                                   autocomplete="off"
                                                   v-model='newEmail.email'
                                                    @blur="checkEmail"
                                                    
                                                   >
                                          </div>
                                          
                                          </div>
                                          
                                        
                                          
                                           <div class="form-group">
                                          <div class="control-label new_label"><label>Current Password</label></div>
                                          
                                          <div class="control-box new_box">
                                              <input type="password"
                                                     name="password"
                                                     class="form-control" 
                                                     placeholder="Password" 
                                                     autocomplete="off"
                                                    v-model='newEmail.password'
                                                    @blur="checkPassword"
                                                     >
                                          </div>
                                          
                                          </div>
                                          
                                        

                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button 
                                               
                                                class="btn btn-success"
                                              
                                                @click="submitForm"
                                                >
                                            Update
                                        </button>
                                      </div>

                                    </form>

                                  </div>
                                </div>


                              </div>


        
        

                              @include('admin.common.footer')



                            </section>

                          
<!-- Mohamed Mamdouh 
-- Loading Vue JS 
-->

  

                            <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">



                            <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>



                            <script>



                              $( function() {

 
                                $( "#dob" ).datepicker(



                                {



                                  changeMonth: true,



                                  changeYear: true,



                                  minDate:'-30Y',



                                  maxDate:0



                                }



                                );



                              } );



                              $( document ).ready(function() {



                                $("#month").click(function(){



                                  var month=$('#month').val();



                                  var year=$('#year').val();



                                  $.ajax({    



                                    type: "GET",



                                    url: "/date_calculation/"+year+"/"+month,



                                    dataType: "html", 



                                    success: function(response){  



                                      da= parseInt(response);  



                                      if(response==""){







                                      }else{



                                        var count;



                                        $('#day').html('');



                                        for(count = 1; count <= da; count++){



                                          $('#day').append('<option>'+count+'</option>');



                                        }



                                      }



                                    }



                                  });



                                });



                              });



                            </script> 
                            <script type="text/javascript">
                             $( document ).ready(function() {

                              $( ".dropdown.user-menu" ).click(function() {

                                $( '.dropdown.user-menu ul' ).toggle();
                              });

                            });
                                                </script>
                      
                      <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.4.2/vue.min.js"></script>
                      <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.16.2/axios.min.js"></script>
                      <script>
                          var app = new Vue({
                              el:'#app',
                              data:{
                                  emailValidation:false,
                                  newEmail:{'email':'','password':''},
                                  emailErrors:[],
                                  password:'',
                                  errors:[
                                      'Invalid Email Address',
                                      'This email already exists ',
                                      'Invalid Password',
                                      'Incorrect Password',
                                      'Sorry something went wrong please try again later'
                                  ],
                                  success:'Almost Done , Please Check your email to activate new email'
                               
                              },
                              
                              methods:{
                                  checkEmail:function(){
                                      /*
                                       * Valid The Email
                                       * Check This Email exist in database or not  
                                       * return true to continue or no
                                       */
                                      var email  = this.newEmail.email;
                                        if (email.length == 0 || !/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email) || email.indexOf(" ") >= 0) {
                                              
                                              if(this.emailErrors.indexOf(this.errors[0]) == -1){
                                                   this.emailErrors.push(this.errors[0]);
                                              }
                                            }
                                            else{
                                                  if(this.emailErrors.indexOf(this.errors[0]) != -1){
                                                    this.emailErrors.splice(this.emailErrors.indexOf(this.errors[0]),1);
                                              }
                                                var valid = false;
                                                 axios.get('artist/checkEmailAddress/'+email)
                                                .then(function (response) {
                                                    if(response.data.status == false){
                                                       if(this.emailErrors.indexOf(this.errors[1]) == -1){
                                                            this.emailErrors.push(this.errors[1]);
                                                       }
                                                    }
                                                    else{
                                                       if(this.emailErrors.indexOf(this.errors[1]) != -1){
                                                    this.emailErrors.splice(this.emailErrors.indexOf(this.errors[1]),1);
                                              }
                                                    }
                                                }.bind(this))
                                                .catch(function (error) {
                                                  console.log(error);
                                                });         
                                                
                                            
                                            }
                                      
                                  },
                                  checkPassword:function(){
                                      var password  = this.newEmail.password;
                                        if (password.length == 0  || password.indexOf(" ") >= 0) {
                                               if(this.emailErrors.indexOf(this.errors[2]) == -1){
                                                    this.emailErrors.push(this.errors[2]);
                                              }
                                        }
                                        else{
                                            if(this.emailErrors.indexOf(this.errors[2]) != -1){
                                                    this.emailErrors.splice(this.emailErrors.indexOf(this.errors[2]),1);
                                              }
                                              
                                               axios.get('artist/checkPassword/'+password)
                                                .then(function (response) {
                                                    if(response.data.status == false){
                                                       if(this.emailErrors.indexOf(this.errors[3]) == -1){
                                                            this.emailErrors.push(this.errors[3]);
                                                       }
                                                    }
                                                    else{
                                                       if(this.emailErrors.indexOf(this.errors[3]) != -1){
                                                    this.emailErrors.splice(this.emailErrors.indexOf(this.errors[3]),1);
                                              }
                                                    }
                                                }.bind(this))
                                                .catch(function (error) {
                                                  console.log(error);
                                                }); 
                                              
                                          
                                        }
                                  },
                                  submitForm:function(){
                                    this.checkEmail();
                                    this.checkPassword();
                                    if(!this.emailErrors.length > 0){
                                         axios.post('/artist/new-email-address-request',this.newEmail)
                                        .then(function (response) {
                                         if(response.data.status == false){
                                               if(app.emailErrors.indexOf(app.errors[4]) == -1){
                                                            app.emailErrors.push(this.errors[4]);
                                                       }
                                               }
                                               else{
                                                   app.emailValidation = true;
                                                   setTimeout(function(){
                                                       app.emailValidation = false;
                                                       $("#myModal").modal('hide');
                                                   },3000)
                                               }
                                        })
                                        .catch(function (error) {
                                            console.log(error);
                                        });
                                    }
                                    else{
                                        alert("please fix the errors");
                                    }
                                  }
                               
                              }
                          });

                          $(document).ready(function(){
                            $("#change-password").hide();
                            $("#user-profile").show();
                            $("#password").click(function(){
                                $("#user-profile").hide();
                                $("#change-password").show();
                                $('#user').removeClass('active');
                                $(this).addClass('active');
                            });
                            $("#user").click(function(){
                                $("#user-profile").show();
                                $("#change-password").hide();
                                $('#password').removeClass('active');
                                $(this).addClass('active');
                            });
                          });

                          
                          function readURL(input) {
                              if (input.files && input.files[0]) {
                                  var reader = new FileReader();
                                  
                                  reader.onload = function (e) {
                                      $('#previewId').attr('src', e.target.result);
                                  }
                                  
                                  reader.readAsDataURL(input.files[0]);
                                  $('#previewId').show();
                              }
                          }
                          
                          $("#profile").change(function(){
                              readURL(this);
                          });
                          $( document ).ready(function() {
							 
								$(".month option[value='<?php echo $dob[0];?>']").attr('selected','selected').change(); 
								$(".day option[value='<?php echo $dob[1];?>']").attr('selected','selected').change(); 
								$(".year option[value='<?php echo $dob[2];?>']").attr('selected','selected').change(); 
							});
                      </script>

                        </body>



                        </html>
