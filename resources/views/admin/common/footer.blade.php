<!--footer section start-->



<footer>
  <style type="text/css">
    .modal{
      z-index: 9999999;
    }
  </style>
  <div class="modal fade" id="myModal" role="dialog">
   <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">List Of Artist</h4>
      </div>
      <div class="modal-body">
        <p> </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<!-- <p>&copy 2015 Easy Admin Panel. All Rights Reserved | Design by <a href="https://w3layouts.com/" target="_blank">w3layouts.</a></p> -->

</footer>

<!-- Include external JS libs. -->

<script>

  initSample();

</script>

<!--footer section end-->

{!! Html::script('https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js') !!}

<!--{!! Html::script('https://code.jquery.com/jquery-1.12.3.min.js') !!}-->

{!! Html::script('js/scripts.js') !!}

{!! Html::script('js/bootstrap.min.js') !!}



{!! Html::script('js/jquery.date-dropdowns.js') !!}

{!! Html::script('js/jquery.inputmask.bundle.min.js') !!}
{!! Html::script('js/jquery.dataTables.js') !!}
{!! Html::script('js/function.js') !!}

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
{!! Html::script('editor_js/languages/ro.js') !!}
{!! Html::script('editor_js/froala_editor.pkgd.min.js') !!}
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
  function getPopup(val){
    if(val=="Artist"){
      $.ajax({
        url: '{{URL('getData')}}',
        type: 'POST',
        dataType: 'json',
        data: {type: 'Artist'},
      })
      .done(function(data) {
        console.log(data);
      })
      .fail(function(data) {
        console.log(data);
      })
      .always(function(data) {
        console.log(data);
      });
      
      $('#myModal').modal('show');
    }else if(val=="User"){
      $('#myModal').modal('show');
    }
  }

</script>

<script type="text/javascript">
  $("input[name=phone]").inputmask("mask", {
    "mask": "(999) 999-9999"
  });


  $("#profile_date_ofbirth").dateDropdowns({

    submitFieldName: 'date_ofbirth',

    displayFormat: 'mdy',

    submitFormat: "mm-dd-yyyy",

  //yearRange: "18",

  minAge: 18,



});

  $('#profile_date_ofbirth').attr('readonly', 'readonly');

</script>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.2.0/js/dataTables.buttons.min.js"></script>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.2.0/js/buttons.print.min.js"></script>

<script type="text/javascript">


</script>






<!-- html editer script  -->



<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>







<script>

  $(document).ready( function () {

   $("input[name=phone]").inputmask("mask", {

    "mask": "(999) 999-9999"

  }); 



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
       "language": {
           "search": "Search:",
           "searchPlaceholder": ""
       }

  } );

   $('#table_id').DataTable({

     "dom": 'lBfrtip',

     "buttons": ['print'],

     "columnDefs": [
        { "targets": [3,4,5,6], "searchable": false }
    ],

     "order": [[ 0, "desc" ]],
       "language": {
           "search": "Search:",
           "searchPlaceholder": ""
       }

   });


    $('#table_video').DataTable({

     "dom": 'lBfrtip',

     "buttons": ['print'],

     "columnDefs": [
        { "targets": [3], "searchable": false }
    ],

     "order": [[ 0, "desc" ]],
       "language": {
           "search": "Search:",
           "searchPlaceholder": ""
       }

   });




   $('#table_video_request').DataTable( {

    "columnDefs": [
        { "targets": [9], "searchable": false }
    ],

    "lengthMenu": [[10,20,30,40,50,-1], ['10 Rows','20 Rows','30 Rows','40 Rows','50 Rows', 'All Rows']],

    "order": [[ 0, "desc" ]],
     "dom": '<f<t>lp>',
       "language": {
           "search": "Search:",
           "searchPlaceholder": ""
       }




  } );

   $('#table_reaction').DataTable( {

    "columnDefs": [
        { "targets": [3], "searchable": false }
    ],

    "lengthMenu": [[10,20,30,40,50,-1], ['10 Rows','20 Rows','30 Rows','40 Rows','50 Rows', 'All Rows']],

    "order": [[ 0, "desc" ]],
     "dom": '<f<t>lp>',
       "language": {
           "search": "Search:",
           "searchPlaceholder": ""
       }




  } );

      $('#table_social').DataTable( {

    "columnDefs": [
        { "targets": [4,5], "searchable": false }
    ],

    "lengthMenu": [[10,20,30,40,50,-1], ['10 Rows','20 Rows','30 Rows','40 Rows','50 Rows', 'All Rows']],

    "order": [[ 0, "desc" ]],
     "dom": '<f<t>lp>',
       "language": {
           "search": "Search:",
           "searchPlaceholder": ""
       }




  } );


       $('#table_review').DataTable( {

    "columnDefs": [
        { "targets": [3,4,5,6], "searchable": false }
    ],

    "lengthMenu": [[10,20,30,40,50,-1], ['10 Rows','20 Rows','30 Rows','40 Rows','50 Rows', 'All Rows']],

    "order": [[ 0, "desc" ]],
     "dom": '<f<t>lp>',
       "language": {
           "search": "Search:",
           "searchPlaceholder": ""
       }




  } );

       $('#table_slider').DataTable( {

    "columnDefs": [
        { "targets": [4], "searchable": false }
    ],

    "lengthMenu": [[10,20,30,40,50,-1], ['10 Rows','20 Rows','30 Rows','40 Rows','50 Rows', 'All Rows']],

    "order": [[ 0, "desc" ]],
     "dom": '<f<t>lp>',
       "language": {
           "search": "Search:",
           "searchPlaceholder": ""
       }




  } );

       $('#table_payment').DataTable( {

    "columnDefs": [
        { "targets": [10,11], "searchable": false }
    ],

    "lengthMenu": [[10,20,30,40,50,-1], ['10 Rows','20 Rows','30 Rows','40 Rows','50 Rows', 'All Rows']],

    "order": [[ 0, "desc" ]],
     "dom": '<f<t>lp>',
       "language": {
           "search": "Search:",
           "searchPlaceholder": ""
       }




  } );




   $('#admin_dob').DataTable( {

    "lengthMenu": [[5,6,7,8,9,10,15,20,25], [5,6,7,8,9,10,15,20,25]],

    "order": [[ 2, "desc" ]],
       "language": {
           "search": "Search:",
           "searchPlaceholder": ""
       }

  } );

   jQuery(".modal_wrap").hide();    

   jQuery(".payment #table_id_wrapper .btn.btn-info").click(function() { 

     jQuery(".modal_wrap").fadeIn("slow");

   });

   jQuery(".modal_wrap .inner_modal_wrap .close_btn").click(function() { 

     jQuery(".modal_wrap").fadeOut("slow");



   });

 });  



