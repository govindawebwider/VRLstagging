@include('admin.common.header')
<style>
  /****** Style Star Rating Widget *****/

  .rating {
    border: none;
    float: left;
  }

  .rating > input { display: none; }
  .rating > label:before {
    margin: 5px;
    font-size: 1.25em;
    font-family: FontAwesome;
    display: inline-block;
    content: "\f005";
  }

  .rating > .half:before {
    content: "\f089";
    position: absolute;
  }

  .rating > label {
    color: #ddd;
    float: right;
  }

  /***** CSS Magic to Highlight Stars on Hover *****/

  .rating > input:checked ~ label, /* show gold star when clicked */
  .rating:not(:checked) > label:hover, /* hover current star */
  .rating:not(:checked) > label:hover ~ label { color: #FFD700;  } /* hover previous stars in list */
  .rating > input:checked + label:hover, /* hover current star when changing rating */
  .rating > input:checked ~ label:hover,
  .rating > label:hover ~ input:checked ~ label, /* lighten current selection */
  .rating > input:checked ~ label:hover ~ label { color: #FFED85;  }
</style>


<body class="admin testimonial_list">



<section class="main-page-wrapper">



  <div class="main-content">



    <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>



    @include('admin.layouts.header')



    <div class="testimonial_list_wrap">



      <div  class="col-md-12 ">



        <div id="page-wrapper">



          <div class="graphs">

            <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/add_admin_review">Add Review</a> </div>

            

            <h1 class="heading">Add Review</h1>
            <p class="desc-title"></p>



         



              <div class="cb-block cb-box-100"> @if(Session::has('success'))



                <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>



                @endif



                <div class="cb-content">



                  <div class="inner-block">



                    <div class="testi_form">



                      <?php //dd($artist);?>



                      <form action="/add_admin_testimonial" method="POST" role="form">



                        {{csrf_field()}}



                        <input type="hidden" class="form-control" id="to_profile_id" name="to_profile_id"  value="{{$admin->ProfileId}}">



                        <div class="form-group">



                          <div class="control-label">



                            <label for="name">Name</label>



                          </div>



                          <div class="control-box">



                            <input type="text" class="form-control" id="user_name" name="user_name"  value="{{Request::old('user_name')}}">

                            @if($errors->first('user_name'))



                            <p class="label label-danger" > {{ $errors->first('user_name') }} </p>



                            @endif

                          </div>



                        </div>



                        <div class="form-group">



                          <div class="control-label">



                            <label for="email">Email</label>



                          </div>



                          <div class="control-box">



                            <input type="text" class="form-control" id="email" name="email" value="{{Request::old('email')}}">

                            @if($errors->first('email'))



                            <p class="label label-danger" > {{ $errors->first('email') }} </p>



                            @endif

                          </div>



                        </div>



                        <div class="form-group">



                          <div class="control-label">



                            <label for="message">Message</label>



                          </div>



                          <div class="control-box">



                            <textarea class="form-control" id="message" name="message" value="{{Request::old('message')}}"></textarea>



                            @if($errors->first('message'))



                            <p class="label label-danger" > {{ $errors->first('message') }} </p>



                            @endif</div>



                        </div>



                        <div class="form-group">



                          <div class="control-label">



                            <label for="message">For Artist</label>



                          </div>



                          <div class="control-box">



                            <select name="artist_id">




										@foreach ($artist as $artist)



											



                              



                              <option value="{{$artist->ProfileId}}">{{$artist->Name}}</option>



                              



                              



										@endforeach	



										



                            



                            </select>



                          </div>



                        </div>

                        <div class="form-group">
                          <div class="control-label">
                            <label for="message">Select Rating</label>
                          </div>
                          <div class="control-box">
                            <fieldset class="rating">
                              <input type="radio" id="star5" name="rate" value="5"/><label class="full" for="star5"
                                                                                           title="5 stars"></label>
                              <input type="radio" id="star4half" name="rate" value="4.5"/><label class="half"
                                                                                                 for="star4half"
                                                                                                 title="4.5 stars"></label>
                              <input type="radio" id="star4" name="rate" value="4"/><label class="full" for="star4"
                                                                                           title="4 stars"></label>
                              <input type="radio" id="star3half" name="rate" value="3.5"/><label class="half"
                                                                                                 for="star3half"
                                                                                                 title="3.5 stars"></label>
                              <input type="radio" id="star3" name="rate" value="3"/><label class="full" for="star3"
                                                                                           title="3 stars"></label>
                              <input type="radio" id="star2half" name="rate" value="2.5"/><label class="half"
                                                                                                 for="star2half"
                                                                                                 title="2.5 stars"></label>
                              <input type="radio" id="star2" name="rate" value="2"/><label class="full" for="star2"
                                                                                           title="2 stars"></label>
                              <input type="radio" id="star1half" name="rate" value="1.5"/><label class="half"
                                                                                                 for="star1half"
                                                                                                 title="1.5 stars"></label>
                              <input type="radio" id="star1" name="rate" value="1"/><label class="full" for="star1"
                                                                                           title="1 star"></label>
                              <input type="radio" id="starhalf" name="rate" value="0.5"/><label class="half"
                                                                                                for="starhalf"
                                                                                                title="0.5 stars"></label>
                            </fieldset>
                          </div>
                        </div>

                        <div class="form-group">



                        <div class="control-label"><label></label></div>



                        <div class="control-box"> <span class=></span>



                          <button type="submit" class="btn btn-primary">Submit</button>



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



  </div>



</section>



@extends('admin.common.footer')



</body>



</html>