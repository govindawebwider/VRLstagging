<!DOCTYPE html>



<html lang="en">



<head>



  @include('frontend.common.head')



</head>



<body class="cb-page register">



  <div class="cb-pagewrap"> @include('frontend.common.header')



    <section id="mian-content">



      <div class="container">



        <div class="cb-grid">



          <div class="cb-block cb-box-70 main-content">



            <div class="cb-content cb-marginT55 cb-marginB50">



              <div class="border-inner-center reg-inner-block">



                <h1 class="title headding-text">Register</h1>
               



                @if(Session::has('register_error'))



                <div class="alert alert-danger"> {{Session::get('register_error') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </div>



                @endif                                                        



                @if(Session::has('success'))



                <div class="alert alert-success"> {{Session::get('success') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </div>



                @endif                                                                                                                



                {!! Form::open(array('url' =>'artist_register','class'=>'form form-horizontal text-left','id'=>'regfrm','method'=>'post','files' => true)) !!}



                <div class="form-group"> {!! Form::label('username', 'Artist Name')!!}                                                                



                  {!! Form::text('username',null,array('class'=>'form-control','name'=>'username','placeholder'=>'Artist Name'))!!}                                                                



                  @if($errors->first('username'))



                  <p class="label label-danger" > {{ $errors->first('username') }} </p>



                  @endif </div>



                  <div class="form-group"> {!! Form::label('email', 'Email')!!}                                                                

                    {!! Form::email('artistEmail',null,array('class'=>'form-control','id'=>'artistEmail','placeholder'=>'xyz@gmail.com'))!!}
                    @if($errors->first('artistEmail'))

                    <p class="label label-danger" > {{ $errors->first('artistEmail') }} </p>

                    @endif </div>

                    <div class="form-group"> {!! Form::label('artistPassword', 'Password')!!}                                                                
                      <input type="password" id="artistPassword" name="artistPassword" value="{{Request::old('artistPassword')}}" placeholder="Enter Password">

                      @if($errors->first('artistPassword'))

                      <p class="label label-danger vali_wrap" > {{ $errors->first('artistPassword') }} </p>

                      @endif </div>



                      <div class="form-group"> {!! Form::label('confirmpassword', ' Confirm Password')!!}                                                                

                      <input type="password" id="confirmpassword" name="confirmpassword" value="{{Request::old('confirmpassword')}}" placeholder="Confirm password">

                        @if($errors->first('confirmpassword'))



                        <p class="label label-danger" > {{ $errors->first('confirmpassword') }} </p>



                        @endif </div>

                        
                        <div class="form-group">
                          <label for="dob">Date Of Birth</label>
                          <div class="artist_register_date_section">
                            <div class="artist_select-date mobile-date">
                            </div>
                            <div class="artist_select-date">
                              <input type="hidden" id="date_ofbirth" >
                              @if($errors->first('date_of_birth'))
                              <p class="label label-danger" > {{ $errors->first('date_of_birth') }} </p>
                              @endif 
                            </div>
                            <!-- <input type="hidden" name="dob" id="dob" value="" class='form-control' > -->
                          </div>
                        </div>




                        <div class="form-group"> {!! Form::label('email', 'Phone No')!!}                                                                



                          {!! Form::text('phone',null,array('class'=>'form-control','id'=>'phone','placeholder'=>'111-111-1111'))!!}                                                                



                          @if($errors->first('phone'))



                          <p class="label label-danger" > {{ $errors->first('phone') }} </p>



                          @endif </div>



                          <div class="form-group"> {!! Form::label('gender', ' Choose Your Gender')!!}                                                                



                            {!! Form::select('gender',array(''=>'Please Select Your Gender','male'=>'Male','female'=>'Female'),null,['class'=>'drop-down-corcer form-control'])!!}                                                                



                            @if($errors->first('gender'))



                            <p class="label label-danger" > {{ $errors->first('gender') }} </p>



                            @endif </div>



                            <div class="form-group"> {!! Form::label('profile', 'Choose Profile image')!!}                                                                



                              {!! Form::file('profile',array('class'=>'form-control'))!!}                                                                @if($errors->first('profile'))



                              <p class="label label-danger" > {{ $errors->first('profile') }} </p>



                              @endif </div>







                              <div class="form-group">  {!! Form::submit('Submit',array('class'=>'btn btn-primary sbt-btn','id'=>'regBtn'))!!}</div>



                              <div class="form-group"> </div>



                              {!! Form::close() !!} </div>



                            </div>



                          </div>



                        </div>



                      </div>



                    </section>



                    <section id="portfolio" class="portfolio">



                      <div class="container">



                        <div class="col-md-12 col-sm-12 col-xs-12"> </div>



                      </div>



                      <!--/.container--> 



                    </section>



                    @include('frontend.common.footer') 

                  </div>



                </body>







                </html>