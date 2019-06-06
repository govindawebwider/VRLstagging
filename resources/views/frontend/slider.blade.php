@include('admin.common.header')

















<body class="sticky-header left-side-collapsed"  onload="initMap()">





  <section>





    <!-- main content start-->





    <div class="main-content">





      <div id="left-side-wrap">





        @include('frontend.artistDashboard.layouts.lsidebar')











      </div>





      <!-- header-starts -->





      <div class="header-section">





        <div class="top-main-left">





          <span class="logo">





            {!! Html::image('images/logo1.png')!!}





          </span>





          <a href="javascript:void(0)" class="toggle menu-toggle"><i class="lnr lnr-menu"></i></a>





                   <!--  <div class="search">





                        <form class="search-form">





                            <input type="text" class="search"><a class="btn"><i class="lnr lnr-magnifier"></i></a>





                        </form>





                      </div> -->





                    </div>





                    <div class="menu-right">





                      <div class="notice-bar">





                        <ul>





                          <li><a href="{{ URL('notifications')}}"><i class="lnr lnr-alarm"></i><span class="no">10</span></a><span class="tooltip">notifications</span></li>





                          <li><a href="{{ URL('activite')}}"><i class="lnr lnr-file-empty"></i><span class="no">10</span></a><span class="tooltip">activite</span></li>





                        </ul>





                      </div>





                      <div class="user-panel-top">





                        <div class="profile_details">





                          <div class="profile_img">





                            <div class="dropdown user-menu">





                              <span class="dropdown-toggle">





                                        <!-- <span class="admin-img" style="background:url(images/1.jpg) no-repeat center"> </span>





                                        <span class="admin-name">Name</span> -->





                                        <i class="arrow"></i>





                                      </span>





                                    </div>





                                <!--<div class="dropdown-menu">





                                    <ul>











                                    </ul>





                                  </div>-->





                                </div>





                              </div>





                            </div>





                          </div>





                        </div>





                        <div id="page-wrapper">





                          <div class="slider_edit_form">





                            @if(Session::has('success'))





                            <div class="alert alert-info">





                              {{Session::get('success') }}





                              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>





                            </div>





                            @endif











                            <form action="{{URL('create_slider')}}" method="POST" enctype="multipart/form-data" role="form">





                              <legend>Create Slider</legend>





                              {!! csrf_field(); !!}











                              <div class="form-group">





                                <label for="slider_title">Title</label>





                                <input type="text" name="slider_title"  class="form-control" id="slider_title">





                                @if($errors->first('slider_title'))





                                <p class="label label-danger" >





                                  {{ $errors->first('slider_title') }}





                                </p>





                                @endif





                              </div>

















                              <div class="form-group">





                                <label for="slider_description">Description</label>


                                <textarea name="slider_description" class="form-control" cols="30" rows="10" id="slider_description"></textarea>





                                @if($errors->first('slider_description'))





                                <p class="label label-danger" >





                                  {{ $errors->first('slider_description') }}





                                </p>





                                @endif





                              </div>





                              <div class="form-group">





                                <label for="slider">Choose Slider</label>





                                <input type="file" name="slider" class="form-control" id="slider">


                                @if($errors->first('slider'))





                                <p class="label label-danger" >





                                  {{ $errors->first('slider') }}





                                </p>





                                @endif


                              </div>























                              <input type="submit" class="btn btn-primary" value="Update">





                            </form>





                          </div>





                        </div>











                      </div>





                      @extends('admin.common.footer')





                    </section>











                  </body>





                  </html>