<!DOCTYPE html>
<html lang="en">
<head>
  @include('frontend.common.head')
  <style>
    .artist-detail .cb-pagewrap {
      background: url("/<?php echo $artist_detail->BannerImg;  ?>") no-repeat;
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -ms-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
      background-attachment: fixed;
      background-position: center center;
    }
    /*.artist-detail h1.heading span{color:#<?php //echo $artist_detail->text_color;  ?>; font-size:<?php //echo $artist_detail->name_heading_size;?>rem;}*/
    .artist-detail h2.heading {
      color:#<?php echo $artist_detail->title_color;
      ?>;
      font-size:<?php echo $artist_detail->size;
      ?>;
    }
    .artist-detail .profile_cover h1.heading span
    {
      color:#<?php echo $artist_detail->text_color;
      ?>;
      font-size:<?php echo $artist_detail->name_heading_size;
      ?>rem;
    }
  </style>
  <style type="text/css">
    <?php echo $artist_detail->custom_css;?>
  </style>
  <script>
    document.getElementsByClassName("new_pause").hide();
    function myvideo(id){
      var id1='video_'+id;
      console.log(id1);
      document.getElementById(id1).play();
    }
    function myvideo_pause(vrl){
      var id1='video_'+vrl;
      console.log(id1);
      document.getElementById(id1).pause();
      document.getElementById('play').show();
      document.getElementById('pause').hide();
    }
  </script>
