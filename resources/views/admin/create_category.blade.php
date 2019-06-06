<!-- create category view created by sandeep -->
@include('admin.common.header')



<body class="admin change_password create_category">



  <section class="main-page-wrapper">



    <div class="main-content">



      <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>



      @include('admin.layouts.header')



      <div class="change_password_wrap">



        <div  class="col-md-12 ">



          <div id="page-wrapper">



            <div class="change_pass_form"> @if(Session::has('error'))



              <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>



              @endif



              <div class="graphs">

                <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="#">Create Category</a>
                </div>
                <h1 class="heading">Create Category</h1>
                <p class="desc-title"></p>


                {!! Form::open(array('url' =>'create_category','class'=>'form form-horizontal text-left','method'=>'post','files'=>true )) !!}



                <div class="inner-wrapper"> @if(Session::has('register_error'))



                  <div class="alert alert-danger"> {{Session::get('login_error') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </div>



                  @endif



                  @if(Session::has('success'))



                  <div class="alert alert-success"> {{Session::get('success') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </div>



                  @endif



                  <!--  {!! Form::open(array('url' =>'create_category','class'=>'form form-horizontal text-left','id'=>'regfrm','method'=>'post','files' => true)) !!} -->





                  <div class="form-group">



                    <div class="control-label"> {!! Form::label('title', 'Title')!!} </div>



                    <div class="control-box"> {!! Form::text('title',null,array('class'=>'form-control','id'=>'title','maxlength'=>'35'))!!} </div>



                    @if($errors->first('title'))



                    <p class="label label-danger" > {{ $errors->first('title') }} </p>



                    @endif 
                  </div>
                  <!-- <div class="form-group">

                    <div class="control-label">

                      <label for="category">Image</label>

                    </div>

                    <div class="control-box">

                    <input type="file" name="category" id="category" class='form-control upload-img'>
                   
                    <span class="msg">Image must be 400 X 400 px and  jpeg, png format.</span> 

                    @if($errors->first('category'))

                    <p class="label label-danger" > {{ $errors->first('category') }} </p>

                    @endif </div>

                  </div>
                  <div class="form-group">
                    <div class="control-label"> {!! Form::label('description', 'Description')!!} </div>
                    <div class="control-box">
                      <textarea id="textarea" rows="5" name="description"> </textarea>
                    </div>
                  </div> -->
                  <div class="form-group">
                   <div class="control-label"> <label></label> </div>
                   <div class="control-box"> {!! Form::submit('Submit',array('class'=>'btn btn-primary sbt-btn','id'=>'regBtn'))!!} </div>
                 </div>
               {!! Form::close() !!} </div>
             </div>
           </div>
         </div>
       </div>
       @extends('admin.common.footer') 
       <script>
        $(function () {
          $( "#datepicker" ).datepicker();
          $("#date_of_birth").datepicker({
            changeMonth: true,
            changeYear: true,
            changeDate: true,
            minDate: 0,
          });



          $("#request_datepicker").datepicker({



            changeMonth: true,



            changeYear: true,



            minDate:0,



            maxDate:'+30Y'



          });



        });



      </script>



    </body>



    </html>