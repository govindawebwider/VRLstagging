<section id="footer">
	<div class="container">
		<div class="cb-grid">
			<div class="cb-block cb-box-50">
				<div class="cb-content  nomargin-top nopadding-bottom nomargin-bottom footer-nav">
					<ul>
						<li><a href="{{URL('about-us')}}"><span>About</span></i></a></li>
						<li><a href="{{URL('help')}}"><span>Help</span></i></a></li>
						<li><a href="{{URL('terms')}}"><span>Terms</span></i></a></li>
						<li><a href="{{URL('privacy')}}"><span>Privacy</span></i></a></li>
					</ul>
				</div>
			</div>
			<div class="cb-block cb-box-50">
				<div class="cb-content social-icon nopadding-bottom  nopadding-top">
					<ul>
						<li><a href="https://www.facebook.com/vid.req.9" target="_blank"><i class="fa fa-facebook"></i></a></li>
						<li><a href="https://www.twitter.com/videorequestline" target="_blank"><i class="fa fa-twitter"></i></a></li>
<!--						<li><a href="#" ><i class="fa fa-google-plus"></i></a></li> -->
					<!--	<li><a href="#"><i class="fa fa-linkedin"></i></a></li> -->
						<!-- <li><a href="#"><i class="fa fa-pinterest"></i></a></li> -->
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div id="copyright">
		<div class="container">
			<div class="cb-grid">
				<div class="cb-block">
					<div class="cb-content copyright  nopadding-bottom  nopadding-top">
						<!-- <p>{{date('Y')}} @ copyright Video Request Line</p> -->
						<p>© {{date('Y')}} Video Request Line - All Rights Reserved
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
{!! Html::script('js/jquery-3.3.1.min.js') !!}
{!! Html::script('js/jscolor.js') !!}
{!! Html::script('js/bootstrap.min.js') !!}
{!! Html::script('js/jquery.easy-ticker.min.js') !!}
{!! Html::script('js/owl.carousel.min.js') !!}
{!! Html::script('js/slider.js') !!}
{!! Html::script('js/custom.js') !!}
{!! Html::script('js/sweetalert.min.js') !!}
{!! Html::script('js/jquery.inputmask.bundle.min.js') !!}
{!! Html::script('js/jquery.date-dropdowns.js ') !!}
<script type="text/javascript">
	$("input[name=phone]").inputmask("mask", {
		"mask": "(999) 999-9999"
	}); 
	$("#date_ofbirth").dateDropdowns({
		submitFieldName: 'date_ofbirth',
		displayFormat: 'mdy',
		submitFormat: "mm-dd-yyyy",
		// yearRange: "18",
		minAge: 18,

	});
	$('#date_ofbirth').attr('readonly', 'readonly');
	$( document ).ready(function() {
		$('#new_song').click(function(){
			$('#new_song_name').show();
			$('#ddselect').hide();
			//alert('test');
			$('#new_song').hide();

		});
		$('#more').hide();
		$('#less').click(function(){
			$('#more').show();
			///$('#less').hide();
			$(this).hide();
		});
		$('#hide_less').click(function(){
			$('#more').hide();
			$('#less').show();
		});

		
		$('#new_song_name').hide();
		
		$('#newsongs').click(function(){
			alert();
			$('#ddselect').hide();
			$('#new_song_name').show();
		});
		$('#completed').on('click',function () {
      //alert('test');
      $('#completed').addClass('active');

      $('#approved').removeClass('active');
      $('#pending').removeClass('active');
      $('#reject').removeClass('active');
      $('#all').removeClass('active');

      $('.complete_block').show();

      $('.approve_block').hide();

      $('.pending_block').hide();

      $('.reject_block').hide();

  })

		$('#approved').on('click',function () {
			$('#approved').addClass('active');

			$('#completed').removeClass('active');
			$('#pending').removeClass('active');
			$('#reject').removeClass('active');
			$('#all').removeClass('active');


			$('.approve_block').show();

			$('.complete_block').hide();

			$('.pending_block').hide();

			$('.reject_block').hide();

		})

		$('#pending').on('click',function () {
			$('#pending').addClass('active');

			$('#approved').removeClass('active');
			$('#completed').removeClass('active');
			$('#reject').removeClass('active');
			$('#all').removeClass('active');



			$('.pending_block').show();

			$('.complete_block').hide();

			$('.approve_block').hide();

			$('.reject_block').hide();

		})

		$('#reject').on('click',function () {
			$('#reject').addClass('active');
			$('#approved').removeClass('active');
			$('#completed').removeClass('active');
			$('#pending').removeClass('active');
			$('#all').removeClass('active');



			$('.reject_block').show();

			$('.complete_block').hide();

			$('.approve_block').hide();

			$('.pending_block').hide();

		})

		$('#all').on('click',function () {
			$('#all').addClass('active');

			$('#approved').removeClass('active');
			$('#completed').removeClass('active');
			$('#pending').removeClass('active');
			$('#reject').removeClass('active');
			$('.reject_block').show();

			$('.complete_block').show();

			$('.approve_block').show();

			$('.pending_block').show();

		})
		$('.req_video_del').on('click',function () {
			//alert('test');

			$('.out_mess_del_video</div>').addClass('delete_video');

		})

		$('.out_mess_del_video .ms_close').on('click',function () {

			$('.out_mess_del_video').removeClass('delete_video');

		})

		$("#month").click(function(){
			var month=$('#month').val();
			var year=$('#year').val();
			$.ajax({    
				type: "GET",
				url: "http://www.videorequestline.com/date_calculation/"+year+"/"+month,             
				dataType: "html", 
				success: function(response){  
					da= parseInt(response);  
					if(response==""){

					}else{
						var count;
						$('#day').html('');
						for(count = 1; count <= da; count++){
							$('#day').append('<option>'+count+'</option>');
						}
					}
				}
			});
		});
	});
