@include('admin.common.header')
<body class="admin artist_list">
<section class="main-page-wrapper">
  <div class="main-content">
    <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>
    @include('admin.layouts.header')
    <div class="artist_list_wrap">
      <div  class="col-md-12 "> @if(Session::has('success'))
        <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>
        @endif
        <div id="page-wrapper">
          <div class="graphs">
            <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i>
              <a class="current" href="#">Completed Video</a> </div>
            <h1 class="heading">Completed Video</h1>
            <p class="desc-title"></p>
            <div class="artists">
              <div class="table-responsive dataTables_wrapper">
                <table class="">
                  <thead>
                  <?php //dd($videos);?>
                    
                  </thead>
                  <tbody>
                    <tr>
                      <td style="font-weight:700;">Title</td>
                      <td>{{$videos->title}}</td>
                    </tr> 
                    <tr>
                      <td style="font-weight:700;">Description</td>
                      <td>{{$videos->description}}</td>
                    </tr> 
                    <tr>
                      <td></td>
                      <td>
                        <video style="max-height:375px;" id="myvideo" controls autoplay>
                          <?php $fileName = 'requested_video/watermark/'. $videos->url;
                          $videoUrl = \Illuminate\Support\Facades\Storage::disk('s3')->url($fileName);?>
                          <source src="{{ $videoUrl }}" type="video/mp4"></source>
                          <source src="{{ $videoUrl }}" type="video/webm"></source>
                          <source src="{{ $videoUrl }}" type="video/ogg"></source>
                          <source src="{{ $videoUrl }}"></source>
                        </video>
                     </td>
                   </tr>
                  </tbody>
                  
                </table>
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