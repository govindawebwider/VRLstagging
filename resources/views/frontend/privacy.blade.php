<!DOCTYPE html>
<html lang="en">
<head>@include('frontend.common.head')</head>
<body class="cb-page privacy">
<div class="cb-pagewrap"> @include('frontend.common.header')  
<section id="mian-content">    
<div class="container">      
<div class="cb-grid">        
<div class="cb-block main-content">          
<div class="cb-content">            
<div class="inner-block">              
<h1 class="heading">                  
<span class="txt1">Privacy</span>
<!--<span class="txt2"></span>-->              
</h1>              
<p>
    <?php $privacyData = isset($privacy_data->content)? breakWords($privacy_data->content, 642): '' ?>
    @foreach($privacyData as $privacy)
        {!! ($privacy) !!}
    @endforeach
</p>        
</div>      
</div>    
</div>  
</section> 
@include('frontend.common.footer') 
</div>
</body>
</html>