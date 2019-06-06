@include('admin.common.header')

<body class="sticky-header left-side-collapsed">
<div class="cb-pagewrap">
  
  <!-- main content start-->
  
  <div class="main-content">
    <div id="left-side-wrap"> @include('frontend.artistDashboard.layouts.lsidebar') </div>
    
    <!-- header-starts -->
    
    @include('admin.layouts.header')
    <div class="artists">
      <div class="row">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Artist Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Account Status</th>
                <th>Action</th>
                <th>Slider Show</th>
              </tr>
            </thead>
            <tbody>
            
            @foreach ($artists as $artist)
            <tr>
              <td>{{$artist->ProfileId}}</td>
              <td>{{$artist->Name}}</td>
              <td>{{$artist->EmailId}}</td>
              <td>{{$artist->is_account_active == 0 ? "Disabled":"Activated"}}</td>
              @if ($artist->is_account_active == 0)
              <td><a href="{{URL('enable_artist/'.$artist->ProfileId)}}">Activate</a></td>
              @endif
              
              
              
              @if ($artist->is_account_active == 1)
              <td><a href="{{URL('disable_artist/'.$artist->ProfileId)}}">Deactivate</a></td>
              @endif
              
              
              
              @if ($artist->slider_show == 1)
              <td><a href="{{URL('disable_slider/'.$artist->ProfileId)}}">Deactivate</a></td>
              @else
              <td><a href="{{URL('activate_slider/'.$artist->ProfileId)}}">Activate </a></td>
              @endif </tr>
            @endforeach
              </tbody>
            
          </table>
        </div>
      </div>
    </div>
  </div>
  @extends('admin.common.footer') </div>
</body>
</html>