@include('admin.common.header')
<body class="admin video_request">
    <section class="main-page-wrapper">
		<div class="main-content">
			<div id="left-side-wrap"> 
            @include('admin.layouts.lsidebar') </div>
			@include('admin.layouts.header')
  
            <div class="video_request_wrap">
				<div  class="col-md-12 ">
					<div id="page-wrapper">
                    	<div class="graphs">
							<h1 class="heading">View Video Request</h1> 
              <p class="desc-title"></p>
              
              
                            
        <div class="users">
          <div class="table-responsive dataTables_wrapper">
         
          </div>
        </div>
						</div>
					</div>
            	</div>
            </div>
		</div>
  </section>
  @include('admin.common.footer')
</body>
</html>