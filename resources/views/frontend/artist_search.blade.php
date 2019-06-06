<!DOCTYPE html>
<html lang="en"><head>	
@include('frontend.common.head')
</head>
<body class="cb-page search">	
	<div class="cb-pagewrap"> 	
		@include('frontend.common.header')	
			<section id="mian-content">			
				<div class="container">			
					<div class="cb-grid">									
						<div class="cb-block cb-box-100 search-result-title">						<div class="cb-content">					
								<div class="inner-block">				
									<div class="heading-bg-search">		
										<h1>Search results for <span></span></h1>	
											
									</div>						
									<div class="not-found">Search Not Found?...
									</div>
								</div>						
							</div>					
						</div>										
						<div class="search-artist cb-box-100">						
							<div class="cb-block cb-box-70">										
<div class="cb-content nopadding-right nomargin-right">								
	<div class="inner-block">									
		@foreach ($search_result as $artist)																			
		<li class="artist-list">										
			<div class="artist-title"> 													
				<a href="{{URL('/')}}/{{ $artist->ProfileId }}">												
					<span class="artist-img">
						<img src="{{$artist->profile_path}}" alt="img">
					</span> 												
					<span class="artist-name">{{$artist->Name}}</span> 												
					<span class="artist-name">{{$artist->profile_description}}</span> 											
				</a>										
			</div>									
		</li>									
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