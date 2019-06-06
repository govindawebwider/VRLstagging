<!DOCTYPE html>
<html lang="en">
<head>
	@include('frontend.common.head')
</head>
<body class="cb-page search">
	<div class="cb-pagewrap"> 
		@include('frontend.common.userHeader')
		<section id="mian-content">
			<div class="container">
				<div class="cb-grid">				
					<div class="cb-block cb-box-100 search-result-title">
						<div class="cb-content">
							<div class="inner-block video-search">
								<div class="heading-bg-search">
									@if(count($search_video)>0)
									<h1>Search result for <span><?php echo '"'.$_GET['search_query'].'"';?></span></h1>
									@else
									<h1>Search result  <span><?php echo '"'.$_GET['search_query'].'"';?> not found</span></h1>
									@endif
								</div>
							</div>
						</div>
						
					</div>
					<div class="cb-block cb-box-100 invalid-txt">
						{{--@if(count($search_video)>0 or count($search_result)>0)--}}
						@if(count($search_video)>0)					
						<div class="cb-content video-wrap">
							<div class="inner-block white-box-search">
								@if(count($search_video)==1)
								<h2 class="heading search-heading">
									<span class="txt1">Video</span>
								</h2>
								@else
								<h2 class="heading search-heading">
									<span class="txt1">Videos</span>
								</h2>
								@endif
								<div class="wi-100">
									@foreach ($search_video as $video)
									<div class="wi-25">
										<div class="video"> 
											<?php $img=$video->VideoThumbnail;		
											$path=substr($img,22);?>
											<img class="responsive-img" src="<?php echo $img;?>" alt="video">
											<div class="video-detail"> 
												<h3>{{$video->Title}}</h3>
												<a href="video/{{ $video->ProfileId }}/{{ $video->Title }}"> 
													<img src="/images/play-icon.png" alt="img">
												</a>
											</div>
										</div>
									</div>
									@endforeach
									<div class="wi-20 last">
										<div class="video"> 
											<a href="{{URL('view-all-video')}}" class="view-more responsive-img"><span>View more <i class="fa fa-forward"></i></span></a>											
										</div>
									</div>							
								</div>								
							</div>
						</div>	
						@endif
						{{--@if(count($search_result)>0)
						<div class="cb-content artist-wrap">
							<div class="inner-block white-box-search">
								@if(count($search_result)==1)
								<h2 class="heading search-heading">
									<span class="txt1">Artist</span>
								</h2>
								@else
								<h2 class="heading search-heading">
									<span class="txt1">Artists</span>
								</h2>
								@endif
								
								<div class="wi-100">
									@foreach ($search_result as $artist)	
									<div class="wi-25">
										<div class="artist"> 
											<a href="{{ $artist->profile_url }}"> 
												<!-- <img class="responsive-img" src="{{ $artist->profile_path}}" alt="artist">  -->
												@if($artist->profile_path != "")
												<img src="{{ $artist->profile_path}}" alt="{{ $artist->Name }}" >

												@else
												<img src="/images/Artist/default-artist.png" alt="{{ $artist->Name }}" >
												@endif
											</a>
											<div class="artist-detail"> 
												<h3>{{ $artist->Name }}</h3>
												<div class="btm">
													<a class="javascript:void(0)"><span class="txt">
														<?php $videos = \App\Video::where('ProfileId',$artist->ProfileId)->get();?>

														{{count($videos)}} videos</span></a>

														--}}{{-- <a class="javascript:void(0)"><i class="fa fa-play"></i><span class="txt"> Listen Now</span></a> --}}{{--
													</div>
												</div>
											</div>
										</div>
										@endforeach	
										<div class="wi-25 last">
											<div class="artist"> 
												<a href="{{URL('view-all-artist')}}" class="view-more responsive-img"><span>View more <i class="fa fa-forward"></i></span></a>											
											</div>
										</div>							
									</div>								
								</div>
							</div>	
							@endif--}}
							{{--@else--}}
							@if(count($random_video)>0)					
							<div class="cb-content video-wrap">
								<div class="inner-block white-box-search">
									<h2 class="heading search-heading">
										<span class="txt1">Video</span>
									</h2>
									<div class="wi-100">
										@foreach ($random_video as $rand_video)
										<div class="wi-25">
											<div class="video"> 
												<?php $img=$rand_video->VideoThumbnail;		
												$path=substr($img,22);?>
												<img class="responsive-img" src="<?php echo $img;?>" alt="video">
												<div class="video-detail"> 
													<h3>{{$rand_video->Title}}</h3>
													<a href="/video/{{$rand_video->ProfileId }}/{{ $rand_video->Title }}"> 
														<img src="/images/play-icon.png" alt="img">
													</a>
												</div>
											</div>
										</div>
										@endforeach
										<div class="wi-20 last">
											<div class="video"> 
												<a href="{{URL('view-all-video')}}" class="view-more responsive-img"><span>View more <i class="fa fa-forward"></i></span></a>											
											</div>
										</div>							
									</div>								
								</div>
							</div>
							@endif
							{{--@if(count($random_artist)>0)
							<div class="cb-content artist-wrap">
								<div class="inner-block white-box-search">
									<h2 class="heading search-heading">
										<span class="txt1">Artist</span>
									</h2>
									<div class="wi-100">
										@foreach ($random_artist as $artist)
										<div class="wi-25">
											<div class="artist"> 
												<a href="{{ $artist->profile_url }}"> 
													<!-- <img class="responsive-img" src="{{ $artist->profile_path}}" alt="artist">  -->
													@if($artist->profile_path != "")
													<img class="responsive-img" src="{{ $artist->profile_path}}" alt="{{ $artist->Name }}" >

													@else
													<img src="/images/Artist/default-artist.png" alt="{{ $artist->Name }}" >
													@endif
												</a>
												<div class="artist-detail"> 
													<h3>{{ $artist->Name }}</h3>
													<div class="btm">
														<a class="javascript:void(0)"><i class="fa fa-headphones"></i><span class="txt"> 10 videos</span></a>
														<a class="javascript:void(0)"><i class="fa fa-play"></i><span class="txt"> Listen Now</span></a>
													</div>
												</div>
											</div>
										</div>
										@endforeach	
										<div class="wi-25 last">
											<div class="artist"> 
												<a href="{{URL('view-all-artist')}}" class="view-more responsive-img"><span>View more <i class="fa fa-forward"></i></span></a>											
											</div>
										</div>							
									</div>								
								</div>
							</div>		
							@endif--}}
							{{--@endif--}}
						</div>		
					</div>
				</div>
			</section>
			@include('frontend.common.footer') 
		</div>
	</body>
	</html>