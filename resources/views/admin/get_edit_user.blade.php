@include('admin.common.header')

<body class="admin site update_profile">

  <section class="main-page-wrapper">

    <!-- main content start-->

    <div class="main-content">

      <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>

      @include('admin.layouts.header')

      <!-- //header-ends -->

      <div  class="col-md-12 profile-update-wrap">

        <div id="page-wrapper">

          <div class="graphs">


    <!-- @if (count($errors) > 0)

    <div class="alert alert-danger">

        <ul>

            @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

    @endif -->

    <h1 class="heading">Update Profile<span><a style="richness:;" href="{{URL(url()->previous())}}">

      <input class="btn btn-primary" type="button" name="artist_csv" value="Back" style="float:right;">

    </a> </span></h1>
    <p class="desc-title"></p>

    {!! Form::open(array('url' =>'edit_user_profile','class'=>'form form-horizontal text-left','id'=>'upProfile','method'=>'post' ,'files'=>true)) !!}

    <div class="inner-wrap">

     @if(Session::has('success')) <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

     <div class="alert alert-success"> {{Session::get('success') }} </div>

     @endif

     @if(Session::has('error'))

     <div class="alert alert-danger"> 

      <span class="close" data-dismiss="alert">&times;</span> 

      <span class="text-center"> {{Session::get('error') }} 

      </div>

      @endif

      <div class="form-group">

        <div class="control-label">

          <label for="username">Name</label>

        </div>

        <div class="control-box">

          <input type="text" name="username" id="username" value="{{$userData->Name}}" class='form-control' >

          @if($errors->first('username'))

          <p class="label label-danger" > {{ $errors->first('username') }} </p>

          @endif </div>

        </div>

        <div class="form-group">

          <div class="control-label">

            <label for="email">Email</label>

          </div>

          <div class="control-box">

            <input type="hidden" name="user_id" value="{{$userData->ProfileId}}">

            <input type="text" readonly name="email" id="email" value="{{$userData->EmailId}}" class='form-control'>

            @if($errors->first('email'))

            <p class="label label-danger" > {{ $errors->first('email') }} </p>

            @endif </div>

          </div>

          

          <div class="form-group">

            <div class="control-label">

              <label for="phone">Phone No.</label>

            </div>

            <div class="control-box">

              <input type="text" name="phone" id="phone" value="{{$userData->MobileNo}}" class='form-control' placeholder="111-111-1111">

              @if($errors->first('phone'))

              <p class="label label-danger" > {{ $errors->first('phone') }} </p>

              @endif </div>

            </div>

            

            <div class="form-group">

              <div class="control-label">

                <label for="address">Address</label>

              </div>

              <div class="control-box">

                <input type="text" name="address" id="address" value="{{$userData->Address}}" class='form-control' >

                @if($errors->first('address'))

                <p class="label label-danger" > {{ $errors->first('address') }} </p>

                @endif </div>

              </div>

              <div class="form-group">

                <div class="control-label">

                  <label for="city">City</label>

                </div>

                <div class="control-box">

                  <input type="text" name="city" id="city" value="{{$userData->City}}" class='form-control' >

                  @if($errors->first('city'))

                  <p class="label label-danger" > {{ $errors->first('city') }} </p>

                  @endif </div>

                </div>

                <div class="form-group">

                  <div class="control-label">

                    <label for="state">State</label>

                  </div>

                  <div class="control-box">

                    <input type="text" name="state" id="state" value="{{$userData->State}}" class='form-control' >

                    @if($errors->first('state'))

                    <p class="label label-danger" > {{ $errors->first('state') }} </p>

                    @endif </div>

                  </div>

                  <div class="form-group">

                    <div class="control-label">

                      <label for="country">Country</label>

                    </div>

                    <div class="control-box">

                      <input type="text" name="country" id="country" value="{{$userData->country}}" class='form-control' >

                      @if($errors->first('country'))

                      <p class="label label-danger" > {{ $errors->first('country') }} </p>

                      @endif </div>

                    </div>

                    

                    

                    <div class="form-group">

                      <div class="control-label">

                        <label for="zip">Zip</label>

                      </div>

                      <div class="control-box">

                        <input type="text" name="zip" id="zip" value="{{$userData->Zip}}" class='form-control' >

                        @if($errors->first('zip'))

                        <p class="label label-danger" > {{ $errors->first('zip') }} </p>

                        @endif 
                      </div>

                    </div>
                    <div class="form-group">

                      <div class="control-label">

                        <label for="password">Password</label>

                      </div>

                      <div class="control-box">

                        <input type="Password" name="password" id="password" value="" class='form-control' >

                        @if($errors->first('password'))

                        <p class="label label-danger" > {{ $errors->first('password') }} </p>

                        @endif </div>

                      </div>
                      <div class="form-group">
                        <div class="control-label">
                          <label for="cpassword">Confirm Password</label>
                        </div>
                        <div class="control-box">
                          <input type="Password" name="cpassword" id="cpassword" value="" class='form-control' >
                          @if($errors->first('cpassword'))
                          <p class="label label-danger" > Password and confirm password should be match! </p>
                          @endif </div>
                        </div>
						
                        <div class="form-group">

                        <div class="control-label"> <label></label></div>

                        <div class="control-box"> {!! Form::submit('Update',array('class'=>'btn btn-primary center-block'))!!} </div>

                      </div>




                        <div class="clearfix"></div>





                      </div>



                      <input type="hidden" name="ProfileId" value="{{$userData->ProfileId}}">

                      



                      <!-- <button type="button" class="btn btn-primary " data-dismiss="modal" class='btn btn-primary center-block'>Close</button> --> 



                      {!! Form::close() !!} </div>

                    </div>

                  </div>

                  @include('admin.common.footer')

                </section>

                <script>

                  $( function() {

                    $( "#dob" ).datepicker(

                    {

                      changeMonth: true,

                      changeYear: true,

                      minDate:'-30Y',

                      maxDate:0

                    }

                    );

                  } );

                </script> 

              </body>

              </html>