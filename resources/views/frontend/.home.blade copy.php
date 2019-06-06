<!DOCTYPE html>
<html lang="en">
<style>
  body {
    -webkit-user-select: none;
    -moz-user-select: -moz-none;
    -ms-user-select: none;
    user-select: none;
  }
</style>
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
 </script>
</head>
@if (count($errors) > 0)
<body class="cb-page home active video-request-form-active" ondragstart="return false" onselectstart="return false" onkeypress="return disableCtrlKeyCombination(event);" onkeydown="return disableCtrlKeyCombination(event);" >
  @elseif(Session::has('error'))
  <body class="cb-page home active video-request-form-active" ondragstart="return false" onselectstart="return false" onkeypress="return disableCtrlKeyCombination(event);" onkeydown="return disableCtrlKeyCombination(event);" >
    @else
    <body class="cb-page home" onkeypress="return disableCtrlKeyCombination(event);" onkeydown="return disableCtrlKeyCombination(event);" >
      @endif
      <div class="cb-pagewrap"> @include('frontend.common.header')
        <section id="banner">
          <div class="container">
            <div class="cb-grid">
              <?php //echo phpinfo();?>
              <div class="cb-block cb-box-70 main-banner">
                <div class="cb-content nopadding-right nomargin-right nopadding-bottom nomargin-bottom">
                  <div class="inner-block">
                   @if (count($slider_data) > 0)
                   <div id="slider"> @foreach ($slider_data as $slider_datas)
                    <div class="item"> <img src="{{$slider_datas->slider_path}}" class="img-responsive">
                      <div class="container">
                        <div class="row">
                          <div class="slider-caption">
                            <?php //dd($slider_datas);?>
                            <h1>{{$slider_datas->slider_title}}</h1>
                            <p><?php echo substr($slider_datas->slider_description,0,260).'...';?></p>
                            @if($slider_datas->Type!='Admin')
                            <a href="{{URL('/'.$slider_datas->profile_url)}}">view more</a> 
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                    @endforeach </div>
                    @else   
                    <div id="slider">
                      <div class="item"> <img src="images/artist_default.jpg" class="img-responsive">
                        <div class="container">
                          <div class="row">
                            <div class="slider-caption">
                              <h1>Image first</h1>
                              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et auctor diam, vitae ullamcorper dui. Vivamus consectetur eget metus eu vulputate.</p>
                              <a href="javascript:void(0)">dummy slider</a> </div>
                            </div>
                          </div>
                        </div>
                        
                        <div class="item"> <img src="images/artist_default.jpg" class="img-responsive">
                          <div class="container">
                            <div class="row">
                              <div class="slider-caption">
                                <h1>Image second</h1>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et auctor diam, vitae ullamcorper dui. Vivamus consectetur eget metus eu vulputate.</p>
                                <a href="javascript:void(0)">dummy slider</a> </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        @endif
                      </div>
                    </div>
                  </div>
                  <div class="cb-block cb-box-30 artist-list">
                    <div class="cb-content nopadding-bottom nomargin-bottom">
                      <div class="inner-block">
                        <h2 class="heading">
                          @if(count($latest_videos)==1)
                          <span>Latest Video</span>
                          @else
                          <span>Latest Videos</span>
                          @endif
                        </h2>
                        <div class="list-audio-container">
                          <ul class="list-audio-inner-container">
                            @foreach ($latest_videos as $latest_video)
                            <?php $str = $latest_video->VideoThumbnail;
                            $pic = substr($str,28);?>
                            <li class="player-list">
                              <div class="player-list-title"> <span class="player-list-cover"> <a href="video/{{ $latest_video->ProfileId }}/{{ $latest_video->Title }}"> <img src="<?php echo $pic;?>" alt=""> </a> </span> <span class="player-title">{{ $latest_video->Name}}</span> </div>
                              <div class="player-list-icon"> <span class="art_name">{{ $latest_video->Title}}</span>  <span class="icon-play"><a href="#"><i class="fa fa-play"></i></a></span>
                              </div>
                            </li>
                            @endforeach
                          </ul>
                        </div>
                        <div class="view-all"><a href="{{URL('view-all-video')}}">View all</a></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
            <section id="mian-content">
              <div class="container">
                <div class="cb-grid">
                  <div class="cb-block cb-box-70 main-content video-list">
                    <div class="cb-content nopadding-right nomargin-right">
                      <div class="inner-block">
                        <h1 class="heading">
                          <span class="txt1">latest</span>
                          @if(count($videos)==1)
                          <span class="txt2">artist video  </span>
                          @else
                          <span class="txt2">artist videos  </span>
                          @endif
                          
                        </h1>
                        <?php //dd($videos);?>
                        <div class="grid-1"> @foreach ($videos as $video)
                          <div class="artist-video">
                            <?php $str = $video->VideoThumbnail;
                            $rest = substr($str,28);
                            ?>
                            <div class="inner-artist-video" style="background:url(<?php echo $rest; ?>) no-repeat center center;-webkit-background-size:cover;-moz-background-size:cover;-ms-background-size:cover;-o-background-size:cover;background-size:cover;height:250px;">


                             <figure>
                              <figcaption>
                                <h3>{{ $video->Title }}</h3>
                                <span class="circle-right-arrow"> <a href="video/{{ $video->ProfileId }}/{{ $video->Title }}"><img src="../images/play-icon.png" alt="img"></a> </a> </span> </figcaption>
                              </figure>
                              
                            </div>
                          </div>
                          @endforeach </div>
                        </div>
                      </div>
                    </div>
                    <div class="cb-block cb-box-30 right-bar">
                      @if (count($errors) > 0)
                      <div class="cb-content nopadding-bottom nomargin-bottom video-request-form-wrapper active">
                        <div class="video_masking">
                        </div>
                        @elseif (Session::has('error'))
                        <div class="cb-content nopadding-bottom nomargin-bottom video-request-form-wrapper ">
                          <div class="video_masking"></div>
                          <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ Session::get('error') }}
                          </div>
                          @else
                          <div class="cb-content nopadding-bottom nomargin-bottom video-request-form-wrapper">
                            <div class="video_masking"></div>
                            @endif
                            @if(!Auth::check())
                            <div class="inner-block">
                             <h2 class="heading"><span>Request Video</span><i class="fa fa-close"></i></h2>
                             <div class="art_req_newvideo">
                              <!-- <form action="/purchase_request" class="form" method="post"> -->
                              <form action="/requestvideo" class="form" method="post">
                                {{csrf_field()}}
                                <input type="hidden" name="business" value="codingbrains18@gmail.com">
                                <input type="hidden" name="tax" value="1">
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="artist_id" value="1">
                                
                                <span>
                                  <label>Select Artist <span class="red">*</span> </label>
                                  <select name="artist" id="artist">
                                    <option value="">Select Artist</option>
                                    @if(count($artists)> 0)
                                    @foreach ($artists as $artist)
                                    <option value="{{$artist->ProfileId}}">{{$artist->Name}}</option>
                                    @endforeach
                                    @endif
                                  </select>
                                  @if($errors->first('artist'))
                                  <p class="label label-danger" >
                                    {{ $errors->first('artist') }}
                                  </p>
                                  @endif
                                </span>
                                <span>
                                  <label>Song Name (if applicable)</label>
                                  <input type="text" name="song_name" value="{{Request::old('song_name')}}" id="song_name" placeholder="Enter Song Name">   
                                  @if($errors->first('song_name'))
                                  <p class="label label-danger" >
                                    {{ $errors->first('song_name') }}
                                  </p>
                                  @endif
                                </span>
                                <span>
                                  <label>Recipient Name <span class="red">*</span> </label>
                                  <input type="text" name="user_name" id="user_name" value="{{Request::old('user_name')}}" placeholder="Enter Recipient Name">   
                                  @if($errors->first('user_name'))
                                  <p class="label label-danger" >
                                    {{ $errors->first('user_name') }}
                                  </p>
                                  @endif
                                </span>
                                <span>
                                  <label>Name Pronunciation ( spell it out ) </label>
                                  <input type="text" name="pronun_name" id="pronun_name" value="{{Request::old('pronun_name')}}" placeholder="Enter your Name Pronunciation">   
                                  @if($errors->first('pronun_name'))
                                  <p class="label label-danger" >
                                    {{ $errors->first('pronun_name') }}
                                  </p>
                                  @endif
                                </span>
                                <span>
                                  <label>Recipient E-Mail Address <span class="red">*</span> </label>
                                  <input type="text" name="user_email" id="user_email" value="{{Request::old('user_email')}}" placeholder="Enter your Recipient Email Address">   
                                  @if($errors->first('user_email'))
                                  <p class="label label-danger" >
                                    {{ $errors->first('user_email') }}
                                  </p>
                                  @endif
                                </span>
                                <span>
                                  <label>Sender Name <span class="red">*</span> </label>
                                  <input type="text" name="sender_name" id="sender_name" value="{{Request::old('sender_name')}}" placeholder="Enter sender Name">   
                                  @if($errors->first('sender_name'))
                                  <p class="label label-danger" >
                                    {{ $errors->first('sender_name') }}
                                  </p>
                                  @endif
                                </span>
                                <span>
                                  <label>Sender Name Pronunciation ( spell it out ) </label>
                                  <input type="text" name="sender_name_pronun" id="sender_name_pronun" value="{{Request::old('sender_name_pronun')}}" placeholder="Enter Name Pronunciation">   
                                  @if($errors->first('sender_name_pronun'))
                                  <p class="label label-danger" >
                                    {{ $errors->first('sender_name_pronun') }}
                                  </p>
                                  @endif
                                </span>
                                <span>
                                  <label> Sender E-Mail Address <span class="red">*</span> </label>
                                  <input type="text" name="sender_email" id="sender_email" value="{{Request::old('sender_email')}}" placeholder="Enter E-Mail Address">   
                                  @if($errors->first('sender_email'))
                                  <p class="label label-danger" >
                                    {{ $errors->first('sender_email') }}
                                  </p>
                                  @endif
                                </span>
                                <span>
                                  <label>Delivery Date <span class="red">*</span> </label>

                                  <input type="text" name="delivery_date" id="datepicker" value="{{Request::old('delivery_date')}}" placeholder="Enter Delivery Date"  class="request_datepicker"> 

                                  @if($errors->first('delivery_date'))
                                  <p class="label label-danger" >
                                    {{ $errors->first('delivery_date') }}
                                  </p>
                                  @endif
                                </span>
                                <span>
                                  <label>Occassion ( birthday, holiday, valentines, anniversary, sorry, thanks, other )</label>
                                  <input type="text" name="Occassion" id="Occassion" value="{{Request::old('Occassion')}}" placeholder="Enter Occassion">   
                                  @if($errors->first('Occassion'))
                                  <p class="label label-danger" >
                                    {{ $errors->first('Occassion') }}
                                  </p>
                                  @endif
                                </span>
                                <span>
                                  <label>Personalized Message </label>
                                  <input type="text" name="person_message" id="person_message" value="{{Request::old('person_message')}}" placeholder="Enter Personalized Message">   
                                  @if($errors->first('person_message'))
                                  <p class="label label-danger" >
                                    {{ $errors->first('person_message') }}
                                  </p>
                                  @endif
                                </span>
                                <!-- <span>
                                <input type="text" name="video_title" value="{{Request::old('video_title')}}" id="video_title" placeholder="Enter Requested Video Title">   
                                  @if($errors->first('video_title'))
                                  <p class="label label-danger" >
                                    {{ $errors->first('video_title') }}
                                  </p>
                                  @endif</span> -->
                                  <!-- <span>
                                  <input type="text" name="video_delivery_time" value="{{Request::old('video_delivery_time')}}" id="request_datepicker" placeholder="Set Video Delivery Date">
                                    @if($errors->first('video_delivery_time'))
                                    <p class="label label-danger" >
                                      {{ $errors->first('video_delivery_time') }}
                                    </p>
                                    @endif</span> -->
                                    <!-- <span><input type="email" name="user_email" value="{{Request::old('user_email')}}" id="user_email" placeholder="Enter your Email">   
                                      @if($errors->first('user_email'))
                                      <p class="label label-danger" >
                                        {{ $errors->first('user_email') }}
                                      </p>
                                      @endif</span> -->
                                     <!--  <span><input type="text" name="user_name" id="user_name" value="{{Request::old('user_name')}}" placeholder="Enter your Name">   
                                        @if($errors->first('user_name'))
                                        <p class="label label-danger" >
                                          {{ $errors->first('user_name') }}
                                        </p>
                                        @endif</span> -->
                                       <!--  <span><input type="text" name="user_city" id="user_city"  value="{{Request::old('user_city')}}" placeholder="City">   
                                          @if($errors->first('user_city'))
                                          <p class="label label-danger" >
                                            {{ $errors->first('user_city') }}
                                          </p>
                                          @endif</span> -->
                                          <!-- <span><input type="text" name="user_zip" id="user_zip" value="{{Request::old('user_zip')}}" placeholder="Zip code">   
                                            @if($errors->first('user_zip'))
                                            <p class="label label-danger" >
                                              {{ $errors->first('user_zip') }}
                                            </p>
                                            @endif</span> -->
                                           <!--  <span><input type="text" name="user_state" id="user_state" value="{{Request::old('user_state')}}" placeholder="State">   
                                              @if($errors->first('user_state'))
                                              <p class="label label-danger" >
                                                {{ $errors->first('user_state') }}
                                              </p>
                                              @endif</span> -->
                                              <!-- <span><input type="text" name="user_country" id="user_country" value="{{Request::old('user_country')}}" placeholder="Country">   
                                                @if($errors->first('user_country'))
                                                <p class="label label-danger" >
                                                  {{ $errors->first('user_country') }}
                                                </p>
                                                @endif</span> -->
                                                <!-- <span><input type="text" name="user_phone" id="user_phone" value="{{Request::old('user_phone')}}" placeholder="Phone">   
                                                  @if($errors->first('user_phone'))
                                                  <p class="label label-danger" >
                                                    {{ $errors->first('user_phone') }}
                                                  </p>
                                                  @endif</span> -->
                                                  
                                                  <!-- <span><textarea name="video_description" id="video_description"  placeholder="Describe Your requirement">{{Request::old('video_delivery_time')}}</textarea>
                                                    @if($errors->first('video_description'))
                                                    <p class="label label-danger" >
                                                      {{ $errors->first('video_description') }}
                                                    </p>
                                                    @endif</span> -->
                                                    <input type="submit" class="btn btn-default login" value="Submit Request">
                                                  </form>
                                                </div>
                                              </div>
                                              @endif
                                            </div>
                                            @if(count($artists)>0)
                                            <div class="cb-content">
                                              <div class="inner-block artist-list-wrap">
                                                <h2 class="heading">
                                                  @if(count($artists)==1)
                                                  <span>ARTIST</span>
                                                  @else
                                                  <span>ARTISTS</span>
                                                  @endif
                                                  
                                                </h2>
                                                <div class="artist-list-container">
                                                  <ul class="artist-list-inner-container">
                                                    @foreach ($artists as $artist)
                                                    <li class="artist-list">
                                                      <div class="artist-title"> 
                                                        <a href="{{ $artist->profile_url }}"> 
                                                          <span class="artist-img">
                                                            @if($artist->profile_path != "")                         
                                                            <img src="{{ $artist->profile_path}}" alt="img" >                                              @else                          
                                                            <img src="/images/Artist/default-artist.png" alt="img" >
                                                            @endif                        
                                                            <!-- <img src="{{$artist->profile_path}}" alt="img"> -->
                                                          </span> 
                                                          <span class="artist-name">{{$artist->Name}}</span> 
                                                        </a>
                                                      </div>
                                                    </li>
                                                    @endforeach
                                                  </ul>
                                                </div>
                                                <div class="view-all"><a href="{{URL('view-all-artist')}}">View all</a></div>
                                              </div>
                                            </div>
                                            @endif
                                            @if(count($testimonials)>0)
                                            <div class="cb-content nopadding-top nomargin-top">
                                              <div class="inner-block testimonial">
                                                <h2 class="heading">
                                                  @if(count($testimonials)==1)
                                                  <span>Testimonial</span>
                                                  @else
                                                  <span>Testimonials</span>
                                                  @endif

                                                </h2>
                                                <div id="slider1"> @foreach ($testimonials as $testimonial)
                                                  <div class="item">
                                                    <div class="slider-caption">
                                                      <blockquote>{{$testimonial->Message}}</blockquote>
                                                      <span><i class="fa fa-user"></i> {{$testimonial->user_name}}</span> 
                                                    </div>
                                                  </div>
                                                  @endforeach 
                                                </div>
                                              </div>
                                            </div>
                                            @endif
                                          </div>
                                        </div>
                                      </div>
                                    </section>
                                    @include('frontend.common.footer') </div>



                                  </body>
                                  </html>
