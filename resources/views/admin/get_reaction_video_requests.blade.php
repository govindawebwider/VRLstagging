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
                        <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="#">Reaction videos</a> </div>
                              <h1 class="heading">Video Request
                                <span>
                                    <a href="{{URL('/video_purge')}}"><input class="btn btn-primary" type="button" name="artist_csv" value="Video Purge" style="float:right;"></a>
                                    <a href="{{URL('/video_req_csv')}}">

                                        <input class="btn btn-primary" type="button" name="artist_csv" value="Export Video Request List" style="float:right;">

                                    </a> 
                                </span>

                            </h1> 
                            <p class="desc-title">
                                 @if(Session::has('success'))
                                    <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} 
                                    </div>
                                @endif
                                    @if(Session::has('error'))
                                    <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} 
                                    </div>
                                @endif
                            </p>

                            <div class="users">
                                <div class="table-responsive dataTables_wrapper">
                                    <table id="table_reaction" class="table display dataTable no-footer">
                                        <div class="table-responsive dataTables_wrapper">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>File Name</th>
                                                    <th>Approved</th>
                                                    <th>Request Status</th>
                                                </tr>
                                            </thead>
                                            <?php $kk=0; ?> 
                                            @foreach ($reactionvideos as $reactions)
                                            <?php //dd($reactions);?>
                                            <tr>
                                                <td>{{$reactions->id}}</td>
                                                <td>{{$reactions->VideoName}}</td>
                                                @if($reactions->status==1)
                                                <td>Approved</td>
                                                @else
                                                <td>Not Approved</td>
                                                @endif
                                                <td  style="width: 150px !important">
                                                    <?php $fileName = 'uploads/reaction-videos/'.$reactions->VideoURL;
                                                    $videoUrl = \Illuminate\Support\Facades\Storage::disk('s3')->url($fileName);?>
                                                    <a href="javascript:void(0)" class="btn btn-primary" id="myBtn<?php echo $kk; ?>" data-url = "{{$videoUrl}}"><i class="icon icon-play3"></i> Play</a>
                                                    @if($reactions->status==0)
                                                    <a href="{{URL('updateReactionStatus/'.$reactions->id.'/1')}}" class="btn btn-danger req_video_del">Accept</a>
                                                    @else
                                                    <a href="{{URL('updateReactionStatus/'.$reactions->id.'/0')}}" class="btn btn-primary">Reject</a>
                                                    @endif
                                                </td>
                                            </tr>

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
                                              <source src="<?php echo $videoUrl; ?>" type="video/mp4">
                                             </video>
                                            </div>

                                          </div>
                                            <?php $kk++; ?>
                                            @endforeach
                                        </div>
                                    </table>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        <div id="videoContainer" style="display:none;">

            <div class="pop-inner"> <i class="icon icon-cancel-circle close-pop"></i>

                <iframe frameborder="0" allowfullscreen wmode="Opaque" id="play_vid"></iframe>

            </div>

        </div>

    </section>

    @include('admin.common.footer')

</body>
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
    var url = baseurl + '/delete_video/' + user_id;
    $('.delete_url').attr('href', url);
    }
    $('a[id*="play_btn_"]').on('click', function(){
        $('#videoContainer').show();
        $('.pop-inner').slideDown(500);
    var url = $(this).data('url');
    $('#play_vid').attr({'src':url});
    });
    $('.close-pop').on('click', function(){
        $('#play_vid').attr({'src':''});
        $('.pop-inner').slideUp(500);
        $(this).delay(500).parents('#videoContainer').hide();
    });

</script>

</html>
