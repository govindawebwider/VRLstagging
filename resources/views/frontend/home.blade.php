<!DOCTYPE html>
<html lang="en">
<?php $page = 'HOME'; ?>
<script language="JavaScript">
    function disableCtrlKeyCombination(e)
    {
        //list all CTRL + key combinations you want to disable
        var forbiddenKeys = new Array("a", "s", "c");
        var key;
        var isCtrl;
        if(window.event)
        {
            key = window.event.keyCode;     //IE
            if(window.event.ctrlKey)
                isCtrl = true;
            else
                isCtrl = false;
        }
        else
        {
            key = e.which;     //firefox
            if(e.ctrlKey)
                isCtrl = true;
            else
                isCtrl = false;
        }
        //if ctrl is pressed check if other key is in forbidenKeys array
        if(isCtrl)
        {
            for (i = 0; i < forbiddenKeys.length; i++)
            {
                //case-insensitive comparation
                if (forbiddenKeys[i].toLowerCase() == String.fromCharCode(key).toLowerCase())
                {
//                                    alert("Key combination CTRL + "
//                                                + String.fromCharCode(key)
//                                                + " has been disabled.");                                    
                    return false;
                }
            }
        }
        return true;
    }
</script>
<script src="{{ asset('js/jstz.min.js') }}"></script>
<style>
    #videoContainer {
        position: fixed;
        top: 0;
        right: 0;
        left: 0;
        bottom: 0;
        overflow: hidden;
        background: rgba(0,0,0,.6);
        z-index: 99999999;
    }
    .pop-inner {
        display: none;
        position: absolute;
        left: 0;
        right: 0;
        width: 500px;
        background-color: #333;
        border-radius: 5px;
        padding: 5px;
        height: 400px;
        margin: 50px auto;
    }
    .pop-inner iframe {
        width: 100%;
        height: 100%;
        border-radius: 5px;
    }
    .close-pop {
        color: #fff;
        right: -10px;
        position: absolute;
        top: -10px;
        font-size: 24px;
        cursor: pointer;
    }
    .video .img img {
        min-width: 300px;
        height: 100%;
    }
</style>
<style>
body {font-family: Arial, Helvetica, sans-serif;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background:none;
  margin: auto;
  padding: 00px;
  border:0px;
  width:400px;
}
.modal-content video{
    display:block;
}

/* The Close Button */
.close {
    position: absolute;
    z-index: 9999999;
    width: 30px;
    top: 8px;
    right: 8px;
    opacity: 1;
    text-shadow: 0px 0px 0px;
    height: 30px;
    text-align: center;
    display: inline-block;
    background: rgba(00,00,00,0.4);
    border-radius: 50%;
    color: #fff;
    line-height: 30px;
}
.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
</style>

<head>
    @include('frontend.common.head')
    <script language=JavaScript>
        var message="Function Disabled!";
        function clickIE4()
        {
            if (event.button==2)
            {
                //alert(message); return false;
            }
        }
        function clickNS4(e){
            if (document.layers||document.getElementById&&!document.all){
                if (e.which==2||e.which==3){
                    //alert(message); return false;
                }
            }
        }
        if (document.layers){
            document.captureEvents(Event.MOUSEDOWN);
            document.onmousedown=clickNS4;
        } else if (document.all&&!document.getElementById){
            document.onmousedown=clickIE4;
        }
        document.oncontextmenu=new Function("return false")
    </script>
 
    <style>
        html,
        body {
        margin: 0;
        width: 100%;
        height: 100%
        }
        .bannerAnimation {
           position: absolute;
           top: 0;
           z-index: 9;
           background: #fff;
           height: 100%;
           width: 100%;
        }
        .bannerAnimation video {
           max-height: 100%;height: 100%;
           max-width: 100%;
           object-fit: fill;border-radius:0px;
        }
        .skip{
            position: absolute;
    margin: auto;
    bottom: 1%;
    right: 1%;
    font-family: "Avenir-Regular";
    background: transparent;
    color: white;
    text-align: center;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    line-height: 19px;
    padding: 8px 30px;
    border: 0;
    outline: 0;
    z-index: 99;
    border-radius: 20px;
        }
 .banner .header{
    z-index: 99;
    position: relative;
 }
