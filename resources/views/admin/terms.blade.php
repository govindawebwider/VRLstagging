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
            <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="#">Terms</a> </div>
              <h1 class="heading">Terms</h1>
              <p class="desc-title"></p>

              <div class="artists">

                @if(Session::has('success'))

                <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

                @endif   

                @if(Session::has('error'))

                <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

                @endif

                <div class="">

                  {!! Form::open(array('url' =>'add_terms','class'=>'form form-horizontal text-left','method'=>'post' )) !!}

                  <table class="">



                    <tbody>

                      <tr>

                     <!-- <td>

                        Content

                      </td>-->

                      <td>

                        <textarea id="textarea" rows="30" cols="140" name="term_content">{{ isset($terms->content) ? $terms->content : '' }}</textarea>

                      </td>

                    </tr>

                    <tr>

                      <td>{!! Form::submit('Save',array('class'=>'btn btn-primary center-block'))!!}</td>

                    </tr>



                  </tbody>

                  

                </table>

                {!! Form::close() !!} 

              </div>

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