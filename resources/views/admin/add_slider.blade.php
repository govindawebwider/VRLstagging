@include('admin.common.header')



<body class="admin artist_list">

  <section class="main-page-wrapper">

    <div class="main-content">

      <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>

      @include('admin.layouts.header')

      <div class="artist_list_wrap">

        <div  class="col-md-12 ">

          <div id="page-wrapper">

            <div class="graphs">
            <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/add_admin_slider">Add Slider</a> </div> 
              @if(Session::has('success'))

              <div class="alert alert-success"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('success') }}</span> </div>

              @endif

              @if(Session::has('error'))

              <div class="alert alert-danger"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('error') }}</span> </div>

              @endif

              <h1 class="heading">Add Slider </h1>
              <p class="desc-title"></p>



          <!-- <a href="{{URL('/artist_csv')}}">



            <input class="btn btn-primary" type="button" name="artist_csv" value="Export Artist List">



          </a> -->

          

          <div class="artists">

            <div class="#"> 



              <!--  <h1 class="heading">Slider</h1> -->

              

              <form action="{{URL('add_admin_slider')}}" method="POST" enctype="multipart/form-data" role="form">

                {!! csrf_field(); !!}

                <div class="form-group">

                  <div class="control-label">

                    <label for="slider_title">Title</label>

                  </div>

                  <div class="control-box">

                    <input type="hidden" name="slider_id" value="">

                    <input type="text" name="slider_title" value="" class="form-control" id="slider_title">

                    @if($errors->first('slider_title'))

                    <p class="label label-danger" > {{ $errors->first('slider_title') }} </p>

                    @endif</div>

                  </div>

                  <div class="form-group">

                    <div class="control-label">

                      <label for="slider_description">Description</label>

                    </div>

                    <div class="control-box">

                      <textarea name="slider_description" class="form-control" cols="30" rows="10" id="slider_description"></textarea>

                      @if($errors->first('slider_description'))

                      <p class="label label-danger" > {{ $errors->first('slider_description') }} </p>

                      @endif </div>

                    </div>

                    <div class="form-group">

                      <div class="control-label">

                        <label for="slider">Choose Slider</label>

                      </div>

                      <div class="control-box">

                        <input type="file" name="slider" class="form-control" id="slider">

                        @if($errors->first('slider'))

                        <p class="label label-danger" > {{ $errors->first('slider') }} </p>

                        @endif 
                        <span class="msg"> Image must be 400 X 400 px and  jpeg, png format.</span>

                      </div>


                    </div>

                    <div class="form-group">

                      <div class="control-label"> <label></label> </div>

                      <div class="control-box">



                        <input type="submit" class="btn btn-primary" value="Update">

                      </div>

                    </form>

                  </div>

                </div>

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