</style>

</head>
@if (count($errors) > 0)
    <body class="cb-page home active video-request-form-active" ondragstart="return false" onselectstart="return false" onkeypress="return disableCtrlKeyCombination(event);" onkeydown="return disableCtrlKeyCombination(event);" >
    @elseif(Session::has('error'))
        <body class="cb-page home active video-request-form-active" ondragstart="return false" onselectstart="return false" onkeypress="return disableCtrlKeyCombination(event);" onkeydown="return disableCtrlKeyCombination(event);" >
        @else
            <body class="cb-page home" onkeypress="return disableCtrlKeyCombination(event);" onkeydown="return disableCtrlKeyCombination(event);" >
            @endif
            
            <div class="banner" >
                    @include('frontend.common.header')
                   <!-- <div class="bannerAnimation" id="bannerAnimation">
                            <video autoplay muted id="myVideo"  >
                                <source src="video/bannerAnimation.mp4" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>  
                        </div> -->
                <div class="container" id="banner">
                    <div class="row  ">
                        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 absoluteText ">
                            <h1 class="  text-center purple-text" id="letter-spacing">
                                <div class="header_heading">
                                    <span class="Personalized">Personalized</span>
                                    <span class="white-text">Video</span>
                                </div>
                            </h1>
                            <h1 class="  text-center purple-text" id="letter-spacing">
                                <div class="header_heading">
                                    <span class="From">From</span>
                                    <span class="white-text">Your</span>
                                    <span class="From">Favourite</span>
                                    <span class="white-text">Celebrities</span>
                                </div>
                            </h1>
                            <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12  text-center Talentspace">
                                <?php $url = URL('view-all-artist#main-content'); ?>
                                <a href="{{$url}}" class="Browsetelent" id="Talent">Browse Talent</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hot-spot"><!--<img src="images/boy.jpg">-->
                   
                        <div class="slide">
                            <video id="video1" muted autoplay loop  class="profilevideo slider-video"  >
                                <source src="/video/slidevideo/bannerAnimation.mp4" type="video/mp4" />
                                <source src="/video/slidevideo/bannerAnimation.mp4" type="video/webm" />
                                <source src="/video/slidevideo/bannerAnimation.mp4" type="video/ogg" />
                            </video>
                            <!-- <div id="overlay1" class="overlay-content">
                                 <div class="play-button"></div>
                             </div>-->
                        </div> 
                        <!--<div class="slide-arrow left"></div>
                        <div class="slide-arrow right"></div>--> 
                </div>
            </div>
            <?php $s3 = \Illuminate\Support\Facades\Storage::disk('s3');?>
            <section id="main-content">
                <div class="container">
                    <?php
                    if(count($artists))
                    {
                    ?>
                    <div class="row space">
                        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 ">
                            <div class="topchart col-md-8 col-sm-8 col-xs-9 col-lg-8">
                                <div class="head-text chartHeadING"><div class="chartHead"><span class="chartHead-text">CHART</span> <span class="purple-text">TOPPERS</span> </div><span class="shadow">CHART TOPPERS</span></div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-3 col-lg-4 responsivechart">
                                <div class="chartHeadING ">
                                    <?php $url = URL('view-all-artist#main-content'); ?>
                                    <a href="{{$url}}" class="btn navbar-btn loginshadow pull-right" id="browse">Browse All</a>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 chartartist_top" id="owl-carousel00">
                            @foreach ($artists as $key=>$artist)
                                <?php
                                $profilePath = $artist->profile_path;
                                ?>
                                <div class="item">
                                    <a href="{{'/'.$artist->profile_url }}" class="artist-name">
                                        <div class="">
                                            <div class="">
                                                <div  class="hex" >
                                                    @if($artist->profile_path != "")
                                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="111" height="121" viewbox="0 0 138.56406460551017 160"
                                                             style="filter: drop-shadow(rgba(0, 0, 0, 0.2) 0px 2px 8px);">
                                                            <defs>
                                                                <pattern id="bias{{$key}}" height="100%" width="100%" patternContentUnits="objectBoundingBox" viewBox="0 0 1 1" preserveAspectRatio="xMidYMid slice">
                                                                    <image height="1" width="1"  preserveAspectRatio="xMidYMid slice" xlink:href="{{ $s3->url('images/Artist/'.$profilePath) }}" />
                                                                </pattern>
                                                            </defs>
                                                            <path fill="url(#bias{{$key}})" fill="#fff" d="M51.961524227066306 9.999999999999998Q69.28203230275508 0 86.60254037844385 10L121.2435565298214 30Q138.56406460551017 40 138.56406460551017 60L138.56406460551017 100Q138.56406460551017 120 121.24355652982139 130L86.60254037844386 150Q69.28203230275508 160 51.96152422706631 150L17.32050807568877 130Q0 120 0 100L0 60Q0 40 17.320508075688775 30Z"></path>
                                                        </svg>
                                                    <!--<img src="{{ url('/images/Artist/').'/'.$artist->profile_path}}" class="img-responsive artist" alt=""/>-->
                                                    @else
                                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="139" height="160" viewbox="0 0 138.56406460551017 160" style="filter: drop-shadow(rgba(0, 0, 0, 0.2) 0px 2px 8px);"><path fill="gray" d="M51.961524227066306 9.999999999999998Q69.28203230275508 0 86.60254037844385 10L121.2435565298214 30Q138.56406460551017 40 138.56406460551017 60L138.56406460551017 100Q138.56406460551017 120 121.24355652982139 130L86.60254037844386 150Q69.28203230275508 160 51.96152422706631 150L17.32050807568877 130Q0 120 0 100L0 60Q0 40 17.320508075688775 30Z"></path></svg>
                                                    @endif
                                                </div>
                                            </div>
                                            <h4 class="artist artistName bold text-center">{{  ($artist->Name)}}</h4>
                                        </div>
                                    </a>
                                </div>

                            @endforeach
                        </div>
                    </div>
                    <?php
                    }
                    ?>

                    <div class="row space">
                        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                            <div class="col-md-8 col-sm-12 col-lg-8 col-xs-12">
                                @if(count($latest_videos))
                                    <div class="latest_videos head-text">
                                        <div class="chartHead">
                                            <span class="chartHead-text">LATEST</span>
                                            <span class="purple-text">VIDEOS</span>
                                            <span class="shadow">LATEST VIDEOS</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 chartartist_top " id="owl-carousel01">  <!-- videosection -->
                            @foreach ($latest_videos as $key=>$latest_video)
                                <?php $url =  $s3->url('requested_video/watermark/thumbnail/'.$latest_video->video_url);
                                $video_title = explode(' ',trim($latest_video->video_title));
                                $video_title = $video_title[0];

                                $user_name = explode(' ',trim($latest_video->user_name));
                                $user_name = $user_name[0];

                                ?>

                                <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12item">
                                    <div class="wrapper videowrapper">
                                        <div class="latest_videowrap">
                                            <video id="myVideo{{$key}}" class="stopvideo">
                                                <source src="{{ $url }}#t=1" type="video/mp4">
                                                <source src="{{ $url }}#t=1" type="video/ogg">
                                                Your browser does not support HTML5 video.
                                            </video>
                                            <div id="overl{{$key}}" class="overlay-desc"></div>
                                            <div class="playone" onclick="play({{ $key }})" id="{{ $key }}"></div>
                                            <div class="pause" onclick="pause({{$key}})" id="pause{{ $key }}" style="display: none;"></div>
                                        </div>
                                        <h4 class="bold videoName text-center">{{($video_title)}}{{'@'.($user_name)}}
                                        </h4>
                                        <p class=" text-center" TITLE="{{($latest_video->video_description)}}">{{ (trim(str_limit($latest_video->video_description, 37)))}}
                                        </p>
                                        <div class="bookbutton" id="bookbutton">
                                            <a href="{{ '/'.$latest_video->artist_profile_url }}"  class="bookNow " id="book">BOOK {{ ($latest_video->artist_name) }} Now</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="DownloadAPP">
                    <div class="container">
                        <div class="col-md-12 col-sm-12 col-lg-12 space">
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="head-text chartHeadING"> <span class="DOWNLOAD-text">DOWNLOAD THE APP</span><span class="shadow1">MOBILE APP</span></div>
                                <p class="pera white-text">Book, Share and Explore personalized Videos from your favorite people. Download VRL and try it yourself.</p>
                                <a href="https://play.google.com/store/apps/details?id=com.videorequestlines.vrl" target="_blank"><div class="col-md-4 col-sm-4 col-lg-4 app-btn" ><img class="img-responsive" src="/images/btn_playstore.png"></div></a>
                                <a href="https://itunes.apple.com/us/app/vrl/id1422069505?ls=1&mt=8" target="_blank"><div class="col-md-4 col-sm-4 col-lg-4 app-btn"><img class="img-responsive" src="/images/btn_appstore.png"></div></a>
                                <div class="col-md-4 col-sm-4 col-lg-4 giftpack"><img class="img-responsive" src="/images/logo_box.png"></div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <img src="/images/Image_from_iOS.png" class="imgshadow">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">

                    <!-- <div class="row paddings  ">
                       <h1 class="amazingtalent text-center bold">
                         We are looking for amazing talents like you</h1>
                       <div class="text-center">
                        <a href="" data-toggle="modal" data-target="#exampleModalCenterJOIN" class="JOINUS">JOIN US NOW!</a>
                       </div>
                     </div>-->

                    <div class="container">
                        @if(count($users)>0)
                            <div class="row space">
                                <div class="col-md-12 col-sm-12 col-lg-12">

                                    <div class="WHATPEOPLE  head-text chartHeadING text-center"><div class="chartHead"><span class="chartHead-text latterspec">WHAT PEOPLE</span> <span class="purple-text latterspec">ARE SAYING</span> </div><span class="shadow">WHAT PEOPLE ARE SAYING</span></div>

                                </div>
                                <div class="col-md-12 col-sm-12 col-lg-12 paddings videoplayer ">
                                    {{--*/ $i = 0; /*--}}
                                    <?php $kk=0; ?> 
                                    @foreach($users as $key=>$testimonial)

                                        <div class="col-md-4  col-sm-6 col-lg-4 small video-display">
                                            <div class="wrapper peoplesays">
                                                <div class="video">
                                                    {{--<video id="myVideo1{{$key}}">--}}
                                                    {{--<source src="{{ url('/uploads/reaction-videos/')}}/{{$testimonial->VideoURL }}" type="video/mp4">--}}
                                                    {{--Your browser does not support HTML5 video.--}}
                                                    {{--</video>--}}
                                                    {{--<div id="overl{{$key}}" class="overlaypeoplesays"></div>--}}
                                                    {{--<div class="play" data-toggle="modal" data-target="#myModal{{ $key }}" ></div>--}}
                                                    <?php
                                                    $videoUrl = ($testimonial->type == 1 || $testimonial->type == 2)
                                                    ? $testimonial->VideoURL : $s3->url('uploads/reaction-videos/'.$testimonial->VideoURL);
                                                    $videoThumbnail = ($testimonial->type == 1 || $testimonial->type == 2)
                                                    ? $testimonial->thumbnail : $s3->url('images/thumbnails/' . $testimonial->thumbnail);
                                                    ?>
                                                    <a href="javascript:void(0)" id="myBtn<?php echo $kk; ?>">
                                                        <span class="img"> <img alt="User Image" src="{{$videoThumbnail}}">
                                                            <div class="play">

                                                            </div>
                                                        </span>
                                                    </a>
                                                    {{--*/ $i++; /*--}}
                                                </div>
                                            </div>

                                            <!-- Modal -->
                                <div id="myModal<?php echo $kk; ?>" class="modal" >
                                 <script>
                                
                                var btn = document.getElementById("myBtn<?php echo $kk; ?>");

                                btn.onclick = function() {
                                    
                                  $('#myModal<?php echo $kk; ?>').css('display','block');
                                }

                                function closefun<?php echo $kk; ?>(i) {
                                  $('#myModal'+i).css('display','none');
                                  var vid = document.getElementById("vid"+i); 
                                  vid.pause(); 
                                  
                                } 

                                </script>
                                  <!-- Modal content -->
                                  <div class="modal-content">
                                    <span class="close" onclick="closefun<?php echo $kk; ?>(<?php echo $kk; ?>);">&times;</span>
                                     <video id="vid<?php echo $kk; ?>" width="400" controls controlsList="nodownload">
                                        <source src="{{$videoUrl}}" type="video/mp4">
                                     </video>
                                  </div>

                                </div>
                                
                                        </div>

                                        <?php $kk++; ?>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    <div id="videoContainer" style="display:none;">
                        <div class="pop-inner"> <button type="button" class="close close-pop" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <iframe frameborder="0" allowfullscreen wmode="Opaque" id="play_vid"></iframe>
                        </div>
                    </div>
            </section>
            @include('frontend.common.footer')
            <script type="text/javascript" charset="utf-8">
                $(document).ready(function () {
                    $('a[id*="play_btn_"]').on('click', function () {
                        var url = $(this).data('url');
                        $('#videoContainer').show();
                        $('.pop-inner').slideDown(300, function () {
                            $('#play_vid').attr({'src': url});
                        });
                    });
                    $('.close-pop').on('click', function () {
                        $('.pop-inner').slideUp(200, function () {
                            $('#play_vid').attr({'src': ''});
                            $(this).delay(200).parents('#videoContainer').hide();
                        });
                    });
                    var tz = jstz.determine();
                    $('#timezone').val(tz.name());
                });
                $(document).keydown(function (e) {
                    if (e.keyCode == 27) {
                        $('.pop-inner').slideUp(200, function () {
                            $('#play_vid').attr({'src': ''});
                            $(this).delay(200).parents('#videoContainer').hide();
                        });
                    }
                });
                function play(id){
                    $('.my_video_request').attr('class', 'stopvideo');
                    $('.stopvideo').removeClass('my_video_request');
                    $("#myVideo"+id).attr('class', 'my_video_request');
                    $('.my_video_request')[0].play();
                    $('.stopvideo').each(function() {
                        $(this).get(0).pause();
                    });
                    $(".pause").hide();
                    $(".playone").show();
                    $(".overlay-desc").show();
                    $("#pause"+id).show();
                    $("#"+id).hide();
                    $("#overl"+id).hide();
                }
                function pause(id) {
                    document.getElementById("myVideo"+id).pause();
                    $("#pause"+id).hide();
                    $("#"+id).show();
                    $("#overl"+id).show();
                }
                function play1(id){
                    document.getElementById("myVideo1"+id).play()
                    $("#pause1"+id).show();
                    //$("#id"+id).hide();
                    $("#myVideo1"+id).css('opacity','1');
                }
                function pause1(id) {
                    document.getElementById("myVideo1"+id).pause();
                    $("#pause1"+id).hide();
                    $("#id"+id).show();
                    //$("#myVideo1"+id).css('opacity','0.7');
                }
                $('.video-display').mouseover(function(){
                    $(this).addClass('large').removeClass('small');
                })
                $('.video-display').mouseout(function(){
                    $(this).addClass('small').removeClass('large');
                })

                function pausemodalvideo(id){
                    document.getElementById("modalvideo"+id).pause();
                    //document.getElementById("modalvideo"+id).pause();
                }
                $(window).ready(function() {
                    $(window).on('mousewheel DOMMouseScroll scroll',function() {
                        $('.my_video_request').each(function() {
                            $(this).get(0).pause();
                        });
                        $(".pause").hide();
                        $(".playone").show();
                        $(".overlay-desc").show();
                    });
                });
            </script>
            <script type="text/javascript" src="js/videoslide.js"></script>
            </body>
</html>
