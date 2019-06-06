@include('admin.common.header')
<body class="admin testimonial_list">
<section class="main-page-wrapper">
  <div class="main-content">
    <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>
    @include('admin.layouts.header')
    <div class="testimonial_list_wrap">
      <div  class="col-md-12 ">
        <div id="page-wrapper">
			<div class="graphs">
				<h1 class="heading">Add Default Review</h1>
				<p class="desc-title"></p>
				<div class="table-responsive dataTables_wrapper">
					<div class="cb-block cb-box-100">
						@if(Session::has('success'))
                            <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>
                        @endif   

				          <div class="cb-content">
				            <div class="inner-block">
				              <div class="testi_form">
				                <?php //dd($artist);?>
				                <form action="/default_testimonial" method="POST" role="form">
									{{csrf_field()}}
									<input type="hidden" class="form-control" id="to_profile_id" name="to_profile_id"  value="{{$admin->ProfileId}}">
									<div class="form-group">
										<label for="name">Name</label>
										<input type="text" class="form-control" id="name" name="name"  value="{{$admin->Name}}" disabled>
									</div>
									<div class="form-group">
										<label for="email">Email</label>
										<input type="text" class="form-control" id="email" name="email" value="{{$admin->EmailId}}" disabled >
									</div>
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
										<label for="message">For Artist</label>
										<select name="artist_id">
										@foreach ($artist as $artist)
											<option value="{{$artist->ProfileId}}">{{$artist->Name}}</option>
										@endforeach	
										</select>
									</div>
									<span class=></span>
									<button type="submit" class="btn btn-primary">Submit</button>
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
  </div>
</section>
@extends('admin.common.footer')
</body>
</html>