@section('content')@endsection<!DOCTYPE HTML><html><head>	
<title>Admin Panel</title>	
<meta name="viewport" content="width=device-width, initial-scale=1">	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
<meta name="keywords" content="Easy Admin Panel"  />
<meta name="csrf-token" content="{{ csrf_token() }}">	

<script type="application/x-javascript"> 
	addEventListener("load", function() { 
		setTimeout(hideURLbar, 0); }, false);
  function hideURLbar(){ window.scrollTo(0,1); 
  }
</script>
<!-- <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
  <script>tinymce.init({ selector:'textarea' });</script> -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css">

  <!-- Include Editor style. -->
  <link href="editor_css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
  <link href="editor_css/froala_style.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=c6rkrvlinmcwmkmpvcrgqj2mydo677eydmueqbbpy1219fhx"></script>
  <script>tinymce.init({
    selector:'#textarea',
              plugins: [
                "advlist autolink lists link  charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code",
                "insertdatetime  nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern"
               
            ],

            templates: [
    {title: 'Hello templates', description: 'Content', content: 'Hello'},
    {title: 'Need assistance templates', description: 'Content', content: 'Need assistance locating your order?'},
    
  ]

    

 
                    
  });</script>

  <!-- {!! Html::script('js/ckeditor.js') !!} -->
  <!-- {!! Html::script('js/sample.js') !!}	 -->
  {!! Html::style('css/bootstrap.min.css') !!}		
  {!! Html::style('css/admin/style.css') !!}    
  {!! Html::style('css/admin/newstyle.css') !!}    
  {!! Html::style('css/admin/liga.css') !!} 
  {!! Html::style('editor_css/froala_style.min.css') !!} 
  {!! Html::style('editor_css/froala_editor.pkgd.min.css') !!} 
  <!--  {!! Html::style('css/admin/liga.js.js') !!}	 -->
  {!! Html::style('css/font-awesome.min.css') !!}	
  {!! Html::style('css/icon-font.min.css') !!}  
  {!! Html::style('css/jquery.dataTables.css') !!}
  {{ Html::favicon( 'images/favicon.ico' ) }}
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.css">
  
  <!-- first web camera -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <link rel="author" type="text/html" href="https://plus.google.com/+MuazKhan">
  <!-- <link rel="stylesheet" href="https://cdn.webrtc-experiment.com/style.css"> -->
    <!-- <style>
        audio {
            vertical-align: bottom;
            width: 10em;
        }
        video {
            max-width: 100%;
            vertical-align: top;
        }
        input {
            border: 1px solid #d9d9d9;
            border-radius: 1px;
            font-size: 2em;
            margin: .2em;
            width: 30%;
        }
        p,
        .inner {
            padding: 1em;
        }
        li {
            border-bottom: 1px solid rgb(189, 189, 189);
            border-left: 1px solid rgb(189, 189, 189);
            padding: .5em;
        }
        label {
            display: inline-block;
            width: 8em;
        }
      </style> -->

        <!-- <style>
            .recordrtc button {
                font-size: inherit;
            }

            .recordrtc button, .recordrtc select {
                vertical-align: middle;
                line-height: 1;
                padding: 2px 5px;
                height: auto;
                font-size: inherit;
                margin: 0;
            }

            .recordrtc, .recordrtc .header {
                display: block;
                text-align: center;
                padding-top: 0;
            }

            .recordrtc video {
                width: 70%;
            }

            .recordrtc option[disabled] {
                display: none;
            }
          </style> -->
  
  <style>
      .mce-notification{
          display: none !important;
      }
  </style>
    <!-- Web Camera Page Styles -->
    <style>
        .customVideoHide{
            display: none;
        }
        .customALertInfo{
                padding: 15px;
                margin-bottom: 20px;
                border: 1px solid transparent;
                border-radius: 4px;
                color: #8a6d3b;
                background-color: #fcf8e3;
                border-color: #faebcc;
        }
        .customALertSuccess{
                padding: 15px;
                margin-bottom: 20px;
                border: 1px solid transparent;
                border-radius: 4px;
                color: #3c763d;
                background-color: #dff0d8;
                border-color: #d6e9c6;
        }
        
         .customALertDanger{
                padding: 15px;
                margin-bottom: 20px;
                border: 1px solid transparent;
                border-radius: 4px;
                color: #a94442;
                background-color: #f2dede;
                border-color: #ebccd1;
        }
        
        
    </style>
    
  
          <!-- first End web camera -->

          <!-- second web camera -->
          <!-- <link rel="icon" sizes="192x192" href="../../../images/webrtc-icon-192x192.png"> -->
          <!-- <link href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet" type="text/css"> -->
    <!-- <link rel="stylesheet" href="../../../css/main.css">
    <link rel="stylesheet" href="camera_css/main.css"> -->
    <!-- End second web camera -->

  </head> 