</head>
<body class="cb-page artist-detail">
  <div class="cb-pagewrap"> 
    @include('frontend.common.header')
    <section id="mian-content">
      <div class="container">
          @if(Session::has('error'))
           <div class="alert alert-danger">
                You can not request a video as the artist. Please logout to view your form.
            </div>
          @endif
        <div class="cb-grid">
           
          <div class="cb-block cb-box-100">
            <div class="cb-content">
              <div class="inner-block">
                <div class="profile_cover"> <span class="CoverImageContainer"><img src="{{ $artist_detail->header_image}}"></span>
                  <div class="inner_profile_cover" >
                    <div class="artist-detail-wrap">
                      <div class="get_now_wrap">
                        <div class="artist-des">
                          @if($artist_detail->profile_description!=null)
                          <span id="more">{{$artist_detail->profile_description}} <span id="hide_less"> Less</span></span>
                          <span id="less"> {{str_limit($artist_detail->profile_description,250)}} 
                            <span>
                              <?php $tot_str=strlen($artist_detail->profile_description);?>
                              @if($tot_str>250)
                              More
                              @endif
                            </span>
                          </span>
                          @else
                          <span id="morebtn" class="box-more-btn"></span> 
                          @endif 
                        </div>
                        @if(Session::get('type') == "Artist") <a href="javascript::void()">
                        <div class="vod_grid_request_btn"> <span class="icon"> ${{ $artist_detail->VideoPrice}} </span> <span class="price">
                          <div class="title"> <span>video Price</span> </div>
                        </span> </div>
                      </a> @else 
                      @if($artist_detail->timestamp != '')
                      <span class="dev_days">Turnaround: {{ $artist_detail->timestamp}} day(s)</span>
                      @else

                      @endif
                      <a href="{{URL('RequestNewVideo/'.$artist_detail->ProfileId)}}">
                        <div class="vod_grid_request_btn"> 
                          <span class="icon">


                            ${{ $artist_detail->VideoPrice}} 
                          </span> 
                          <span class="price">
                            <div class="title"> <span>Request a video</span> </div>
                          </span> </div>
                        </a> 
                        @endif </div>
                      </div>

                      <div class="artist-detail-wrap">
                        <div class="artist_des_wrap">
                          <div class="artist-img"> <img src="/{{ $artist_detail->profile_path}}"> </div>
                        </div>                  
                      </div>
                      <?php //dd($social_link);?>
                      <h1 class="heading"><span class="txt1">{{ $artist_detail->Name}}</span><!--<span class="txt2">of artist</span>--></h1>
                      <ul class="social-wrap">
                        @foreach ($social_link as $social_links)
                        <li> <a target="_blank" href="{{ $social_links->social_url ==""?'javascript::void(0)': $social_links->social_url}}"><img src="{{$social_links->social_img}}" height="20px" width="20px"></a></li>
                        @endforeach
                  <!-- @if($artist_detail->facebook_link!="")
                  <li> <a target="_blank" href="{{ $artist_detail->facebook_link ==""?'javascript::void(0)': $artist_detail->facebook_link}}"> <i class="fa fa-facebook"></i> </a> </li>
                  @endifartist_deta
                  
                  
                  
                  @if($artist_detail->twitter_link!="")
                  <li> <a target="_blank" href="{{ $artist_detail->twitter_link ==""?'javascript::void(0)':$artist_detail->twitter_link}}"><i class="fa fa-twitter"></i></a></li>
                  @endif
                  
                  
                  
                  @if($artist_detail->instagram_link!="")
                  <li><a target="_blank" href="{{ $artist_detail->instagram_link ==""?'javascript::void(0)':$artist_detail->instagram_link}}"><i class="fa fa-instagram"></i></a></li>
                  @endif -->
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php if(count($video)!=0){?>
      <div class="cb-block cb-box-100">
        <div class="cb-content">
          <div class="inner-block">
            <div class="artist-videos-warp cb-box-100">
              <?php if(count($video)>0){?>
              <h2 class="heading">
                @if(count($video)==1)
                <span>Video</span>
                @else
                <span>Videos</span>
                @endif
              </h2>
              <?php }?>
              <?php //dd($video);?>
              @foreach ($video as $video_data)

              <div class="artist-video">
                <div class="videos wi-25">
                  <div class="video-des">
                    <?php $str = $video_data->VideoThumbnail;
                    $pic = substr($str,28);?>
                    <div class="video"> <!-- <img src="<?php echo $pic;?>"> -->
                      <video style="width:100%;height:200px;"  id="video_{{$video_data->VideoId}}" src="{{substr($video_data->VideoURL,28)}}" controls 
                        <?php if($video_data->profile_auto_play_status=='Enable')
                        {
                          echo "autoplay";
                        }?>></video>
                      </div>
                      <div class="video-content">
                        <h3>{{str_limit($video_data->Title,30)}}</h3>
                        <li>{{str_limit($video_data->Description,50)}}</li>
                        
                        <ul>
                          <li><a href="{{URL('video/'.$video_data->ProfileId.'/'.$video_data->Title)}}">view</a></li>
                          <li> <span class="icon-play new_play" id="play_{{$video_data->VideoId}}" onClick="myvideo({{$video_data->VideoId}})">
                            <a href="javascript:void(0)" >
                              <i class="fa fa-play"></i>
                            </a></span>
                            <span class="icon-play new_pause" id="pause_{{$video_data->VideoId}}" onClick="myvideo_pause({{$video_data->VideoId}})">
                              <a href="javascript:void(0)" >
                                <i class="fa fa-pause"></i>
                              </a></span> </li>
                            </ul>
                          </div>
                        </div>
                        @if($video_data->download_status=='Enable')
                        <a class="btn btn-primary" href="download_sample_video/{{substr($video_data->VideoURL,45)}}">Download</a>

                        @endif
                      </div>
                    </div>
                    @endforeach
                    <div class="pagination"> {!! $video->render()!!} </div>
                  </div>
                </div>
              </div>
            </div>
            <?php }?>
            @if( Auth::check() )
            @if( Auth::user()->type == 'User')
            <div class="cb-block cb-box-100">
              <div class="cb-content">
                <div class="inner-block">
                  <div class="testi_form">
                    <h2 class="heading"><span>Add Your Comment</span></h2>
                    @if($errors->first('message')) 
                    <p class="label label-danger" >
                     <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>  {{ $errors->first('message') }} </div>
                   </p>

                   @endif
                   @if(Session::has('success'))
                   <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>
                   @endif
                   @if(Session::has('error'))
                   <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>
                   @endif
                   <form action="/comment" method="POST" role="form">
                    {{csrf_field()}}
                    <input type="hidden" class="form-control" id="to_profile_id" name="to_profile_id"  value="{{$artist_detail->ProfileId}}">
                    <div class="form-group">
                      <label for="name">Name</label>
                      <input type="text" class="form-control" id="name" name="name"  value="{{$user_data->user_name}}" disabled>
                    </div>
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="text" class="form-control" id="email" name="email" value="{{$user_data->email}}" disabled >
                    </div>
                    <div class="form-group">
                      <label for="message">Message</label>
                      <textarea class="form-control" id="message" name="message"></textarea>

                    </div>
                    <span class=></span>
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          @endif
          @endif

          <?php //dd($testimonials);?>

          <div class="cb-block cb-box-100">
            <div class="cb-content">@if(count($testimonials) > 0)
              <div class="inner-block">
                <div class="testi">
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
                        <span><i class="fa fa-user"></i> {{$testimonial->user_name}}</span> </div>
                      </div>
                      @endforeach </div>
                    </div>
                  </div>@endif
                </div>
              </div>
            </div>
          </div>
        </section>
        @include('frontend.common.footer') 
      </div>
    </body>
    </html>