
<!DOCTYPE html>
<html lang="en">
    <script language="JavaScript">
      function disableCtrlKeyCombination(e)
      {
                //list all CTRL + key combinations you want to disable
                var forbiddenKeys = new Array("a", "s", "c");
                var key;
                var isCtrl;
                if(window.event)
                {
                        key = window.event.keyCode;     //IE
                        if(window.event.ctrlKey)
                          isCtrl = true;
                        else
                          isCtrl = false;
                      }
                      else
                      {
                        key = e.which;     //firefox
                        if(e.ctrlKey)
                          isCtrl = true;
                        else
                          isCtrl = false;
                      }
                //if ctrl is pressed check if other key is in forbidenKeys array
                if(isCtrl)
                {
                  for (i = 0; i < forbiddenKeys.length; i++)
                  {
                                //case-insensitive comparation
                                if (forbiddenKeys[i].toLowerCase() == String.fromCharCode(key).toLowerCase())
                                {
//                                    alert("Key combination CTRL + "
//                                                + String.fromCharCode(key)
//                                                + " has been disabled.");                                    
return false;
}
}
}
return true;
}
</script>

<head>

  @include('frontend.common.head')
  <script language=JavaScript> 
    var message="Function Disabled!";
    function clickIE4()
    { 
      if (event.button==2)
      { 
          //alert(message); return false;
        }
      } 
      function clickNS4(e){ 
        if (document.layers||document.getElementById&&!document.all){
         if (e.which==2||e.which==3){
          //alert(message); return false; 
        } 
      } 
    } 
    if (document.layers){ 
      document.captureEvents(Event.MOUSEDOWN); 
      document.onmousedown=clickNS4;
    } else if (document.all&&!document.getElementById){
     document.onmousedown=clickIE4;
   } 
   document.oncontextmenu=new Function("return false") 
 </script>
