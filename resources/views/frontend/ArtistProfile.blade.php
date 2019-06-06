<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <title>VRL | Artist Profile</title>
  <!-- core CSS -->
  {!! Html::style('css/bootstrap.min.css') !!}
  {!! Html::style('css/font-awesome.min.css') !!}
  {!! Html::style('css/animate.min.css') !!}
  {!! Html::style('css/owl.carousel.css') !!}
  {!! Html::style('css/owl.transitions.css') !!}
  {!! Html::style('css/prettyPhoto.css') !!}
  {!! Html::style('css/main.css') !!}
  {!! Html::style('css/responsive.css') !!}
  {!! Html::script('js/jquery.js') !!}
  {!! Html::script('js/bootstrap.min.js') !!}


  <script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip(); 
    });
  </script>
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->       
  </head><!--/head-->
  <body id="home" class="homepage">
    <header id="header">
      <nav id="main-menu" class="navbar navbar-default navbar-fixed-top" role="banner">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="">
              <img src="images/logo1.png" alt="logo"></a>
            </div>

            <div class="collapse navbar-collapse navbar-right">
              <ul class="nav navbar-nav">
                <li><a href="/"><span class="grey-color">Home</span></a></li>
                <li class="dropdown">
                  <?php 
                  $name = session()->get('name');
                  $email = session()->get('email');
                  $type = session()->get('type');
                  ?>
                  <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="grey-color">Welcome </span>{{Auth::user()->user_name}}<span class="caret"></span></a>
                  <ul class="dropdown-menu">

                   <li><a href="{{ URL::route('artistDash') }}">Change Profile</a></li>
                   <li><a href="#">Notifications <span class="badge">15</span></a></li>
                   <li><a href="{{ URL::route('getLogout') }}">Logout</a></li> 
                 </ul>
               </li>                      
             </ul>
           </div>
         </div><!--/.container-->
       </nav><!--/nav-->
     </header><!--/header-->

     <section id="main-slider">
      <div class="owl-carousel">
        <div class="item" style="background-image: url(images/bg.jpg);"></div><!--/.item-->
        <div class="item" style="background-image: url(images/bg1.jpg);"></div><!--/.item-->
      </div><!--/.owl-carousel-->
    </section><!--/#main-slider-->
    <div class="container no-padding">
      <section class="banner-artists">
       <img src="images/profile-cover.jpg" alt="" />
       <a  class="profile-photo" href="#" >&nbsp;</a>
     </section>
   </div>
   <section id="portfolio" class="portfolio">
    <div class="container">
      <div class="col-md-3 col-sm-3 col-xs-12">
       <h1 class="title">Settings</h1>
       <ul class="list-group">
         <li class="list-group-item"><span class="badge label-info">12</span> <a href="#">New Requests</a></li>
         <li class="list-group-item"><span class="badge label-danger">5</span> <a href="#">Deleted Requests</a></li> 
         <li class="list-group-item"><span class="badge">3</span> <a href="#">Make Request</a></li> 
       </ul>
     </div>
     <div class="col-md-9 col-sm-9 col-xs-12 no-padding">
       <h1 class="title">My Videos</h1>
       <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
        <a href="#"><img class="img-responsive" src="images/bg1.jpg" alt=""></a>
        <a href="#"><img class="img-responsive" src="images/full.jpg" alt=""></a>
        <a href="#"><img class="img-responsive" src="images/033.jpg" alt=""></a>
        <a href="#"><img class="img-responsive" src="images/022.jpg" alt=""></a>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
        <a href="#"><img class="img-responsive" src="images/033.jpg" alt=""></a>
        <a href="#"><img class="img-responsive" src="images/022.jpg" alt=""></a>
        <a href="#"><img class="img-responsive" src="images/bg1.jpg" alt=""></a>
        <a href="#"><img class="img-responsive" src="images/full.jpg" alt=""></a>
      </div>
    </div>

    <!--/.container-->
  </section><!--/#portfolio-->
  <footer id="footer">
    <div class="container">
      <div class="row">
        <div class="col-sm-6">
          &copy; 2014 Your Company. Designed by <a target="_blank" href="" title="">Coding Brains</a>
        </div>
        <div class="col-sm-6">
          <ul class="social-icons">
            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
            <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
            <li><a href="#"><i class="fa fa-pinterest"></i></a></li>
            <li><a href="#"><i class="fa fa-dribbble"></i></a></li>
            <li><a href="#"><i class="fa fa-behance"></i></a></li>
            <li><a href="#"><i class="fa fa-flickr"></i></a></li>
            <li><a href="#"><i class="fa fa-youtube"></i></a></li>
            <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
            <li><a href="#"><i class="fa fa-github"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
  </footer><!--/#footer-->
  <!-- Modal -->
  <div id="login" class="modal fade" role="dialog">
   <div class="modal-dialog"> 
    <!-- Modal content-->
    <div class="modal-content">
     <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Login</h4>
    </div>
    <div class="modal-body">
      <form role="form">
       <div class="form-group">
        <input type="email" class="form-control first" id="email" placeholder="Email Address"> 
      </div>
      <div class="form-group">
        <input type="password" class="form-control last" id="pwd" placeholder="Password">
      </div>
      <div class="checkbox">
        <label>
         <input type="checkbox">
         Remember me</label>
       </div>
       <button type="submit" class="btn btn-default">Login</button>
     </form>
   </div>
   <div class="modal-footer">
    <p>or login via</p>
    <ul class="social">
     <li><a href="#" class="fb" data-toggle="tooltip" data-placement="top" title="Facebook"><i class="fa fa-facebook"></i></a></li>
     <li><a href="#" class="tw" data-toggle="tooltip" data-placement="top" title="Twitter"><i class="fa fa-twitter"></i></a></li>
     <li><a href="#" class="yt" data-toggle="tooltip" data-placement="top" title="Youtube"><i class="fa fa-youtube"></i></a></li>
   </ul>
 </div>
</div>
</div>
</div>
{!! Html::script('http://maps.google.com/maps/api/js?sensor=true') !!}
{!! Html::script('js/owl.carousel.min.js') !!}
{!! Html::script('js/mousescroll.js') !!}
{!! Html::script('js/smoothscroll.js') !!}
{!! Html::script('js/jquery.prettyPhoto.js') !!}
{!! Html::script('js/jquery.isotope.min.js') !!}
{!! Html::script('js/jquery.inview.min.js') !!}
{!! Html::script('js/wow.min.js') !!}
{!! Html::script('js/main.js') !!}

</body>
</html>