@extends('admin.common.header')
<body class="sticky-header left-side-collapsed"  onload="initMap()">
	<section> 
		<!-- main content start-->
		<div class="main-content">
        	<div id="left-side-wrap">
				@include('admin.common.lsidebar')
			</div>
			<!-- header-starts -->
			<div class="header-section">
            	<div class="logo">
                	<h1>VRL<span class="tag-line"><span class="txt1">Video</span><span class="txt2">Request</span><span class="txt3">Line</span></span></h1>
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
                                        <span class="admin-img" style="background:url(images/1.jpg) no-repeat center"> </span>
                                        <span class="admin-name">{{ $profileData->Name}}</span>	
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
            <!-- //header-ends -->
            
            <div  class="col-md-10 profile-update-wrap">
                <div id="page-wrapper">


                    @if(Session::has('success'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{Session::get('success') }}
                    </div>
                    @endif

                    @if(Session::has('error'))
                    <div class="alert alert-danger">
                        {{Session::get('error') }}
                    </div>

                    @endif

                    <div class="graphs">
                        <h1 class="heading">Upload Video</h1>
                        {!! Form::open(array('url' =>'record_video','class'=>'form form-horizontal text-left','id'=>'upProfile','method'=>'post' ,'files'=>true)) !!}

                        <div class="inner-wrapper">
                            <div class="form-group">
                                <div class="control-label"><label for="video_title">Video title</label></div>
                                <div class="control-box"><input type="text" name="video_title" id="video_title" class='form-control' value="{{Request::old('video_title')}}">
                                    @if($errors->first('video_title')) 
                                    <p class="label label-danger" >
                                        {{ $errors->first('video_title') }} 
                                    </p>
                                    @endif</div>

                                </div>
                                <div class="form-group">
                                    <div class="control-label"><label for="video_description">Video Description</label></div>
                                    <div class="control-box"><textarea name="video_description" id="video_description"  class='form-control' value="{{Request::old('video_description')}}"></textarea>
                                        @if($errors->first('video_description')) 
                                        <p class="label label-danger" >
                                            {{ $errors->first('video_description') }} 
                                        </p>
                                        @endif</div>
                                    </div>

                                    <div class="form-group">
                                        <div class="control-label"><label for="video_price">Video Price</label></div>
                                        <div class="control-box"><input type="text" name="video_price" id="video_price" class='form-control' value="{{Request::old('video_price')}}" >($)
                                            @if($errors->first('video_price')) 
                                            <p class="label label-danger" >
                                                {{ $errors->first('video_price') }} 
                                            </p>
                                            @endif</div>
                                        </div>
                                        <div class="form-group">
                                            <div class="control-label"><label for="video">Choose video</label></div>
                                            <div class="control-box"><input type="file" name="video" id="video"  class='form-control' >
                                                @if($errors->first('video')) 
                                                <p class="label label-danger" >
                                                    {{ $errors->first('video') }} 
                                                </p>
                                                @endif</div>
                                            </div>




                                        </div>



                                        <br>
                                        {!! Form::submit('Upload',array('class'=>'btn btn-primary center-block'))!!}

                                        <!-- <button type="button" class="btn btn-primary " data-dismiss="modal" class='btn btn-primary center-block'>Close</button> -->
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>

                            @extends('admin.common.footer')
                        </section>

                        {!! Html::script('js/jquery.nicescroll.js') !!}
                        {!! Html::script('js/scripts.js') !!}
                        <!-- Bootstrap Core JavaScript -->
                        {!! Html::script('js/jquery-1.10.2.min.js') !!}
                        {!! Html::script('js/bootstrap.min.js') !!}

                    </body>
                    </html>