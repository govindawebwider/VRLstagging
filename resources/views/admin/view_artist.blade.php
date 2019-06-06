@include('admin.common.header')

<body class="admin artist_detail">
<section class="main-page-wrapper">
  <div class="main-content">
    <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>
    @include('admin.layouts.header')
    <div class="usert_list_wrap">
      <div  class="col-md-12 ">
        <div id="page-wrapper">
          <div class="graphs">
            <a class="btn btn-primary" href="{{URL(url()->previous())}}">Back</a>
            <div class="artist_detail_wrap form-horizontal">
              <div class="form-group">
                <div class="control-label">
                  <label>Name</label>
                </div>
                <div class="control-box"> {{$artist->Name}} </div>
              </div>
              <div class="form-group">
                <div class="control-label">
                  <label>Description</label>
                </div>
                <div class="control-box"> {{$artist->profile_description}} </div>
              </div>
              <div class="form-group">
                <div class="control-label">
                  <label>Social</label>
                </div>
                <div class="control-box"> <span style="max-width:40px; display:block; float:left;"><i class="icon icon-facebook"></i></span> <span style="max-width:40px;display:block; float:left;"><i class="icon icon-twitter"></i></span> <span style="max-width:40px;display:block; float:left;"><i class="icon icon-youtube"></i></span> </div>
              </div>
              <div class="form-group">
                <div class="control-label">
                  <label>Video Price</label>
                </div>
                <div class="control-box"> {{$artist->VideoPrice}} </div>
              </div>
              <div class="form-group">
                <div class="control-label">
                  <label>Email id</label>
                </div>
                <div class="control-box"> {{$artist->EmailId}} </div>
              </div>
              <div class="form-group">
                <div class="control-label">
                  <label>Address</label>
                </div>
                <div class="control-box"> {{$artist->Address}} </div>
              </div>
              <div class="form-group">
                <div class="control-label">
                  <label>City</label>
                </div>
                <div class="control-box"> {{$artist->City}} </div>
              </div>
              <div class="form-group">
                <div class="control-label">
                  <label>State</label>
                </div>
                <div class="control-box"> {{$artist->State}} </div>
              </div>
              <div class="form-group">
                <div class="control-label">
                  <label>Mobile No</label>
                </div>
                <div class="control-box"> {{$artist->MobileNo}} </div>
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