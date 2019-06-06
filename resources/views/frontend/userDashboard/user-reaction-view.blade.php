@extends('frontend.userDashboard.layouts.user-layout')

@section('body')

<div class="dashboard_url_wrap">
    <div  class="col-md-12 ">
        <div id="page-wrapper">
            <div class="change_pass_form">
                <div class="graphs">
                    <h1 class="heading">Videos list</h1>
                    <p class="desc-title"></p>
                    <div class="inner_wrapper">
                        <div class="content grid_bottom clearfix">
                            <div class="row_4 all_user_list">
                                <div class="info_box">
                                    <div class="box box-warning">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">All Video</h3>
                                            <div class="box-tools pull-right"> <span class="label label-warning"><a class="uppercase" href="http://localhost:8000/videos">{{count($reactionvideos)}} Video</a></span> </div>
                                        </div>
                                        <!-- /.box-header -->
                                        <div class="box-body no-padding">
                                            <ul class="users-list clearfix">
                                                @foreach($reactionvideos as $reactions)
                                                <li> 
                                                    <a href="javascript:void(0)" id="play_btn_{{$reactions->id}}" data-url="{{$reactions->VideoURL,28}}"> 
                                                        <span class="img"> 
                                                            <img alt="User Image" src="/images/thumbnails/1512690610.jpg"> 
                                                            <i class="icon icon-play3"></i> 
                                                        </span> </a>
                                                     <a href="#" class="users-list-name"></a>
                                                    {{$reactions->VideoName}}
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div> 
                                        <div class="box-footer text-center"> <a class="uppercase" href="http://localhost:8000/videos">View All Video</a> </div>
                                         
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
<div class="modal fade" tabindex="-1" role="dialog" id="mymodal123">    
      <div class="modal-body">
       <div id="videoContainer">
            <div class="pop-inner"> <i class="icon icon-cancel-circle close-pop"></i>
                <iframe frameborder="0" allowfullscreen wmode="Opaque" id="play_vid"></iframe>
            </div>
        </div>
      </div>    
 </div>
<style>   
.pop-inner {
    /* display: none; */
    position: absolute;
    left: 0;
    right: 0;
    max-width: 500px;
    background-color: #333;
    border-radius: 5px;
    padding: 5px 5px 0;
    height: auto;
    margin: 50px auto;
}
iframe#play_vid {
    width: 100%;
    min-height: 400px;
}
.icon-cancel-circle.close-pop {
    color: #fff;
    right: -10px;
    position: absolute;
    top: -10px;
    font-size: 24px;
    cursor: pointer;
}
</style>
@stop