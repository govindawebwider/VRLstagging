<?php
if(isset($_REQUEST['search_query']))
{
$keyname=$_REQUEST['search_query'];
}
else
{
$keyname='';	
}
?>
<!DOCTYPE html>
<html lang="en">
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
 </script>   <style>
 html,
body {
   margin: 0;
  width: 100%;
  height: 100%
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
  <div class="banner">
          @include('frontend.common.header')
          <div class="container">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12   absoluteText ">
                <h1 class="text-center purple-text" id="letter-spacing">
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
              </div> 
            </div> 
           </div> 
          <!--<div class="boy-img"></div>-->
        <div class="hot-spot"><!--<img src="images/boy.jpg">-->
              <div class="video-slider"> 
                  <div class="slide">
                    <video id="video1"   muted autoplay loop   onended="nextSlide()"  class="profilevideo slider-video"  >
                          <source src="/video/slidevideo/bannerAnimation.mp4" type="video/mp4" />
                          <source src="/video/slidevideo/bannerAnimation.mp4" type="video/webm" />
                          <source src="/video/slidevideo/bannerAnimation.mp4" type="video/ogg" />
                      </video>
                    <!-- <div id="overlay1" class="overlay-content">
                          <div class="play-button"></div>
                      </div>-->
                  </div>
                  <div class="slide">
                    <video id="video2"   onended="nextSlide()" class="profilevideo slider-video"  >
                          <source src="/video/slidevideo/bannerAnimation.mp4" type="video/mp4" />
                          <source src="/video/slidevideo/bannerAnimation.mp4" type="video/webm" />
                          <source src="/video/slidevideo/bannerAnimation.mp4" type="video/ogg" />
                      </video>
                    <!-- <div id="overlay2" class="overlay-content">
                          <div class="play-button"></div>
                      </div>-->
                  </div>
                  <!--<div class="slide-arrow left"></div>
                  <div class="slide-arrow right"></div>-->
              </div> 
        </div> 
 </div>
      <?php $s3 = \Illuminate\Support\Facades\Storage::disk('s3');?>
  <section id="main-content">
        <div class="container artistfluad">
          <div class="row space">
            <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
              <div class="col-md-3 col-sm-4 col-xs-12  left_panel">
                <div class="panel panel-default category-panel">
                  <h4 class="menutitle paddings"><a href="view-all-artist#main-content" style="text-decoration: none; cursor: pointer; color: #b135cd;">
ALL CATEGORIES
</a></h4>
{{-- <div class="OCCUPATION_left">--}}
{{-- <div class="OCCUPATION_left">--}}
<!-- <div class="menutitle2 accordion">OCCUPATION</div>  -->
<?php
$title = app('request')->input('title');
?>
<ul class="  category show">
@foreach ($catData as $category)

<li>
  <a href="view-all-artist?title={{$category->title}}#main-content" style="color: {{($title == $category->title) ? '#b135cd' : '#a3a3a3'}};">
      {{ (ucfirst($category->title))}}
  </a>
</li>
@endforeach
</ul>
<!--<div class="menutitle2 accordion">MEDIUM</div>
<ul class="category">
<li><a href="#"> Instagram </a></li>
<li><a href="#"> Youtube </a></li>
<li><a href="#"> TV </a></li>
<li><a href="#"> Twitter </a></li>
</ul>
<div class="menutitle2 accordion">EVENT</div>
<ul class="category">
<li><a href="#"> New Year </a></li>
<li><a href="#"> Birthday </a></li>
<li><a href="#"> Mothers Day </a></li>
</ul>-->
{{--</div>--}}
</div>
</div>
<div class="col-md-9 col-xs-12  col-sm-8 col-lg-9 ">
    <?php $url = '/search_artist?#main-content';?>
<form method="get" action="{{$url}}" >
<!--  <input type="text" name="search_query" class="icon-input form-control search_artist" placeholder="Search By Actor Name"> -->

<div class="rectangle-2-copy">
<img class="oval-43" src="/images/search_icon.png" />
<input type="text" name="search_query" class="search-by-actor-name" placeholder="Search By Name" onfocus="this.placeholder = ''"
onblur="this.placeholder = 'Search By Name'" value="<?php echo $keyname; ?>">
</div>

</form>
<div class="col-md-12 col-sm-12  col-xs-12 col-lg-12">
<div class="col-md-6 col-sm-12  col-xs-12 col-lg-6">
<div class="actors" style="margin-bottom:20px;">
  <?php
  if (!isset($title)) {
      $title = 'All Categories';
  }
  $title=(ucfirst($title));
  $title=explode("?",$title);
  echo $title[0];
?>
</div>
</div>
<?php
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$currentUrl = substr($actual_link, 0 , strpos($actual_link, "="));
$nextPage = $artist->currentPage()+1;
$prePage = $artist->currentPage()-1;

if($artist->currentPage() == 1 ){
$preUrl = $actual_link;
if($artist->lastPage() == $artist->currentPage()){
$nextUrl= $actual_link;
}else{
$nextUrl=$actual_link.'?page=2';
}
}
else{
if($artist->lastPage() == $artist->currentPage()){
$nextUrl= $actual_link;
}else{
$nextUrl= $currentUrl.'='.$nextPage;
}
if($artist->currentPage() == 2){
$current = substr($actual_link, 0 , strpos($actual_link, "?"));
$preUrl=$current;
}else{
$preUrl=$currentUrl.'='.$prePage;
}
}
$pp=count($artist);
?>
<div class="col-md-6 col-xs-12  col-sm-12 col-lg-6">
<span class="show-data pull-right"> Showing {{ $artist->currentPage()}} of {{$artist->lastPage()}}
<?php if($pp > 16){ ?>
<a href="{{$nextUrl}}#main-content"><label class="next-page pull-right"></label></a>
<a href="{{$preUrl}}#main-content"><label class="prev-page pull-right"></label></a>
<?php } ?>
</span>
</div>
</div>
<!-- <div class="col-md-12 col-sm-12  col-xs-12 col-lg-12">
<div class="col-md-12 col-sm-12  col-xs-12 col-lg-12">
<span class="sort pull-right" style="color:#8f8f8f">SORT:<b style="color: #b135cd;"> BY POPULARITY</b></span>

</div>
</div> -->
<hr id="hr-less">
<div class="col-md-12 col-sm-12  col-xs-12 col-lg-12" style="padding-left: 0px;">
<?php

if($pp > 0)
{
?>
@foreach ($artist as $key=>$artists)
<?php
$key=$key+1;
?>
@if ($key % 4 == 1)
<div class="row">
@endif

<div class="col-md-3 col-sm-4 col-xs-12 col-lg-4 col-mdprofile" >
<a href="{{ $artists->profile_url }}" class="artist-name">
<!-- <div class="hexa artist">
<div class="hex1">
  <div class="hex2" style="background:url('/{{ url('/images/Artist/').'/'.$artists->profile_path}}'); background-size: contain;">
    @if($artists->profile_path != "")
    <img src="{{ url('/images/Artist/').'/'.$artists->profile_path}}" class="img-responsive artist" align="middle" alt=""/>
    @else
    <img src="/images/Artist/default-artist.png" class="img-responsive artist" alt="{{ $artists->Name }}" >
    @endif
  </div>
</div>
</div> -->
<div class="hex">
@if($artists->profile_path != "")
  <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="119" height="129" viewbox="0 0 138.56406460551017 160"
     style="filter: drop-shadow(rgba(0, 0, 0, 0.2) 0px 2px 8px);">
   <defs>
     <pattern id="bias{{$key}}" height="100%" width="100%" patternContentUnits="objectBoundingBox" viewBox="0 0 1 1" preserveAspectRatio="xMidYMid slice">
     <image height="1" width="1"  preserveAspectRatio="xMidYMid slice" xlink:href="{{ $s3->url('images/Artist/'.$artists->profile_path) }}" />
     </pattern>
   </defs>
   <path fill="url(#bias{{$key}})" fill="#fff" d="M51.961524227066306 9.999999999999998Q69.28203230275508 0 86.60254037844385 10L121.2435565298214 30Q138.56406460551017 40 138.56406460551017 60L138.56406460551017 100Q138.56406460551017 120 121.24355652982139 130L86.60254037844386 150Q69.28203230275508 160 51.96152422706631 150L17.32050807568877 130Q0 120 0 100L0 60Q0 40 17.320508075688775 30Z"></path>
  </svg>
@else
    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="119" height="129" viewbox="0 0 138.56406460551017 160" style="filter: drop-shadow(rgba(0, 0, 0, 0.2) 0px 2px 8px);"><path fill="gray" d="M51.961524227066306 9.999999999999998Q69.28203230275508 0 86.60254037844385 10L121.2435565298214 30Q138.56406460551017 40 138.56406460551017 60L138.56406460551017 100Q138.56406460551017 120 121.24355652982139 130L86.60254037844386 150Q69.28203230275508 160 51.96152422706631 150L17.32050807568877 130Q0 120 0 100L0 60Q0 40 17.320508075688775 30Z"></path></svg>
    
@endif
</div>
<h4 class="artist bold text-center">{{ ($artists->Name) }}</h4></a>
<p class="artist-title text-center">{{ (ucfirst($artists->main_category)) }}</p>
<?php $ratingCount = array_column($artists->testimonials, 'rate');
$totalRating = [];?>
@foreach ($artists->testimonials as $testimonial)
<?php $totalRating[] = $testimonial->rate;?>

@endforeach
<?php
if (!empty($ratingCount)) {
    $avrageRating = round(array_sum($totalRating)/count($ratingCount), 2);
} else {
    $avrageRating = 0;
}
?>
        <!--<h5 class="profile-artist-subtitle text-center">Model</h5>-->
<p class="text-center">
    <input id="input-6" name="rate" class="rating"
           data-size="uni"
           value="{{$avrageRating}}"
           data-glyphicon="false"
           data-rating-class="fontawesome-icon" data-show-caption="false" data-readonly="true">
</p>
{{--<p class="text-center">
<i class="fa fa-star yellow-text"></i>
<i class="fa fa-star yellow-text"></i>
<i class="fa fa-star yellow-text"></i>
<i class="fa fa-star yellow-text"></i>
<i class="fa fa-star"></i>
</p>--}}
</div>

@if ($key % 4 == 0)
</div>

@endif

@endforeach
<?php } else { ?>

<p class="alert alert-danger">No result found!</p>
<?php } ?>
<!-- start for refernce purpose -->

<!--   @foreach ($artist as $key=>$artists)
<div class="col-md-3 col-sm-4  col-xs-12 col-lg-2 col-mdprofile">
<a href="{{ $artists->profile_url }}" class="artist-name"> -->
<!-- <div class="hexa artist">
<div class="hex1">
  <div class="hex2" style="background:url('/{{ url('/images/Artist/').'/'.$artists->profile_path}}'); background-size: contain;">
    @if($artists->profile_path != "")
    <img src="{{ $artists->profile_path}}" class="img-responsive artist" align="middle" alt=""/>
    @else
    <img src="/images/Artist/default-artist.png" class="img-responsive artist" alt="{{ $artists->Name }}" >
    @endif
  </div>
</div>
</div> -->
<!--  <div class="hex">
@if($artists->profile_path != "")
  <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="119" height="129" viewbox="0 0 138.56406460551017 160"
     style="filter: drop-shadow(rgba(0, 0, 0, 0.2) 0px 2px 8px);">
   <defs>
     <pattern id="bias{{$key}}" height="100%" width="100%" patternContentUnits="objectBoundingBox" viewBox="0 0 1 1" preserveAspectRatio="xMidYMid slice">
     <image height="1" width="1"  preserveAspectRatio="xMidYMid slice" xlink:href="{{ url('/images/Artist/').'/'.$artists->profile_path }}" />
     </pattern>
   </defs>
   <path fill="url(#bias{{$key}})" fill="#fff" d="M51.961524227066306 9.999999999999998Q69.28203230275508 0 86.60254037844385 10L121.2435565298214 30Q138.56406460551017 40 138.56406460551017 60L138.56406460551017 100Q138.56406460551017 120 121.24355652982139 130L86.60254037844386 150Q69.28203230275508 160 51.96152422706631 150L17.32050807568877 130Q0 120 0 100L0 60Q0 40 17.320508075688775 30Z"></path>
  </svg>
@else
    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="119" height="129" viewbox="0 0 138.56406460551017 160" style="filter: drop-shadow(rgba(0, 0, 0, 0.2) 0px 2px 8px);"><path fill="gray" d="M51.961524227066306 9.999999999999998Q69.28203230275508 0 86.60254037844385 10L121.2435565298214 30Q138.56406460551017 40 138.56406460551017 60L138.56406460551017 100Q138.56406460551017 120 121.24355652982139 130L86.60254037844386 150Q69.28203230275508 160 51.96152422706631 150L17.32050807568877 130Q0 120 0 100L0 60Q0 40 17.320508075688775 30Z"></path></svg>
@endif
</div>
<h4 class="artist bold text-center">{{$artists->Name}}</h4></a>
<p class="artist-title text-center">Entertainer</p>
<p class="text-center">
<i class="fa fa-star yellow-text"></i>
<i class="fa fa-star yellow-text"></i>
<i class="fa fa-star yellow-text"></i>
<i class="fa fa-star yellow-text"></i>
<i class="fa fa-star"></i>
</p>
</div>
@endforeach  -->
<!-- end for refernce purpose -->
</div>
</div>
</div>
</div>
</div>
</div>
<div class="DownloadAPP viewartistdown">
<div class="container">
<div class="col-md-12 col-xs-12  col-sm-12 col-lg-12 space">
<div class="col-md-6 col-xs-12  col-sm-12 col-lg-6">
<div class="head-text chartHeadING"> <span class="DOWNLOAD-text">DOWNLOAD THE APP</span><span class="artistmobile shadow1">MOBILE APP</span></div>
<p class="pera white-text">Book, Share and Explore personalized Videos from your favorite people. Download VRL and try it yourself.</p>
<a href="https://play.google.com/store/apps/details?id=com.videorequestlines.vrl" target="_blank"><div class="col-md-4  col-sm-4 col-lg-4 app-btn" ><img class="img-responsive" src="/images/btn_playstore.png"></div></a>
<a href="https://itunes.apple.com/us/app/vrl/id1422069505?ls=1&mt=8" target="_blank"><div class="col-md-4  col-sm-4 col-lg-4 app-btn" style="padding-left: 11px;"><img class="img-responsive" src="/images/btn_appstore.png"></div></a>
<div class="col-md-4 col-sm-4 col-lg-4"><img class="img-responsive" src="/images/vrl_logo_app_section.png"></div>
</div>
<div class="col-md-6  col-xs-12  col-sm-12 col-lg-6">
<img src="/images/Image_from_iOS.png" class="imgshadow">
</div>
</div>
</div>
</div>
<!-- <div class="row paddings join-us-bg">
<h1 class="head-talents text-center">We are looking for amazing talents like you</h1>
<div class="col-md-offset-5">
<a href="" data-toggle="modal" data-target="#exampleModalCenterJOIN" class="btn join-us-btn-now">JOIN US NOW!</a>
</div>
</div> -->
</section>
@include('frontend.common.footer')
<script>
/*
var acc = document.getElementsByClassName("accordion");
var panel = document.getElementsByClassName('category');

for (var i = 0; i < acc.length; i++) {
acc[i].onclick = function() {
var setClasses = !this.classList.contains('active');
setClass(acc, 'active', 'remove');
setClass(panel, 'show', 'remove');

if (setClasses) {
this.classList.toggle("active");
this.nextElementSibling.classList.toggle("show");
}
}
}
function setClass(els, className, fnName) {
for (var i = 0; i < els.length; i++) {
els[i].classList[fnName](className);
}
}*/

</script>
<script type="text/javascript" src="js/videoslide.js"></script>
</body>
</html>
