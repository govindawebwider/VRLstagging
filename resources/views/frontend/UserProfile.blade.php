<!DOCTYPE html>
<html lang="en">
<head>
  @include('frontend.common.head')
</head>
<?php
$dob=$user_dtl->DateOfBirth;
$dob=explode("-",$dob);

?>
<body class="cb-page user_profile notranslate">
  <div class="cb-pagewrap"> @include('frontend.common.userHeader')
    <section id="mian-content">
      <div class="container">
        <div class="cb-grid">
          <div class="cb-block cb-box-100 main-content user_data">
            <div class="cb-content">
              <div class="inner-block">
                <h1 class="heading"> <span class="txt1">Dashboard</span> <span class="txt2">Edit Profile </span> </h1>
                <div class="request_video_listing_wrap">
                  <div class="cb-block cb-box-100"> @if(Session::has('success'))
                    <div class="alert alert-success"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('success') }}</span> </div>
                    @endif
                    @if(Session::has('error'))
                    <div class="alert alert-danger"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('error') }}</span> </div>
                    @endif
                    <div class="cb-content video-wrap artist-video">
                      <?php //dd($user_dtl);?>
                      {!! Form::open(array('url' =>'profile','class'=>'form form-horizontal text-left','id'=>'upProfile','method'=>'post' ,'files'=>true)) !!}
                      <div class="inner-wrap"> </div>
                      <div class="form-group">
                        <div class="control-label">
                          <label for="username">User Name</label>
                        </div>
                        <div class="control-box">
                          <?php $user = \App\User::where('profile_id',$user_dtl->ProfileId)->first(); ?>
                          <input type="text" name="username" id="username" value="{{ $user_dtl->Name}}" class='form-control' >
                          @if($errors->first('username'))
                          <p class="label label-danger" > {{ $errors->first('username') }} </p>
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
                            <input type="text" name="email" id="email" value="{{$user_dtl->EmailId}}" class='form-control' disabled="true">
                            <a class="popup_btn" data-toggle="modal" data-target="#myModal">Send request for update email</a>
                            @if($errors->first('email'))
                            <p class="label label-danger" > {{ $errors->first('email') }} </p>
                            @endif </div>
                          </div>
                          <div class="form-group">
                            <label for="dob">Date Of Birth</label>
                            <div class="artist_register_date_section">
                              <div class="artist_select-date mobile-date">
                                <input type="text" name="dob" id="dob" value="{{$user_dtl->DateOfBirth}}" class='form-control' disabled="true">
                              </div>

                              <div class="artist_select-date">
                                <input type="hidden" id="date_ofbirth" >
                              </div>
                              <input type="hidden" name="dob" id="dob" value="{{$user_dtl->DateOfBirth}}" class='form-control' >
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="control-label">
                              <label for="phone">Phone No.</label>
                            </div>
                            <div class="control-box">
                              <input type="text" name="phone" id="phone" value="{{$user_dtl->MobileNo}}" class='form-control' >
                              @if($errors->first('phone'))
                              <p class="label label-danger" > {{ $errors->first('phone') }} </p>
                              @endif </div>
                            </div>
                            <div class="form-group">
                              <div class="control-label">
                                <label for="nickName">Real Name:</label>
                              </div>
                              <div class="control-box">
                                <input type="text" name="nickName" id="nickName" value="{{$user_dtl->Name}}" class='form-control' >
                                @if($errors->first('nickName'))
                                <p class="label label-danger" > {{ $errors->first('nickName') }} </p>
                                @endif </div>
                              </div>
                              <div class="form-group">
                                <div class="control-label">
                                  <label for="address">Address</label>
                                </div>
                                <div class="control-box">
                                  <input type="text" name="address" id="address" value="{{$user_dtl->Address}}" class='form-control' >
                                  @if($errors->first('address'))
                                  <p class="label label-danger" > {{ $errors->first('address') }} </p>
                                  @endif </div>
                                </div>
                                <div class="form-group">
                                  <div class="control-label">
                                    <label for="city">City:</label>
                                  </div>
                                  <div class="control-box">
                                    <input type="text" name="city" id="city" value="{{$user_dtl->City}}" class='form-control' >
                                    @if($errors->first('city'))
                                    <p class="label label-danger" > {{ $errors->first('city') }} </p>
                                    @endif </div>
                                  </div>
                                  <div class="form-group">
                                    <div class="control-label">
                                      <label for="state">State:</label>
                                    </div>
                                    <div class="control-box">
                                      <input type="text" name="state" id="state" value="{{$user_dtl->State}}" class='form-control' >
                                      @if($errors->first('state'))
                                      <p class="label label-danger" > {{ $errors->first('state') }} </p>
                                      @endif </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="control-label">
                                        <label for="country">Country</label>
                                      </div>
                                      <div class="control-box">
                                        <input type="text" name="country" id="country" value="{{$user_dtl->country}}" class='form-control' >
                                        @if($errors->first('country'))
                                        <p class="label label-danger" > {{ $errors->first('country') }} </p>
                                        @endif </div>
                                      </div>
                                      <div class="form-group">
                                        <div class="control-label">
                                          <label for="zip">Zip</label>
                                        </div>
                                        <div class="control-box">
                                          <input type="text" name="zip" id="zip" value="{{$user_dtl->Zip}}" class='form-control' >
                                          @if($errors->first('zip'))
                                          <p class="label label-danger" > {{ $errors->first('zip') }} </p>
                                          @endif </div>
                                        </div>
                                        <input type="hidden" name="ProfileId" value="{{$user_dtl->ProfileId}}">
                                        <div class="cb-box-100" style=" text-align: center;">
                                          <div class="control-box"> {!! Form::submit('Update',array('class'=>'btn btn-primary center-block profile-update-btn'))!!} </div>
                                        </div>
                                        <!-- <button type="button" class="btn btn-primary " data-dismiss="modal" class='btn btn-primary center-block'>Close</button> --> 
                                        {!! Form::close() !!} </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </section>
                        <section id="grid-botttom">
                          <div class="container">
                            <div class="cb-grid"> </div>
                          </div>
                        </section>
                        <!-- Modal -->
                        <div id="myModal" class="modal fade" role="dialog">
                          <div class="modal-dialog">

                            <!-- Modal content-->
                            <form class="modal-content" id="emailupdate" action="{{URL('/emailupdate')}}">
                              {{csrf_field()}}
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <!--  <h4 class="modal-title">Form for Email Update</h4> -->
                                <h4 class="modal-title">Update your Email Address</h4> 
                                
                              </div>
                              <div class="modal-body">
                                <div class="form-group">
                                  <div id="error_all" style="color:red;display: none;margin-left:26px; font-size:13px;">Please Fill All Fields.</div>
                                  <div class="control-label new_label"><label>New Email Address</label></div>
                                  <div class="control-box new_box">
                                    <input type="email" name="reqEmail"  class='form-control' id="updateemail" placeholder="Enter New Email Address">
                                  </div>
                                  <div id="error_email" style="color:red;display: none;margin-left:26px; font-size:13px;">New email address must not be same as old email address</div>
                                  <div class="control-label new_label"><label>Confirm New Email Address</label></div>
                                  <div class="control-box new_box">
                                    <input type="email" name="ConfirmNewEmail"  class='form-control' id="ConfirmNewEmail" placeholder="Enter Confirm New Email Address">
                                  </div>
                                  <div id="error_ConfEmail" style="color:red;display: none;margin-left:26px; font-size:13px;">New email address must be same as Confirm email address</div>

                                  <div class="control-label new_label"><label>Enter Password for Verification</label></div>
                                  <div class="control-box new_box">
                                    <input type="password" name="password"  class='form-control'
                                           id="verifyPassword" placeholder="Enter password">
                                  </div>

                                </div>    
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <input  type="submit" name="submit" class="btn btn-success"  value="Send Request">
                              </div>

                            </form>

                          </div>
                        </div>

                        @include('frontend.common.footer') </div>
                        <script>
                         $(document).ready(function() {

                           $('#emailupdate').submit(function(){

                            var email = document.getElementById('email').value;
                            var updatedemail = document.getElementById('updateemail').value;
                            var ConfirmNewEmail = document.getElementById('ConfirmNewEmail').value;
                            var password = document.getElementById('verifyPassword').value;
                            if(updatedemail == "" || password == ""){
                              $('#error_email').hide();
                              $('#error_ConfEmail').hide();
                              $('#error_all').show();
                              //alert('Please Fill All Fields.');
                            }else if(email == updatedemail){
                              $('#error_ConfEmail').hide();
                              $('#error_all').hide();
                              $('#error_email').show();
                              //alert('New email not same as old email.');
                            }else if(ConfirmNewEmail != updatedemail){
                              $('#error_all').hide();
                              $('#error_email').hide();
                              $('#error_ConfEmail').show();
                            }else{
                              var url = $('#emailupdate').attr('action');
                              var data = $('#emailupdate').serialize();
                              $.ajax({
                                url: url,
                                type: 'POST',
                                data : data,
                                dataType:'JSON',
                              })
                              .done(function(responce) {
                               if(responce.error == "error"){
                                alert(responce.msg);
                              }else if(responce.success == "success"){
                                $('#myModal').modal('hide');
                                alert(responce.msg);
                              }else{
                                console.log(responce);
                              }
                            })
                              .fail(function(error) {
                                console.log(error.responseText);
                              })

                              return false;

                            }

                            return false;

                          });






                           $("#month").click(function(){
                            var month=$('#month').val();
                            var year=$('#year').val();
                            $.ajax({    
                              type: "GET",
                              url: "https://www.videorequestline.com/date_calculation/"+year+"/"+month,             
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
                           $('#completed').on('click',function () {
                             $('.complete_block').show();
                             $('.approve_block').hide();
                             $('.pending_block').hide();
                             $('.reject_block').hide();
                           })
                           $('#approved').on('click',function () {
                             $('.approve_block').show();
                             $('.complete_block').hide();
                             $('.pending_block').hide();
                             $('.reject_block').hide();
                           })
                           $('#pending').on('click',function () {
                             $('.pending_block').show();
                             $('.complete_block').hide();
                             $('.approve_block').hide();
                             $('.reject_block').hide();
                           })
                           $('#reject').on('click',function () {
                             $('.reject_block').show();
                             $('.complete_block').hide();
                             $('.approve_block').hide();
                             $('.pending_block').hide();
                           })
                           $('#all').on('click',function () {
                            $('.reject_block').show();
                            $('.complete_block').show();
                            $('.approve_block').show();
                            $('.pending_block').show();
                          })
                         });
                       </script>
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
							 
								$(".month option[value='<?php echo $dob[0];?>']").attr('selected','selected').change(); 
								$(".day option[value='<?php echo $dob[1];?>']").attr('selected','selected').change(); 
								$(".year option[value='<?php echo $dob[2];?>']").attr('selected','selected').change(); 
							});
                      </script>
                    </body>
                    </html>