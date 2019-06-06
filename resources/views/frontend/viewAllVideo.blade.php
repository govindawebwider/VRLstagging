<!DOCTYPE html><html lang="en"><head>	@include('frontend.common.head')</head><body class="cb-page artist-video">	<div class="cb-pagewrap">			@include('frontend.common.header')		<section id="mian-content">			<div class="container">				<div class="cb-grid">					<div class="cb-block cb-box-100">					<!-- Search-->					<!--<div class="cb-content video_search">                      <div class="inner-block">                       <h2 class="heading">                         <span>Search Videos</span>                       </h2>                                              <form role="search_video" method="get" id="searchform"  action= "/search_video" >                        <div class="input-group">                          <input type="text" placeholder="Enter your keyword" name="search_query"  class="input-group-field">                          <div class="input-group-button">                            <input type="submit" value="Submit" class="button">                          </div>                        </div>                      </form>                    </div>                  </div>-->						<div class="cb-content">							
<div class="inner-block">								
  <h1 class="heading  @if(count($video)==0)  text-center @endif">
    @if(count($video)==0)  
       <span class="text-center">No videos are available at this time</span> 
    @elseif(count($video)==1)
    <span class="txt1">Video</span> 
    <span class="txt2">list of Video</span>
    @else
    <span class="txt1">Videos</span> 
    <span class="txt2">list of Videos</span>
    @endif
  </h1>
    @if(Session::has('success-review'))
    <div class="alert alert-success">
        Thank you for your review, you can also send gift for your friends
    </div>
    @elseif (Session::has('error-review'))
     <div class="alert alert-error">
       Sorry Something Went Wrong you can do this action
    </div>
    @endif
  <?php //dd($video);?>								

  @foreach ($video as $videos)								

  <?php $path=$videos->VideoThumbnail;$pic=substr($path, 28);?>								

  <div class="artists-video wi-33-3">									

   <div class="artists-video-block">										

    <div class="inner-artists-video">											

     <div class="artists-video-img">

      <?php //if($videos->video_auto_play_status!="Enable"){?>											

      <img class="responsive-img" src="<?php echo $pic;?>" alt="" >

      <?php //}?>

      <?php //if($videos->video_auto_play_status=="Enable"){?>

<!-- <video class="view-video-section-hight" style="z-index: -99;-ms-transform: translateX(-50%) translateY(-50%);-moz-transform: translateX(-50%) translateY(-50%);

    -webkit-transform: translateX(-50%) translateY(-50%);

    transform: translateX(2%) translateY(3%);

    margin-top: -45px;

    height: 300px !important;

    width: 398px;

" id="video_{{$videos->VideoId}}" src="{{substr($videos->VideoURL,28)}}" controls 

                      <?php //if($videos->video_auto_play_status=='Enable')

                      {

                        //echo "autoplay";

                      }?>></video> -->

                      <?php //}?>												

                      <div class="video-circle-arrow"> 													

                      	<a href="{{URL('video/'.$videos->ProfileId.'/'.$videos->Title)}}">

                      		<span class="fa fa-play"></span>

                      	</a>												

                      </div>											

                    </div>                                            <div class="video-content">                                              <span class="title">{{ $videos->Title }}</span>                                              <span class="name">{{ $videos->Name }}</span>                                              <!--<span class="title"><a href="javascript:void(0)">{{ $videos->Title }}</a></span>                                              <span class="name"><a href="javascript:void(0)">{{ $videos->Name }}</a></span>-->                                            </div>										</div>									</div>								</div>								@endforeach								<div class="pagination"> {!! $video->render()!!} </div>							</div>						</div>					</div>				</div>			</div>		</section>   




		@include('frontend.common.footer')		</div>	</body></html>