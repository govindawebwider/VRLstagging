<!--footer section start-->

<footer>
	<!-- <p>&copy 2015 Easy Admin Panel. All Rights Reserved | Design by <a href="https://w3layouts.com/" target="_blank">w3layouts.</a></p> -->
</footer>
<!--footer section end-->
{!! Html::script('https://code.jquery.com/jquery-1.12.3.min.js') !!}
{!! Html::script('js/jquery.nicescroll.js') !!}
{!! Html::script('js/scripts.js') !!}
{!! Html::script('js/bootstrap.min.js') !!}
{!! Html::script('js/Chart.js') !!}
{!! Html::script('js/wow.min.js') !!}

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.2.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.2.0/js/buttons.print.min.js"></script>
<script>
$(document).ready( function () {
	
	$("#add_more").click(function(){
		var len=$('.form-control').length;
    	$(".form-group-add").append('<div class="form-group"><div class="control-label"><label for="twitter_link">Twitter</label></div><div class="control-box"><input type="text" name="twitter_link'+len+'" id="twitter_link'+len+'" value="" class="form-control" ></div></div>');

	});
$('#testimonialsss').DataTable( {
        "paging":   false,
        "ordering": false,
        "info":     false
    } );
$('#testimonial').DataTable( {
        "lengthMenu": [[5,6,7,8,9,10,15,20,25], [5,6,7,8,9,10,15,20,25]],
         "order": [[ 2, "desc" ]],
         buttons: ['print'],
"paging":   true,
    	} );
		$('#table_id').DataTable({
			dom: 'lBfrtip',
			buttons: ['print'],
		});

		$('#example').DataTable( {
        "lengthMenu": [[5,6,7,8,9,10,15,20,25], [5,6,7,8,9,10,15,20,25]],
         "order": [[ 2, "desc" ]]
      } );

$('#admin_dob').DataTable( {
        "lengthMenu": [[5,6,7,8,9,10,15,20,25], [5,6,7,8,9,10,15,20,25]],
         "order": [[ 2, "desc" ]]
    	} );
		jQuery(".modal_wrap").hide();		
		jQuery(".payment #table_id_wrapper .btn.btn-info").click(function() { 
			jQuery(".modal_wrap").fadeIn("slow");
		});
		jQuery(".modal_wrap .inner_modal_wrap .close_btn").click(function() { 
			jQuery(".modal_wrap").fadeOut("slow");

		});
		jQuery(function(){
			jQuery('.toggle.menu-toggle').click(function(){
				jQuery('#left-side-wrap, #page-wrapper').toggleClass('active');
			});			
		});
	} );  

</script>


<script>
jQuery(document).ready(function() {
	function close_accordion_section() {
		jQuery('#left-side-wrap .left-side-inner .cbicon-arrow').removeClass('show');
		jQuery('#left-side-wrap .left-side-inner .sub-child').slideUp(300).removeClass('open');
	}
	
	jQuery('#left-side-wrap .left-side-inner .cbicon-arrow').click(function(e) {
		var currentAttrValue = jQuery(this).attr('href');
		if(jQuery(e.target).is('.show')) {
			close_accordion_section();
		}else {
			close_accordion_section();
			jQuery(this).addClass('show');
			jQuery('#left-side-wrap .left-side-inner .custom-nav ' + currentAttrValue).slideDown(300).addClass('open'); 
		}
		e.preventDefault();
	});
});
</script>


<script>
jQuery(document).ready(function() {
// function close_accordion_section() {
//  jQuery('#left-side-wrap .left-side-inner .cbicon-arrow').removeClass('show');
//  jQuery('#left-side-wrap .left-side-inner .sub-child').slideUp(300).removeClass('open');
// }
// 
// jQuery('#left-side-wrap .left-side-inner .cbicon-arrow').click(function(e) {
//  var currentAttrValue = jQuery('.sub-child').attr('ul');
//  if(jQuery(e.target).is('.show')) {
//   close_accordion_section();
//  }else {
//   close_accordion_section();
//   jQuery(this).addClass('show');
//   jQuery('#left-side-wrap .left-side-inner .sub-child ' + currentAttrValue).slideDown(300).addClass('open'); 
//  }
//  e.preventDefault();
// });
 $('#left-side-wrap > div > div > ul ul').hide();
	 $('#left-side-wrap > div > div > ul > li > span').on('click',function(){
		 if($(this).parent().hasClass('active')){
			 $(this).parent().removeClass('active');
			 $(this).next().slideUp();
		 }else{
		 	$('#left-side-wrap > div > div > ul > li').removeClass('active');
		 	$(this).next().slideToggle();
			$(this).parent().addClass('active');
		 }
	});
});
</script>





@if(Auth::check())
      @if(Auth::user()->type =="Artist")
        <script type="text/javascript">
          setInterval(function() {
              $.ajax({url: "/check_user_auth" ,cache: false, success: function(result){ 
                if(result =="false"){
                  window.location.href="/getLogout";
                }
               }});
            },10000);
        </script>
@endif
 @if(Auth::user()->type =="Admin")
        <script type="text/javascript">
          setInterval(function() {
              $.ajax({url: "/check_admin_auth",cache: false, success: function(result){ 
                if(result =="false"){
                  window.location.href="/getLogout";
                }
               }});
            },10000);
        </script>
@endif
@endif



