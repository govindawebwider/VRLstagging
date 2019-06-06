<!DOCTYPE HTML><html><head><title></title><meta name="viewport" content="width=device-width, initial-scale=1"><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />{!! Html::style('css/style.css') !!}{!! Html::style('css/custom.css') !!}
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="{{ asset('js/jstz.min.js') }}"></script>
</head><body class="amin_login">    <div class="cb-pagewrap">		<div class="sign-in-form">            <div class="form-topbar">                <!--<p><span>Sign In </span></p>-->                <img class="logo" src="/images/vrl_logo_login.png" alt="VRL logo" >            </div>            <div class="signin"> @if(Session::has('login_error'))                <div class="alert alert-danger"> {{Session::get('login_error') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </div>                @endif                <form action="{{URL('admin')}}" method="post">                    {!! csrf_field() !!}                    <div class="log-input">                        <div class="log-input-left">                            <input type="text" name="email" class="user" placeholder="Username"/>                            @if($errors->first('email'))                            <p class="label label-danger" > {{ $errors->first('email') }} </p>                            @endif </div>                        <div class="clearfix"> </div>                    </div>                    <div class="log-input">                        <div class="log-input-left">
<input type="password" name="password" class="lock" placeholder="Password" />
<input type="hidden" name="timezone" id="timezone">
@if($errors->first('password'))                            
<p class="label label-danger" > {{ $errors->first('password') }} </p>                            @endif                             
</div>                    
</div>                    
<div class="btn-wrap clearfix">                    
	<input type="submit" value="Sign in">                                            
</div>                    
<a class="signin-rit" href="/forget_pass">Forgot Password?</a>                
</form>                
<div> 						                
</div>            
</div>		
</div>        
<div class="form-footer">            
	<div class="cb-grid cb-box-100">                
		<div class="cb-block cb-box-50 main-banner">                	
			<div class="cb-content nopadding-right nomargin-right nopadding-bottom nomargin-bottom">                    	
				<a href="/">Go to site Home Page.</a>                    
			</div>				
		</div>               
		<div class="cb-block cb-box-50 main-banner">                	<div class="cb-content nopadding-bottom nomargin-bottom text-right">
			
			© {{ date('Y') }} Video Request Line All Rights Reserved                    
		</div>				
	</div>			
</div>        
</div>    </div>
<script>
	$(document).ready(function(){
		var tz = jstz.determine();
		$('#timezone').val(tz.name());
	});
</script></body></html>