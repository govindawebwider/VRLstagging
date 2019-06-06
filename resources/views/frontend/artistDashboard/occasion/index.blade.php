
{{--
  |  Occasion index page
  |
  |  @package resourses/views/frontend/artistDashboard/occasion
  |
  |  @author Azim Khan <azim@surmountsoft.in>
  |
  |  @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
  |
--}}
@include('admin.common.header')
<body class="admin video_request">
<section class="main-page-wrapper">
    <div class="main-content">
        <div id="left-side-wrap">
            @include('frontend.artistDashboard.layouts.lsidebar')
        </div>
        <div class="header-section">
            <div class="top-main-left">
                <a href="{{URL('Dashboard')}}"><span class="logo1 white"><img src="/images/vrl_logo_nav.png" class="img img-responsive"></span> </a>
                <a href="javascript:void(0)" class="toggle menu-toggle"><i class="lnr lnr-menu"></i></a>
            </div>
            <div class="menu-right">
                <div class="user-panel-top">
                    <div class="profile_details">
                        <div class="profile_img">
                            <div class="dropdown user-menu"><span class="dropdown-toggle"> <span class="admin-img"> <img
                                                src="{{url('images/Artist/').'/'. $users->profile->profile_path}}"
                                                alt=""> </span> <span
                                            class="admin-name">{{$users->profile->Name}}</span> <i
                                            class="arrow"></i> </span>
                                <ul>
                                    @if(session('current_type') == 'Artist')
                                        <li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>Switch to Admin</span></a>
                                        </li>
                                    @endif
                                    <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"><a
                                                href="{{ URL($users->profile->profile_url)}}"> <i
                                                    class="icon icon-users"></i> <span>view Profile</span> </a></li>
                                    @if($users->admin_link=='yes')
                                        <li class="{{ Request::is('Switch to Admin') ? 'active' : '' }}"><a
                                                    href="{{URL('/login_admin')}}"> <i class="icon icon-users"></i>
                                                <span>Switch to Admin</span> </a>
                                        </li>
                                    @endif
                                    <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"><a
                                                href="{{URL('ProfileUpdate')}}"> <i class="icon icon-users"></i> <span>Edit Profile</span>
                                        </a></li>
                                    <li class="{{ Request::is('change-password') ? 'active' : '' }}"><a
                                                href="{{URL('change-password')}}"> <i class="icon icon-lock"></i> <span>Change Password</span>
                                        </a></li>
                                    <li class="{{ Request::is('getLogout') ? 'active' : '' }}"><a
                                                href="{{ URL::route('getLogout') }}"> <i class="icon icon-exit"></i>
                                            <span>Logout</span> </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="video_request_wrap">
            <div class="col-md-12 ">
                <div id="page-wrapper">
                    <div class="graphs">
                        <div id="breadcrumb"><a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i
                                        class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right"
                                                                             aria-hidden="true"></i><a class="current"
                                                                                                       href="/occasions">Occasion(s)</a>
                        </div>
                        <h1 class="heading">Occasion(s)<span><a style="float:right" href="{{URL('/occasions/create')}}">  <input
                                            class="btn btn-primary" type="button" name="artist_csv" value="Create"></a> </span>
                        </h1>
                        @if(Session::has('success'))
                            <div class="alert alert-success"><span class="close" data-dismiss="alert">&times;</span>
                                <span class="text-center"> {{Session::get('success') }}</span></div>
                        @endif
                        @if(Session::has('error'))
                            <div class="alert alert-danger"><span class="close" data-dismiss="alert">&times;</span>
                                <span class="text-center"> {{Session::get('error') }}</span></div>
                        @endif
                        <div class="content grid_bottom clearfix">
                            <div class="col-sm-12">
                                <div class="box table-info">
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
            </div>
        </div>
    @include('admin.common.footer')
</section>
<script type="text/javascript">
    $(document).ready(function () {
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
