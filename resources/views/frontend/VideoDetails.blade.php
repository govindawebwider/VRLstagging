<!DOCTYPE html>

<html lang="en">

<head>  

	@include('frontend.common.head')

	<style>
    video::-internal-media-controls-download-button {
      display:none;
    }

    video::-webkit-media-controls-enclosure {
      overflow:hidden;
    }

    video::-webkit-media-controls-panel {
      width: calc(100% + 30px); /* Adjust as needed */
    }
    .video-detail .cb-pagewrap{

     background:url("/<?php echo $user_detail->video_background;  ?>") no-repeat;

     -webkit-background-size: cover;

     -moz-background-size: cover;

     -ms-background-size: cover;

     -o-background-size: cover;

     background-size: cover;

     background-attachment: fixed;

     background-position: center center;

   }

 </style>

 <script language=JavaScript>    

  var message="Function Disabled!";   

  function clickIE4()    {      

    if (event.button==2)        

		{//alert(message); return false;        }    }     function clickNS4(e){         if (document.layers||document.getElementById&&!document.all){         if (e.which==2||e.which==3){          //alert(message); return false;         }       }     }     if (document.layers){       document.captureEvents(Event.MOUSEDOWN);       document.onmousedown=clickNS4;    } else if (document.all&&!document.getElementById){     document.onmousedown=clickIE4;    }     document.oncontextmenu=new Function("return false")   

</script>

</head>

<body class="cb-page video-detail" y> 

  <div class="cb-pagewrap">    

    @include('frontend.common.header')    

    <section id="mian-content">      
      <?php //dd($video_detail);?>
      <div class="container">        

        <div class="cb-grid">          

          <div class="cb-block cb-box-70 main-content">           

            <div class="cb-content nopadding-right nomargin-right">              

              <div class="inner-block">              

                <h1 class="heading">              

                  <a href="{{URL($user_detail->profile_url)}}">               

                    <span class="txt1">{{$video_detail->Title}}</span>  
                    <span class="sub_title">{{$user_detail->Name}}</span>

                  </a> 

                </h1>  
                @if(Session::has('error'))
                <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>
                @endif
                @if(Session::has('success'))

                <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

                @endif




                <?php $str = $video_detail->VideoURL;$rest = substr($str,28);?>  

                <!-- <iframe id="video_ip" src="<?php //echo $rest ;?>" frameborder="0" allowfullscreen></iframe> -->                          

                <video style="width:100%;max-height:575px;" id="myvideo" controls autoplay>                                

                  <source src="<?php echo $rest ;?>" type="video/mp4"></source>                                

                  <source src="<?php echo $rest ;?>" type="video/webm"></source>                                

                  <source src="<?php echo $rest ;?>" type="video/ogg"></source>                                



                </video> 



                <div class="SinglePostStats">                          	

                  <div class="media-object-section">								

                    <div class="author-des clearfix">                                    

                      <div class="singlePostDescription"> 
                        @if($video_detail->Description !=null )
                        <?php 
                        $string = $video_detail->Description;
                        $desc = substr($string,0,300);
                        ?>
                        <span id="more">{{$video_detail->Description}} <span id="hide_less" class="more" style="color:red"> Less</span></span>
                        <span id="less"> {{$desc}} ...
                          <span>
                            <?php $tot_str=strlen($video_detail->Description);?>
                            @if($tot_str>600)
                            <span style="color:red" class="more">More</span> 
                            @endif
                          </span> 
                        </span>
                        @else
                        <span id="morebtn" class="box-more-btn"></span> 
                        @endif 



                      </div>                                      

                      <div class="share">                                    

                    <!--<div class="post-like-btn">                                      

                    <span><a href="#"><i class="fa fa-thumbs-o-up"></i></a></span>                                      

                    <span><a href="#"><i class="fa fa-thumbs-o-down"></i></a></span>                                    

                  </div>-->                                    

                  {{--*/ $Pro_id=$user_detail->ProfileId; $id= array();/*--}}                                                                        

                  @foreach($req_pro_id as $req_pro_ids)                                    

                  <?php $id[]=$req_pro_ids->requestToProfileId;?>                                    

                  @endforeach                                   

                  @if (in_array($Pro_id, $id))                                   

                    <!-- <div class="download">                                      

                    <a href="{{URL('Buy/'.$video_detail->VideoId)}}">Download</a>                                    

                  </div> -->                                    

                  @endif                                 

                </div>                                

              </div>								                              

            </div>                             

            <div class="artist_details">                                

              <div class="artist_img_sec clearfix">                                                                    

                <!-- <a href="{{URL('artist/'.$user_detail->profile_url)}}">  -->                                     

                <div class="author-single-post">                                          

                    <!-- <span class="artist_img">

                    <img src="/{{$user_detail->profile_path}}">

                    </span>                                           

                    <span>{{$user_detail->Name}}</span>   --> 



                    <div class="cb-content">

                      @if(Auth::check())

                      <div class="testimo_wraper_block">

                        <?php //dd($comment_detail);?>



                        @foreach ($comment_detail as $testimonial)

                        <div class="inner_wrap">

                          <div class="mess">

                            <div class="user_img"> <img src="/images/user.jpg"></div>

                            <div class="txt_block">

                              <span class="name">{{$testimonial->user_name}}</span>

                              <span class="date">{{date('M d Y H:i:s',strtotime($testimonial->created_at))}}</span>

                              <span class="txt">{{$testimonial->Message}}</span>

                            </div>

                          </div>



                        </div>

                        @endforeach

                      </div>

                      <div class="testi_form">

                        <?php //dd($artist);?>

                        <h1>Leave a Comment here</h1>

                        


                        <form action="/video_testimonial" method="POST" role="form">

                          {{csrf_field()}}

                          <input type="hidden" id="to_profile_id" name="to_profile_id"  value="{{$user_detail->ProfileId}}">

                          <input type="hidden" id="by_profile_id" name="by_profile_id" value="{{$user->profile_id}}">

                          <input type="hidden" id="video_id" name="video_id" value="{{$video_detail->VideoId}}">

                          <div class="form-group">

                            <label for="message">Message</label>

                            <textarea class="form-control" id="message" name="message"></textarea>

                            @if($errors->first('message')) 

                            <p class="label label-danger" >

                             {{ $errors->first('message') }} 

                           </p>



                           @endif

                         </div>



                         <span class=></span>

                         <button type="submit" class="btn btn-primary">Submit</button>

                       </form>

                     </div>

                     @endif

                   </div>                                                                           

                 </div>                                                                         	


               </div>                             

             </div>                                                         

           </div>                          



         </div>                    

       </div>                 

     </div>                  

     <div class="cb-block cb-box-30 right-bar"> 

      <div class="cb-content video_search">  

        <div class="inner-block">                       

          <h2 class="heading">

           <span>Search Videos</span>                       

         </h2>                                             

         <form role="search" method="get" id="searchform"  action= "/search" >                       

          <div class="input-group">                          

            <input type="text" placeholder="Enter your keyword" name="search_query"  class="input-group-field">  

            <div class="input-group-button">                    

             <input type="submit" value="Submit" class="button">

           </div>  

         </div>

       </form>                   

     </div>                

   </div>

   <div class="cb-content">

    <div class="inner-block">

      <h2 class="heading">                
        @if(count($other_video)==1)
        <span>Related Video</span> 
        @else
        <span>Related Videos</span> 
        @endif
      </h2>                     

      <div class="widgetContent">

        @foreach($other_video as $other_videos)   

        <div class="video-box thumb-border"> 

          <div class="title_des_img">

            <a href="{{URL('video/'.$other_videos->ProfileId.'/'.$other_videos->Title)}}">

             <?php $str = $other_videos->VideoThumbnail;

             $rest = substr($str,21);?>                           

             <img src="<?php echo $rest?>" alt="">                                                       

           </a>                                                       

         </div>                       

         <div class="title_des_view">                          

          <h4 class="video_title">{{ $other_videos->Title}}</h4>  

          <p>{{str_limit($other_videos->Description,50)}}</p>

          <!--<p>{{ $other_videos->VideoPrice}}</p>-->                       

        </div>

      </div>                      

      @endforeach 

    </div>

  </div>

</div>              

</div>            

</div>          

</div>        

</section>        

@include('frontend.common.footer')        

</div>      
<script type="text/javascript">

  $(document).ready(function() {
    $('#myvideo').bind('contextmenu', function(e) {
      return false;
    }); 
  });
</script>
</body>   

</html>