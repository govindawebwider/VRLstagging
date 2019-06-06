@include('admin.common.header')

<style>

  #videoContainer {

    position: fixed;

    top: 0;

    right: 0;

    left: 0;

    bottom: 0;

    overflow: hidden;

    background: rgba(0,0,0,.6);

    z-index: 99999999;

  }

  .pop-inner {

    display: none;

    position: absolute;

    left: 0;

    right: 0;

    width: 500px;

    background-color: #333;

    border-radius: 5px;

    padding: 5px;

    height: 400px;

    margin: 50px auto;

  }

  .pop-inner iframe {

    width: 100%;

    height: 100%;

    border-radius: 5px;

  }

  .close-pop {

    color: #fff;

    right: -10px;

    position: absolute;

    top: -10px;

    font-size: 24px;

    cursor: pointer;

  }

</style>
<style>
body {font-family: Arial, Helvetica, sans-serif;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background:none;
  margin: auto;
  padding: 00px;
  border:0px;
  width:400px;
}
.modal-content video{
  display:block;
}

/* The Close Button */
.close {
    position: absolute;
    z-index: 9999999;
    width: 30px;
    top: 8px;
    right: 8px;
    opacity: 1;
    text-shadow: 0px 0px 0px;
    height: 30px;
    text-align: center;
    display: inline-block;
    background: rgba(00,00,00,0.4);
    border-radius: 50%;
    color: #fff;
    line-height: 30px;
}
.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
</style>

