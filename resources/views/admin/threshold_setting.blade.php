@include('admin.common.header')



<body class="admin slider">

  <section class="main-page-wrapper">

    <div class="main-content">

      <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>

      @include('admin.layouts.header')

      <div class="slider_wrap">

        <div  class="col-md-12 ">

          <div id="page-wrapper">

            <div class="graphs">
            <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/threshold_setting">Threshold Setting</a> </div>

              <h1 class="heading">Threshold Setting</h1>
              <p class="desc-title"></p>

              @if(Session::has('success'))



              <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>



              @endif



              @if(Session::has('error'))



              <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>



              @endif

              <div class="artists">

                <div class=""> {!! Form::open(array('url' =>'threshold_setting','class'=>'form form-horizontal text-left','method'=>'post' )) !!}

                  <div class="form-group">

                    <div class="control-label"> {!! Form::label('threshold', '')!!} </div>

                    <div class="control-box">

                      <input type="text" class="form-control" name="threshold_val" id="threshold_val" value="{{$threshold->status}}">

                      @if($errors->first('threshold_val'))

                      <p class="label label-danger" > {{ $errors->first('threshold_val') }} </p>

                      @endif </div>

                    </div>

                    <div class="form-group">

                      <div class="control-label"> <label></label></div>

                      <div class="control-box"> {!! Form::submit('Submit',array('class'=>'btn btn-primary sbt-btn','id'=>'regBtn'))!!} </div>

                    </div>

                    {!! Form::close() !!} </div>

                  </div>

                </div>

              </div>

            </div>

          </div>

        </div>

      </section>

      @extends('admin.common.footer')

    </body>

    </html>