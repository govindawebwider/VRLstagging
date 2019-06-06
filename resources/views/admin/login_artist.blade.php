@include('admin.common.header')
<body class="admin site update_profile edit_artist-page">
<section class="main-page-wrapper">
<!-- main content start-->
<div class="main-content">
<div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>
<div class="header-section">
  <div class="top-main-left"> <span class="logo1"> {!! Html::image('/images/logo1.png')!!} </span> <a href="javascript:void(0)" class="toggle menu-toggle"><i class="lnr lnr-menu"></i></a> </div>
  <div class="menu-right">
    <div class="user-panel-top">
      <div class="profile_details">
        <div class="profile_img">
          <div class="dropdown user-menu"> <span class="dropdown-toggle"> <span class="admin-img"> <img src="" alt=""> </span> <span class="admin-name"></span> <i class="arrow"></i> </span> </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- //header-ends -->
<div  class="col-md-12 profile-update-wrap">
  <div id="page-wrapper"> 
    <div class="graphs">
 @if(Session::has('success'))
                    <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>
                    @endif
                    @if(Session::has('error'))
                    <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>
                    @endif
                    
                    
                    
<h1 class="heading">Update Profile
<span>
<a style="float:right" href="{{URL(url()->previous())}}">  
<input class="btn btn-primary" type="button" name="artist_csv" value="Back"></a> 
</span>
</h1>
<p class="desc-title"></p>
       </div>
  </div>
</div>
@include('admin.common.footer')
</section>
</body>
</html>