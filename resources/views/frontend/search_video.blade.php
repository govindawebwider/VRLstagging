<!DOCTYPE html><html lang="en"><head>	
@include('frontend.common.head')
</head><body class="cb-page search">	
<div class="cb-pagewrap"> 		
	@include('frontend.common.header')		
	<section id="mian-content">			
		<div class="container">				
			<div class="cb-grid">														
				<div class="cb-block cb-box-100 search-result-title">						
					<div class="cb-content">							
						<div class="inner-block">								
							<div class="heading-bg-search">									
								<h1>Search results for <span></span></h1>								
							</div>								
							<div class="not-found">Search Not Found?...</div>							
						</div>						
					</div>					
				</div>										
				<div class="search-artist cb-box-100">						
					<div class="cb-block cb-box-70">										
						<div class="cb-content nopadding-right nomargin-right">								
							<div class="inner-block">									
								<?php echo "<pre>";//print_r($search_video);echo "</pre>";?>									
								@foreach($search_video as $other_videos)																			
								<li class="artist-list">										
									<div class="artist-title"> 													
										<a href="">												
											<span class="artist-img"><?php $str = $other_videos->VideoThumbnail;													
												$rest = substr($str,21);?>													
												<img src="<?php echo $rest?>" alt=""> </span>													
												<h4 class="video_title">{{ $other_videos->Title}}</h4>													
												<p>{{str_limit($other_videos->Description,50)}}</p> 													
												<span class="artist-name"></span> 													
												<span class="artist-name"></span> 												
											</a>											
										</div>										
									</li>										
									@endforeach									
								</div>								
							</div>							
						</div>																				</div>																							
					</div>				
				</div>			
			</section>			
			@include('frontend.common.footer') 		
		</div>	</body>	</html>