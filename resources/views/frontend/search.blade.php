<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.common.head')
</head>
<body class="cb-page search">
<div class="cb-pagewrap">
    @include('frontend.common.userHeader')
    <section id="mian-content">
        <div class="container">
            <div class="cb-grid">
                <div class="cb-block cb-box-100 search-result-title">
                    <div class="cb-content">
                        <div class="inner-block video-search">
                            <div class="heading-bg-search">
                                @if(count($search_video)>0)
                                    <h1>Search result for <span><?php echo '"'.$_GET['search_query'].'"';?></span></h1>
                                @else
                                    <h1>Search result  <span><?php echo '"'.$_GET['search_query'].'"';?> not found</span></h1>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
                <?php $s3 = \Illuminate\Support\Facades\Storage::disk('s3')?>
                <div class="cb-block cb-box-100 invalid-txt">
                    @if(count($search_video)>0 || count($random_video)>0)
                        <div class="cb-content video-wrap">
                            <div class="inner-block white-box-search">
                                @if(count($search_video)==1)
                                    <h2 class="heading search-heading">
                                        <span class="txt1">Video</span>
                                    </h2>
                                @else
                                    <h2 class="heading search-heading">
                                        <span class="txt1">Videos</span>
                                    </h2>
                                @endif
                                <div class="wi-100">
                                    @foreach ($search_video as $video)
                                        <div class="wi-25">
                                            <div class="video">
                                                <?php $img=$video->VideoThumbnail;
                                                $path=substr($img,22);?>
                                                <img class="responsive-img" src="{{ $s3->url('images/thumbnails/'.$img) }}" alt="video">
                                                <div class="video-detail">
                                                    <h3>{{$video->Title}}</h3>
                                                    <a href="/video_detail/{{ $video->VideoId }}/?type=sample">
                                                        <img src="/images/play-icon.png" alt="img">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @foreach ($random_video as $rand_video)
                                        <div class="wi-25">
                                            <div class="video">
                                                <?php $img=$rand_video->thumbnail;
                                                $path=substr($img,22);?>
                                                <img class="responsive-img" src="{{ $s3->url('images/thumbnails/'.$img) }}" alt="video">
                                                <div class="video-detail">
                                                    <h3>{{$rand_video->title}}</h3>
                                                    <a href="/video_detail/{{$rand_video->id }}?type=requested">
                                                        <img src="/images/play-icon.png" alt="img">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                   {{-- <div class="wi-20 last">
                                        <div class="video">
                                            <a href="{{URL('view-all-video')}}" class="view-more responsive-img"><span>View more <i class="fa fa-forward"></i></span></a>
                                        </div>
                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @include('frontend.common.footer')
	
	
</div>
</body>
</html>