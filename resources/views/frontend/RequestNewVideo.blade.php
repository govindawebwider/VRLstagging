
<?php //dd($req_detail);?>
<!DOCTYPE html>
<html lang="en">
<head>
  @include('frontend.common.head')
  
  <style>
      #recipient-pronounciation-record , #sender-pronounciation-record{
            width: 27px;
            height: 26px;
            margin-top: 28px;
            cursor:pointer;
      }
      
            /* The Modal (background) */
      .modal {
          display: none; /* Hidden by default */
          position: fixed; /* Stay in place */
          z-index: 1; /* Sit on top */
          left: 0;
          top: 0;
          width: 100%; /* Full width */
             height: 47% !important; /* Full height */
          overflow: auto; /* Enable scroll if needed */
          background-color: rgb(0,0,0); /* Fallback color */
          background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
      }

      /* Modal Content/Box */
      .modal-content {
          background-color: #fefefe;
          margin: 7% auto; /* 15% from the top and centered */
          padding: 20px;
          border: 1px solid #888;
          width: 80%; /* Could be more or less, depending on screen size */
      }

      /* The Close Button */
      .close {
          color: #aaa;
          float: right;
          font-size: 28px;
          font-weight: bold;
      }

      .close:hover,
      .close:focus {
          color: black;
          text-decoration: none;
          cursor: pointer;
      }
      
      .hidden {
          display: none;
      }
   
      </style>
  
</head>
<body class="cb-page request_new_video">
  <div class="cb-pagewrap"> @include('frontend.common.header')
    <section id="mian-content">
      <div class="container">
        <div class="cb-grid">
          <div class="cb-block cb-box-35 main-content ">
            <div class="cb-content">
              <div class="inner-block">
                <div class="artist_content"> <a href="{{URL($user_detail->profile_url)}}"> <img src="/{{$user_detail->profile_path}}"/>
                  <h4>{{$user_detail->Name}}</h4>
                </a> </div>
              </div>
            </div>
          </div>
          <div class="hide turnaroundtimevalue" data-turnaroundtime="{{$user_detail->timestamp}}"></div>
          <div class="cb-block cb-box-65 main-content ">
            <div class="cb-content">
              <div class="inner-block">
                <div class="request_new_video_form">
                  <input type="hidden" name="artist_id" value="{{$user_detail->ProfileId}}" id="artist_id" class="artist_id">

                  <h2 class="heading"><span>Request Video</span></h2>
                  
                  @if(Session::has('success'))
                  <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>{{Session::get('success') }} </div>
                  @endif
                  @if(Session::has('error'))
                  <div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>
                  @endif
                  <div class="request-form">
                      <form action="/RequestNewVideo/{{$user_detail->ProfileId}}" class="form" id="requestForm" method="post">
                      {{csrf_field()}}
                      <input type="hidden" name="business" value="info@videorequestline.com">
                      <input type="hidden" name="user_id" value="<?php echo isset($user_id);?>">
                      <input type="hidden" name="artist" value="{{$user_detail->ProfileId}}">
                    <div>
                  <label>Desired Song Name *</label>
                 <input type="text" name="song_name1"
                         id="ddselect"
                         placeholder="If no song provided, it will be artists' choice"
                         value="{{Request::old('song_name1')}}"
                         
                         >
                   @if($errors->first('song_name1'))
                  <p class="label label-danger" >
                   {{ $errors->first('song_name1') }}
                 </p>
                 @endif
                 
                  @if($video_list!=null)
                  
                      <!-- <select name="song_name1" id="ddselect" placeholder="Enter Song Name">
                       <option value="" disabled selected>Select your Song</option>

                       @foreach ($video_list as $video_list)
                       <option>{{$video_list->title}}</option>
                       @endforeach


                     </select> -->
                     <!-- <label for="" id="new_song" style="background-color: red">New Song</label> -->
                 @else
                 
<!--                       <input type="text"
                        name="song_name2"
                        value="{{Request::old('song_name2')}}"
                        id="song_name2"
                        placeholder="Enter Song Name"
                        >  -->

                

                @endif
