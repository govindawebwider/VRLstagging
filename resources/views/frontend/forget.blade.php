<!DOCTYPE html>

<html lang="en">

<head>
<?php  $page ='Login'; ?>
  @include('frontend.common.head')


</head>

<body class="cb-page login"> 
  <div class="cb-pagewrap"> @include('frontend.common.header') 
    <section id="main-content"> 
      <div class="row purple thanksbox">
      </div>
      <div class="container"> 
        
        <div class="row"> 
          <div class="col-md-12 col-sm-12 col-lg-12 cb-block cb-box-40 main-content">
            <div class="LoginBox">
                <div class="panel panel-default cb-content cb-marginT55 cb-marginB50">

              <div class="login-inner-block">

                <h3 class="text-center yourAccount">
                  <span class=""> Forgot Password </span>
                </h3>

                <div class="login_body">

                  @if($errors->first('email'))                              
                  <div class="alert alert-danger">                   
                    <p class="label label-danger" > 
                      {{ ($errors->first('email')) }}
                    </p>  
                    <button type="button" class="close"  data-dismiss="alert" aria-label="close">
                      <span aria-hidden="true">&times;</span>
                    </button>  
                  </div>    
                  @endif              
                  @if(Session::has('forget_error'))       
                    <div class="alert alert-danger"> {{ (Session::get('forget_error')) }}
                    <button type="button" class="close"  data-dismiss="alert" aria-label="close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    </div>              
                  @endif  
                  {!! Form::open(array('method'=>'post','url' =>'forget_pass','id'=>'loginfrm')) !!}                
                  <label class="forgot">Please enter the email address for your account. A verification code will be sent to you. Once you have received the verification code, you will be able to choose a new password for your account.</label>                <div class="form-group">                  <span class="label">Email Address<span class="require">*</span></span>
                      {!! Form::email('email',null,array('id'=>'email','class'=>'form-control first','placeholder'=>('Email Address')))!!}                            </div>
                  <input type="submit" class="forget-submit-button" value="Submit">       {!! Form::close() !!}
 
  
      </div>

  </div>

</div>

</div>

</div>

</section>
@include('frontend.common.footer') </div>
</body>

</html>