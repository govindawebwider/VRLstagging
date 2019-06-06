<!DOCTYPE html>
<html lang="en">
<head>@include('frontend.common.head')</head>
<body class="cb-page privacy">
<div class="cb-pagewrap"> @include('frontend.common.header')  
<section id="mian-content">    
<div class="container">      
<div class="cb-grid">        
<div class="cb-block main-content helpcontent">          
<div class="cb-content">            
<div class="inner-block">              
<h1 class="heading">                  
<span class="txt1">About</span>
<!--<span class="txt2"></span>-->              
</h1>              
<p>

{!! isset($about_data->content) ? ( $about_data->content ) : '' !!}
</p>        
</div>      
</div>    
</div>  
</section> 
@include('frontend.common.footer') 
</div>
</body>
</html>