</script>
@if(Auth::check())
@if(Auth::user()->type =="Artist" OR Auth::user()->type =="Admin")

<!--<script type="text/javascript">
	setInterval(function() {
		var url = "{{URL('/')}}" ;
		$.ajax({
			url: url+"/check_user_auth",
			cache: false, 
			success: function(result){ 
				if(result === "false")
				{
					window.location.href="https://www.videorequestline.com/getLogout";
				}
			}
		});
	},1000);
</script>-->
@endif  
@if(Auth::user()->type == "User")
<!--<script type="text/javascript">
	setInterval(function() {
		$.ajax({
			url: "/check_user_auth",
			cache: false, 
			success: function(result){ 
				if(result === "false"){
					window.location.href="https://www.videorequestline.com/getLogout";
				}
			}
		});
	},1000);
</script>-->
@endif  
@endif
<script>
	$(document).ready(function() {
		var da=0;
		$("#artist").change(function(){
			$('#datepicker').val("");
			$("#datepicker").datepicker("destroy");
			var myData = $('#artist').val();
			$.ajax({    
				type: "GET",
				url: "https://www.videorequestline.com/testby/"+myData,             
				dataType: "html", 
				success: function(response){  
					da= parseInt(response); 
					$("#datepicker").datepicker({ maxDate: "+30Y",minDate: da});
				}
			});   
		});
	});
	$(function () {
		//$( "#datepicker" ).datepicker();
		$("#date_of_birth").datepicker({
			changeMonth: true,
			changeYear: true,
			changeDate: true,
			minDate:'-80Y',
			yearRange: "-100:+0",
			maxDate:0
		});
	});
</script> 
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.js"></script> 
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.2.0/js/dataTables.buttons.min.js"></script> 
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.2.0/js/buttons.print.min.js"></script> 
<script>
	$(document).ready( function () {
		$('#table_id').DataTable({
			dom: 'lBfrtip',
			buttons: ['print','csv']
		});
	} ); 
	$(document).ready(function() {

		$("#ddselect").change(function(){
			//alert();
			if($("#ddselect option:selected").attr("id") == "newsong"){
				$('#new_song_name').show();
				$('#ddselect').hide();
			}
		});
		$('#new_song_name').hide(); 
	}); 
</script> 

<script>
	$(document).ready(function(){
		$("#hideshow").click(function(){
			$("#content").toggle();
		});
		$("#hideshow1").click(function(){
			$("#content1").toggle();
		});
		$("#hide").click(function(){
			$(this).parent(".popup").hide();
		});
	});
</script>
<!--<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
async defer>
</script>-->
