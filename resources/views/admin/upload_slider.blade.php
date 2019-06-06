@include('admin.common.header')

<body class="sticky-header left-side-collapsed">
<div class="cb-pagewrap">
  
  <!-- main content start-->
  
  <div class="main-content">
    <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>
    
    <!-- header-starts -->
    
    @include('admin.layouts.header')
    <div id="page-wrapper">
      <div class="slider_form"> @if(Session::has('success'))
        <div class="alert alert-info"> {{Session::get('success') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </div>
        @endif
        <form action="upload_slider" method="POST" enctype="multipart/form-data" role="form">
          <legend>Upload Slider</legend>
          {!! csrf_field(); !!}
          <div class="form-group">
            <label for="slider_title">Title</label>
            <input type="text" name="slider_title" class="form-control" id="slider_title">
            @if($errors->first('slider_title'))
            <p class="label label-danger" > {{ $errors->first('slider_title') }} </p>
            @endif </div>
          <div class="form-group">
            <label for="slider_description">Description</label>
            <input type="text" name="slider_description" class="form-control" id="slider_description">
            @if($errors->first('slider_description'))
            <p class="label label-danger" > {{ $errors->first('slider_description') }} </p>
            @endif </div>
          <div class="form-group">
            <label for="artist_id">Select Artist</label>
            <select name="artist_id" id="artist_id" class="form-control">
              <option value="">Please Select Artist</option>
              

                                @foreach ($artists as $artist)

                                
              <option value="{{$artist->ProfileId}}">{{$artist->Name}}</option>
              

                                @endforeach

                            
            </select>
            @if($errors->first('artist_id'))
            <p class="label label-danger" > {{ $errors->first('artist_id') }} </p>
            @endif </div>
          <div class="form-group">
            <label for="slider">Choose Slider</label>
            <input type="file" name="slider" class="form-control" id="slider">
            @if($errors->first('slider'))
            <p class="label label-danger" > {{ $errors->first('slider') }} </p>
            @endif </div>
          <input type="submit" class="btn btn-primary" value="Upload">
        </form>
      </div>
    </div>
  </div>
  @extends('admin.common.footer') </div>
</body>
</html>