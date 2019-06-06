@include('admin.common.header')
<body class="admin slider">
<section class="main-page-wrapper">
    <div class="main-content">
        <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>
        @include('admin.layouts.header')
        <div class="slider_wrap">
            <div class="col-md-12 ">
                <div id="page-wrapper">
                    <div class="graphs">
                        <div id="breadcrumb">
                            <a class="tip-bottom" href="/admin_dashboard"  data-original-title="Go to Home">
                                <i class="lnr lnr-home"></i> Home</a>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                            <a class="current" href="/share-percentage">
                                {{Lang::get('messages.update_share_percentage')}}
                            </a>
                        </div>
                        <h1 class="heading">{{Lang::get('messages.update_share_percentage')}}</h1>
                        <p class="desc-title"></p>
                        @if (isset($success))
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{$success['success']}}
                            </div>
                        @endif
                        @if(isset($error))
                            <div class="alert alert-danger">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{$error['error'] }}
                            </div>
                        @endif
                        <div class="artists">
                            <div class="">
                                {!! Form::open(['url' =>'share-percentage', 'class'=>'form form-horizontal text-left',
                                'method' => 'post']) !!}
                                <div class="form-group">
                                    <div class="control-label"> {!! Form::label('Admin Percentage', '')!!} </div>
                                    <div class="control-box">
                                        <input type="text" class="form-control" name="admin_share" id="admin"
                                               value="{{!is_null($share) ? $share->admin_share : null}}">
                                        @if (isset($errors) && $errors->first('admin_share'))
                                        <p class="label label-danger"> {{ $errors->first('admin_share') }} </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="control-label"> {!! Form::label('Artist Percentage', '')!!} </div>
                                    <div class="control-box">
                                        <input type="text" class="form-control" name="artist_share" id="artist"
                                               value="{{!is_null($share) ? $share->artist_share : null}}">
                                        @if (isset($errors) && $errors->first('artist_share'))
                                        <p class="label label-danger"> {{ $errors->first('artist_share') }} </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="control-label"><label></label></div>
                                    <div class="control-box"> {!! Form::submit('Submit',array('class'=>'btn btn-primary
                                        sbt-btn'))!!}
                                    </div>
                                </div>

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@extends('admin.common.footer')
</body>
</html>