</head>
@if (count($errors) > 0)
<body class="cb-page home active video-request-form-active" ondragstart="return false" onselectstart="return false" onkeypress="return disableCtrlKeyCombination(event);" onkeydown="return disableCtrlKeyCombination(event);" >
  @elseif(Session::has('error'))
  <body class="cb-page home active video-request-form-active" ondragstart="return false" onselectstart="return false" onkeypress="return disableCtrlKeyCombination(event);" onkeydown="return disableCtrlKeyCombination(event);" >
    @else
    <body class="cb-page home" onkeypress="return disableCtrlKeyCombination(event);" onkeydown="return disableCtrlKeyCombination(event);" >
      @endif

      <section id="main-content">
        <div class="bg-img">
          @include('frontend.common.header')
          <div class="container">
            <div class="row">
              <div class="col-md-8 col-sm-12 col-lg-12 col-xs-12    ">
                <h1 class="text-center purple-text" id="letter-spacing">
                   <div class="header_heading">
                      <span class="Personalized">Personalized</span>
                      <span class="white-text">Video</span>
                   </div> 
                </h1>
                <h1 class="  text-center purple-text" id="letter-spacing">
                   <div class="header_heading">
                      <span class="From">From</span>
                      <span class="white-text">Your</span>
                      <span class="From">Favourite</span>
                      <span class="white-text">People</span>
                   </div> 
                 </h1>
                
              </div>
              <!--<div class="col-xs-3 absoluteprofile">
                <div class="profile-wrapper" style=" ">
                      <div class="flexwrap"> 
                        <div class="flex-viewport" style="overflow: hidden; position: relative;">
                          <img src="images/boy.jpg" >
                        </div>
                      </div> 
                <img src="images/mobile outline.png" class="phoneframe"/> 
              </div> -->
            </div> 
            </div>
          </div>
          <!--<div class="boy-img"></div>-->
        </div>
        <div class="container artistfluad">
          <div class="row space">
            <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
              <div class="col-md-3 col-sm-4 col-xs-12  left_panel">
                <div class="panel panel-default category-panel">
                  <h4 class="menutitle paddings">ALL CATEGORIES</h4>
                  <div class="OCCUPATION_left">
                     <!-- <div class="menutitle2 accordion">OCCUPATION</div>  -->
                      <ul class="  category show">
                      <!-- category list added by sandeep -->
                      <li>
                         <?php 
                         for ($i=0; $i < count($categories) ; $i++) {?>  
                            <a href="" onclick="view()"><?php echo $categories[$i]->title;?><br></a>
                           <?php } ?>
                      </li>   
                      </ul>
                  </div>
                </div>
              </div>
              <div class="col-md-9 col-xs-12  col-sm-8 col-lg-9 ">
              <form method="get" action="/search_artist" >
               <!--  <input type="text" name="search_query" class="icon-input form-control search_artist" placeholder="Search By Actor Name"> -->

                <div class="rectangle-2-copy">
                  <img class="oval-43" src="/images/search_icon.png" />
                  <input type="text" name="search_query" class="search-by-actor-name" placeholder="Search By Actor Name" onfocus="this.placeholder = ''" 
                    onblur="this.placeholder = 'Search By Actor Name'">
                </div>
              </form>          
              <!-- <?php print_r($categories_artist_list); ?> --> 
              
                  <div class="col-md-12 col-sm-12  col-xs-12 col-lg-12" style="padding-left: 0px;">
                  <!-- looping artists information by sandeep -->
                     @foreach ($categories_artist_list as $key=>$artists)
                      <div class="col-mdprofile" >
                        <a href="{{ $artists->Name }}" class="artist-name">
                        <div class="hex">
                          @if($artists->profile_path != "")
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="119" height="129" viewbox="0 0 138.56406460551017 160"
                               style="filter: drop-shadow(rgba(0, 0, 0, 0.2) 0px 2px 8px);">
                             <defs>
                               <pattern id="bias{{$key}}" height="100%" width="100%" patternContentUnits="objectBoundingBox" viewBox="0 0 1 1" preserveAspectRatio="xMidYMid slice">
                               <image height="1" width="1"  preserveAspectRatio="xMidYMid slice" xlink:href="{{ $artists->profile_path }}" />
                               </pattern>
                             </defs>  
                             <path fill="url(#bias{{$key}})" fill="#fff" d="M51.961524227066306 9.999999999999998Q69.28203230275508 0 86.60254037844385 10L121.2435565298214 30Q138.56406460551017 40 138.56406460551017 60L138.56406460551017 100Q138.56406460551017 120 121.24355652982139 130L86.60254037844386 150Q69.28203230275508 160 51.96152422706631 150L17.32050807568877 130Q0 120 0 100L0 60Q0 40 17.320508075688775 30Z"></path>
                            </svg>
                          @else
                              <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="119" height="129" viewbox="0 0 138.56406460551017 160" style="filter: drop-shadow(rgba(0, 0, 0, 0.2) 0px 2px 8px);"><path fill="gray" d="M51.961524227066306 9.999999999999998Q69.28203230275508 0 86.60254037844385 10L121.2435565298214 30Q138.56406460551017 40 138.56406460551017 60L138.56406460551017 100Q138.56406460551017 120 121.24355652982139 130L86.60254037844386 150Q69.28203230275508 160 51.96152422706631 150L17.32050807568877 130Q0 120 0 100L0 60Q0 40 17.320508075688775 30Z"></path></svg>
                          @endif
                        </div>
                        <h4 class="artist bold text-center">{{$artists->Name}}</h4></a>
                        <p class="artist-title text-center">{{$artists->title}}</p>
                        <p class="text-center">
                          <i class="fa fa-star yellow-text"></i>
                          <i class="fa fa-star yellow-text"></i>
                          <i class="fa fa-star yellow-text"></i>
                          <i class="fa fa-star yellow-text"></i>
                          <i class="fa fa-star"></i>
                        </p>
                      </div>
                    @endforeach             
              </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row DownloadAPP">
          <div class="container">
            <div class="col-md-12 col-xs-12  col-sm-12 col-lg-12 space">
              <div class="col-md-6 col-xs-12  col-sm-12 col-lg-6">
                <div class="head-text chartHeadING"> <span class="DOWNLOAD-text">DOWNLOAD THE APP</span><span class="artistmobile shadow1">MOBILE APP</span></div>
                <p class="pera white-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                <div class="col-md-4  col-sm-4 col-lg-4 app-btn" ><img class="img-responsive" src="images/btn_playstore.png"></div>
                <a href="https://itunes.apple.com/us/app/vrl/id1422069505?ls=1&mt=8" target="_blank"> <div class="col-md-4  col-sm-4 col-lg-4 app-btn" style="padding-left: 11px;"><img class="img-responsive" src="images/btn_appstore.png"></div> </a>
                <div class="col-md-4 col-sm-4 col-lg-4"><img class="img-responsive" src="images/vrl_logo_app_section.png"></div>
              </div>
              <div class="col-md-6  col-xs-12  col-sm-12 col-lg-6">
                <img src="images/Image_from_iOS.png" class="imgshadow">
              </div>
            </div>
          </div>
        </div>
        <!-- <div class="row paddings join-us-bg">
            <h1 class="head-talents text-center">We are looking for amazing talents like you</h1>
            <div class="col-md-offset-5">
             <a href="" data-toggle="modal" data-target="#exampleModalCenterJOIN" class="btn join-us-btn-now">JOIN US NOW!</a> 
            </div>
          </div> -->
      </section>
@include('frontend.common.footer') 
<script>
 /*
var acc = document.getElementsByClassName("accordion");
var panel = document.getElementsByClassName('category');
 
for (var i = 0; i < acc.length; i++) {
    acc[i].onclick = function() {
    	var setClasses = !this.classList.contains('active');
        setClass(acc, 'active', 'remove');
        setClass(panel, 'show', 'remove');
        
       	if (setClasses) {
            this.classList.toggle("active");
            this.nextElementSibling.classList.toggle("show");
        }
    }
} 
function setClass(els, className, fnName) {
    for (var i = 0; i < els.length; i++) {
        els[i].classList[fnName](className);
    }
}*/
</script>
 <script>
      function view(){


        $.ajax({
        method: 'GET',
        async: false,
        contentType: "application/json",dataType: "json",// Type of response and matches what we said in the route
        url:'', // This is the url we gave in the route
       
        data: {'id' : $("#id").val()}, // a JSON object to send back
        success: function(response){ // What to do if we succeed
        //response=JSON.stringify(response);
        console.log(response);
        alert( "response");
         },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
        
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
    }
});
}
 </script>

</body>
</html>
