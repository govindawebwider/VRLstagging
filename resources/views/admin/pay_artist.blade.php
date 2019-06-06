@include('admin.common.header')

<body class="sticky-header left-side-collapsed">
<div class="cb-pagewrap">
  
  <!-- main content start-->
  
  <div class="main-content">
    <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>
    
    <!-- header-starts -->
    
    @include('admin.layouts.header')
    <div id="page-wrapper">
      <div class="slider_edit_form"> @if(Session::has('success'))
        <div class="alert alert-info"> {{Session::get('success') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </div>
        @endif
        <form action="{{URL('pay_artist')}}" method="POST" role="form">
          <legend>Pay To Artist</legend>
          {!! csrf_field(); !!}
        </form>
      </div>
    </div>
  </div>
  @extends('admin.common.footer') </div>
</body>
</html>