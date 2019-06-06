@include('admin.common.header')
@section('customStyle')
    <style>
        .customVideoHide{
            display: none;
        }
    </style>
@endsection
<body class="admin video_price">
<section class="main-page-wrapper">
    <div class="main-content">
        <div id="left-side-wrap"> @include('frontend.artistDashboard.layouts.lsidebar') </div>
        <div class="header-section">
            <div class="top-main-left">
                <a href="{{URL('Dashboard')}}"><span class="logo1 white"><img src="/images/vrl_logo_nav.png" class="img img-responsive"></span> </a>
                <a href="javascript:void(0)" class="toggle menu-toggle"><i class="lnr lnr-menu"></i></a>
            </div>
            <div class="menu-right">
                <div class="user-panel-top">
                    <div class="profile_details">
                        <div class="profile_img">
                            <div class="dropdown user-menu"> <span class="dropdown-toggle"> <span class="admin-img">
                                        <?php $imageUrl = '';
                                        $fileName = 'images/Artist/'.$artist->profile_path; ?>
                                        @if (\Illuminate\Support\Facades\Storage::disk('s3')->exists($fileName))
                                            <?php $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')
                                                    ->url($fileName);?>
                                        @endif
                                        <img src="{{$imageUrl}}" alt="" >
                                        {{--<img src="{{url('images/Artist/').'/'. $artist->profile_path}}" alt=""> --}}
                                    </span><span class="admin-name">{{$artist->Name}}</span> <i class="arrow"></i> </span>
                                <ul>
                                    @if(session('current_type') == 'Artist')
                                        <li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>Switch to Admin</span></a></li>
                                    @endif
                                    <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{ URL($artist->profile_url)}}"> <i class="icon icon-users"></i> <span>view Profile</span> </a> </li>
                                    <li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{URL('ProfileUpdate')}}"> <i class="icon icon-users"></i> <span>Edit Profile</span> </a> </li>
                                    <li class="{{ Request::is('change-password') ? 'active' : '' }}"> <a href="{{URL('change-password')}}"> <i class="icon icon-lock"></i> <span>Change Password</span> </a> </li>
                                    <li class="{{ Request::is('getLogout') ? 'active' : '' }}"> <a href="{{ URL::route('getLogout') }}"> <i class="icon icon-exit"></i> <span>Logout</span> </a> </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="video_price_wrap">
            <div  class="col-md-12 ">
                <div id="page-wrapper">
                    <div class="graphs">

                        @if(Session::has('error'))
                            <div class="alert alert-danger"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('error') }}</span> </div>
                        @endif
                        @if(Session::has('success'))
                            <div class="alert alert-success"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('success') }}</span> </div>
                        @endif
                        <h1 class="heading">web camera </h1>
                        <?php //dd($requested_video);?>
                        <form method="post" id="mainFormData">
                            {{csrf_field()}}
                            <input type="hidden" id="requested_video_id" name="requested_video_id" value="{{$requested_video->VideoReqId}}">
                            <input type="hidden" id="requested_video_title" name="requested_video_title" value="{{$requested_video->Title}}">
                            <input type="hidden" id="requested_video_description" name="requested_video_description" value="{{$requested_video->Description}}">
                            <input type="hidden" id="requestedby" name="requestedby" value="{{$requested_video->requestByProfileId}}">
                            <input type="hidden" id="user_email" name="user_email" value="{{$user->EmailId}}">
                            <input type="hidden" id="uploadedby" name="uploadedby" value="{{$artist->ProfileId}}">
                        </form>
                        <div class="alert alert-info">Video must be at least 15 seconds 
                        </div>

                        <div id="waitingForUpload"  class="customALertInfo customVideoHide">Please Wait we are uploading your video
                            <i class="fa fa-spinner fa-pulse  fa-fw "></i>
                        </div>
                        <div id="successfullyUploaded"  class="customALertSuccess customVideoHide">Successfully Uploaded Please check your mail to view it
                        </div>

                        <div id="errorUploadingVideo"  class="customALertDanger customVideoHide">
                            Sorry Something went wrong please try again later
                        </div>

                        <div>
                            <div class="col-md-12">
                                <button class="btn btn-primary " onclick="countDownToStart()" id="startVideo">Start Recording</button>
                                <button class="btn btn-primary customVideoHide" onclick="pauseVideo()" id="pauseVideo">Pause</button>
                                <button class="btn btn-primary customVideoHide" onclick="resumingVideo()" id="resumeVideo">Resume video</button>
                                <button class="btn btn-warning customVideoHide" id="stopVideo">Save</button>
                                <button class="btn btn-success customVideoHide" id="saveAndUpload" onclick="saveVedio()">Start Uploading</button>
                                <button class="btn btn-primary customVideoHide" id="restart" onclick="refresh()">restart Recording</button>
                                <br>
                                <h4 id="Headercounter"  class="customVideoHide">Your Video Will Start In <span id="counter">4</span></h4>
                            </div>
                            <hr>

                            <div class="col-md-12">
                                <video controls autoplay id="mainVideo" class="customVideoHide"></video>
                            </div>
                        </div>
                        <hr>
                        <div class="request-details">
                            <h4>Request Details :</h4>

                            <table style="padding:20px;">
                                <tr style="width:30%; padding:10px;">
                                    <th style="width:30%;padding: 10px;">Recipient Name:</th>
                                    <td style="width:30%;padding: 10px;">{{$requested_video['Name']}}</td>
                                </tr>
                                <tr style="width:30%; padding:10px;">
                                    <th style="width:30%;padding: 10px;">Recipient  Name Voice:</th>
                                    <td style="width:30%;padding: 10px;">
                                        <audio src="{{asset('usersRecords')}}/{{$requested_video['recipient_record']}}" controls ></audio>
                                    </td>
                                </tr>
                                <tr style="width:30%; padding:10px;">
                                    <th style="width:30%;padding: 10px;">Recipient  pronunciation</th>
                                    <td style="width:30%;padding: 10px;">{{$requested_video['receipient_pronunciation']}}</td>
                                </tr>


                                <tr style="width:30%; padding:10px;">
                                    <th style="width:30%;padding: 10px;">Sender Name:</th>
                                    <td style="width:30%;padding: 10px;">{{$requested_video['sender_name']}}</td>
                                </tr>
                                <tr style="width:30%; padding:10px;">
                                    <th style="width:30%;padding: 10px;">Sender Name Voice:</th>
                                    <td style="width:30%;padding: 10px;">
                                        <audio src="{{asset('usersRecords')}}/{{$requested_video['sender_record']}}" controls ></audio>


                                    </td>
                                </tr>
                                <tr style="width:30%; padding:10px;">
                                    <th style="width:30%;padding: 10px;">Sender pronunciation</th>
                                    <td style="width:30%;padding: 10px;">{{$requested_video['sender_name_pronunciation']}}</td>
                                </tr>


                                <tr style="width:30%; padding:10px;">
                                    <th style="width:30%;padding: 10px;">Occasion:</th>
                                    <td style="width:30%;padding: 10px;">{{$requested_video['Title']}}</td>
                                </tr>
                                <tr style="width:30%; padding:10px;">
                                    <th style="width:30%;padding: 10px;">Message:</th>
                                    <td style="width:30%;padding: 10px;">{{$requested_video['Description']}}</td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('admin.common.footer')
