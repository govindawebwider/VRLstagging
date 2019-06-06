@include('admin.common.header')

<body class="admin deliver_video">

  <section class="main-page-wrapper">

    <div class="main-content">

      <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>

      @include('admin.layouts.header')

      <div class="deliver_video_wrap">

        <div  class="col-md-12 ">

          <div id="page-wrapper">

            <div class="graphs">

             
              @if(Session::has('success'))

              <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

              @endif                    

              @if(Session::has('error'))

              <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

              @endif

              <h1 class="heading">Edit Review<span><a  class="btn btn-primary" style="float:right" href="{{URL('/reviews')}}">Back</a> </span></h1>
              <p class="desc-title"></p>

              <?php //dd($testimonial);?>

              <div class="inner-wrap">

                <div class="content clearfix">

                  <div class="xs">

                    <div class="status-table-wrap">

                      <div class="mailbox-content ">

                        <form action="/update_admin_testimonial/{{$testimonial->id}}" method="POST" enctype="multipart/form-data" role="form">

                          {!! csrf_field(); !!}

                          <input type="hidden" name="test_id" value="{{$testimonial->id}}">

                          <div class="form-group">
                            <div class="control-label">
                              <label>Name</label>
                            </div>
                            <div class="control-box">
                              <input type="text" name="user_name" value="{{$testimonial->user_name}}" class="form-control">
                              @if($errors->first('user_name'))
                              <p class="label label-danger" > {{ $errors->first('user_name') }} </p>
                              @endif </div>
                            </div>
                            <div class="form-group">
                              <div class="control-label">
                                <label>Email</label>
                              </div>
                              <div class="control-box">
                                <input type="text" name="user_email" value="{{$testimonial->Email}}" class="form-control">
                                @if($errors->first('user_email'))
                                <p class="label label-danger" > {{ $errors->first('user_email') }} </p>
                                @endif 
                              </div>
                            </div>



                          </div>


                          <div class="form-group">

                            <div class="control-label">

                              <label>Message</label>

                            </div>

                            <div class="control-box">

                              <textarea name="message" class="form-control" rows="5">{{$testimonial->Message}}</textarea>

                              @if($errors->first('message'))

                              <p class="label label-danger" > {{ $errors->first('message') }} </p>

                              @endif </div>

                            </div>
                            <div class="form-group">

                              <div class="control-label">

                                <label for="message">For Artist</label>

                              </div>

                              <div class="control-box">
                                <?php $id=$testimonial->by_profile_id;?>
                                <select name="artist_id">
                                  @foreach ($artist as $artist)
                                  <option value="{{$artist->ProfileId}}" {{ $artist->ProfileId == $testimonial->to_profile_id ? 'selected' : '' }}>
                                    {{$artist->Name}}
                                  </option>
                                  @endforeach 
                                </select>
                              </div>
                            </div>

                            <div class="form-group">

                              <div class="control-label">

                                <label></label>

                              </div>

                              <div class="control-box">

                                <div class="edit_testimonial-btn" style="margin-top:-5px;">

                                  <input type="submit" class="btn btn-primary" value="Update">

                                </div>

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

          @extends('admin.common.footer') </section>

        </body>

        </html>