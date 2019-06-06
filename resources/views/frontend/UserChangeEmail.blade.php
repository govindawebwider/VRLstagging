<!DOCTYPE html>
<html lang="en">
<head>
@include('frontend.common.head')
</head>
<body class="cb-page user_profile">
<div class="cb-pagewrap"> @include('frontend.common.header')
  <section id="mian-content">
    <div class="container">
      <div class="cb-grid">
        <div class="cb-block cb-box-100 main-content">
          <div class="cb-content">
            <div class="inner-block">
              <h1 class="heading"> <span class="txt1">Dashboard</span> <span class="txt2">Change Email</span> </h1>
              <div class="request_video_listing_wrap">
              <div class="cb-block cb-box-100">
@if(Session::has('success'))
                <div class="alert alert-success"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('success') }}</span> </div>
                
                @endif
                @if(Session::has('error'))
                <div class="alert alert-danger"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center">
                 {{Session::get('error') }}</span> </div>
                @endif
          <div class="cb-content video-wrap artist-video">
           <?php //dd($user_dtl);?>
            {!! Form::open(array('url' =>'profile','class'=>'form form-horizontal text-left','id'=>'upProfile','method'=>'post' ,'files'=>true)) !!}
                  <div class="inner-wrap">
                  </div>
                    <div class="form-group">
                      <div class="control-label">
                        <label for="username">User Name</label>
                      </div>
                      <div class="control-box">
                        <input type="text" name="username" id="username" value="" class='form-control' >
                        @if($errors->first('username'))
                        <p class="label label-danger" > {{ $errors->first('username') }} </p>
                        @endif </div>
                    </div>
                
                    <div class="form-group">
                      <div class="control-label">
                        <label for="email">Email</label>
                      </div>
                      <div class="control-box">
                        <input type="text" name="email" id="email" value="" class='form-control'>
                        @if($errors->first('email'))
                        <p class="label label-danger" > {{ $errors->first('email') }} </p>
                        @endif </div>
                    </div>
                    
                    
                    
                     
                 
                  <input type="hidden" name="ProfileId" value="{{$user_dtl->ProfileId}}">
                  <div class="cb-box-100" style=" text-align: center;">                      
                      <div class="control-box">
                        {!! Form::submit('Update',array('class'=>'btn btn-primary center-block'))!!} </div>
                    </div>
                   
                  
                  <!-- <button type="button" class="btn btn-primary " data-dismiss="modal" class='btn btn-primary center-block'>Close</button> --> 
                  
                  {!! Form::close() !!} 
          </div>
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
      <div class="cb-grid">
        
      </div>
    </div>
  </section>
  @include('frontend.common.footer') </div>
  	
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
  </script>
</body>
</html>