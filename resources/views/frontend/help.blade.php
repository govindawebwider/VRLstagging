<!DOCTYPE html><html lang="en"><head>@include('frontend.common.head')</head>
<body class="cb-page privacy">
    <div class="cb-pagewrap"> @include('frontend.common.header') 
         <section id="mian-content">    
             <div class="container">  
                 <div class="cb-grid helpcontent" >       
                      <div class="cb-block main-content">    
                                <div class="cb-content">    
                                            <div class="inner-block">      
                                                        <h1 class="heading"><span class="txt1">Help</span>
                                                            <!--<span class="txt2"></span>-->
                                                        </h1>
                                                <p>{!! isset($help_data->content) ? ($help_data->content) : '' !!} </p>
                                            </div>
    </div>    </div>  </section> @include('frontend.common.footer') </div>
</body></html>