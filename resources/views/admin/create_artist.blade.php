@include('admin.common.header')

<body class="admin change_password create_artist">
 <section class="main-page-wrapper">
   <div class="main-content">
     <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>
     @include('admin.layouts.header')
     <div class="change_password_wrap">
       <div  class="col-md-12 ">
         <div id="page-wrapper">
           <div class="change_pass_form"> @if(Session::has('error'))
             <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>
             @endif
             <div class="graphs">
               <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="#">Create Artist</a> </div>
                 <h1 class="heading">Create Artist</h1>               <p class="desc-title"></p>               {!! Form::open(array('url' =>'create_artist','class'=>'form form-horizontal text-left','method'=>'post', )) !!}
               <div class="inner-wrapper"> @if(Session::has('register_error'))
                 <div class="alert alert-danger"> {{Session::get('login_error') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </div>
                 @endif
                 @if(Session::has('success'))
                 <div class="alert alert-success"> {{Session::get('success') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </div>
                 @endif
                 {!! Form::open(array('url' =>'create_artist','class'=>'form form-horizontal text-left','id'=>'regfrm','method'=>'post','files' => true)) !!}
                 <div class="form-group">
                   <div class="control-label"> {!! Form::label('username', 'Artist Name')!!} </div>
                   <div class="control-box"> {!! Form::text('username',null,array('class'=>'form-control','name'=>'username'))!!} 
				   @if($errors->first('username'))
                   <p class="label label-danger" style="margin-left:0px;"> {{ $errors->first('username') }} </p>
                   @endif</div>
                    </div>
                   <div class="form-group">
                     <div class="control-label"> {!! Form::label('email', 'Email')!!} </div>
                     <div class="control-box"> {!! Form::email('artistEmail',null,array('class'=>'form-control','id'=>'artistEmail'))!!} @if($errors->first('artistEmail'))
                     <p class="label label-danger" style="margin-left:0px;">{{ $errors->first('artistEmail') }} </p>
                     @endif</div>
                      </div>
                   <div class="form-group">
                       <div class="control-label"> {!! Form::label('date_of_birth', 'Date of Birth')!!} </div>
                       <div class="control-box"><input type="date" name="date_of_birth" value="{{old('date_of_birth')}}" class="form-control" id="dob" max="{{ date('Y-m-d') }}"> 
					   @if($errors->first('date_of_birth'))

                           <p class="label label-danger" style="margin-left:0px;">{{ $errors->first('date_of_birth') }} </p>
                       @endif
					   </div>
                      
                       
                   </div>

                     <!--  category selection created by sandeep --> 
                     <div class="form-group">
                        <div class="control-label"> {!! Form::label('category', 'Category')!!} </div>
                        <div class="control-box">                           
                        <?php
                        $testing=array();
                        if(old('category_id'))
                        {
                          $testing=old('category_id');
                        }


?>

                          <select name="category_id[]" multiple="multiple" id="category" size='5' onchange="mainCategoryOptions();">
                              <?php 
                              foreach ($catData as $catDataValue) {
                                $selected = in_array($catDataValue->id, $testing) ? 'selected' : '' ;
                              ?>
                                <option value="{{$catDataValue->id}}" {{$selected}}>{{$catDataValue->title}}</option>
                              
                              <?php } ?>
                            </select>  
@if($errors->first('category_id'))
                             <p class="label label-danger" style="margin-left:0px;"> {{ $errors->first('category_id') }} </p>
                         @endif							
                        </div>
                         
                        <style type="text/css">                        
                        .checkbox{                           
                        margin-left: 15px;                         
                        }                       
                        </style>                       
                        <!--  @if($errors->first('artistEmail'))
                        <p class="label label-danger" >{{ $errors->first('artistEmail') }} </p>
                        @endif  -->                    
                    </div>
                    <?php //echo '<pre>';print_r($catData);exit;?>
                    <div class="form-group">
                        <div class="control-label"> {!! Form::label('category', 'Main Category')!!} </div>
                        <div class="control-box">                           
                          <select name="main_category_id">
                            <?php for ($i=0; $i <count($catData) ; $i++) {  ?>        
                            <option value="<?php echo $catData[$i]->id; ?>" @if (old('main_category_id') == $catData[$i]->id) selected="selected" @endif><?php echo $catData[$i]->title; ?></option>                   
                            <?php } ?>                         
                          </select> 
@if($errors->first('main_category_id'))
                            <p class="label label-danger" style="margin-left:0px;"> {{ $errors->first('main_category_id') }} </p>
                        @endif						  
                        </div>
                        
                        <style type="text/css">                        
                        .checkbox{                           
                        margin-left: 15px;                         
                        }                       
                        </style>                       
                        <!--  @if($errors->first('artistEmail'))
                        <p class="label label-danger" >{{ $errors->first('artistEmail') }} </p>
                        @endif  -->                    
                    </div>

                     <div class="form-group">
                       <div class="control-label"> {!! Form::label('artistPassword', 'Password')!!} </div>
                       <div class="control-box"> {!! Form::password('artistPassword',array('class'=>'form-control'))!!} 
					   @if($errors->first('artistPassword'))
                       <p class="label label-danger" style="margin-left:0px;" >{{ $errors->first('artistPassword') }} </p>
                       @endif
					   </div>
                        </div>
                       <div class="form-group">
                         <div class="control-label"> {!! Form::label('confirmpassword', ' Confirm Password')!!} </div>
                         <div class="control-box"> {!! Form::password('confirmpassword',array('class'=>'form-control'))!!} 
                         @if($errors->first('confirmpassword'))
                         <p class="label label-danger" style="margin-left:0px;">Password and confirm password should be match! </p>
                         @endif 
						 </div>
						 </div>
                         <div class="form-group">
                           <div class="control-label"> {!! Form::label('email', 'Phone No')!!} </div>
                           <div class="control-box"> {!! Form::text('phone',null,array('class'=>'form-control','id'=>'phone'))!!} 
						   @if($errors->first('phone'))
                           <p class="label label-danger"  style="margin-left:0px;" > {{ $errors->first('phone') }} </p>
                           @endif 
						   </div>
                           </div>
                           <div class="form-group">
                             <div class="control-label"> {!! Form::label('gender', ' Gender')!!} </div>
                             
                             <div class="control-box"> 
                               
                               <select name="gender" id="gender" class="">
                                 <option value="">Please Select Gender</option>
                                 <option value="male" <?php if(old('gender') == 'male'){ echo "selected";}?>>Male</option>
                                 <option value="female" <?php if(old('gender') == 'female'){ echo "selected";}?> >Female</option>
                               </select>
							   @if($errors->first('gender'))
                             <p class="label label-danger" style="margin-left:0px;" > {{ $errors->first('gender') }} </p>
                             @endif 
                             </div>
                                                                                        
                                                          </div>

								<div class="form-group">
                               <div class="control-label"> <label></label> </div>
                               <div class="control-box"> {!! Form::submit('Submit',array('class'=>'btn btn-primary sbt-btn','id'=>'regBtn'))!!} </div>
                             </div>
                                                         {!! Form::close() !!} </div>
                           </div>
                         </div>
                       </div>
                     </div>
                     
                     @extends('admin.common.footer') 
                    
                     <script>
                       /*$(function () {
                         $( "#dob" ).datepicker();
                         $("#dob").datepicker({
                           changeMonth: true,
                           changeYear: true,
                           changeDate: true,
                           minDate:'-80Y',
                           yearRange: "-100:+0",
                           maxDate:new Date()
                         });
                         $("#request_datepicker").datepicker({
                           changeMonth: true,
                           changeYear: true,
                           minDate:0,
                           maxDate:'+30Y'
                         });
                       });*/
                       
                     </script>
                   </body>
                   </html>