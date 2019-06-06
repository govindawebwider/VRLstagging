@include('admin.common.header')



<body class="admin artist_list">

	<section class="main-page-wrapper">

		<div class="main-content">

			<div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>

			@include('admin.layouts.header')

			<div class="artist_list_wrap">

				<div  class="col-md-12 ">

					<div id="page-wrapper">

						<div class="graphs">

							@if(Session::has('success'))

							<div class="alert alert-success"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('success') }}</span> </div>

							@endif

							@if(Session::has('error'))

							<div class="alert alert-danger"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('error') }}</span> </div>

							@endif

							<h1 class="heading">View Slider <span><a  class="btn btn-primary" style="float:right" href="{{URL('/sliders')}}">Back</a> </span></h1>
                            <p class="desc-title"></p>

							

          <!-- <a href="{{URL('/artist_csv')}}">



            <input class="btn btn-primary" type="button" name="artist_csv" value="Export Artist List">



        </a> -->

        

        <div class="artists"> 

        	

        	<!--  <h1 class="heading">Slider</h1> -->

        	

        	<form action="{{URL('update_slider')}}" method="POST" enctype="multipart/form-data" role="form">

        		{!! csrf_field(); !!}

        		<div class="form-group">

        			<div class="control-label">

        				<label for="slider_title">Title</label>

        			</div>

        			<div class="control-box">

        				<input type="hidden" name="slider_id" value="{{$sliders->id}}">

        				<input type="text" name="slider_title" value="{{$sliders->slider_title}}" class="form-control" id="slider_title">

        				@if($errors->first('slider_title'))

        				<p class="label label-danger" > {{ $errors->first('slider_title') }} </p>

        				@endif </div>

        			</div>

        			<div class="form-group">

        				<div class="control-label">

        					<label for="slider_description">Description</label>

        				</div>

        				<div class="control-box">

        					<textarea name="slider_description" class="form-control" cols="30" rows="10" id="slider_description">{{$sliders->slider_description}}</textarea>

        					@if($errors->first('slider_description'))

        					<p class="label label-danger" > {{ $errors->first('slider_description') }} </p>

        					@endif </div>

        				</div>

        				<div class="form-group">

        					<div class="control-label">

        						<label for="slider">Choose Slider</label>

        					</div>

        					

        					

        					<div class="control-box">

        						<div><img src="{{url('images/Sliders/')}}/{{$sliders->slider_path}}" alt="" height="100" width="100"></div>

        						<label for="slider">Choose Slider</label>

        					</div>

        				</div>

        				

        				

        				<div class="form-group">

        					<div class="control-label">

        						<label for="slider"></label>

        					</div>

        					

        					

        					<div class="control-box">

        						<input type="file" name="slider" class="form-control" id="slider">
        						<span class="msg">Image must be 400 X 400 px and jpeg, png format</span>

        						@if($errors->first('slider'))

        						<p class="label label-danger" > {{ $errors->first('slider') }} </p>

        						@endif 

        					</div>

        				</div>

        				

        				

        				<div class="form-group">

        					<div class="control-label">

        						<label for="slider"></label>

        					</div>

        					

        					

        					<div class="control-box">

        						<input type="submit" class="btn btn-primary" value="Update">

        					</form>

        				</div>

        			</div>

        			

        			

        			

        			

        		</div>

        	</div>

        </div>

    </div>

</div>

</div>

</div>

</section>

@extends('admin.common.footer')

</body>

</html>