<script src="https://cdn.webrtc-experiment.com/RecordRTC.js"></script>
<script src="https://cdn.webrtc-experiment.com/gif-recorder.js"></script>
<script src="https://cdn.webrtc-experiment.com/getScreenId.js"></script>
<script src="https://cdn.webrtc-experiment.com/gumadapter.js"></script>

<script type="text/javascript">
    var recordRTC;
    var btnStopRecording = document.getElementById('stopVideo');
    var video = document.querySelector('video');
    var count = 4;
    function successCallback(stream) {
        // RecordRTC usage goes here
        // video.src= URL.createObjectURL(stream);
        setSrcObject(stream, video);
        var options = {
            mimeType: 'video/webm', // or video/webm\;codecs=h264 or video/webm\;codecs=vp9
            // audioBitsPerSecond: 128000,
            // videoBitsPerSecond: 128000,
            bitsPerSecond: 128000, // if this line is provided, skip above two
            video: {
                width: 320,
                height: 200
            },
        };
        recordRTC = RecordRTC(stream, options);
        video.muted = true;
        recordRTC.startRecording();
    }
    function errorCallback(error) {
        // maybe another application is using the device
    }
    var mediaConstraints = { video: true, audio: true };
    function startRecordingNow(){
        // video.currentTime = video.setCurrentTime(0);
        navigator.mediaDevices.getUserMedia(mediaConstraints).then(successCallback).catch(errorCallback);
        video.srcObject = stream;
    }
    btnStopRecording.onclick = function () {
        recordRTC.stopRecording(function (audioVideoWebMURL) {
            video.src = audioVideoWebMURL;
            video.muted = false;
            var recordedBlob = recordRTC.getBlob();
            recordRTC.getDataURL(function(dataURL) { });
            $("#resumeVideo").hide();
            $("#pauseVideo").hide();
            $("#stopVideo").hide();
            $("#mainVideo").show();
            $("#saveAndUpload").show();
            $("#restart").show();
        });
    };
    function pauseVideo(){
        recordRTC.pauseRecording();
        $("#pauseVideo").hide();
        $("#resumeVideo").show();
        $("#stopVideo").show();
        $("#mainVideo").hide();
    }
    function resumingVideo()
    {
        $("#pauseVideo").show();
        $("#resumeVideo").hide();
        $("#stopVideo").show();
        $("#Headercounter").show();
        count = 4;
        countDownToContinue();
    }
    function saveVedio(){
        $("#waitingForUpload").show();
        $("#resumeVideo").hide();
        $("#stopVideo").hide();
        $("#Headercounter").hide();
        $("#pauseVideo").hide();
        $("#mainVideo").hide();
        $("#startVideo").hide();
        $("#saveAndUpload").hide();
        $("#restart").hide();
        var blob = recordRTC.getBlob();
        var fileType = 'video'; // or "audio"
        var fileName = 'webrecording.webm';  // or "wav"
        var formData = new FormData();
        formData.append(fileType + '-filename', fileName);
        formData.append('video', blob);
        formData.append('requested_video_id' , $("#requested_video_id").val());
        formData.append('requested_video_title' , $("#requested_video_title").val());
        formData.append('requested_video_description' , $("#requested_video_description").val());
        formData.append('requestedby' , $("#requestedby").val());
        formData.append('user_email' , $("#user_email").val());
        formData.append('uploadedby' , $("#uploadedby").val());
        formData.append('_token', $("input[name=_token]").val() );
        formData.append('_method', 'PATCH');
        xhr('upload-requested-video-web-cam', formData);
        function xhr(url, data) {
            var request = new XMLHttpRequest();
            request.open('POST', url);
//                request.setRequestHeader('Content-Type', 'application/x-www-url-formurlencoded');
//                request.setRequestHeader('X-CSRF-TOKEN',$("input[name=_token]").val() );
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    var responseData = JSON.parse(request.responseText);
                    $("#waitingForUpload").hide();
                    if(responseData.process == true){
                        $("#successfullyUploaded").show();
                        setTimeout(function(){
                            var url = "{{URL::to('/deliver_video')}}";
                            window.location = url;
                        },3000);
                    }
                    else{
                        setTimeout(function(){
                            $("#errorUploadingVideo").text(responseData.message);
                            $("#errorUploadingVideo").show();
                            // location.reload();
                        },3000);
                    }
                }
            };
            request.send(data);
        }
    }
    function countDownToStart() {
        $("#Headercounter").show();
        if (count > 1) {
            console.log(count);
            count--;
            $("#counter").text(count);
            setTimeout(countDownToStart, 1000);
        }
        else {
            $("#mainVideo").show();
            $("#Headercounter").hide();
            $("#startVideo").hide();
            $("#resumeVideo").hide();
            $("#pauseVideo").show();
            $("#stopVideo").show();
            startRecordingNow();
        }
    }
    function countDownToContinue() {
        $("#Headercounter").show();
        if (count > 1) {
            console.log(count);
            count--;
            $("#counter").text(count);
            setTimeout(countDownToContinue, 1000);
        }
        else {
            $("#mainVideo").show();
            $("#Headercounter").hide();
            $("#startVideo").hide();
            $("#resumeVideo").hide();
            $("#pauseVideo").show();
            $("#stopVideo").show();
            recordRTC.resumeRecording();
        }
    }
    function refresh(){
        // location.reload();
        recordRTC.reset();
        count=4;
        $("#mainVideo").hide();
        $("#stopVideo").hide();
        $("#saveAndUpload").hide();
        $("#restart").hide();
        countDownToStart();
    }
</script>
</body>
</html>