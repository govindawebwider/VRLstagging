<section id="footer">
	<div id="footer-top">
		<ul class="footer-nav">
			<li><a href="{{URL('help')}}"><span>Help</span></a></li>
			<li><a href="{{URL('about-us')}}"><span>About</span></a></li>
			<li><a href="{{URL('privacy')}}"><span>Privacy Policy</span></a></li>
			<li><a href="{{URL('terms')}}"><span>Terms and Services</span></a></li>
			<li><a data-toggle="modal" data-target="#exampleModalCenterJOIN"><span>Join Us</span></a></li>
		</ul>
		<ul class="social-nav">
			<li><a href="https://www.facebook.com/VideoRequestLine"><img src="/images/icn_footer_facebook.png"/></a></li>
			<li><a href="https://twitter.com/RequestVideo"><img src="/images/icn_footer_twiter.png"> </a></li>
			<li><a href="https://www.instagram.com/videorequestline/?hl=en"><img src="/images/icn_footer_insta.png"></a></li>
		</ul>
	</div>
	<div id="copyright">
		<p class="text-center">&copy;2018 - VRL All right reserved.</p>
	</div>
</section>
{!! Html::script('js/jquery-3.3.1.min.js') !!}
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.8.1/jquery.validate.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{!! Html::script('js/jscolor.min.js') !!}
{!! Html::script('js/bootstrap.min.js') !!}
{!! Html::script('js/jquery.easy-ticker.min.js') !!}

{!! Html::script('js/slider.js') !!}
{!! Html::script('js/custom.js') !!}
{!! Html::script('js/sweetalert.min.js') !!}
{!! Html::script('js/jquery.inputmask.bundle.min.js') !!}
{!! Html::script('js/jquery.date-dropdowns.js ') !!}
{!! Html::script('js/function.js ') !!}
{!! Html::script('js/owl.carousel.min.js') !!}
{!! Html::script('js/star-rating.js') !!}
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>
	$( function() {
	    let $minDate = 0;
        if ($('.turnaroundtimevalue').length) {
            $minDate = $('.turnaroundtimevalue').data('turnaroundtime');
            if($minDate >= 0)
            {
            	$cDate=$minDate;
            }
            else
            {
            	$cDate=0;
            }
            //alert($minDate);
        }


		$("#datepicker").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'mm/dd/yy',
			minDate: $cDate,
			yearRange: '-80Y:+1'
			
            
		});

	} );
	$('#owl-carousel00').owlCarousel({
		loop:false,
		margin:30,
		items : 6,
		autoPlay : true,
		navigation : true,
		navigationText : ["<img src='/images/prev.png' class='previmg'>","<img src='/images/next.png' class='nextimg'>"],
    	rewindNav : true,
		stopOnHover : true,
		singleItem : false,
		responsive:{
			0:{
				items:2
			},
			600:{
				items:3
			},
			1000:{
				items:6
			}
		}
	});
	$('#owl-carousel01').owlCarousel({
		loop:false,
		items : 4,
		autoPlay : true,
		navigation : true,
		navigationText : ["<img src='/images/prev.png' class='previmg'>","<img src='/images/next.png' class='nextimg'>"],
		rewindNav : true,
		stopOnHover : true,
		singleItem : false,
	})
</script>
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

			$('.out_mess_del_video').addClass('delete_video');

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
    $(document).ready(function() {
		$('select').select2({
			minimumResultsForSearch: 10
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
	/*$(document).ready(function() {
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
	});*/
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
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.2.0/js/dataTables.buttons.min.js"></script> 
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.2.0/js/buttons.print.min.js"></script> 
<script>
	$(document).ready( function () {
		$('#table_id').DataTable({
			dom: 'lBfrtip',
			buttons: ['print','csv'],
			"language": {
				"search": "Search:",
				"searchPlaceholder": ""
			}
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
		$(".lang-convert").select2({
			minimumResultsForSearch: 10
		});
		$('.ui-datepicker').addClass('notranslate');
	});
	function textCounter(field, maxLimit, className) {
		if (field.value.length > maxLimit) {
			field.value = field.value.substring(0, maxLimit);
			field.blur();
			field.focus();
			return false;
		} else {
			if (field.value.length == 0) {
				$('.' + className).hide();
			} else {
				$('.' + className).show(function () {
					$(this).text(field.value.length + '/' + maxLimit);
				});
			}
		}
	}
	function formatLanguage (lang) {
		if (!lang.id) {
			return lang.text;
		}
		var baseUrl = "/images";
		var $lang = $(
				'<span><img src="' + baseUrl + '/' + lang.element.value.toLowerCase() + '.png" class="img-flag" /></span>'
		);
		return $lang;
	};
	/**
	 * Google translation
	 * @param elmnt
	 */
	function open_translate(elmnt) {
		var a = document.getElementById("google_translate_element");
		if (a.style.display == "") {
			a.style.display = "none";
			elmnt.innerHTML = "<i class='fa fa-globe fa-lg'></i>";
		} else {
			a.style.display = "";
			if (window.innerWidth > 830) {
				a.style.width = "100%";
			} else {
				a.style.width = "100%";
			}
			elmnt.innerHTML = "<i class='fa fa-close fa-lg' aria-hidden='true'></i>";
		}
	}
	function googleTranslateElementInit() {
		new google.translate.TranslateElement({
			pageLanguage: 'en',
			autoDisplay: false,
			gaTrack: true,
			gaId: 'UA-3855518-1',
			layout: google.translate.TranslateElement.InlineLayout.SIMPLE
		}, 'google_translate_element');
	}
</script>

<!--<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
async defer>
</script>-->


<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.2.0/js/buttons.print.min.js"></script>
<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>



<script type="text/javascript">

	$(".toggle-password").click(function() {

  $(this).toggleClass("fa-eye-slash fa-eye");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});

</script>