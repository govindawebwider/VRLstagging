<!DOCTYPE html>
<html lang="en">
<head>
  @include('frontend.common.head')
</head>
<body class="cb-page success_payment">
  <div class="cb-pagewrap"> @include('frontend.common.header')
    <section id="mian-content">
      <div class="container">
        <div class="cb-grid">
          <div class="cb-block cb-box-50 main-content">

            @if(Session::has('success'))

            <h2>{{Session::get('success')}}</h2>

            @endif
            @if(Session::has('error'))

            <h2>{{Session::get('error')}}</h2>

            @endif
            <p>Click here to <a href="/login"> Login</a></p> 
          </div>
        </div>
      </div>
    </section>
    @include('frontend.common.footer') </div>
  </body>
  </html>