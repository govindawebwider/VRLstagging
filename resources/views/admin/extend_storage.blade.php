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
            <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/extend_price">Storage Price</a> </div>
              <h1 class="heading">Storage Price
                <span>
                  <a style="float:right" href="{{URL('/')}}">  
                    <input class="btn btn-primary" type="button" name="artist_csv" value="Back"></a> 
                  </span></h1>
                  <p class="desc-title"></p>
                  @if(Session::has('success'))
                  <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>
                  @endif
                  @if(Session::has('error'))
                  <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>
                  @endif
                  <div class="artists">
                    <div class=""> 
                      {!! Form::open(array('url' =>'extend_price','class'=>'form form-horizontal text-left','id'=>'upProfile','method'=>'post' ,'files'=>true)) !!}
                      <div class="#">
                        <?php //dd($storage_data->status);?>
                        <div class="form-group">
                          <div class="control-label">
                            <label for="phone">Storage Price</label>
                          </div>
                          <div class="control-box">
                            <input type="text" name="stor_price" id="stor_price" value="{{$storage_data->status}}" class="form-control" autocomplete="off" placeholder="Please Enter an amount"><small>(per day price $)</small>
                            @if($errors->first('stor_price'))
                            
                            <p class="label label-danger" > {{ $errors->first('stor_price') }} </p>
                            @endif </div>
                          </div>
                          
                        </div>
                        <div class="form-group">
                          <div class="control-label"> <label></label></div>
                          <div class="control-box"> {!! Form::submit('Save',array('class'=>'btn btn-primary center-block'))!!} </div>
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
        </section>
        @extends('admin.common.footer')
      </body>
      </html>