</script>

<script>

  jQuery(document).ready( function () {

   jQuery(function(){

     jQuery('.toggle.menu-toggle').click(function(){

      jQuery('#left-side-wrap, #page-wrapper').toggleClass('active');

    });     

   });

 }); 
 function contentMove() {
  $(".main-page-wrapper").toggleClass('col-xs');
  $(".main-page-wrapper").toggleClass('col-lg');
 }

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

  $('.req_video_del').on('click',function () {

    $('.out_mess_del_video').addClass('delete_video');

  })

  $('.out_mess_del_video .ms_close').on('click',function () {

    $('.out_mess_del_video').removeClass('delete_video');

  })

  jQuery(document).ready(function() {

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

<script>

  $(function() {

    $('#edit').froalaEditor()

  });

  $(function() {

    $("#table_id > tbody > tr:nth-child(1) > td:nth-child(2) > div > div:nth-child(4)").children().remove().detach(); 

  });
  $(document).ready(function() {
      $('select').select2({
        minimumResultsForSearch: 10
    });
    
  });

</script>









<!--END html editer script  -->







@if(Auth::check())

@if(Auth::user()->type =="Artist" OR Auth::user()->type =="Admin")



<script type="text/javascript">

// setInterval(function() {
//
//  var url = "{{URL('/')}}" ;
//
//  $.ajax({
//
//    url: url+"/check_user_auth",
//
//    cache: false, 
//
//    success: function(result){ 
//
//      if(result === "false")
//
//      {
//
//        window.location.href="https://www.videorequestlive.com/getLogout";
//
//      }
//
//    }
//
//  });
//
//},3000);

</script>

@endif  

@endif

<script type="text/javascript">
 $('#ssn_number').keyup(function() {
  var val = this.value.replace(/\D/g, '');
  var newVal = '';
  if(val.length > 4) {
   this.value = val;
 }
 if((val.length > 3) && (val.length < 6)) {
   newVal += val.substr(0, 3) + '-';
   val = val.substr(3);
 }
 if (val.length > 5) {
   newVal += val.substr(0, 3) + '-';
   newVal += val.substr(3, 2) + '-';
   val = val.substr(5);
 }
 newVal += val;
 this.value = newVal.substring(0, 11);
});
 $('#pin').keyup(function() {
  var val = this.value.replace(/\D/g, '');
  var newVal = '';
  if(val.length > 4) {
   this.value = val;
 }
 if((val.length > 3) && (val.length < 6)) {
   newVal += val.substr(0, 3) + '-';
   val = val.substr(3);
 }
 if (val.length > 5) {
   newVal += val.substr(0, 3) + '-';
   newVal += val.substr(3, 2) + '-';
   val = val.substr(5);
 }
 newVal += val;
 this.value = newVal.substring(0, 11);
});
</script>





