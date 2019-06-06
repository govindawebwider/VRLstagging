@extends('frontend.userDashboard.layouts.user-layout')
 
@section('body')
<div class="dashboard_url_wrap">
    <div  class="col-md-12 ">
        <div id="page-wrapper">
            <div class="graphs">
            <div id="breadcrumb"> <a class="tip-bottom" href="/" data-original-title="Go to Home"><i class="lnr lnr-home">Home</i></a></div>
                <h1 class="heading">Dashboard</h1>
                <p class="desc-title">User Dashboard</p>
                <div class="inner_wrapper">
                    <div class="content info_box_wrap clearfix">
                        <div class="row_3">
                            <a class="javascript:void(0)" href="http://localhost:8000/video_requests">
                                <div class="info_box purple">
                                    <div class="content">
                                        <h2 class="content"> 36 <span> All Requests </span> </h2>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="row_3">
                            <a class="javascript:void(0)" href="http://localhost:8000/pending_requests">
                                <div class="info_box yellow">
                                    <div class="content">
                                        <h2> 0 <span> Pending Requests </span> </h2>
                                    </div>
                                </div>
                            </a> 
                        </div>
                        <div class="row_3">
                            <a class="javascript:void(0)" href="http://localhost:8000/deliver_video">
                                <div class="info_box green">
                                    <div class="content">
                                        <h2> 38 <span> Completed </span> </h2>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="row_3">
                            <a class="javascript:void(0)" href="http://localhost:8000/artist_video">
                                <div class="info_box black">
                                    <div class="content">
                                        <h2> 5 <span> Videos </span> </h2>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="content grid_bottom clearfix">
                        <div class="col-sm-8 all_user_list">
                            <div class="info_box">
                                <div class="box box-warning">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"> My Videos</h3>
                                    </div>
                                    <div class="box-body no-padding">
                                        <ul class="users-list clearfix">
                                            <li> <a href="javascript::void()" id="play_btn_0" data-url="/home/vrl/public_html/public/video/watermark/1494303434.mp4"> <span class="img"> <img alt="User Image" src="/images/thumbnails/1494303434.jpg"> <i class="icon icon-play3"></i>  </span> </a> <a href="#" class="users-list-name">Personalized Video Greeting</a> </li>
                                            <li> <a href="javascript::void()" id="play_btn_0" data-url="/home/vrl/public_html/public/video/watermark/1494303515.mp4"> <span class="img"> <img alt="User Image" src="/images/thumbnails/1494303514.jpg"> <i class="icon icon-play3"></i>  </span> </a> <a href="#" class="users-list-name">Personalized Birthday Greeting</a> </li>
                                            <li> <a href="javascript::void()" id="play_btn_0" data-url="/home/vrl/public_html/public/video/watermark/1494303641.mp4"> <span class="img"> <img alt="User Image" src="/images/thumbnails/1494303641.jpg"> <i class="icon icon-play3"></i>  </span> </a> <a href="#" class="users-list-name">Personalized Graduation Congratulations</a> </li>
                                            <li> <a href="javascript::void()" id="play_btn_0" data-url="/home/vrl/public_html/public/video/watermark/1494303789.mp4"> <span class="img"> <img alt="User Image" src="/images/thumbnails/1494303788.jpg"> <i class="icon icon-play3"></i>  </span> </a> <a href="#" class="users-list-name">Personalized Wedding Congratulations</a> </li>
                                            <li> <a href="javascript::void()" id="play_btn_0" data-url="/home/vrl/public_html/public/video/watermark/1512690610.mp4"> <span class="img"> <img alt="User Image" src="/images/thumbnails/1512690610.jpg"> <i class="icon icon-play3"></i>  </span> </a> <a href="#" class="users-list-name">Send a Personalized Holiday Greeting</a> </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 all_artist_list">
                            <div class="info_box">
                                <div class="box box-danger">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Notifications</h3>
                                        <div class="box-tools pull-right"></div>
                                    </div>
                                    <div class="box-body no-padding notification-msg">
                                        <section>
                                            <ul class="list-group"> </ul>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop