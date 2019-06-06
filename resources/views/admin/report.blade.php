@include('admin.common.header')
<body class="admin video_request">

    <section class="main-page-wrapper">

        <div class="main-content">

            <div id="left-side-wrap"> 

                @include('admin.layouts.lsidebar') </div>

            @include('admin.layouts.header')
            <div class="video_request_wrap">

                <div  class="col-md-12 ">

                    <div id="page-wrapper">

                        <div class="graphs">
                        <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/report">Report</a> </div> 
                              <h1 class="heading">Report 
                                <span>
                                    <a href="{{URL('/video_purge')}}"><input class="btn btn-primary" type="button" name="artist_csv" value="Video Purge" style="float:right;"></a>
                                    <a href="{{URL('/video_req_csv')}}">

                                        <input class="btn btn-primary" type="button" name="artist_csv" value="Export Video Request List" style="float:right;">

                                    </a> 
                                </span>

                            </h1> 
                            <p class="desc-title"></p>

                            <div class="users">
                                <div class="table-responsive dataTables_wrapper">
                                    <table id="table_report" class="table display dataTable no-footer">
                                        <div class="table-responsive dataTables_wrapper">
                                            <thead>
                                                <tr>
                                                    <th>Report ID</th>
                                                    <th>By</th>
                                                    <th>For</th>
                                                    <th>Reported on</th>
                                                    <th>Report Type</th>
                                                    <th>Comments / Reason</th>
                                                </tr>
                                            </thead>
                                            @foreach ($report as $report_detail)
                                           
                                            <tr>
                                                <td>{{$report_detail->report_id}}</td>
                                                <td>{{$report_detail->userName}}</td>
                                                <td>{{$report_detail->artistName}}</td>
                                                <?php
                                                $dateTime = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $report_detail->created_at, 'UTC')
                                                        ->setTimezone(session('timezone'))
                                                        ->format('Y-m-d H:i:s')
                                                ?>
                                                <td>{{$dateTime}}</td>
                                                <td>{{ Config::get('constants.REPORT_LIST.'.$report_detail->report_type) }}</td>
                                                <td>{{$report_detail->comment}}</td>
                                            </tr>
                                            @endforeach
                                        </div>
                                    </table>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
   
        </div>
    </section>
    @include('admin.common.footer')
</body>
<script>
    var baseurl = "{{URL('/')}}";
    $(document).ready(function() {
    $('#example_paginate').show();
    });
    function reactionVideo(id, status){
    var tok = '<?php echo csrf_token() ?>';
    $.post(baseurl+'/updateReactionStatus/', {'_token':tok, 'video_id':id, 'status':status}, function(data){
    location.reload();
    });
    }

     $('#table_report').DataTable( {

    

    "lengthMenu": [[10,20,30,40,50,-1], ['10 Rows','20 Rows','30 Rows','40 Rows','50 Rows', 'All Rows']],

    "order": [[ 0, "desc" ]],
     "dom": '<f<t>lp>',
       "language": {
           "search": "Search:",
           "searchPlaceholder": ""
       }




  } );
</script>
</html>