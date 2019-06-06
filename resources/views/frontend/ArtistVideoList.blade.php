<!DOCTYPE html>
<html lang="en">
<?php $page = 'ARTIST PROFILE'; ?>
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
                //if ctrl is pressed check if other key is in forbiden Keys array
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
 <style type="text/css">
  /*.ar_fix .row{
     display: -webkit-box;     
  display: -moz-box;         
  display: -ms-flexbox;     
  display: -webkit-flex;     
  display: flex;             
  flex-wrap: wrap;
  }*/

  h4.artist{
    font-size: 16px;
  }
  .ar_fix .row .col-lg-2 {
  min-height: 280px;
}
    .ar_fix .row:after, .ar_fix .row:before{
      clear: inherit;
    }
   #login{
    color: #B135CD; 
    font-family: Roboto;  
    font-size: 16px;  
    font-weight: 500; 
    letter-spacing: 0.51px; 
    line-height: 19px;  
    text-align: center;
   }
   a:hover,a:focus{
    text-decoration: none;
   }

#slider1 .owl-prev {
  background: url(/images/prev.png);
  height: 30px;
  position: absolute;
  background-size: 100%;
  top: 50%;
  transform: translateY(-50%);
  width: 30px;
  left: 0;
  border-radius: 100%;
  box-shadow: 0 0 10px -1px rgba(121, 76, 76, 0.13), 0 0 10px 0 rgba(0, 0, 0, 0.18), 0 0 1px 0 rgba(152, 84, 187, 0.48);
}

