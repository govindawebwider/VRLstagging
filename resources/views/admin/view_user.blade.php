@include('admin.common.header')



<body class="admin usert_list">

<section class="main-page-wrapper">

  <div class="main-content">

    <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>

    @include('admin.layouts.header')

    <div class="usert_list_wrap">

      <div  class="col-md-12 ">

        <div id="page-wrapper">

          <div class="graphs">

           

            <h1 class="heading">View User<span>
            <?php //dd($user);?>
            <a style="richness:;" href="{{URL(url()->previous())}}">
              <input class="btn btn-primary" type="button" name="artist_csv" value="Back" style="float:right;">
            </a> 
              </span>
            </h1>
            <p class="desc-title"></p>

            <div class="usert_list_wrap">

              <div class="form-group">

                <div class="control-label">

                  <label>Name</label>

                </div>

                <div class="control-box">

                  <input type="text" class="form-control" id="name" name="name" value="{{$user->Name}}" disabled="true">

                </div>

              </div>

              <div class="form-group">

                <div class="control-label">

                  <label>Email id</label>

                </div>

                <div class="control-box">

                  <input type="text" class="form-control" id="name" name="name" value="{{$user->EmailId}}" disabled="true">

                </div>

              </div>

              <div class="form-group">

                <div class="control-label">

                  <label>City</label>

                </div>

                <div class="control-box">

                  <input type="text" class="form-control" id="name" name="name" value="{{$user->City}}" disabled="true">

                </div>

              </div>

              <div class="form-group">

                <div class="control-label">

                  <label>State</label>

                </div>

                <div class="control-box">

                  <input type="text" class="form-control" id="name" name="name" value="{{$user->State}}" disabled="true">

                </div>

              </div>

              <div class="form-group">

                <div class="control-label">

                  <label>Country</label>

                </div>

                <div class="control-box">

                  <input type="text" class="form-control" id="name" name="name" value="{{$user->country}}" disabled="true">

                </div>

              </div>

              <div class="form-group">

                <div class="control-label">

                  <label>Mobile No</label>

                </div>

                <div class="control-box">

                  <input type="text" class="form-control" id="name" name="name" value=" {{$user->MobileNo}}" disabled="true">

                </div>

              </div>

            </div>

          </div>

        </div>

      </div>

    </div>

  </div>

  <div id="videoContainer" style="display:none;">

    <div class="pop-inner"> <i class="icon icon-cancel-circle close-pop"></i>

      <iframe frameborder="0" allowfullscreen wmode="Opaque" id="play_vid"></iframe>

    </div>

  </div>

</section>

@include('admin.common.footer')

</body>

</html>