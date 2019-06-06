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
                        <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="#">Social Media Videos</a> </div>
                              <h1 class="heading">Social Media Videos 
                                <span>   
                                    <a href="{{URL('/twitterUserTimeLine')}}">
                                        <input class="btn btn-primary" type="button" name="artist_csv" value="Sync Videos" style="float:right;">
                                    </a> 
                                </span>

                            </h1> 
                            <p class="desc-title"></p>

                            <div class="users">
                                <div class="table-responsive dataTables_wrapper">
                                    <table id="table_social" class="table display dataTable no-footer">
                                        <div class="table-responsive dataTables_wrapper">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>File Name</th>
                                                    <th>Approved</th>
                                                    <th>Source</th>
                                                    <th>Thumbnail</th>
                                                    <th>Request Status</th>
                                                </tr>
                                            </thead>
                                     @if(!empty($twitter_data))
                                      <?php $kk=0; ?> 
                                        @foreach($twitter_data as $key => $value)
                                            <tr>
                                                <td>{{ $value->id }}</td>
                                                <td>{{ $value->VideoName }}</td>
                                                @if($value->status==1)
                                                <td>Approved</td>
                                                @else
                                                <td>Not Approved</td>
                                                @endif 
                                                <td>{{ Config::get('constants.SOCIAL_PLATFORMS.'.$value->type) }}</td> 
                                                <td><img src="{{ $value->thumbnail }}" style="width:100px;"></td>
                                                <td  style="width: 150px !important">
                                                    <a href="javascript:void(0)" class="btn btn-primary" id="myBtn<?php echo $kk; ?>" ><i class="icon icon-play3"></i> Play</a>
                                                    @if($value->status==0) 
                                                    <a href="javascript:void(0)" style="cursor: default;"  class="btn btn-primary">Accepted</a>
                                                    @else
                                                    <a href="javascript:void(0)" style="cursor: default;" class="btn btn-danger req_video_del">Rejected</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <!-- Modal -->
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
                                              <source src="<?php echo $value->VideoURL; ?>" type="video/mp4">
                                             </video>
                                            </div>

                                          </div>
                                            <?php $kk++; ?>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">There are no data.</td>
                                        </tr>
                                    @endif
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

    $('a[id*="play_btn_"]').on('click', function(){
        $('#videoContainer').show();
        var url = $(this).data('url');
        $('.pop-inner').slideDown(300, function () {
            $('#play_vid').attr({'src':url});
        });
    });
    $('.close-pop').on('click', function () {
        $('.pop-inner').slideUp(200, function () {
            $('#play_vid').attr({'src': ''});
            $(this).delay(200).parents('#videoContainer').hide();
        });
    });
    $(document).keydown(function (e) {
        if (e.keyCode == 27) {
            $('.pop-inner').slideUp(200, function () {
                $('#play_vid').attr({'src': ''});
                $(this).delay(200).parents('#videoContainer').hide();
            });
        }
    });
</script>

</html>