@include('admin.common.header')



<body class="admin video_price">

  <section class="main-page-wrapper">

    <div class="main-content">

      <div id="left-side-wrap"> @include('frontend.artistDashboard.layouts.lsidebar') </div>

      <div class="header-section">
          <div class="top-main-left">
              <a href="{{URL('Dashboard')}}"><span class="logo1 white"><img src="/images/vrl_logo_nav.png" class="img img-responsive"></span> </a>
              <a href="javascript:void(0)" class="toggle menu-toggle"><i class="lnr lnr-menu"></i></a>
          </div>
        <div class="menu-right">

          <div class="user-panel-top">

            <div class="profile_details">

              <div class="profile_img">
                <div class="dropdown user-menu"> <span class="dropdown-toggle"> <span class="admin-img">
                            <?php $imageUrl = '';
                            $fileName = 'images/Artist/'.$artist->profile_path; ?>
                            @if (\Illuminate\Support\Facades\Storage::disk('s3')->exists($fileName))
                                <?php $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                        ->url($fileName);?>
                            @endif
                            <img src="{{$imageUrl}}" alt="">
                            {{--<img src="{{url('images/Artist/').'/'.$artist->profile_path}}" alt="">--}}
                        </span><span class="admin-name">{{$artist->Name}}</span><i class="arrow"></i> </span>

                  <ul>
                   @if(session('current_type') == 'Artist')
                   <li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>Switch to Admin</span></a></li>
                   @endif
                   <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{ URL($artist->profile_url)}}"> <i class="icon icon-users"></i> <span>view Profile</span> </a> </li>
                   @if($user->admin_link=='yes')

                   <li class="{{ Request::is('Switch to Admin') ? 'active' : '' }}"> <a href="{{URL('/login_admin')}}"> <i class="icon icon-users"></i> <span>Switch to Admin</span> </a>

                   </li>

                   @endif

                   <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{URL('ProfileUpdate')}}"> <i class="icon icon-users"></i> <span>Edit Profile</span> </a> </li>

                   <li class="{{ Request::is('change-password') ? 'active' : '' }}"> <a href="{{URL('change-password')}}"> <i class="icon icon-lock"></i> <span>Change Password</span> </a> </li>

                   <li class="{{ Request::is('getLogout') ? 'active' : '' }}"> <a href="{{ URL::route('getLogout') }}"> <i class="icon icon-exit"></i> <span>Logout</span> </a> </li>

                 </ul>

               </div>

             </div>

           </div>

         </div>

       </div>

     </div>

     <div class="video_price_wrap">

      <div  class="col-md-12 ">

        <div id="page-wrapper">

          <div class="graphs">
          <div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/addPrice">Video Price</a> 
            
          </div> 

            @if(Session::has('error'))

            <div class="alert alert-danger"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('error') }}</span> </div>

            @endif

            @if(Session::has('success'))

            <div class="alert alert-success"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('success') }}</span> </div>

            @endif

            <h1 class="heading">Video Price
              <span><a style="float:right" href="{{URL('/occasions/create')}}">  <input
                                            class="btn btn-primary" type="button" name="artist_csv" value="Create Occasion"></a> </span>
            </h1>
           
            
            {!! Form::open(array('url' =>'addPrice','class'=>'form form-horizontal text-left','id'=>'regfrm','method'=>'post')) !!}
            
            <div class="inner-wrapper">
            	 
              <div class="form-group">

                <div class="control-label">

                    <label for="video_price" style=" line-height: 2;">Video Price</label>

                </div>

                <div class="control-box" style="position:relative;">
                  <?php 
                  if($artist->VideoPrice == 0.99){
                    $price=99;
                  } 
                  if($artist->VideoPrice > 0.99){
                    $price=$artist->VideoPrice;
                  } 
                  ?>
                    <span style="    /* height: 35px; */
    line-height: 35px;
    /* margin-left: 10px; */
    margin-right: 13px;">($)</span>  
                  <input type="text" name="video_price" id="video_price" class='form-control' value="{{ $price=$artist->VideoPrice}}" >

                  @if($errors->first('video_price'))

                  <p class="label label-danger" style="position:absolute;bottom:-30px;left:0px; "> {{ $errors->first('video_price') }} </p>

                  @endif</div>

                </div>

                <div class="form-group">

                  <div class="control-label"> <label></label> </div>

                  <div class="control-box"> {!! Form::submit('Save',array('class'=>'btn btn-primary center-block', 'id'=>'videosend'))!!}</div>

                </div>
                
              </div>
              {!! Form::close() !!} 
              <br/>
              <h2 class="heading">Occasion(s)</h2>
              
                  <table class="table1 table-fhr1 dataTable" id="example">
                      <thead>
                      <tr>
                          <tH>#</tH><tH>Title</tH><tH>Price</tH><tH>Action</tH>
                      </tr>
                      </thead>
                      <tbody>
                      @foreach($occasions as $index => $occasion)
                          <tr>
                              <td data-order>{{ ++$index }}</td>
                              <td>{{ $occasion->title}}</td>
                              <td>{{ $occasion->price}}</td>
                              <td>
                                  <a class="btn btn-danger"
                                     href="{{URL('occasions/edit/'.$occasion->id)}}">Edit
                                  </a>
                                  <a class="btn btn-danger"
                                     onclick="destroyOccasion({{$occasion->id}})"
                                     href="javascript:void(0)" title="Delete"><i
                                              class="fa fa-trash red"></i>
                                  </a>
                              </td>
                          </tr>
                      @endforeach
                      </tbody>
                  </table>
              

              </div>

            </div>

          </div>

        </div>

      </div>

      @include('admin.common.footer') </section>

      <script type="text/javascript">

        $( document ).ready(function() {

          $( ".dropdown.user-menu" ).click(function() {

            $( '.dropdown.user-menu ul' ).toggle();

          });


           $('#example_paginate').show();
        $(".dropdown.user-menu").click(function () {
            $('.dropdown.user-menu ul').toggle();
        });

        });



    function destroyOccasion(id) {
        if (confirm('Are you sure you want to delete this?')) {
            $.ajax({
                url: '/occasions/delete/' + id,
                type: 'GET',
                success: function (response) {
                    if (response == 200) {
                        alert('Deleted Successfully!');
                        location.reload();
                    } else {
                        alert('Something went wrong!');
                    }
                }
            });
        } else {
            return false;
        }
    }

      </script>

    </body>

    </html>
