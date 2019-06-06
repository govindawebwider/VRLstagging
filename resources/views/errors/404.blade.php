<!DOCTYPE html>



<html lang="en">

<head>

  @include('frontend.common.head')

</head>

    <title>Page Not Found</title>    

</head>

<body class="cb-page error">



    <div class="cb-pagewrap"> 

   <section id="top-bar">

    

	<div class="container">

        <div class="offcanvas-toggle">

            <i class="fa fa-fw fa-bars"></i>

        </div>

		<div class="cb-grid">

			<div class="cb-block cb-box-30">

				<div class="cb-content">

					<div id="logo"> <a href="/"><img class="logo" src="/images/logo1.png" alt="VRL logo" width="120" ></a> </div>

				</div>

			</div>

			<div class="cb-block hidden-phone hidden-mobile  cb-box-70">

				<div class="cb-content">

					<nav class="main-menu-wrap">

						<ul class="menu">





							<li class="item1 first {{ Request::is('/') ? 'active' : '' }}"><a href="/"><span>Home</span></a></li>

							<li class="{{ Request::is('view-all-artist') ? 'active' : '' }}"><a href="{{URL('view-all-artist')}}"><span>Artists</span></a></li>

							<li class="{{ Request::is('view-all-video') ? 'active' : '' }}"><a href="{{URL('view-all-video')}}/"><span>Videos</span></a></li>

						

							</li>

							<li class="item3 topsearch last"><a href="javascript:void(0)"><span><i class="fa fa-search"></i></span></a></li>

							<div class="search-wrap">

								<form class="search-form" action="{{URL('search')}}" method="get">

									<input type="text" name="search_query" placeholder="Search">



									<button>Search</button>

									<i class="fa fa-close"></i>

								</form>

							</div>

							



						</ul>

					</nav>

				</div>

			</div>

		</div>

	</div>

</section>





        <section id="mian-content">

            <div class="container">

                <div class="cb-grid">

                    <div class="cb-block">

                        <div class="cb-content">

                            <div class="inner-block">

                            	<div class="error_wrap">

                                	<h1>

                                    	<span>Four</span>

                                        <span class="beat">oh!</span>

                                        <span>Four</span>

                                    </h1>

                                    <h2>We are sorry.</h2>

                                    <h4><span>Sorry, the page you're looking for dosen't exist.</span> <!--Go back to <a href="/">Home</a>--></h4>

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

