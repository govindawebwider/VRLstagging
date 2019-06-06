<!DOCTYPE html>

<html lang="en">

<head>

	@include('frontend.common.head')

</head>

<body class="cb-page user_profile user_video">

	<div id="popup1" class="overlay">

		<form action="{{URL('/RePayment')}}" method="POST">

			{{ csrf_field() }}

			<div class="popup">

				<a class="close" href="javascript:;" id="hide">&times;</a>
				<div class="form-group content">

					<div class="control-label">

						<label for="description">Total no of Extended days</label>

					</div>

					<div class="control-box">

						<input type="hidden" value="{{$storage_data->status}}" name="storage_data" id="storage_data">

						<input type="number" min="1" max="60"  step="1" name="days" id="days" value="" class="form-control" autocomplete="off">

						<label for="description">Total Price</label>

						<input type="text" name="tot_price" id="tot_price" value="" class="form-control" autocomplete="off" disabled="false">

					</div>

					<div class="pay-btn">

						<a class="btn btn-primary"  id="calc_price" >Calculate</a>

						<div class="video-content" style="display: none;"> 

							<span class="title">

								<input type="hidden" name="request_day" id="request_day">

								<input type="hidden" name="thumbnail" id="thumbnail">

								<input type="hidden" name="user_email" id="user_email">

								<input type="hidden" name="amount" id="amountfinal" value="0">

								<input type="hidden" name="request_id" id="request_id" >

								<input type="hidden" name="url" id="url">

								<input type="hidden" id="requestToProfileId" name="requestToProfileId" >

								<input type="hidden" name="requestByProfileId" id="requestByProfileId">

								<script id="stripe_butt"

								src="https://checkout.stripe.com/checkout.js" class="stripe-button"

								data-key="pk_test_u2EpaiGskW20KXn5Nw7MMJta" 

								/*Livekey*/

								/*data-key="pk_live_ibbVEpwDbfWJAboByQ6Kvygy"*/

								data-amount=<?php echo isset($_POST['tot_price']);?>

								data-name="VRL"

								data-description="Video Request Line"

								data-label="Pay to Extends storage"

								data-image="/images/logo1.png"

								data-locale="auto">

							</script>

						</span>

					</div>

				</div>

			</div>

		</div>

	</form>

</div>

