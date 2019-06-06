<!DOCTYPE html>
<html lang="en">
<head>
  @include('frontend.common.head')
</head>
<body class="cb-page video-detail">
  <div class="cb-pagewrap"> 
    @include('frontend.common.header')
    <section id="mian-content">
        <div class="container">
            <div class="cb-grid">
                <div class="cb-block cb-box-70 main-content">
                    <div class="cb-content nopadding-right nomargin-right">
                        <div class="inner-block">
                        	<h1 class="heading">
                                <span class="txt1">{{$user_detail->Name}}</span>
                                <span class="txt2">Video Description</span>
                            </h1>
                          <?php $str = $video_detail->VideoURL;$rest = substr($str,28);?>
                         <!-- <iframe id="video_ip" src="<?php //echo $rest ;?>" frameborder="0" allowfullscreen></iframe> -->
                          <video style="width:100%" id="myvideo" controls>
                                <source src="<?php echo $rest ;?>" type="video/mp4"></source>
                                <source src="<?php echo $rest ;?>" type="video/webm"></source>
                                <source src="<?php echo $rest ;?>" type="video/ogg"></source>
                                <source src="<?php echo $rest ;?>"></source>
                            </video>
                          <div class="SinglePostStats">
                          	<div class="media-object-section">
								<div class="author-des clearfix">
                                    <div class="post-detail">
                                        <span class="artist_name">{{$video_detail->Title}}</span>
                                        <div class="singlePostDescription">
                                            {{$video_detail->Description}}
                                        </div>
                                    </div>
                                    <div class="share">
                                    <!--<div class="post-like-btn">
                                      <span><a href="#"><i class="fa fa-thumbs-o-up"></i></a></span>
                                      <span><a href="#"><i class="fa fa-thumbs-o-down"></i></a></span>
                                    </div>-->
                                    {{--*/ $Pro_id=$user_detail->ProfileId; 
                                    $id= array();/*--}}
    
                                    @foreach($req_pro_id as $req_pro_ids)
                                      <?php $id[]=$req_pro_ids->requestToProfileId;?>
                                    @endforeach
                                    @if (in_array($Pro_id, $id))
                                    <div class="download">
                                      <a href="{{URL('Buy/'.$video_detail->VideoId)}}">Download</a>
                                    </div>
                                   @endif
								</div>
                              	</div>								
                            </div>
                            <div class="artist_details"> 
                                <div class="artist_img_sec">
									                                                 
                                    <div class="author-single-post">
                                    	<a href="#"><img src="{{$user_detail->profile_path}}"></a>
                                    </div>
                                    <a href="#">{{$user_detail->Name}}</a>
                                </div>
                            </div>                              
                          </div>
                          <!-- <div class="vodeo_box3 comment-wrap">
                          
                          <h5><i class="lnr lnr-bubble"></i> COMMENTS • <?php echo count($comment_detail);?></h5>
                    
                          <form action="{{URL('comment')}}" method="post">
                            <div class="user_comment">
                              <span class="glyphicon glyphicon-user"></span>
                              <span class="glyphicon glyphicon-bookmark"></span>
                              <textarea name="message" placeholder="Add a public comment..." class="area-msg"></textarea>
                              {!! csrf_field() !!}
                              <input type="hidden" name="to_profile_id" value="{{$video_detail->ProfileId}}">
                              <input type="hidden" name="video_id" value="{{$video_detail->VideoId}}">
                            </div>
                            <input type="submit" value="comment" class="btn btn-info" >
                          </form>
                          <ul>
                            @foreach ($comment_detail as $comment)
                            <li><img src="{{ $comment->profile_path }}" alt="" >{{ $comment->Name }} {{ $comment->Message }}</li>
                            @endforeach                      
                          </ul>
                        </div>  --> 
                      </div>
                    </div>
                  </div>
                  <div class="cb-block cb-box-30 right-bar">
                  	<div class="cb-content video_search">
                        <div class="inner-block">
                        	<h2 class="heading">
                            	<span>Search Videos</span>
                            </h2>
                        	
                            <form role="search" method="get" id="searchform">
                                <div class="input-group">
                                    <input type="text" placeholder="Enter your keyword" class="input-group-field">
                                    <div class="input-group-button">
                                        <input type="submit" value="Submit" class="button">
                                    </div>
                                </div>
                            </form>
                        </div>
					</div>
                    <div class="cb-content">
                        <div class="inner-block">
                        	<h2 class="heading">
                            	<span>Related Videos</span>
                            </h2>
                            <div class="widgetContent">
                                @foreach($other_video as $other_videos)
                                <div class="video-box thumb-border">
                                    <div class="title_des_img">                            
                                        <a href="{{URL('vrl/'.$other_videos->ProfileId.'/'.$other_videos->VideoId)}}">
                                        <?php $str = $other_videos->VideoThumbnail;
                                                    $rest = substr($str,21);?>
                                         <img src="<?php echo $rest?>" alt="">                             
                                        </a>                                
                                    </div>
                                    <div class="title_des_view">
                                        <h4 class="video_title">{{ $other_videos->Title}}</h4>
                                        <p>{{str_limit($other_videos->Description,50)}}</p>
                                        <!--<p>{{ $other_videos->VideoPrice}}</p>-->
                                     </div>
                                  </div>
                                @endforeach
                            </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>
      </section>
      @include('frontend.common.footer')  
    </div>  
  </body>
</html>