<body class="admin usert_list">

  <section class="main-page-wrapper">

    <div class="main-content">

      <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>

      @include('admin.layouts.header')

      <div class="usert_list_wrap">

        <div  class="col-md-12 ">

          <div id="page-wrapper">

            <div class="graphs">
            <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="#">Sample Videos</a> </div>
              @if(Session::has('success'))

              <div class="alert alert-success"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('success') }}</span> </div>

              @endif

              

              <h1 class="heading">Sample Videos<span><a style="float:right" href="{{URL('/video_csv')}}" >   <input class="btn btn-primary" type="button" name="artist_csv" value="Export Sample Videos List"></a> </span></h1>
              <p class="desc-title"></p>


              

              <div class="users">

                <div class="table-responsive dataTables_wrapper">

                  <table class="table" id="table_video">

                    <thead>

                      <tr>

                        <th>Video Id</th>

                        <th>Title</th>

                        <th>Description</th>

                        <th>Action</th>

                      </tr>

                    </thead>

                    <tbody>



                      {{--*/ $i = 0; /*--}}

                      
                      <?php $kk=0; ?> 
                      @foreach ($videos as $key=>$video)



                      <tr>
                        <?php
                           $url='video/watermark/'.$video->VideoURL;
                        ?>
                        

                        <td>{{$video->VideoId}}</td>

                        <td>{{$video->Title}}</td>

                        <td>{{$video->Description}}</td>

                        <td>

                          <!-- <a  class="btn btn-primary" 
                           data-toggle="modal" data-target="#myModal{{ $key }}"> 

                            <i class="icon icon-play3"></i>Play

                          </a> -->

                          <a href="javascript:void(0)" class="btn btn-primary" id="myBtn<?php echo $kk; ?>" ><i class="icon icon-play3"></i> Play</a> 


                          <a onClick="delete_req({{$video->VideoId}})" href="javascript:void(0)" class="btn btn-danger req_video_del"> 

                            <i class="icon icon-bin"></i> Delete

                          </a>

                        </td>

                      </tr>

                      {{--*/ $i++; /*--}}
 
                      <div id="myModal<?php echo $kk; ?>" class="modal" >
                                                           <script>
                                          
                                          var btn = document.getElementById("myBtn<?php echo $kk; ?>");

                                          btn.onclick = function() {
                                            
                                            $('#myModal<?php echo $kk; ?>').css('display','block');
                                          }

                                          function closefun<?php echo $kk; ?>(i) {
                                            $('#myModal'+i).css('display','none');
                                            var vid = document.getElementById("vid"+i); 
                                            vid.pause(); 
                                            
                                          } 

                                          </script>
                                            <!-- Modal content -->
                                            <div class="modal-content">
                                            <span class="close" onclick="closefun<?php echo $kk; ?>(<?php echo $kk; ?>);">&times;</span>
                                             <video id="vid<?php echo $kk; ?>" width="400" controls controlsList="nodownload">
                                              <source src="https://videorequestline-staging.s3.us-west-2.amazonaws.com/video/watermark/<?php echo $video->VideoURL; ?>" type="video/mp4">
                                             </video>
                                            </div>

                                          </div>
                                            <?php $kk++; ?>

                      @endforeach

                    </tbody>

                    

                  </table>
                </div>

              </div>

            </div>

          </div>

        </div>

      </div>

    </div>

    <div id="videoContainer" style="display:none;">

            <div class="pop-inner"> 
            <i class="icon icon-cancel-circle close-pop"></i>
            <div id="play_vid_d" style="width: 100%;height: 100%;">
              
            </div>

               
            </div>

        </div>


  </section>

  <div class="out_mess_del_video">

    <div class="inner_message">

      <div class="head_wrap">Delete Video</div>

      <span class="ms_close"><i></i></span>

      <div>

        <div class="main_body message">Are you sure, you want to remove this video.</div>

        <div class="footer_wrap"><a href="javascript:void(0)" class="btn_no ms_close">No</a>

          <a href="" class="btn_yes delete_url">Yes</a></div>

        </div>

      </div>

    </div>

    @include('admin.common.footer') 

  <!--   <script>

      function delete_req(user_id){

    //alert(user_id);

    var baseurl = "{{URL('/')}}";

    var url = baseurl+'/delete_video/'+user_id;

    $('.delete_url').attr('href',url);

  }

  // $(document).ready(function(){

  //   $('a[id*="play_btn_"]').on('click', function(){

  //     $('#videoContainer').show();

  //     $('.pop-inner').slideDown(500);

  //     var url = 'http://videorequestlive.com' + $(this).data('url');

  //     $('#play_vid').attr({'src':url});

  //   });

  //   $('.close-pop').on('click', function(){

  //     $('.pop-inner').slideUp(500);

  //     $(this).delay(500).parents('#videoContainer').hide();

  //   });

    

  // });

   function pausemodalvideo(id){  
  document.getElementById("modalvideo"+id).pause(); 
   //document.getElementById("modalvideo"+id).pause();
 }

</script> -->

<script>
    var baseurl = "{{URL('/')}}";
    $(document).ready(function() {
    $('#example_paginate').show();
    });
    function reactionVideo(id, status){
    var tok = '<?php echo csrf_token() ?>';
    $.post(baseurl+'/updateReactionStatus/', {'_token':tok, 'video_id':id, 'status':status}, function(data){
    location.reload();
    });
    }
    
    function delete_req(user_id){
     var url='';
     
     url = baseurl + '/delete_video/' + user_id;
    $('.delete_url').attr('href', url);
    }
    $('a[id*="play_btn_c_"]').on('click', function(){
      $("#play_vid_d").html('');
       var url='';
        
     url = "{{ \Illuminate\Support\Facades\Storage::disk('s3')->url('video/watermark/') }}" +  $(this).data('url');
     
    //$('#play_vid_d').html(' <iframe id="play_vid" frameborder="0" src="'+url+'" allowfullscreen wmode="Opaque" ></iframe>');
    $('#play_vid_d').html('<iframe frameborder="0" id="play_vid5" allowfullscreen wmode="Opaque" src="'+url+'"></iframe>');

    $('#videoContainer').show();
        $('.pop-inner').slideDown(500);

    });
    
    $('.close-pop').on('click', function(){
        $('#play_vid5').attr({'src':''});
        $('.pop-inner').slideUp(500);
        $(this).delay(500).parents('#videoContainer').hide();
    });

</script>

<style>
.video{
  width:550px;
}
</style>
</body>
