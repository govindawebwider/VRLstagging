<!-- left side start-->
<div class="left-side sticky-left-side">

    <div class="left-side-inner">

        <ul class="nav nav-pills nav-stacked custom-nav">

            <li class="{{ Request::is('userAccount') ? 'active' : '' }}">

                <a href="{{URL('userAccount')}}"> <img src="/images/dashboard.png"> <span>Dashboard</span></a>
            </li>
            <li class="{{ Request::is('userAccount') ? 'active1' : '' }}">



                <a href="javascript:void(0)"> <img src="/images/video.png"><span>Reaction Videos</span>  </a> <span class="cbicon-arrow"> <i class="icon icon-ctrl"></i></span>

                <ul class="sub-child">
                    <li class="{{ Request::is('userAccount/view-reaction') ? 'active' : '' }}"> <a href="{{URL('userAccount/view-reaction')}}"> <i class="icon icon-play3"></i>
                            <span>View Videos</span> 

                        </a> 
                    </li>

                    <!--li class="{{ Request::is('userAccount/reaction-upload') ? 'active' : '' }}"> <a href="{{URL('userAccount/reaction-upload')}}"> <i class="icon icon-play3"></i> 

                            <span>Upload Videos</span>                  
                        </a> 
                    </li-->
                </ul>

            </li>
   

        </ul>

    </div>

</div>

<!-- left side end-->