#slider1 .owl-next {
  background: url(/images/next.png);
  height: 30px;
  position: absolute;
  background-size: 100%;
  top: 50%;
  transform: translateY(-50%);
  width: 30px;
  right: 0;
  border-radius: 100%;
  box-shadow: 0 0 10px -1px rgba(121, 76, 76, 0.13), 0 0 10px 0 rgba(0, 0, 0, 0.18), 0 0 1px 0 rgba(152, 84, 187, 0.48);
}
#slider1{
  padding: 0 40px;
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
     @include('frontend.common.header')
      <?php $s3 = \Illuminate\Support\Facades\Storage::disk('s3');?>
    <section id="main-content">
      <div class="row purple profile-p">
          <img src="{{ $s3->url('images/Artist/'.$artist_detail->header_image)}}" alt="" class="img img-responsive header-dimension">
      </div>
      <div class="container">
        <div class="row"> 
        <div class="col-md-12 col-sm-12 col-lg-12">
          <div class="col-md-9 col-sm-12 col-lg-9" style="padding-right: 0px;">
            <div class="row">
              <div class="col-md-4  col-xs-12 col-sm-4 col-lg-4 hex-profile">
                <div class="hexa-profile"> 
                    <div class="hex hex2">
                      <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 138.56406460551017 160" style="filter: drop-shadow(rgba(0, 0, 0, 0.2) 0px 2px 8px);">
                          <defs>
                            <pattern id="bias0" height="100%" width="100%" patternContentUnits="objectBoundingBox" viewBox="0 0 1 1" preserveAspectRatio="xMidYMid slice">
                            <image height="1" width="1" preserveAspectRatio="xMidYMid slice" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="{{ $s3->url('images/Artist/'.$artist_detail->profile_path)}}"></image>
                            </pattern>
                          </defs>  
                          <path fill="url(#bias0)" d="M51.961524227066306 9.999999999999998Q69.28203230275508 0 86.60254037844385 10L121.2435565298214 30Q138.56406460551017 40 138.56406460551017 60L138.56406460551017 100Q138.56406460551017 120 121.24355652982139 130L86.60254037844386 150Q69.28203230275508 160 51.96152422706631 150L17.32050807568877 130Q0 120 0 100L0 60Q0 40 17.320508075688775 30Z"></path>
                      </svg>
                    </div>
                </div>
                <!-- style="color: #{{ ($artist_detail->text_color) }}; font-size: {{ ($artist_detail->name_heading_size) }}px ;" -->
                <h4 class="text-center profile-artist-title">{{ ($artist_detail->Name) }}</h4>
                  <?php
                      $ratingCount = $testimonials->where('to_profile_id', $artist_detail->ProfileId);
                      $totalCount = 0;
                      $totalRating = $ratingCount->count();
                      foreach($ratingCount as $key => $value) {
                          $totalCount += $value->rate;
                      }
                  ?>
                <p class="text-center">
                    <input id="input-6" name="rate" class="rating"
                           data-size="uni"
                           value="{{($totalRating > 0) ? $totalCount/$totalRating : 0}}"
                           data-glyphicon="false"
                           data-rating-class="fontawesome-icon" data-show-caption="false" data-readonly="true">
                </p>
              </div>
              <div class="col-md-8 col-xs-12 col-sm-8 col-lg-8 profilemodal paddings">
                <p class="prof-desc">{{$artist_detail->profile_description}} </p>
                <div class="tags buttonTag">
                  @forelse ($artist_category as $artcategory)
                    <a href="/view-all-artist?category_id={{$artcategory->id}}&title={{$artcategory->title}}#main-content"><span class="badge tags">{{isset($artcategory->title)?$artcategory->title: ''}}</span></a>
                  @empty
                    <P class="book-title" style="padding-left: 171px ;color:#6c646d;">No Description</P>
                  @endforelse

                </div>
                <hr>
              </div>
            </div>
              @if(count($video)>0 || count($sample_video)>0)
            <div class="row">
              <div class="col-md-12 col-sm-12 col-lg-12"> 
                <h1 class="col-sm-12 head-text-grey artistLATESTvideo  paddings">
                  <span>LATEST</span>
                  <b class="head-text-purple">VIDEOS</b>
                  <span class="shadow">LATEST VIDEOS</span>
                </h1>
                </div>
                
                <div class="col-md-12 col-sm-12 col-lg-12 latestvideo">
                    <?php  $last = 0;?>
                  @foreach ($video as $key=>$latest_video)
                  <?php 
                  $url =  $s3->url('requested_video/watermark/thumbnail/'.$latest_video->video_url);
                  $video_title = explode(' ',trim($latest_video->video_title));
                  $video_title = $video_title[0];
    
                  $user_name = explode(' ',trim($latest_video->user_name));
                  $user_name = $user_name[0];
                  ?>
                  <div class="col-md-4 col-xs-6 col-sm-6 col-lg-4">
                    <div class="wrapper videowrapper">
                      <div class="latest_videowrap1"> 
                        <video id="myVideo{{$key}}">
                          <source src="{{ $url }}" type="video/mp4">
                          <source src="{{ $url }}" type="video/ogg">
                          Your browser does not support HTML5 video.
                        </video>
                        <div id="overlay{{$key}}" class="overlay-desc3"></div>
                        <div class="play playsingle" onclick="play({{ $key }})" id="{{ $key }}"></div>
                        <div class="pause" onclick="pause({{$key}})" id="pause{{ $key }}" style="display: none;"></div>
                      </div>
                    </div>
                        <h4 class="video-head text-center">
                        {{ $video_title}}{{'@'.$user_name}}
                        </h4>
                        <p class="video-desc text-center" title="{{ ($latest_video->video_description) }}">
                        {{ (str_limit($latest_video->video_description,50))}}
                        </p>
                  </div>
                  <?php $last = $key+1;?>
                  @endforeach
                  @foreach ($sample_video as $video_data)
                      <?php
                      $url =  $s3->url('video/watermark/thumbnail/'.$video_data->VideoURL);
                      ?>
                      <div class="col-md-4 col-xs-6 col-sm-6 col-lg-4">
                          <div class="wrapper videowrapper">
                              <div class="latest_videowrap1">
                                  <video id="myVideo{{$last}}">
                                      <source src="{{ $url }}" type="video/mp4">
                                      <source src="{{ $url }}" type="video/ogg">
                                      Your browser does not support HTML5 video.
                                  </video>
                                  <div id="overlay{{$last}}" class="overlay-desc1"></div>
                                  <div class="play" onclick="play({{ $last }})" id="{{ $last }}"></div>
                                  <div class="pause" onclick="pause({{$last}})" id="pause{{ $last }}" style="display: none;"></div>
                              </div>
                          </div>
                          <h4 class="video-head text-center">
                              {{ (str_limit($video_data->Title,30))}}
                          </h4>
                          <p class="video-desc text-center">
                              {{ (str_limit($video_data->Description,50)) }}
                          </p>
                      </div>
                      <?php $last++; ?>
                  @endforeach
                </div>
              </div>
              @endif
			  
			  
			  
			  

    @if(count($testimonials) > 0)
                    <div class="relatedArtist">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="col-md-8 col-sm-12 col-lg-8">
                <h1 class="head-text-grey RELATEDheading paddings">All <b class="head-text-purple">REVIEWS</b> <span class="shadow">ALL REVIEWS</span></h1>
            </div>
        </div>
        <div class="clear"></div>
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="cb-content">
                <div class="inner-block">
                    <div class="testi">
                        <div id="slider1" class=" chartartist_top"><!-- slider1 -->
                            @foreach ($testimonials->take(5) as $testimonial)
                                <div class="item">
                                    <div class="slider-caption">
                                        <blockquote>
                                    <span>
                                        {{ ($testimonial->Message) }}
                                        <input id="input-6" name="rate" class="rating"
                                               data-size="uni"
                                               value="{{$testimonial->rate}}"
                                               data-glyphicon="false"
                                               data-rating-class="fontawesome-icon" data-show-caption="false" data-readonly="true">
                                    </span>
                                        </blockquote>
                                        <span><i class="fa fa-user"></i> {{ ($testimonial->user_name) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
                        </div>
    @endif
	
	
          </div> 
         @if($type!="Artist")
          <div class="col-md-3 col-sm-12 col-lg-3">
              <div class="panel panel-default profile-panel">
                <h4 class="book">Book</h4>
                <h3 class="book-title">{{ ($artist_detail->Name) }}</h3>
                <hr>
                @if (count($errors) > 0)
                <div class="cb-content nopadding-bottom nomargin-bottom video-request-form-wrapper active">
                  <div class="video_masking"> </div>
                @elseif (Session::has('error'))
                <div class="cb-content nopadding-bottom nomargin-bottom video-request-form-wrapper ">
                  <div class="video_masking"></div>
                  <div class="alert alert-danger">
                      {{ (Session::get('error')) }}
                       <img class="img-responsive center-block" data-dismiss="alert" aria-label="close" src="/images/Popup_close.png" alt="VRL close">
                  </div>
                @else
                  <div class="cb-content nopadding-bottom nomargin-bottom video-request-form-wrapper">
                  <div class="video_masking"></div>
                @endif

                <div class="hide turnaroundtimevalue" data-turnaroundtime="{{$artist_detail->timestamp}}"></div>
                <form action="/requestvideo" method="post" id="requestForm">
                  {{csrf_field()}}
                    <input type="hidden" name="business" value="codingbrains18@gmail.com">
                    <input type="hidden" name="tax" value="1">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="artist" value="{{$artist_detail->ProfileId}}" id="artist_profile_id">
                    <input type="hidden" name="artist_name" value="{{$artist_detail->Name}}">
                    <input type="hidden" name="video_price" value="{{$artist_detail->VideoPrice}}" id="video_price">
                  <div class="form-group">
                    <label class="user-video-text">Who is this video for?</label>
                    <input type="text" size="23" name="user_name"
                           value="{{Request::old('user_name')}}" class="user-input" placeholder="{{ ('James') }}"
                    onfocus="this.placeholder = ''" 
                    onblur="this.placeholder = 'James'" onblur="textCounter(this, 50, 'for-whom');" onkeyup="textCounter(this, 50, 'for-whom');">
                    <p class="for-whom limit-text-align"></p>
                    @if($errors->first('user_name'))
                    <p class="label label-danger" >
                      {{ ($errors->first('user_name')) }}
                    </p>
                    @endif
                  </div>
                  <div class="form-group">
                    <label class="user-video-text">Recipient Name Pronunciation (Optional)</label>
                      <div class="sender-proun">
                    <input type="text" size="23" name="pronun_name" id="pronun_name" value="{{Request::old('pronun_name')}}" class="user-input" placeholder="{{ ('Name Pronunciation') }}" onfocus="this.placeholder = ''"
                    onblur="this.placeholder = '{{("Name Pronunciation")}}'">
                    @if($errors->first('pronun_name'))
                    <p class="label label-danger" >
                      {{ ($errors->first('pronun_name')) }}
                    </p>
                    @endif
                    </div>
                    <div class="m-b-20">
                          <image id="recipient-pronounciation-record" data-name="Recipient" data-index="0" class="record" title="Record Pronunciation Name" src="/images/icn_record.png"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="user-video-text">Recipient Email Address</label>
                    <input type="text" name="user_email" size="23" id="user_email"   value="{{Request::old('user_email')}}" class="email user-input" placeholder="{{ ('Enter Email Address') }}" onfocus="this.placeholder = ''"
                    onblur="this.placeholder = '{{ ("Enter Email Address") }}'">
                    @if($errors->first('user_email'))
                    <p class="label label-danger" >
                      {{ ($errors->first('user_email')) }}
                    </p>
                    @endif   
                  </div>
                  <div class="form-group">
                    <label class="user-video-text">Your Name</label>
                    <input type="text" size="23" name="sender_name" id="sender_name" value="{{!is_null(Auth::user()) ? Auth::user()->user_name : Request::old('sender_name')}}" class="user-input" placeholder="{{ ('Enter Your Name') }}" onfocus="this.placeholder = ''"
                    onblur="this.placeholder = '{{ ("Enter Your Name") }}'" onblur="textCounter(this, 50, 'your-name');" onkeyup="textCounter(this, 50, 'your-name');" {{!is_null(Auth::user()) ? 'readonly' : ''}}>
                      <p class="your-name limit-text-align"></p>
                    @if($errors->first('sender_name'))
                    <p class="label label-danger" >
                      {{ ($errors->first('sender_name')) }}
                    </p>
                    @endif   
                  </div>
                  <div class="form-group">
                    <label class="user-video-text">Your Name Pronunciation (Optional)</label>
                      <div class="sender-proun">
                      <input type="text" size="20" name="sender_name_pronun" id="sender_name_pronun" value="{{  Request::old('sender_name_pronun')}}" class="user-input" placeholder="{{ ('Name Pronunciation') }}" onfocus="this.placeholder = ''"
                      onblur="this.placeholder = '{{ ("Name Pronunciation") }}'">
                      @if($errors->first('sender_name_pronun'))
                      <p class="label label-danger" >
                        {{ ($errors->first('sender_name_pronun')) }}
                      </p>
                      @endif
                    </div>
                      <div class="m-b-20">
                          <image id="sender-pronounciation-record" data-name="Sender" data-index="1" class="record" title="Record Pronunciation Name" src="/images/icn_record.png"/>
                      </div>
                  </div>
                  <div class="form-group" >
                    <label class="user-video-text">Your Email</label>
                    <input type="text" size="23" name="sender_email" id="sender_email" value="{{!is_null(Auth::user()) ? Auth::user()->email : Request::old('sender_email')}}" class="email user-input" placeholder="{{ ('Enter E-Mail Address') }}" onfocus="this.placeholder = ''"
                    onblur="this.placeholder = '{{ ("Enter E-Mail Address") }}'" {{!is_null(Auth::user()) ? 'readonly' : ''}}>
                    @if($errors->first('sender_email'))
                    <p class="label label-danger" >
                      {{ ($errors->first('sender_email')) }}
                    </p>
                    @endif
                  </div>
                  <div class="form-group">
                    <label class="user-video-text">Desired Song Name (Optional)</label>
                    <input type="text" size="23" name="song_name" value="{{Request::old('song_name')}}" id="song_name" class="user-input" placeholder="{{ ('Desired Song Name') }}" onfocus="this.placeholder = ''"
                    onblur="this.placeholder = '{{ ("Desired Song Name") }}'" onblur="textCounter(this, 70, 'song-name');" onkeyup="textCounter(this, 70, 'song-name');">
                    <p class="song-name limit-text-align"></p>
                    @if($errors->first('song_name'))
                    <p class="label label-danger" >
                      {{ ($errors->first('song_name')) }}
                    </p>
                    @endif
                  </div>
                 
                  @if(count($occasions)>0)
                <div class="form-group main-category">
                    <label class="user-video-text">Occasion's</label>
                    {!! Form::select('occasion_id', [0=>'Select Occasion']+$occasions, 0, ['class' => 'form-control user-input', 'id'=>"occasion_id"]) !!}
                    @if($errors->first('occasion_id'))
                        <p class="label label-danger" >
                            {{ ($errors->first('occasion')) }}
                        </p>
                    @endif
                </div>
                @endif
                  <input type="hidden" name="Occassion" id="Occassion" value="{{Request::old('Occassion')}}">
                  <div class="form-group">
                    <label class="user-video-text">Phone (Optional)</label>
                    <input type="text" name="phone" size="23" id="phone" value="" 
                    value="{{Request::old('phone')}}" class="user-input" placeholder="512 654 3534" onfocus="this.placeholder = ''"
                    onblur="this.placeholder = '512 654 3534'">
                  </div>
                  <div class="form-group">
                    <label class="user-video-text">Personalized Message</label>
                    <textarea type="text" size="23" name="person_message" id="person_message" value="{{Request::old('person_message')}}" class="user-input" placeholder="{{ ('It`s James 36th Birthday') }}"
                    onfocus="this.placeholder = ''" 
                    onblur="this.placeholder = '{{ ("It`s James 36th Birthday") }}'" onblur="textCounter(this, 200, 'message_limit');" onkeyup="textCounter(this, 200, 'message_limit');"></textarea>
                    <p class="message_limit limit-text-align"></p>
                    @if($errors->first('person_message'))
                    <p class="label label-danger" >
                      {{ ($errors->first('person_message')) }}
                    </p>
                    @endif
                  </div>
                  
                  <div class="form-group">
                    <label class="user-video-text">Delivery Date</label>
                    <input type="text" size="23" name="delivery_date" id="datepicker" placeholder="mm/dd/yyyy" value="{{Request::old('delivery_date')}}"   class="user-input delivery_datepicker">
                    @if($errors->first('delivery_date'))
                    <p class="label label-danger" >
                      {{ ($errors->first('delivery_date')) }}
                    </p>
                    @endif
                  </div>
                  <div class="form-group">
                    <div class="checkbox checkbox-keepvideo">
                      <input id="checkbox2" type="checkbox" value="0" name="is_hidden">
                      <label for="checkbox2" class="info" id="video-hidden">
                          <span class="keepvideo">Keep this video hidden</span>
                          <a  class="not-active" data-toggle="tooltip" title="{{ ('If you check this, we won`t feature your video on VRL`s page or elsewhere.')}}"><i class="fa fa-info-circle" id="hidden-icon"></i></a>

                      </label>
                    </div>
                  </div>
                  <button type="submit" class="btn book-btn" TITLE="Book Now for ${{ floatval($artist_detail->VideoPrice) }}" id="book-now">{{ trim(str_limit('Book Now for', 12)) }}
                    ${{ floatval($artist_detail->VideoPrice) }} </button>



                </form>

              </div>
          </div>
          @endif
        </div>
      </div>    </div>
      @if(count($related_artist) > 0)
      <div class="relatedArtist">
        <div class="row space ar_fix">
            <div class="col-md-12 col-sm-12 col-lg-12">
              <div class="col-md-8 col-sm-12 col-lg-8">
                <h1 class="head-text-grey RELATEDheading paddings">RELATED <b class="head-text-purple">ARTISTS</b> <span class="shadow">RELATED ARTISTS</span></h1>
              </div>
            </div>
            <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
              @foreach($related_artist as $key => $artists)
                    <?php
                    $key=$key+1;
                    ?>
                    @if ($key % 6 == 1)
                        <div class=""> <!-- row -->
                     @endif
                    <div class="col-md-2 col-xs-6 col-sm-4 col-lg-2">
                        <a href="{{ $artists->profile_url }}" class="artist-name">
                            <div class=" ">
                                <div class="hex">
                                    @if($artists->profile_path != "")
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="119" height="129" viewbox="0 0 138.56406460551017 160"
                                             style="filter: drop-shadow(rgba(0, 0, 0, 0.2) 0px 2px 8px);">
                                            <defs>
                                                <pattern id="bias{{$key}}" height="100%" width="100%" patternContentUnits="objectBoundingBox" viewBox="0 0 1 1" preserveAspectRatio="xMidYMid slice">
                                                    <image height="1" width="1"  preserveAspectRatio="xMidYMid slice" xlink:href="{{ $s3->url('images/Artist/'.$artists->profile_path)}}" />
                                                </pattern>
                                            </defs>
                                            <path fill="url(#bias{{$key}})" fill="#fff" d="M51.961524227066306 9.999999999999998Q69.28203230275508 0 86.60254037844385 10L121.2435565298214 30Q138.56406460551017 40 138.56406460551017 60L138.56406460551017 100Q138.56406460551017 120 121.24355652982139 130L86.60254037844386 150Q69.28203230275508 160 51.96152422706631 150L17.32050807568877 130Q0 120 0 100L0 60Q0 40 17.320508075688775 30Z"></path>
                                        </svg>
                                    @else
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="119" height="129" viewbox="0 0 138.56406460551017 160" style="filter: drop-shadow(rgba(0, 0, 0, 0.2) 0px 2px 8px);"><path fill="gray" d="M51.961524227066306 9.999999999999998Q69.28203230275508 0 86.60254037844385 10L121.2435565298214 30Q138.56406460551017 40 138.56406460551017 60L138.56406460551017 100Q138.56406460551017 120 121.24355652982139 130L86.60254037844386 150Q69.28203230275508 160 51.96152422706631 150L17.32050807568877 130Q0 120 0 100L0 60Q0 40 17.320508075688775 30Z"></path></svg>
                                    @endif
                                </div>
                            </div>
                            <h4 class="artist text-center">{{ ($artists->Name) }}</h4>
                        </a>
                        <p class="artist-title text-center">
                            {{ $artists->main_category }}
                        </p>
                        <?php
                            $ratingCount = $testimonials->where('to_profile_id', $artists->ProfileId);
                            $totalCount = 0;
                            $totalRating = $ratingCount->count();
                            foreach($ratingCount as $key => $value) {
                                $totalCount += $value->rate;
                            }
                        ?>
                        <p class="text-center">
                            <input id="input-6" name="rate" class="rating"
                                   data-size="uni"
                                   value="{{ ($totalRating > 0) ? $totalCount/$totalRating : 0 }}"
                                   data-glyphicon="false"
                                   data-rating-class="fontawesome-icon" data-show-caption="false" data-readonly="true">
                        </p>
                    </div>
                     @if ($key % 6 == 0)
                        </div>
                     @endif
              @endforeach
              {{--<div class="col-md-2 col-xs-6 col-sm-4 col-lg-2">
                <a href="" class="artist-name">
                <div class=" ">
                  <div class="hex">
                     <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="130" height="150" viewBox="0 0 138.56406460551017 160" style="filter: drop-shadow(rgba(0, 0, 0, 0.2) 0px 2px 8px);">
                           <defs>
                             <pattern id="artist1" height="100%" width="100%" patternContentUnits="objectBoundingBox" viewBox="0 0 1 1" preserveAspectRatio="xMidYMid slice">
                             <image height="1" width="1" preserveAspectRatio="xMidYMid slice" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="images/1.jpg"></image>
                             </pattern>
                           </defs>  
                           <path fill="url(#artist1)" d="M51.961524227066306 9.999999999999998Q69.28203230275508 0 86.60254037844385 10L121.2435565298214 30Q138.56406460551017 40 138.56406460551017 60L138.56406460551017 100Q138.56406460551017 120 121.24355652982139 130L86.60254037844386 150Q69.28203230275508 160 51.96152422706631 150L17.32050807568877 130Q0 120 0 100L0 60Q0 40 17.320508075688775 30Z"></path>
                       </svg> 
                   </div>
                </div>
                <h4 class="artist text-center">Leon Manning</h4>
                </a>
                <p class="artist-title text-center">Entertainer</p>
                <p class="text-center">
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star"></i>
                </p>
              </div>
              <div class="col-md-2 col-xs-6 col-sm-4 col-lg-2">
               <div class=" ">
                  <div class="hex">
                     <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="130" height="150" viewBox="0 0 138.56406460551017 160" style="filter: drop-shadow(rgba(0, 0, 0, 0.2) 0px 2px 8px);">
                           <defs>
                             <pattern id="artist2" height="100%" width="100%" patternContentUnits="objectBoundingBox" viewBox="0 0 1 1" preserveAspectRatio="xMidYMid slice">
                             <image height="1" width="1" preserveAspectRatio="xMidYMid slice" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="images/2.jpg"></image>
                             </pattern>
                           </defs>  
                           <path fill="url(#artist2)" d="M51.961524227066306 9.999999999999998Q69.28203230275508 0 86.60254037844385 10L121.2435565298214 30Q138.56406460551017 40 138.56406460551017 60L138.56406460551017 100Q138.56406460551017 120 121.24355652982139 130L86.60254037844386 150Q69.28203230275508 160 51.96152422706631 150L17.32050807568877 130Q0 120 0 100L0 60Q0 40 17.320508075688775 30Z"></path>
                       </svg> 
                   </div>
                </div>
                <a href="" class="artist-name"><h4 class=" artist text-center">Harriet Adams</h4></a>
                <p class="artist-title text-center">Model</p>
                <p class="text-center">
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star"></i>
                </p>
              </div>
              <div class="col-md-2 col-xs-6 col-sm-4 col-lg-2">
                <div class=" ">
                  <div class="hex">
                     <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="130" height="150" viewBox="0 0 138.56406460551017 160" style="filter: drop-shadow(rgba(0, 0, 0, 0.2) 0px 2px 8px);">
                           <defs>
                             <pattern id="artist3" height="100%" width="100%" patternContentUnits="objectBoundingBox" viewBox="0 0 1 1" preserveAspectRatio="xMidYMid slice">
                             <image height="1" width="1" preserveAspectRatio="xMidYMid slice" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="images/3.jpg"></image>
                             </pattern>
                           </defs>  
                           <path fill="url(#artist3)" d="M51.961524227066306 9.999999999999998Q69.28203230275508 0 86.60254037844385 10L121.2435565298214 30Q138.56406460551017 40 138.56406460551017 60L138.56406460551017 100Q138.56406460551017 120 121.24355652982139 130L86.60254037844386 150Q69.28203230275508 160 51.96152422706631 150L17.32050807568877 130Q0 120 0 100L0 60Q0 40 17.320508075688775 30Z"></path>
                       </svg> 
                   </div>
                </div>
                <a href="" class="artist-name"><h4 class="artist text-center">Leon Manning</h4></a>
                <p class="artist-title text-center">Entertainer</p>
                <p class="text-center">
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star"></i>
                </p>
              </div>
              <div class="col-md-2 col-xs-6 col-sm-4 col-lg-2">
                <div class=" ">
                  <div class="hex">
                     <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="130" height="150" viewBox="0 0 138.56406460551017 160" style="filter: drop-shadow(rgba(0, 0, 0, 0.2) 0px 2px 8px);">
                           <defs>
                             <pattern id="artist4" height="100%" width="100%" patternContentUnits="objectBoundingBox" viewBox="0 0 1 1" preserveAspectRatio="xMidYMid slice">
                             <image height="1" width="1" preserveAspectRatio="xMidYMid slice" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="images/4.jpg"></image>
                             </pattern>
                           </defs>  
                           <path fill="url(#artist4)" d="M51.961524227066306 9.999999999999998Q69.28203230275508 0 86.60254037844385 10L121.2435565298214 30Q138.56406460551017 40 138.56406460551017 60L138.56406460551017 100Q138.56406460551017 120 121.24355652982139 130L86.60254037844386 150Q69.28203230275508 160 51.96152422706631 150L17.32050807568877 130Q0 120 0 100L0 60Q0 40 17.320508075688775 30Z"></path>
                       </svg> 
                   </div>
                </div>
                <a href="" class="artist-name"><h4 class="artist text-center">Martha Schmidt</h4></a>
                <p class="artist-title text-center">Actor</p>
                <p class="text-center">
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star"></i>
                </p>
              </div>
              <div class="col-md-2 col-xs-6 col-sm-4 col-lg-2">
                <div class=" ">
                  <div class="hex">
                     <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="130" height="150" viewBox="0 0 138.56406460551017 160" style="filter: drop-shadow(rgba(0, 0, 0, 0.2) 0px 2px 8px);">
                           <defs>
                             <pattern id="artist5" height="100%" width="100%" patternContentUnits="objectBoundingBox" viewBox="0 0 1 1" preserveAspectRatio="xMidYMid slice">
                             <image height="1" width="1" preserveAspectRatio="xMidYMid slice" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="images/1.jpg"></image>
                             </pattern>
                           </defs>  
                           <path fill="url(#artist5)" d="M51.961524227066306 9.999999999999998Q69.28203230275508 0 86.60254037844385 10L121.2435565298214 30Q138.56406460551017 40 138.56406460551017 60L138.56406460551017 100Q138.56406460551017 120 121.24355652982139 130L86.60254037844386 150Q69.28203230275508 160 51.96152422706631 150L17.32050807568877 130Q0 120 0 100L0 60Q0 40 17.320508075688775 30Z"></path>
                       </svg> 
                   </div>
                </div>
                <a href="" class="artist-name"><h4 class="artist text-center">Leon Manning</h4></a>
                <p class="artist-title text-center">Model</p>
                <p class="text-center">
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star"></i>
                </p>
              </div>
              <div class="col-md-2 col-xs-6 col-sm-4 col-lg-2">
               <div class=" ">
                  <div class="hex">
                     <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="130" height="150" viewBox="0 0 138.56406460551017 160" style="filter: drop-shadow(rgba(0, 0, 0, 0.2) 0px 2px 8px);">
                           <defs>
                             <pattern id="artist6" height="100%" width="100%" patternContentUnits="objectBoundingBox" viewBox="0 0 1 1" preserveAspectRatio="xMidYMid slice">
                             <image height="1" width="1" preserveAspectRatio="xMidYMid slice" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="images/6.jpg"></image>
                             </pattern>
                           </defs>  
                           <path fill="url(#artist6)" d="M51.961524227066306 9.999999999999998Q69.28203230275508 0 86.60254037844385 10L121.2435565298214 30Q138.56406460551017 40 138.56406460551017 60L138.56406460551017 100Q138.56406460551017 120 121.24355652982139 130L86.60254037844386 150Q69.28203230275508 160 51.96152422706631 150L17.32050807568877 130Q0 120 0 100L0 60Q0 40 17.320508075688775 30Z"></path>
                       </svg> 
                   </div>
                </div>
                <a href="" class="artist-name"><h4 class="artist text-center">Adele Alverz</h4></a>
                <p class="artist-title text-center">Entertainer</p>
                <p class="text-center">
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star yellow-text"></i>
                  <i class="fa fa-star"></i>
                </p>
              </div>
            </div>--}}
        </div>
        @endif
          {{--@if( Auth::check() )
          @if( Auth::user()->type == 'User')
          <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="col-md-8 col-sm-12 col-lg-8">
              <h1 class="head-text-grey RELATEDheading paddings">ADD <b class="head-text-purple">REVIEWS</b> <span class="shadow">ALL REVIEWS</span></h1>
            </div>
          </div>
          <div class="clear"></div>
          
          <div class="col-md-12 col-sm-12 col-lg-12">
              <div class="panel panel-default  col-md-8 col-sm-12 col-lg-8 testimonial-panel">
                <h4 class="book">Add Your Comment</h4>
                <h3 class="book-title">{{ $artist_detail->Name}}</h3>
                <hr>
               
                <div class="cb-content nopadding-bottom nomargin-bottom video-request-form-wrapper active">

                --}}{{--@if($errors->first('message'))
                <p class="label label-danger" >
                 <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>  {{ $errors->first('message') }} </div>
                </p>
                @endif--}}{{--
                @if(Session::has('success'))
                <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>
                @endif
                @if(Session::has('error'))
                <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>
                @endif

                <form action="/comment" method="post">
                  {{csrf_field()}}
                  <input type="hidden" class="form-control" id="to_profile_id" name="to_profile_id"  value="{{$artist_detail->ProfileId}}">
                  
                  <div class="form-group">
                    <label class="user-video-text">Your Name</label>
                    <!-- <input type="text" size="23" name="user_name" id="" class="user-input" placeholder="Your Name"> -->
                    <input type="text" size="23" name="name" id="name" value="{{$user_data->user_name}}" class="user-input" disabled>
                  </div>
               
                  <div class="form-group" >
                    <label class="user-video-text">  Your Email</label>
                    <input type="text" name="email" id="email" value="{{$user_data->email}}" class="email user-input" disabled> 
                  </div>
                
                  <div class="form-group">
                    <label class="user-video-text">Message</label>
                    <textarea type="text" size="23" name="message" id="person_message" value="{{Request::old('message')}}" class="user-input" placeholder="Enter your review"
                    ></textarea>   
                    @if($errors->first('message'))
                    <p class="label label-danger" >
                      {{ $errors->first('message') }}
                    </p>
                    @endif
                  </div>
                    <div class="form-group">
                        <label class="user-video-text">Rate</label>
                        <input id="input-17a" name="rate" class="rating"
                               data-size="md"
                               value="0"
                               data-glyphicon="false"
                               data-rating-class="fontawesome-icon" data-show-caption="false">
                    </div>
                 
                  <button type="submit" class="btn book-btn" id="book-now">Submit</button> 
                  
               <!--    <p class="text-center detail">Your card isn't charged until the video is complete.</p> -->
                </form>
              </div>
          </div>
          </div>
          @endif
          @endif--}}
      </div>    
    </div>
        <div id="myModal" class="modal record-model">
          <div class="modal-content recording-model">
            <span class="close close-recording">&times;</span>
            <h5 style="font-size:21px;">Record <span id="toWhom"></span>Name</h5>
            <button class="btn btn-info " id="start-recording">Start Record</button>
            <button class="btn btn-primary hidden" id="stop-recording">Stop Record</button>
            <button class="btn btn-success hidden" id="save-recording">Save Record</button>
            <span id="audiotimer" style="display:none; margin-left:15px;">00:00</span>
            <hr>
            <audio id="audioRecordTag" controls></audio>
          </div>

        </div>
    </div>
          </div>
      </div>
    </section>
     @include('frontend.common.footer')
<script>
 $(window).ready(function() {
		$(window).on('mousewheel DOMMouseScroll scroll',function() {
			$('.my_video_request').each(function() {
        $(this).get(0).pause();
      });
      $(".pause").hide();
      $(".playsingle").show();
      $(".overlay-desc3").show();
		});
	}); 
  function valueChanged() {
    if($('.showdiv').is(":checked"))   
        $(".gift-details").show();
    else
        $(".gift-details").hide();
  }

  function play(id) {
    document.getElementById("myVideo"+id).play()
    $('.my_video_request').attr('class', 'stopvideo');
    $('.stopvideo').removeClass('my_video_request');
    $("#myVideo"+id).attr('class', 'my_video_request');
    $('.my_video_request')[0].play(); 
    $('.stopvideo').each(function() {
       $(this).get(0).pause();
    });
    $(".pause").hide();
    $(".playsingle").show();
    $(".overlay-desc3").show();
    $("#pause"+id).show();
    $("#"+id).hide(); 
    $("#overl"+id).hide();
  }  
  $('#sender_email,#user_email').keypress(function() { 
  var windwidth= $(window).width(); 
  var textLength = $(this).val().length; 
    console.log(textLength)
  if(windwidth > 991)
  if(textLength > 29) { 
   $(this).css('font-size', '80%');
   console.log(textLength)
  } else if (textLength >38) {
      $(this).css('font-size', '60%');
  }if(textLength < 28) {
 $(this).css('font-size', '100%');
  }   
});
   
  function pause(id) {
    document.getElementById("myVideo"+id).pause();
    $("#pause"+id).hide();
    $("#"+id).show();  $("#overlay"+id).show();
  }
  $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
  });

  var starttime=0;
  var recordinterval= null;
  function startTimer(){
      $('#audiotimer').show();
      $('#audiotimer').text("00:00");
      starttime= 0;
      recordinterval = setInterval(function(){
          starttime++;
          var minutes = Math.floor(starttime / 60);
          var seconds =  (starttime - minutes * 60);
          
          var timestr =  ("0"+minutes).substr(-2) + ":" + ("0"+seconds).substr(-2);

          $("#audiotimer").text(timestr);

      },1000);
  }
  function stopTimer(){
      clearInterval(recordinterval);
      $('#audiotimer').hide();
  }
</script>

    <script src="{{ URL::asset('audio-recorder-polyfill-master/polyfill.js') }}"></script>
    <script src="https://cdn.WebRTC-Experiment.com/RecordRTC.js"></script>
    <script src="https://cdn.webrtc-experiment.com/gumadapter.js"></script>
    
    <script>
        var records = new Array();
         var recordRTC;
         var recorder;
         var aud = document.getElementById('audioRecordTag');
            aud.addEventListener("ended", function(){
                this.currentTime = 0;
                this.pause();
            });
           $(document).ready(function(){
              // Get the modal
             

            $(".record").click(function(){
                $("#start-recording").show();
                $("#stop-recording").hide();
                $("#save-recording").hide();
                
                $("#audioRecordTag").remove();
                $("#myModal .modal-content").append('<audio id="audioRecordTag" controls></audio>');
                var toWhom = $(this).attr("data-name"); 
                var index = $(this).attr("data-index"); 

                $("#myModal").show();
                 
                 $("#toWhom").attr('data-index',index);
                 
                 $("#toWhom").text(toWhom);

            });       
            
            $(".close").click(function(){
                 $("#myModal").hide();
            });   
             
           });
           $("#start-recording").click(function(){
               $(this).hide();
               $("#stop-recording").show();
               $("#save-recording").hide();
              startRecordingNow();
           });
           
           $("#stop-recording").click(function(){
               $("#start-recording").hide();
               $("#stop-recording").hide();
               $("#save-recording").show();
              stopRecording(); 
           });

        $("#save-recording").click(function () {
            $("#start-recording").show();
            $("#stop-recording").hide();
            $("#save-recording").hide();
           
            let audio = document.querySelector('audio');
            audio.muted = true;
            audio.currentTime = 0;
            var index = $("#toWhom").attr('data-index');
            (index == 0) ? $('#pronun_name').attr('placeholder','Rec000.wav') : $('#sender_name_pronun').attr('placeholder','Rec001.wav');
            //records[index] = blob;
            $("#myModal").hide();
        });

        function startRecordingNow(index) {
            var mediaConstraints = {audio: true};
            navigator.mediaDevices.getUserMedia(mediaConstraints).then(successCallback).catch(errorCallback);

        }

        function successCallback(stream) {
            var audio = document.querySelector('audio');
            startTimer();
            audio.muted = false;
            recorder = new MediaRecorder(stream);
            // Set record to <audio> when recording will be finished
            recorder.addEventListener('dataavailable', e => {
              audio.src = URL.createObjectURL(e.data);
              let index = $("#toWhom").attr('data-index');
              records[index] = e.data;
            });

            // Start recording
            recorder.start();
        }

        function errorCallback(error) {
            // maybe another application is using the device
            console.log(error);
            // alert(error);
        }

        function stopRecording() {
                var video = document.querySelector('audio');
                // console.log(video,recordRTC);
                 recorder.stop();
                 stopTimer();
        }

        $(".book-btn").click(function (e) {
            var form = $('form')[2];
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    var data = xhttp.responseText;
                    var jsonResponse = JSON.parse(data);
                    if (jsonResponse["proccess"] == "success") {
                        var url = "{{URL::to('/booking_checkout')}}";
                        window.location.replace(url);
                    } else {
                        $(".errorsDetails").remove();
                        $.each(jsonResponse["errors"], function (i, value) {
                            var p = document.createElement("p");
                            p.setAttribute("class", "label label-danger errorsDetails");
                            p.textContent = value;
                            $("input[name=" + i + "]").parent().append(p);
                            $("textarea[name=" + i + "]").parent().append(p);
                        })
                    }
                }
            };

            e.preventDefault();
            var action = $("#requestForm").attr('action');
            var formData = new FormData(form);
            if (records[0] != null) {
                formData.append("recipient-record", records[0]);
            }
            if (records[1] != null) {
                formData.append("sender-record", records[1]);
            }
            xhttp.open("post", action);
            xhttp.send(formData);
        });
        $("#occasion_id").change(function() {
            var id = $(this).val();
            var artist_profile_id = $('#artist_profile_id').val();
            var optionText = $('option:selected', this).text();
            $('#Occassion').val(optionText);
            $.get("occasions/get-price?id="+id+"&artist_profile_id="+artist_profile_id, function(data){
                $('#video_price').val(data);
                data = data.replace(/\.00$/,'');
                if (data == Math.floor(data) && data < 999) {
                    $("#book-now").css("font-size", "14px");
                }else{
                    $("#book-now").css("font-size", "12px");
                }
                $('#book-now').text("BOOK NOW FOR $"+data);
            });
        });
        $('#video-hidden').on('click', function () {
            if ($("#checkbox2").val() == 0) {
                $("#checkbox2").val(1)
            } else {
                $("#checkbox2").val(0)
            }
        });
    </script>
      
    </body>
</html>
