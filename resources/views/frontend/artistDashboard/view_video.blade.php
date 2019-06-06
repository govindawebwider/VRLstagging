@include('admin.common.header')







<body class="admin deliver_video">



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
                                            <?php
                                            $fileName = 'images/Artist/'.$artist->profile_path;
                                            $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                                        ->url($fileName);?>
                                            <img src="{{$imageUrl}}" alt="">
                                            {{--<img src="/{{$artist->profile_path}}" alt=""> --}}
                                        </span> <span class="admin-name">{{$artist->Name}}</span><i class="arrow"></i> </span>



                                    <ul>

                                        @if(session('current_type') == 'Artist')
                                        <li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>Switch to Admin</span></a></li>
                                        @endif

                                        <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{ URL($artist->profile_url)}}"> <i class="icon icon-users"></i> <span>view Profile</span> </a> </li>



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



                            <h1 class="heading">My Video</h1>



                            @if(count($video)>0)



                            <div class="inner-wrap">



                                <?php //dd($video);?>



                                <div class="content clearfix"> @foreach ($video as $video_data)



                                    <div class="row">



                                        <div class="artists">



                                            <div class="table-responsive dataTables_wrapper">



                                                <table class="">



                                                    <thead>



                                                    </thead>



                                                    <tbody>

                                                        <tr>



                                                            <td style="font-weight:bold;">Request ID </td>



                                                            <td>{{$video_data->id}}</td>



                                                        </tr>
                                                        <tr>
                                                            <td style="font-weight:bold;">Occasion </td>
                                                            <td>{{$video_data->title}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-weight:bold;">Message</td>
                                                            <td>{{$video_data->description}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Video
                                                            </td>
                                                            <td><div class="video_wrp">
                                                                    <?php
                                                                        $rest = '';
                                                                        $fileName = 'requested_video/watermark/'.$video_data->url;
                                                                        $rest = \Illuminate\Support\Facades\Storage::disk('s3')
                                                                            ->url($fileName);?>
                                                                    <video style="width:100%;max-height:575px;" id="myvideo" controls autoplay>
                                                                        <!--  <video style="width:100%;max-height:575px;" id="myvideo"> -->
                                                                        <source src="<?php echo $rest; ?>" type="video/mp4"></source>
                                                                        <source src="<?php echo $rest; ?>" type="video/webm"></source>
                                                                        <source src="<?php echo $rest; ?>" type="video/ogg"></source>
                                                                        <source src="<?php echo $rest; ?>" type="video/x-msvideo"></source>
                                                                        <source src="<?php echo $rest; ?>" type="video/x-flv"></source>
                                                                        <source src="<?php echo $rest; ?>" type="application/x-mpegURL"></source>
                                                                        <source src="<?php echo $rest; ?>" type="video/quicktime"></source>
                                                                        <source src="<?php echo $rest; ?>" type="video/3gpp"></source>
                                                                        <source src="<?php echo $rest; ?>"></source>
                                                                    </video>
                                                                </div></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-weight:bold;">recipient Name</td>
                                                            <td>
                                                                {{$requestInfo->Name}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-weight:bold;">recipient  Email</td>

                                                            <td>
                                                               {{$requestInfo->requestor_email}}
                                                            </td>

                                                        </tr>
                                                         <tr>
                                                            <td style="font-weight:bold;">Request Song Name </td>

                                                            <td>
                                                                {{$requestInfo->song_name}}
                                                            </td>

                                                         </tr>
                                                         
                                                         <tr>
                                                            <td style="font-weight:bold;">Sender Name </td>

                                                            <td>
                                                                {{$requestInfo->sender_name}}
                                                            </td>

                                                         </tr>
                                                         
                                                          
                                                         
                                                          <tr>
                                                            <td style="font-weight:bold;">Sender Email  </td>

                                                            <td>
                                                                {{$requestInfo->sender_email}}
                                                            </td>

                                                         </tr>
                                                         
                                                          <tr>
                                                            <td style="font-weight:bold;">Sender voice pronunciation  </td>

                                                            <td>
                                                                @if(strlen($requestInfo->sender_voice_pronunciation) < 1) Not Filled @else $requestInfo->sender_voice_pronunciation @endif
                                                            </td>

                                                         </tr>
                                                         
                                                          <tr>
                                                            <td style="font-weight:bold;">Request Status  </td>

                                                            <td>
                                                                {{$requestInfo->RequestStatus}}
                                                            </td>

                                                         </tr>
                                                       <!-- <tr>
                                                         <td>
                                                        <?php //echo $str = $video_data->url;$rest = substr($str,22); ?>
                                                           <object type="application/x-vlc-plugin" data="https://www.videorequestline.com/<?php //echo $rest; ?>" width="400" height="300" id="video1">
                                                             <param name="movie" value="https://www.videorequestline.com/<?php //echo $rest;  ?>"/>
                                                             <embed type="application/x-vlc-plugin" name="video1"
                                                             autoplay="no" loop="no" width="400" height="300"
                                                             target="https://www.videorequestline.com/<?php //echo $rest;  ?>" />
                                                             <a href="https://www.videorequestline.com/<?php //echo $rest;  ?>">Download Video1</a>
                                                           </object>
                                                         </td>
                                                       </tr> -->



                                                    </tbody>



                                                </table>



                                            </div>



                                        </div>



                                    </div>



                                    @endforeach



                                    <div class="pagination"> {!! $video->render()!!} </div>



                                </div>



                                @else <span>



                                    <h1>No delivered videos yet</h1>



                                </span><br/>



                                @endif </div>



                        </div>



                    </div>



                </div>



            </div>



            @include('admin.common.footer') </section>







    <script type="text/javascript">



        $(document).ready(function () {



            $(".dropdown.user-menu").click(function () {



                $('.dropdown.user-menu ul').toggle();



            });



        });



    </script>

    <style>
        .video_wrp{ width:300px; height:300px;}
    </style>

</body>



</html>