@include('admin.common.header')
<?php
$dob=$userData->DateOfBirth;
$dob=explode("-",$dob);

?>
<body class="admin site update_profile edit_artist-page">

  <section class="main-page-wrapper">

    <!-- main content start-->

    <div class="main-content">

      <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>
      @include('admin.layouts.header')

      <!-- //header-ends -->

      <div  class="col-md-12 profile-update-wrap">

        <div id="page-wrapper"> 

          <div class="graphs">

           <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="#">Profile Image</a> </div> 

             @if(Session::has('success'))

             <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

             @endif

             @if(Session::has('error'))

             <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

             @endif


             <h1 class="heading">{{\Lang::get('views.update_profile')}}

              <span>

                <a style="float:right" href="{{URL(url()->previous())}}">  

                  <input class="btn btn-primary" type="button" name="artist_csv" value="Back"></a> 

                </span>

              </h1>
              <p class="desc-title"></p>

              {!! Form::open(array('url' =>'edit_profile','class'=>'form form-horizontal text-left','id'=>'upProfile','method'=>'post' ,'files'=>true)) !!}

              <div class="inner-wrap">

                <div class="form-group">

                  <div class="control-label">

                    <label for="profile">Profile Image :</label>

                  </div>

                  <div class="control-box">

                    <input type="file" name="profile" id="profile" class='form-control upload-img'>
                    <?php $fileName = 'images/Artist/'.$userData->profile_path;
                    $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')->url($fileName);?>
                    <img src="{{$imageUrl}}" alt="" class="image-circle">
                    <span class="msg">Image must be 400 X 400 px and  jpeg, png format.</span> 

                    @if($errors->first('profile'))

                    <p class="label label-danger" > Profile Image must be 400 X 400 px and  jpeg, png format. </p>

                    @endif </div>
                    <img id="previewId" src="#" alt="preview image" style="width:400px;height:400px;display:none;margin-left:26%;" />
                  </div>

                  <div class="form-group">

                    <div class="control-label">

                      <label for="username">Artist Name</label>

                    </div>

                    <div class="control-box">

                      <input type="text" name="username" id="username" value="{{$userData->Name}}" class='form-control' >

                      @if($errors->first('username'))

                      <p class="label label-danger" > {{ $errors->first('username') }} </p>

                      @endif </div>

                    </div>

                    <div class="form-group">

                      <div class="control-label">

                        <label for="description">Description</label>

                      </div>

                      <div class="control-box">

                        <input type="text" name="description" id="description" value="{{$userData->profile_description}}" class='form-control' >

                        @if($errors->first('description'))

                        <p class="label label-danger" > {{ $errors->first('description') }} </p>

                        @endif 

                      </div>

                    </div>

                    <!-- edit_category added by sandeep -->
                    <div class="form-group">
                      <div class="control-label">
                        <label for="category">Category</label>
                      </div>

                      <div class="control-box">
                       
                        <select multiple="multiple" name="category_id[]">
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
                        <label for="category">Main Category</label>
                      </div>
                      <div class="control-box">
                        <select name="main_category_id" >
                          <?php 
                          foreach ($catData as $catDataValue) {
                            $selected = $main_category_id==$catDataValue->id ? 'selected' : '' ;
                          ?>
                            <option value="{{$catDataValue->id}}" {{$selected}}>{{$catDataValue->title}}</option>
                          
                          <?php } ?>
                        </select>
                        @if($errors->first('description'))
                        <p class="label label-danger" > {{ $errors->first('description') }} </p>
                        @endif 
                      </div>
                    </div>


                    <style type="text/css">
                      .checkbox{
                        margin-left: 15px;
                      }
                    </style>

                    <div class="form-group">

                      <div class="control-label">

                        <label for="artistEmail">Email</label>

                      </div>

                      <div class="control-box">

                        <input type="text" readonly="" name="artistEmail" id="artistEmail" value="{{$userData->EmailId}}" class='form-control'>

                        @if($errors->first('artistEmail'))

                        <p class="label label-danger" > {{ $errors->first('artistEmail') }} </p>

                        @endif </div>

                      </div>

                      <div class="form-group">

                       <div class="control-label">

                        <label for="dob">Date Of Birth</label></div>

                        

                        <div class="control-box">



                          <div class="artist_register_date_section">

                            <div class="artist_select-date mobile-date">

                              <input type="text" name="dateofbirth" class="artist-date-so" value="{{$userData->DateOfBirth}}" disabled>

                            </div>

                            <div class="artist_select-date">

                              <input type="hidden" id="profile_date_ofbirth" name="profile_date_ofbirth" >

                            </div>

                            <input type="hidden" name="profile_date_ofbirth1" id="profile_date_ofbirth1" value="{{$userData->DateOfBirth}}" class='form-control' >

                          </div>

                        </div>@if($errors->first('date_ofbirth'))

                        <p class="label label-danger" > {{ $errors->first('date_ofbirth') }} </p>

                        @endif 

                      </div>

                      

                      

                      <div class="form-group">

                        <div class="control-label">

                          <label for="phone">Phone No.</label>

                        </div>

                        <div class="control-box">

                          <input type="text" name="phone" id="phone" value="{{$userData->MobileNo}}" class='form-control' placeholder="111-111-1111" >

                          @if($errors->first('phone'))

                          <p class="label label-danger" > {{ $errors->first('phone') }} </p>

                          @endif </div>

                        </div>

                        <div class="form-group">

                          <div class="control-label">

                            <label for="nickName">Real Name</label>

                          </div>

                          <div class="control-box">

                            <input type="text" name="nickName" id="nickName" value="{{$userData->NickName}}" class='form-control' >

                            @if($errors->first('nickName'))

                            <p class="label label-danger" > {{ $errors->first('nickName') }} </p>

                            @endif </div>

                          </div>

                          <div class="form-group">

                            <div class="control-label">

                              <label for="address">Address</label>

                            </div>

                            <div class="control-box">

                              <input type="text" name="address" id="address" value="{{$userData->Address}}" class='form-control' >

                              @if($errors->first('address'))

                              <p class="label label-danger" > {{ $errors->first('address') }} </p>

                              @endif </div>

                            </div>

                            <div class="form-group">

                              <div class="control-label">

                                <label for="city">City</label>

                              </div>

                              <div class="control-box">

                                <input type="text" name="city" id="city" value="{{$userData->City}}" class='form-control' >

                                @if($errors->first('city'))

                                <p class="label label-danger" > {{ $errors->first('city') }} </p>

                                @endif </div>

                              </div>

                              <div class="form-group">

                                <div class="control-label">

                                  <label for="state">State</label>

                                </div>

                                <div class="control-box">

                                  <input type="text" name="state" id="state" value="{{$userData->State}}" class='form-control' >

                                  @if($errors->first('state'))

                                  <p class="label label-danger" > {{ $errors->first('state') }} </p>

                                  @endif </div>

                                </div>

                                <div class="form-group">

                                  <div class="control-label">

                                    <label for="country">Country</label>

                                  </div>

                                  <div class="control-box">

                                    <input type="text" name="country" id="country" value="{{$userData->country}}" class='form-control' >

                                    @if($errors->first('country'))

                                    <p class="label label-danger" > {{ $errors->first('country') }} </p>

                                    @endif </div>

                                  </div>

                                  <div class="form-group">

                                    <div class="control-label">

                                      <label for="zip">Zip</label>

                                    </div>

                                    <div class="control-box">

                                      <input type="text" name="zip" id="zip" value="{{$userData->Zip}}" class='form-control' >

                                      @if($errors->first('zip'))

                                      <p class="label label-danger" > {{ $errors->first('zip') }} </p>

                                      @endif </div>

                                    </div>

                                    

                                    <div class="form-group">

                                      <div class="control-label">{!! Form::label('gender', ' Choose Your Gender')!!}</div>

                                      

                                      <div class="control-box">

                                        <select name="gender" id="gender" class="">

                                          <option value="">Please Select you Gender</option>

                                          <option value="male" id="male" <?php if($userData->Gender=='male' or $userData->Gender=='Male' ) echo "Selected";?>>Male</option>

                                          <option value="female" id="female" <?php if($userData->Gender=='female' or $userData->Gender=='Female') echo "Selected";?>>Female</option>

                                        </select>

                                        

                                        @if($errors->first('gender'))

                                        <p class="label label-danger" > {{ $errors->first('gender') }}</p>

                                        @endif

                                      </div>

                                    </div>

                                    <div class="form-group">

                                      <div class="control-label">

                                        <label for="password">Password</label>

                                      </div>

                                      <div class="control-box">

                                        <input type="Password" name="password" id="password" value="" class='form-control' >

                                        @if($errors->first('password'))

                                        <p class="label label-danger" > {{ $errors->first('password') }} </p>

                                        @endif </div>

                                      </div>

                                      

                                      

                                      <div class="form-group">

                                        <div class="control-label">

                                          <label for="password">Confirm Password</label>

                                        </div>

                                        <div class="control-box">

                                          <input type="Password" name="cpassword" id="cpassword" value="" class='form-control' >

                                          @if($errors->first('cpassword'))

                                          <p class="label label-danger" > {{ $errors->first('cpassword') }} </p>

                                          @endif </div>

                                        </div>
                                        
                                        
                                        <div class="form-group">
                                          <div class="control-label">

                                           <label> </label>

                                         </div>
                                         
                                         <div class="control-box"> {!! Form::submit('Update',array('class'=>'btn btn-primary center-block'))!!} </div>
                                       </div>

                                       
                                       

                                       <div class="clear"></div>

                                     </div>

                                     

                                     <input type="hidden" name="ProfileId" value="{{$userData->ProfileId}}">

                                     


                                     <!-- <button type="button" class="btn btn-primary " data-dismiss="modal" class='btn btn-primary center-block'>Close</button> --> 



                                     {!! Form::close() !!} </div>

                                   </div>

                                 </div>

                                 @include('admin.common.footer')

                               </section>

                              <script>
                                    
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
