@include('admin.common.header') 
<body class="admin artist dashboard">
    <section class="main-page-wrapper">
        <div class="main-content">
            <div id="left-side-wrap"> @include('frontend.userDashboard.layouts.lsidebar') </div>
            <div class="header-section">
            <div class="col-md-2 white">
            <div>
                    <a href="{{URL('admin_dashboard')}}"><span class="logo1"><img src="/images/vrl_logo_nav.png" class="img img-responsive"></span> </a>  
            </div> 
            </div>
            <div class="col-md-1 span-icon">
                <i class="lnr lnr-menu" onclick="contentMove();"></i>
            </div>
                <div class="menu-right">
                    <div class="user-panel-top">
                        <div class="profile_details">
                            <div class="profile_img">
                                <div class="dropdown user-menu"> 
                                    <span class="dropdown-toggle"> <span class="admin-img"> <img src="{{URL('/')}}/images/Artist/1494304967.jpg" alt=""> </span><i class="arrow"></i> </span>
                                    <ul>
                                        <li><a href="#"><i class="icon icon-users"></i><span> View Profile</span></a></li>
                                        <li><a href="#"><i class="fa fa-pencil-square-o"></i><span> Edit Profile</span></a></li>
                                        <li><a href="#"><i class="icon icon-lock"></i><span> Change Password</span></a></li>
                                        <li><a href="{{ URL::route('getLogout') }}"><i class="icon icon-exit"></i><span> Logout</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @yield('body')
        </div>
        @include('admin.common.footer') 
    </section>
    <script type="text/javascript">    
        $(document).ready(function () {
            $(".dropdown.user-menu").click(function () {
                $('.dropdown.user-menu ul').toggle();
            });
        });
    </script>
    <script>
        var baseurl = "{{URL('/')}}";
        $('a[id*="play_btn_"]').on('click', function(){
            $('#mymodal123').modal('show');
            $('.pop-inner').slideDown(500);
            var url = baseurl+$(this).data('url');
            $('#play_vid').attr({'src':url});
        });
        $('.close-pop').on('click', function(){
            $('.pop-inner').slideUp(500);
            $(this).delay(500).parents('#mymodal123').modal('hide');
        });
    </script>
</body>
</html>