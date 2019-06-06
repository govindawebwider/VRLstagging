<!DOCTYPE html>
<html lang="en">
<head>  
	@include('frontend.common.head')
	<style>
		video::-internal-media-controls-download-button {
			display:none;
		}
		video::-webkit-media-controls-enclosure {
			overflow:hidden;
		}
		video::-webkit-media-controls-panel {
			width: calc(100% + 30px); /* Adjust as needed */
		}
		.video-detail .cb-pagewrap{
			background:url("/<?php //echo $user_detail->video_background;  ?>") no-repeat;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-ms-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			background-attachment: fixed;
			background-position: center center;
		}

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
	<script language=JavaScript>    
		var message="Function Disabled!";   
		function clickIE4()    {      
			if (event.button==2)        
		{//alert(message); return false;        }    }     function clickNS4(e){         if (document.layers||document.getElementById&&!document.all){         if (e.which==2||e.which==3){          //alert(message); return false;         }       }     }     if (document.layers){       document.captureEvents(Event.MOUSEDOWN);       document.onmousedown=clickNS4;    } else if (document.all&&!document.getElementById){     document.onmousedown=clickIE4;    }     document.oncontextmenu=new Function("return false")   
</script>
</head>
<body class="cb-page video-detail notranslate">
	<div class="cb-pagewrap">
		@include('frontend.common.userHeader')
		<?php $s3 = \Illuminate\Support\Facades\Storage::disk('s3')?>
		<section id="mian-content">
			<div class="container">        
				<div class="cb-grid"> 
					<?php //dd($video_detail);?>         
					<div class="cb-block cb-box-70 main-content video-detail">
						<div class="cb-content heading">
							<div class="inner-block">              
								<h1 class="heading">
									<span class="txt1">{{ !empty($video_detail)?$video_detail->title:$sample_video->Title}}</span>
								</h1>
									@if (!is_null($artist_path))
										<span><a href="{{URL($artist_path)}}">{{$name}} </a></span>
									@else
										<span class="txt-color">{{$name}}</span>
									@endif
								@if(Session::has('error'))
								<div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>
								@endif
								@if(Session::has('success'))
								<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>
								@endif
								<?php 
								$str = !empty($video_detail)?$video_detail->url:$sample_video->VideoURL;
								$needle   = "http://videorequestline.com";
								if( strpos( $str, $needle ) !== false ) {
									$rest = substr($str,10);
								}else
								{
									$rest = substr($str,5);
								}
							    $url = !empty($video_detail)? "requested_video/watermark/":"video/watermark/"
								?>
								<!-- <iframe id="video_ip" src="<?php //echo $rest ;?>" frameborder="0" allowfullscreen></iframe> -->                          
								<video style="width:100%;max-height:575px;" id="myvideo" controls autoplay>

									<source src="{{ $s3->url($url)}}{{$str}}" type="video/mp4"></source>

									<source src="{{$s3->url($url)}}{{$str}}" type="video/webm"></source>

									<source src="{{$s3->url($url)}}{{$str}}" type="video/ogg"></source>
								</video>   
								<div class="SinglePostStats">                          	
									<div class="media-object-section">
										<div class="author-des clearfix">                                    
											<div class="singlePostDescription"> 
												<?php 
												$string=!empty($video_detail)?$video_detail->description:$sample_video->Description;
												$desc=substr($string,0,300);
												echo $desc."....";
												?>
												<!-- <span class="more"><a href="#">More ...</a></span> -->

											</div>                                      
											<div class="share">                                    
											</div>                                
										</div>								                              
									</div>
									 <div class="artist_details">
									<div class="artist_img_sec clearfix">
										<div class="author-single-post">
											<div class="cb-content">
												@if(!empty($comment_detail))
												@foreach ($comment_detail as $testimonial)
													@if ($testimonial->AdminApproval == '1')
													<div class="testimo_wraper_block">
														<div class="inner_wrap">
															<div class="mess">
																<div class="user_img"><i class="fa fa-user"></i></div>
																<div class="txt_block">
																	<span class="name">{{$testimonial->user_name}}
																		<input id="input-6" name="rate" class="rating"
																			   data-size="uni"
																			   value="{{$testimonial->rate}}"
																			   data-glyphicon="false"
																			   data-rating-class="fontawesome-icon" data-show-caption="false" data-readonly="true">
																	</span>
																	<span class="date">{{date('M d Y H:i:s',strtotime($testimonial->created_at))}}</span>
																	<span class="txt">{{$testimonial->Message}}</span>
																</div>
															</div>
														</div>
													</div>
													@endif
												@endforeach
												@endif
												@if (empty($comment_detail) && !empty($video_detail) && !is_null($artist_path))
												<div class="testi_form user_profile">
												@if(count($reactionvideos)==0)
													<div id="recVideoDiv" style="float:right;margin-top:-70px;">
														<form action="/reaction_video_uplaod" method="POST" enctype="multipart/form-data" role="form">
															{{ csrf_field() }}
															<input type="hidden" name="requested_video_id" value="{{$video_detail->id}}">
															<label id="uploadLabel" for="recVidId" class="btn profile-update-btn">
																<i class="fa fa-video-camera"></i> Reaction Video Upload
															</label>
															<p id="vidPath" style="margin-top:10px;margin-left:10px;"></p>
															<input name="file" id="recVidId" class="btn profile-update-btn" type="file" style="display:none">
															
															<button type="reset" id="cancelUpload" class="btn profile-update-btn" style="display:none;margin-top:-10px;">Cancel</button>    
															<button type="submit" id="submitUpload" class="btn profile-update-btn" style="display:none;margin-top:-10px;">Submit</button>
															<!--<input type="reset" id="cancelUpload" class="btn" value="Cancel Upload" style="display:none">
															<input type="submit" id="submitUpload" class="btn" value="Upload Reaction" style="display:none"> 
															<button type="submit" id="submitUpload" class="btn" onchange="this.form.submit()" style="margin-top:-10px;float:right;display:none">Upload Reaction Video</button> -->
														</form>
													</div>
												@endif
													<h1>Leave a Comment here</h1>
													<form action="/video_testimonial" method="POST" role="form">

														{{ csrf_field() }}

														<input type="hidden" id="to_profile_id" name="to_profile_id"  value="{{$video_detail->uploadedby}}">

														<input type="hidden" id="by_profile_id" name="by_profile_id" value="{{$video_detail->requestby}}">

														<input type="hidden" id="video_id" name="video_id" value="{{$video_detail->id}}">

														<div class="form-group">
															<label for="message">Message</label>
															<textarea class="form-control" id="message" name="message"></textarea>
															@if($errors->first('message'))
																<p class="label label-danger" >
																	{{ $errors->first('message') }}
																</p>
															@endif

														</div>
														<div class="form-group">
															<label class="rate">Rate</label>
															<input id="input-17a" name="rate" class="rating"
																   data-size="md"
																   value="0"
																   data-glyphicon="false"
																   data-rating-class="fontawesome-icon" data-show-caption="false">
															@if($errors->first('rate'))
																<p class="label label-danger" >
																	{{ $errors->first('rate') }}
																</p>
															@endif
														</div>
														<span class=""></span>
                                                        <button type="reset" class="btn profile-update-btn" onclick="history.back(-1)">Cancel</button>    
														<button type="submit" class="btn profile-update-btn">Submit</button>

													</form>
													@if(count($reactionvideos) > 0)
														<div class="box-body no-padding">
															<h1>Reaction Video</h1>
															<ul class="users-list clearfix">
																@foreach($reactionvideos as $reactions)
																<li> 
																	<a href="javascript:void(0)" id="play_btn_{{$reactions->id}}" data-url="{{$reactions->VideoURL,28}}"> 
																		<span class="img"> 
																			<img alt="User Image" src="{{ $s3->url('images/thumbnails/'.$reactions->thumbnail)}}" alt="reaction video" style="width:100px;height:100px;margin-top:15px;border:2px solid #777;">
																			<i class="icon icon-play3"></i> 
																		</span> </a>
																		<a href="#" class="users-list-name"></a>
																	{{$reactions->VideoName}}
																</li>
																@endforeach
															</ul>
														</div> 
													@endif

												</div>
												@endif
											</div>                                                                           

										</div>                                                                         	


									</div>                             

								</div>
							</div>                          
						</div>                    
					</div>                 
				</div>                  
					<div class="cb-block cb-box-30 right-bar video_details video-detail">
					<div class="cb-content video_search heading">
						<div class="inner-block">                       
							<h2 class="heading">
								<span class="txt1">Search Videos</span>
							</h2>                                             
							<form role="search" method="get" id="searchform"  action= "/search" >                       
								<div class="input-group">                          
									<input type="text" placeholder="Enter your keyword" name="search_query"
										   class="input-group-field">
									<div class="input-group-button">                    
										<input type="submit" value="Submit" class="button">
									</div>  
								</div>
							</form>                   
						</div>                
					</div>
					<div class="cb-content heading">

						<div class="inner-block">

							<h2 class="heading">                

								<span class="txt1">Related Videos</span>

							</h2>

							<div class="widgetContent">
								@foreach($related_video as $other_videos)   
								<div class="video-box thumb-border"> 
									<div class="title_des_img">
										<a href="{{URL('video_detail/'.$other_videos->id)}}"> 


											<?php $str = $other_videos->thumbnail;

											$rest = substr($str, 5);?>

											<img src="{{ $s3->url('images/thumbnails/'.$str) }}" alt="">

										</a>                                                       

									</div>                       

									<div class="title_des_view">                          

										<h4 class="video_title">{{ $other_videos->title}}</h4>  

										<p>{{str_limit($other_videos->description,50)}}</p>



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
    <div class="modal fade" tabindex="-1" role="dialog" id="mymodal123">    
      <div class="modal-body">
       <div id="videoContainer">
            <div class="pop-inner"> <i class="icon icon-cancel-circle close-pop"></i>
                <iframe frameborder="0" allowfullscreen wmode="Opaque" id="play_vid"></iframe>
            </div>
        </div>
      </div>    
 </div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#myvideo').bind('contextmenu', function(e) {
			return false;
		}); 
	});
        
	var baseurl = "<?php echo $s3->url('uploads/reaction-videos/'); ?>";
	$('a[id*="play_btn_"]').on('click', function(){
		$('#mymodal123').modal('show');
		$('.pop-inner').slideDown(500);
		var url = baseurl+$(this).data('url');
		$('#play_vid').attr({'src':url});
	});
	$('.close-pop').on('click', function(){
		$('.pop-inner').slideUp(500);
		$(this).delay(500).parents('#mymodal123').modal('hide');
	});
    
	
	$('#recVidId').change(function() {
		if ($(this).val()) {		
			var filename = $(this).val().replace(/C:\\fakepath\\/i, '');
			$(this).closest('#recVideoDiv').find('#vidPath').html(filename);			
			$("#uploadLabel").hide();
			$("#submitUpload").show();
			$("#cancelUpload").show();
		}
	});
	$('#cancelUpload').click(function(){
		var filename = ' ';
		$(this).closest('#recVideoDiv').find('#vidPath').html(filename);
		//$("#vidPath").hide();
		$("#uploadLabel").show();
		$("#submitUpload").hide();
		$("#cancelUpload").hide();
	});


</script>
</body>   
</html>