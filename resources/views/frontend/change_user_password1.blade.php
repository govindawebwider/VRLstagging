<!DOCTYPE html>

<html lang="en">

<head>

  @include('frontend.common.head')

</head>

<body class="cb-page login">

  <div class="cb-pagewrap"> @include('frontend.common.header')

    <section id="mian-content">

      <div class="container">

        <div class="cb-grid">

          <div class="cb-block cb-box-40 main-content">

            <div class="cb-content cb-marginT55 cb-marginB50">

              <div class="login-inner-block">

                <h1 class="title log-in-txt">Log In</h1>

                <div class="login_body">

                 @if (count($errors) > 0)
                 <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
                @endif

                @if(Session::has('pass_success'))
                <div class="alert alert-primary"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('pass_success') }} </div>
                @endif
                @if(Session::has('error'))
                <div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>
                @endif

                {!! Form::open(array('method'=>'post','url' =>'user_change_password','id'=>'loginfrm')) !!}

                <div class="form-group"> <span class="label form-text">eMail Address </span> {!! Form::email('email',null,array('id'=>'email','class'=>'form-control first'))!!}

                  @if($errors->first('email'))

                  <p class="label label-danger" > {{ $errors->first('email') }} </p>

                  @endif 

                </div>

                <div class="form-group"> <span class="label form-text">Password</span> {!! Form::password('password',array('id'=>'password','class'=>'form-control first'))!!} 

                  @if($errors->first('password'))

                  <p class="label label-danger" > {{ $errors->first('password') }} </p>

                  @endif </div>



                  <div class="g-recaptcha" data-sitekey="6LdERAwUAAAAAHIIBfu8WK8u7SR3gCkdvTp-SjES"></div>

                  <div class="checkbox space-bottom">

                  <!--<input type="checkbox" class="text-section-remeber" id="remeber_me">

                  <label for="remeber_me" class="remeber-section">Remember me</label>-->

                  <a  href="forget_pass" class="btn-submit forget-pass-text" >Forget Password</a> 

                </div>

              </div>
              <input type="submit" class="btn btn-default login log-btn" value="Login">



              {!! Form::close() !!} 

            </div>

          </div>

        </div>

      </div>

    </div>

  </section>

  @include('frontend.common.footer') </div>

</body>

</html>