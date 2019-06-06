<!DOCTYPE html>
<html lang="en">
<head>
	@include('frontend.common.head')
</head>
<body class="cb-page artist-video">
	<div class="cb-pagewrap">
		@include('frontend.common.header')
		<section id="mian-content">
			<div class="container">
				<div class="cb-grid">
					<div class="cb-block cb-box-100">
						<div class="cb-content">
							<div class="inner-block">
								<h1 class="heading"><span class="txt1">{{$artist->Name}}</span> <span class="txt2">

									My Video List</span></h1>
									@foreach ($video as $videos)
									<?php $path=$videos->VideoThumbnail;$pic=substr($path, 22);?>
									<div class="artists-video wi-33-3">
										<div class="artists-video-block">
											<div class="inner-artists-video">
												<div class="artists-video-img">
													<img class="responsive-img" src="<?php echo $pic;?>" alt="" >
													<div class="video-circle-arrow">
														<a href="{{URL('video/'.$videos->ProfileId.'/'.$videos->Title)}}"><span class="fa fa-play"></span></a>
													</div>
												</div>
												<div class="video-content">
                                                  <span class="title"><a href="javascript:void(0)">{{ $videos->Title }}</a></span>
                                                  <!--<span class="name"><a href="javascript:void(0)">{{ $videos->Name }}</a></span>-->
                                                </div>
											</div>

										</div>
									</div>
									@endforeach
									<div class="pagination"> {!! $video->render()!!} </div>
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