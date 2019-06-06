<!-- edit category view added by the sandeep -->
@include('admin.common.header')

<body class="admin site update_profile edit_artist-page">

  <section class="main-page-wrapper">

    <!-- main content start-->

    <div class="main-content">

      <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>

        @include('admin.layouts.header')

      <!-- //header-ends -->

      <div  class="col-md-12 profile-update-wrap">

        <div id="page-wrapper"> 

          <div class="graphs">

              <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="#">Update Category</a>
              </div>

             @if(Session::has('success'))

             <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

             @endif

             @if(Session::has('error'))

             <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

             @endif

             

             <h1 class="heading">Update Category

              <span>

                <a style="float:right" href="{{ URL('categories') }}">  

                  <input class="btn btn-primary" type="button" name="artist_csv" value="Back"></a> 

                </span>

              </h1>
              <p class="desc-title"></p>

              {!! Form::open(array('url' =>'edit_cat','class'=>'form form-horizontal text-left','id'=>'upProfile','method'=>'post' ,'files'=>true)) !!}

              <div class="inner-wrap">
                               
                              <div class="form-group">

                                <div class="control-label">

                                  <label for="title">Title</label>

                                </div>

                                <div class="control-box">

                                  <input type="text" name="title" id="title" value="{{$catData->title}}" maxlength="35"  class='form-control' >

                                  @if($errors->first('title'))

                                  <p class="label label-danger" > {{ $errors->first('title') }} </p>

                                  @endif </div>

                                </div>
                                 
                                        <div class="form-group">
                                          <div class="control-label">

                                           <label> </label>

                                         </div>
                                         
                                         <div class="control-box"> {!! Form::submit('Update',array('class'=>'btn btn-primary center-block'))!!} </div>
                                       </div>

                                       <div class="clear"></div>

                                     </div>


                                     <input type="hidden" name="Id" value="{{$catData->id}}">


                                     {!! Form::close() !!} </div>

                                   </div>

                                 </div>

                                 @include('admin.common.footer')

                               </section>

                             </body>

                             </html>