<!--                      <input 
                       type="text"
                       id="new_song_name"
                       name="song_name3"
                       value="{{Request::old('song_name3')}}" 
                       placeholder="Enter Song Name"
                       minlength="5"
                       maxlength="255"
                       >-->
              </div>
                
                
                
                
              <div>
                <label>Recipient Name (TO:)<span class="red">*</span> </label>
                <input 
                    type="text"
                    name="user_name" 
                    id="user_name"
                    value="<?php echo $output =isset($req_detail->Name) ? $req_detail->Name : Request::old('user_name');?>"
                    placeholder="Who is this for ?"

                    maxlength="255"
                    
                    >   
                
                
                @if($errors->first('user_name'))
                <p class="label label-danger" >
                  {{ $errors->first('user_name') }}
                </p>
                @endif
              </div>
                
                      <div style="display:flex">
                          
                           <div style="width:100%">
                            <label>Recipient Name Pronunciation ( spell it out ) </label>

                            <input type="text"
                                   name="pronun_name"
                                   id="pronun_name" 
                                   value="<?php echo $output =isset($req_detail->receipient_pronunciation) ? $req_detail->receipient_pronunciation : Request::old('pronun_name');?>" 
                                   placeholder="How do you say their name?"
                                   maxlength="255"
                                   >   
                            @if($errors->first('pronun_name'))
                            <p class="label label-danger" >
                              {{ $errors->first('pronun_name') }}
                            </p>
                            @endif
                          </div>
                           <div style="width:5%; ">
                               <image id="recipient-pronounciation-record" data-name="Recipient" data-index="0" class="record" title="Record Pronunciation Name" src="http://cdn.ilovefreesoftware.com/wp-content/uploads/2013/05/Sound-Recorder-icon.png"/>
                           </div>    
                      </div>
                

              <div>
                <label>Recipient E-Mail Address <span class="red">*</span> </label>
                
                <?php if(isset($req_detail) == null){
                  ?>
                  <?php //echo $user_email;?>
                  <input 
                      type="text"
                      name="user_email"
                      id="user_email"
                      value="<?php echo $output =isset($user_email) ? $user_email : Request::old('user_email');?>"
                      placeholder="Enter their Email Address"
                      maxlength="255"
                      >   
                 
                  
                  @if($errors->first('user_email'))
                  <p class="label label-danger" >
                    {{ $errors->first('user_email') }}
                  </p>
                  @endif
                  <?php
                }else{?>
                
                  <input type="text"
                         name="user_email"
                         id="user_email"
                         accesskey=""value="<?php echo $output =isset($req_detail->requestor_email) ? $req_detail->requestor_email : Request::old('user_email');?>"
                         placeholder="Enter Recipient Email Address"
                         maxlength="255"
                    >  
               
                
                <input type="hidden" 
                       name="recei_email"
                       id="recei_email" 
                       value="<?php echo $output =isset($req_detail->requestor_email) ? $req_detail->requestor_email : Request::old('user_email');?>
                       "
                       > 
               
                
                @if($errors->first('user_email'))
                <p class="label label-danger" >
                  {{ $errors->first('user_email') }}
                </p>
                @endif
                <?php }?>
              </div>
                
              <div>
                <label>Your Name (FROM:)<span class="red">*</span> </label>

                <input type="text"
                       name="sender_name"
                       id="sender_name"
                       value="<?php echo $output =isset($req_detail->sender_name) ? $req_detail->sender_name : Request::old('sender_name');?>" 
                       placeholder="Who is the video request from?"
                       maxlength="255"
                       >   
                @if($errors->first('sender_name'))
                <p class="label label-danger" >
                  {{ $errors->first('sender_name') }}
                </p>
                @endif
              </div>
                      <div style="display: flex">
                          <div style="width:95%">
                            <label>Your Name Pronunciation ( spell it out ) </label>

                            <input type="text"
                                   name="sender_name_pronun"
                                   id="sender_name_pronun" 
                                   value="<?php echo $output =isset($req_detail->sender_name_pronunciation) ? $req_detail->sender_name_pronunciation : Request::old('sender_name_pronun');?>" 
                                   placeholder="How do you say your name?"
                                   >   


                            @if($errors->first('sender_name_pronun'))
                            <p class="label label-danger" >
                              {{ $errors->first('sender_name_pronun') }}
                            </p>
                            @endif
                          </div>
                           <div style="width:5%; ">
                               <image id="sender-pronounciation-record" data-name="Sender" data-index="1" class="record" title="Record Sender Name" src="http://cdn.ilovefreesoftware.com/wp-content/uploads/2013/05/Sound-Recorder-icon.png"/>
                           </div>  
                      </div>
              <div>
                <label> Your E-Mail Address <span class="red">*</span> </label>

                @if($is_login!="No")
                <?php $sender_email=Auth::user()->email;?>
                
                <input type="text"
                       name="sender_email"
                       id="sender_email"
                       value="<?php echo $output =isset($user_email) ? $user_email : Request::old('sender_email');?>"
                       placeholder="Your E-Mail Address" 
                       readonly>   
               
                
                @if($errors->first('sender_email'))
                <p class="label label-danger" >
                  {{ $errors->first('sender_email') }}
                </p>
                
                @endif
                @endif
                @if($is_login=="No")
                <input type="text"
                       name="sender_email"
                       id="sender_email"
                       value="<?php echo $output =isset($req_detail->sender_email) ? $req_detail->sender_email : Request::old('sender_email');?>" 
                       placeholder="Enter Your E-Mail Address"
                       >
               
                @if($errors->first('sender_email'))
                <p class="label label-danger" >
                  {{ $errors->first('sender_email') }}
                </p>
                @endif
                @endif 

              </div>
                
              <div>
                <label>Requested Delivery Date <span class="red">*</span> </label>
                <input type="text" 
                       name="delivery_date" 
                       id="datepicker"
                       class="request_datepicker delivery_datepicker"
                       value="{{Request::old('delivery_date')}}" 
                       placeholder="When do you want this to be delivered?"
                       >
                
                @if($errors->first('delivery_date'))
                <p class="label label-danger" >
                  {{ $errors->first('delivery_date') }}
                </p>
                @endif
              </div>

              <div>
                <label>Occassion ( birthday, holiday, valentines, anniversary, sorry, thanks, other )</label>
                <input type="text"
                       name="Occassion"
                       id="Occassion"
                       value="{{Request::old('Occassion')}}"
                       placeholder="Enter the reason for the gift"
                       >

                @if($errors->first('Occassion'))
                <p class="label label-danger" >
                  {{ $errors->first('Occassion') }}
                </p>
                @endif
              </div>

                
              <div>
                <label>Personalized Message </label>
                <textarea 
                       name="person_message"
                       id="person_message"
                       value="{{Request::old('person_message')}}"
                       placeholder="Enter a unique message"
                       maxlength="500"
                       ></textarea>
                @if($errors->first('person_message'))
                <p class="label label-danger" >
                  {{ $errors->first('person_message') }}
                </p>
                @endif
              </div>
                   
                      <a href="{{URL(url()->previous())}}" style="background-color: gray;
                      border-radius: 0;
                      color: #fff;
                      font-weight: 700;
                      height: 2.5rem;
                      line-height: 1.2rem;
                      margin-top: 1rem;
                      float: left;
                      padding: 10px 1.5rem;
                      text-shadow: none;
                      text-transform: uppercase;
                      width: auto;"><span style="font-size:13px !important;" >Back</span> </a>
                      
                      <input type="submit" class="btn btn-default login" value="Proceed to Payment">
                    </form>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
        
    </section>

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
                <li><a href="https://www.facebook.com/vid.req.9"><i class="fa fa-facebook"></i></a></li>
                <li><a href="https://www.twitter.com/videorequestline"><i class="fa fa-twitter"></i></a></li>
             <!--   <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                <li><a href="#"><i class="fa fa-pinterest"></i></a></li> -->
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
                <p>© {{date('Y')}} Video Request - Line All Rights Reserved 
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
        
        <!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <h5 style="font-size:21px;">Record <span id="toWhom"></span> Name</h5>
    <button class="btn btn-info " id="start-recording">Start Record</button>
    <button class="btn btn-primary hidden" id="stop-recording">Stop Record</button>
    <button class="btn btn-success hidden" id="save-recording">Save Record</button>
    <hr>
    <audio id="audioRecordTag" controls autoplay></audio>
  </div>