<div class="cb-pagewrap"> @include('frontend.common.header')

	<section id="mian-content">

		<div class="container">

			<div class="cb-grid">

				<div class="cb-block cb-box-100 main-content">

					<div class="cb-content">

						<div class="inner-block">

							<h1 class="heading"> <span class="txt1">Dashboard</span> <span class="txt2">My Video Requests </span> </h1>

						</div>

					</div>

				</div>

			</div>

		</div>

	</section>

	<section id="grid-botttom">

		<div class="container">

			<div class="cb-grid">

				<div class="cb-block cb-box-100">

					@if(Session::has('success'))

					<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

					@endif

					@if(Session::has('error'))

					<div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

					@endif
					<?php //dd($my_videos);?>
					<div class="cb-content video-wrap artist-video"> @if ($my_videos!=Null)

						<div class="inner-block">

							<h2 class="heading"><span>My  Videos</span></h2>

							<?php //dd($my_videos);?>

							@foreach($my_videos as $my_video)

							<div class="artists-video wi-33-3">

								<div class="artists-video-block">

									<div class="inner-artists-video">

										<span>
											<?php $now = new \DateTime();

											$date1=date_create($my_video->purchase_date);

											$diff=date_diff($date1,$now);

											$diff_date=$diff->format("%a");

											//echo $my_video->remain_storage_duration-$diff_date;

											?>
											@if($my_video->remain_storage_duration-$diff_date > 0)

											<?php $str = $my_video->thumbnail;$rest = substr($str,28);?>

											<a href="video_detail/{{$my_video->id}}">   

												<img src="{{$rest}}" alt="Mountain View" style="width:360px;height:225px;">

											</a>

											@else

											

											<img src="/images/thumbnails/default-video.png" alt="Mountain View" style="width: 100%;height: 225px;">

											

											@endif                 

											<!-- <video style="width:100%;max-height:575px;" id="myvideo" controls>                                

												<source src="<?php //echo $rest ;?>" type="video/mp4"></source>                                

												<source src="<?php //echo $rest ;?>" type="video/webm"></source>                                

												<source src="<?php //echo $rest ;?>" type="video/ogg"></source>                                

												<source src="<?php //echo $rest ;?>"></source>                            

											</video>  -->

										</span>

										<div class="video-content"> <span class="title" style="white-space:normal;">{{$my_video->title}}</span> </div>

										<?php $now = new \DateTime();

										$date1=date_create($my_video->purchase_date);

										$diff=date_diff($date1,$now);

										$diff_date=$diff->format("%a");

											//echo $my_video->remain_storage_duration-$diff_date;

										?>

										@if($my_video->remain_storage_duration-$diff_date > 0)

										<div class="video-content"> 

											<span class="title">
												<a class="btn res-more-btn" href="{{URL('resend_video/'.$my_video->requestby.'/'.$my_video->id)}}">resend video</a>
											</span> 
											<?php $sub_str=substr($my_video->url,55); ?>
											<span class="title">
												<a class="btn res-more-btn" href="{{URL('download_video/'.$sub_str)}}">Download</a>
											</span> 

										</div>

										@endif

										<?php $now = new \DateTime();

										$date1=date_create($my_video->purchase_date);

										$diff=date_diff($date1,$now);

										$diff_date=$diff->format("%a");

										//dd($my_video);

										?>

										@if($my_video->remain_storage_duration-$diff_date <= 0)

										<div class="box">

											<?php //echo $my_video->desti_thumbnail?>

											<a class="button-pay" data-requestid="{{$my_video->request_id}}" data-thumbnail="{{$my_video->desti_thumbnail}}" data-url="{{$my_video->desti_url}}"  data-rtpid="{{$my_video->uploadedby}}" data-rbpid="{{$my_video->requestby}}" id="extendStorege" >Extends storage</a>

										</div>

										@endif

										@if($my_video->remain_storage_duration-$diff_date <= 0)

										<span class="dutation_time">

											Your video storage duration is ended,please pay to extend storage of video.

										</span>

										@else

										<span class="dutation_time">Your video storage duration is ending in {{$my_video->remain_storage_duration-$diff_date}} days,please pay to extend storage of video.</span>

										@endif

										<div class="artists-video-img">

										</div>

										<!-- <a href="/move_file/.''">move</a> -->

									</div>

								</div>

							</div>

							@endforeach </div>

							@else

							<div class="inner-block">

								<div class="cb-box-50">

									<h1 class="title not-found">No Requested Video found</h1>

								</div>

								<div class="cb-box-50">

									<div class="user_video">

										<img src="/images/not-found-vdo.jpg" alt="" class="fr-fic fr-dii">

									</div>

								</div>

							</div>

							@endif </div>

						</div>

					</div>

				</div>

			</section>

			@include('frontend.common.footer') </div>

			<script>

				$(document).ready(function() {

					$('#completed').on('click',function () {

						$('.complete_block').show();

						$('.approve_block').hide();

						$('.pending_block').hide();

						$('.reject_block').hide();

					})

					$('#approved').on('click',function () {

						$('.approve_block').show();

						$('.complete_block').hide();

						$('.pending_block').hide();

						$('.reject_block').hide();

					})

					$('#pending').on('click',function () {

						$('.pending_block').show();

						$('.complete_block').hide();

						$('.approve_block').hide();

						$('.reject_block').hide();

					})

					$('#reject').on('click',function () {

						$('.reject_block').show();

						$('.complete_block').hide();

						$('.approve_block').hide();

						$('.pending_block').hide();

					})

					$('#all').on('click',function () {

						$('.reject_block').show();

						$('.complete_block').show();

						$('.approve_block').show();

						$('.pending_block').show();

					})

				});

				$('#extendStorege').click(function(){

					$('#popup1').addClass('overlay').css({visibility: 'visible',opacity: 1});

					$('#request_id').val($(this).data('requestid'));

					$('#url').val($(this).data('url'));

					$('#thumbnail').val($(this).data('thumbnail'));

					$('#requestToProfileId').val($(this).data('rtpid'));

					$('#requestByProfileId').val($(this).data('rbpid'));

				});

				$('#calc_price').click(function(){

					var days=parseInt($('#days').val());					



					if(!isNaN(days)){

						if(days != 0 && days != null ){

							var amount = parseFloat($('#storage_data').val());

							var da = days*amount;

							$('#request_day').val(days);

							$("#tot_price").val(da);

							$("#amountfinal").val(da);

							

							

							$(".video-content").show();

							

						}

					}else{

						alert('please add only number in day field.');

					}

					

				});

			</script>



			<script>

				jQuery(document).ready(function(){

					jQuery(".close").click(function(){

						jQuery(".form-group .content").hide();

					});

					

				});

			</script>





		</body>

		</html>