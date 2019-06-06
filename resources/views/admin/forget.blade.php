<!DOCTYPE HTML>
<html>
<head>
<title></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Easy Admin Panel Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
{!! Html::style('css/style.css') !!}
{!! Html::style('css/custom.css') !!}
{!! Html::style('css/admin/style.css') !!}
</head>
<body class="forget-password">
    <div class="cb-pagewrap">
        <div class="forget-wrapper">
            <div class="graphs">
                <div class="forget-form">
                    <div class="forget-form-top">Forget Password</div>
                    <div class="forget"> @if(Session::has('login_error'))
                    <div class="alert alert-danger"> 
                    	{{Session::get('login_error') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
                    </div>
                    @endif 
                    <!--<h1 class="title">Forget Password</h1>--> 
                    @if(Session::has('forget_error'))
                    <div class="alert alert-danger"> {{Session::get('forget_error') }} </div>
                    @endif
                    {!! Form::open(array('method'=>'post','url' =>'forget_pass','id'=>'loginfrm')) !!}
                    <p class="txt">Please enter the email address for your account. A verification code will be sent to you. Once you have received the verification code, you will be able to choose a new password for your account.</p>
                    <div class="form-group"> <span class="label">Email id :<span class="require">*</span></span> {!! Form::email('email',null,array('id'=>'email','class'=>'form-control first','placeholder'=>'Email Address'))!!} </div>
                    
                    <div class="btn-wrap clearfix">
						<input type="submit" class="btn btn-default login" value="Submit">                        
					</div>
                    {!! Form::close() !!}
                                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>