</div>
        
        
    </section>
    {!! Html::script('js/jquery-1.10.2.min.js') !!}
    <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

    {!! Html::script('js/jquery.inputmask.bundle.min.js') !!}
    {!! Html::script('js/jquery.date-dropdowns.js ') !!}
    <script type="text/javascript">


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


        $('.req_video_del').on('click',function () {
      //alert('test');

      $('.out_mess_del_video').addClass('delete_video');

    })

        $('.out_mess_del_video .ms_close').on('click',function () {

          $('.out_mess_del_video').removeClass('delete_video');

        })


      });
    </script>
    @if(Auth::check())
    @if(Auth::user()->type =="Artist" OR Auth::user()->type =="Admin")

    <script type="text/javascript">
//      setInterval(function() {
//        var url = "{{URL('/')}}" ;
//        $.ajax({
//          url: url+"/check_user_auth",
//          cache: false, 
//          success: function(result){ 
//            if(result === "false")
//            {
//              window.location.href="http://www.videorequestline.com/getLogout";
//            }
//          }
//        });
//      },1000);
    </script>
    @endif  
    @if(Auth::user()->type == "User")
    <script type="text/javascript">
//      setInterval(function() {
//        $.ajax({
//          url: "/check_user_auth",
//          cache: false, 
//          success: function(result){ 
//            if(result === "false"){
//              window.location.href="http://www.videorequestline.com/getLogout";
//            }
//          }
//        });
//      },1000);
    </script>
    @endif  
    @endif
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
      /*$( function() {
        $(document).ready(function(){
          var myData = $('#artist_id').val();
          $.ajax({
            url: "https://www.videorequestline.com/testby/"+myData,
            type: "GET",
            dataType: "html",
            success: function(result){
              da= parseInt(result);
              $("#datepicker").datepicker({ maxDate: "+30Y",
                minDate: da});
            },
            error: function (jqXHR, status, err) {
            //alert("Local error callback.");
          }
        });
        });
      });*/
    </script>
    
    <!--
    
        Working on voice recorders
        
    -->
    <script src="https://cdn.WebRTC-Experiment.com/RecordRTC.js"></script>
    <script src="https://cdn.webrtc-experiment.com/gumadapter.js"></script>
    
    <script>
        var records = new Array();
         var recordRTC ;
         
         var aud = document.getElementById('audioRecordTag');
            aud.addEventListener("ended", function(){
                this.currentTime = 0;
                this.pause();
            });
           $(document).ready(function(){
              // Get the modal
             

            $(".record").click(function(){
                 $("#start-recording").show();
               $("#stop-recording").hide();
               $("#save-recording").hide();
                
                
                var toWhom = $(this).attr("data-name"); 
                var index = $(this).attr("data-index"); 

                $("#myModal").show();
                 
                 $("#toWhom").attr('data-index',index);
                 
                 $("#toWhom").text(toWhom);

            });       
            
            $(".close").click(function(){
                 $("#myModal").hide();
            });   
             
           });
           $("#start-recording").click(function(){
               $(this).hide();
               $("#stop-recording").show();
               $("#save-recording").hide();
              startRecordingNow(); 
              
              
           });
           
           $("#stop-recording").click(function(){
               $("#start-recording").hide();
               $("#stop-recording").hide();
               $("#save-recording").show();
              stopRecording(); 
           });
           
           $("#save-recording").click(function(){
             $("#start-recording").show();
               $("#stop-recording").hide();
               $("#save-recording").hide();  
               
               
             var blob = recordRTC.getBlob();
             
             console.log(blob);
             $("#audioRecordTag").currentTime=0;
             var index = $("#toWhom").attr('data-index');
             records[index] = blob;
             $("#myModal").hide();
           });
           
            function startRecordingNow(index){
                 var mediaConstraints = { video: false, audio: true };

                  navigator.mediaDevices.getUserMedia(mediaConstraints).then(successCallback).catch(errorCallback);
            }
            
            function successCallback(stream) {
                var video = document.querySelector('audio');
                video.muted = true;
                
                // RecordRTC usage goes here
               video.src= URL.createObjectURL(stream);

                var options = {
                  // mimeType: 'video/webm', // or video/webm\;codecs=h264 or video/webm\;codecs=vp9
                  // audioBitsPerSecond: 128000,
                  // videoBitsPerSecond: 128000,
                  type:'audio',
                  bitsPerSecond: 128000 // if this line is provided, skip above two
                };

                recordRTC = RecordRTC(stream, options);

                recordRTC.startRecording();
            }

            function errorCallback(error) {
                  // maybe another application is using the device
                }
            
            function stopRecording(){
                               var video = document.querySelector('audio');

                        recordRTC.stopRecording(function (audioVideoWebMURL) {
                            video.src = audioVideoWebMURL;
                            video.muted = false;
                            
                            var recordedBlob = recordRTC.getBlob();
                            recordRTC.getDataURL(function(dataURL) { });
                        });
                     

            }
            
            $(".login").click(function(e){
                var form = $('form')[1];
                  var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                      if (this.readyState == 4 && this.status == 200) {
                            var data=xhttp.responseText;
                            var jsonResponse = JSON.parse(data);
                            console.log(jsonResponse);
                            if(jsonResponse["proccess"]=="success"){   
                            window.location.replace("https://www.videorequestline.com/stripe_payment");
 
                            }
                            else{
                                $(".errorsDetails").remove();
                                $.each(jsonResponse["errors"],function(i,value){
                                   var p = document.createElement("p");
                                   p.setAttribute("class" , "label label-danger errorsDetails");
                                   p.textContent = value;
                                    $("input[name="+i+"]").parent().append(p);
                                    $("textarea[name="+i+"]").parent().append(p);
                                })
                            }
                        }
                        
                    };

                e.preventDefault();
                var action = $("#requestForm").attr('action'); 
                var formData = new FormData(form);
                if(records[0] != null)
                {
                        formData.append("recipient-record",records[0]);
                }

                if(records[1] != null){
                   formData.append("sender-record",records[1]);
                }


                xhttp.open("post", action);
                xhttp.send(formData);

            })
           
    </script>
  </div>
</body>
</html>