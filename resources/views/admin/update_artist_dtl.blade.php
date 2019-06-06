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
            <h1 class="heading">Bank Detail </h1>
            <p class="desc-title"></p>
            @if(Session::has('success'))
            <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} 
            </div>
            @endif
            @if(Session::has('error'))
            <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} 
            </div>
            @endif
            <div class="artists">
              {!! Form::open(array('url' =>'update_artist','class'=>'form form-horizontal text-left','method'=>'post','enctype'=>'multipart/form-data' )) !!}
              <input type="hidden" name="profileId" id="profileId" value="{{$profileData->ProfileId}}">
              <div class="inner-wrapper">

                <div class="form-group">

                  <div class="control-label">

                    <label for="currency">Currency</label>

                  </div>

                  <div class="control-box">

                    <select name="currency" id="currency" class='form-control'>

                      <option value="USD" selected>USD</option>

                    </select>

                  </div>

                </div>

                <div class="form-group">

                  <div class="control-label">

                    <label for="country"> Bank Country</label>

                  </div>

                  <div class="control-box">

                    <select name="country" id="country" class='form-control'>

                      <option value="United_states" selected>United States</option>

                    </select>

                  </div>

                </div>

                <div class="form-group">

                  <div class="control-label">

                    <label for="routing_number">Routing Number</label>

                  </div>

                  <div class="control-box">

                    <input type="text" name="routing_number" id="routing_number" class='form-control' value="{{$profileData->routing_number}}" >

                    @if($errors->first('routing_number'))

                    <p class="label label-danger" > {{ $errors->first('routing_number') }} </p>

                    @endif</div>

                  </div>

                  <div class="form-group">

                    <div class="control-label">

                      <label for="account_number">Account Number</label>

                    </div>

                    <div class="control-box">

                      <input type="text" name="account_number" id="account_number" class='form-control' value="{{$profileData->account_number}}" >

                      @if($errors->first('account_number'))

                      <p class="label label-danger" > {{ $errors->first('account_number') }} </p>

                      @endif</div>

                    </div>

                    <div class="form-group">

                      <div class="control-label">

                        <label for="account_number"> Confirm Account Number</label>

                      </div>

                      <div class="control-box">

                        <input type="text" name="confirm_account_number" id="confirm_account_number" class='form-control' >

                        @if($errors->first('confirm_account_number'))

                        <p class="label label-danger" > {{ $errors->first('confirm_account_number') }} </p>

                        @endif</div>

                      </div>

                      <div class="form-group">

                        <div class="control-label">

                          <label for="ssn_number"> SSN Number</label>

                        </div>

                        <div class="control-box">

                          <input type="text" name="ssn_number" id="ssn_number" class='form-control' value="{{$profileData->ssn_number}}">

                          @if($errors->first('ssn_number'))

                          <p class="label label-danger" > {{ $errors->first('ssn_number') }} </p>

                          @endif</div>

                        </div>

                        <div class="form-group">

                          <div class="control-label">

                            <label for="pin"> Pin</label>

                          </div>

                          <div class="control-box">

                            <input type="text" name="pin" id="pin" class='form-control' value="{{$profileData->pin}}" >

                            @if($errors->first('pin'))

                            <p class="label label-danger" > {{ $errors->first('pin') }} </p>

                            @endif</div>

                          </div>

                          <div class="form-group">

                            <div class="control-label">

                              <!-- <label for="id_pic">Upload Valid Id</label> -->
                              <label for="id_pic">Driver's License Image</label>

                            </div>

                            <div class="control-box">

                              <input type="file" name="id_pic" id="id_pic" class='form-control' >

                              @if($errors->first('id_pic'))

                              <p class="label label-danger" > {{ $errors->first('id_pic') }} </p>

                              @endif</div>

                            </div>

                          </div>
                          <div class="form-group">

                            <div class="control-label"></div>
                            <?php $artist = \App\Profile::find($profileData->ProfileId); ?>
                            @if($artist->is_bank_updated=='0')
                            <div class="control-box"> 
                              {!! Form::submit('Save',array('class'=>'btn btn-primary center-block'))!!}
                            </div>
                            @endif 
                          </div>
                          {!! Form::close() !!}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
            @include('admin.common.footer')
          </body>
          </html>