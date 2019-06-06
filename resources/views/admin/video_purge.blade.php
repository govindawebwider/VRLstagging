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

             <h1 class="heading">Video Purge Details
               @if(Session::has('success'))
               <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>
               @endif
               @if(Session::has('error'))
               <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>
               @endif

             </h1> 
             <p class="desc-title"></p>
             <div class="users">
              <div class="table-responsive dataTables_wrapper">
               <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>#Id</th>
                    <th>Title</th>
                    <th>description</th>
                    <th>Request Id</th>
                    <th>Remain storage duration</th>
                    <!-- <th>Action</th> -->

                  </tr>
                </thead>
                <!-- <tfoot>
                  <tr>
                    <th>#ID</th>
                    <th>Title</th>
                    <th>description</th>
                    <th>Request Id</th>
                    <th>Remain storage duration</th>
                   
                  </tr>
                </tfoot> -->
                <tbody>
                  @foreach ($videos as $video)
                  <tr>
                    <td>{{$video->id}}</td>
                    <td>{{$video->title}}</td>
                    <td>{{$video->description}}</td>
                    <td>{{$video->request_id}}</td>
                    <?php $date_expire = $video->purchase_date;    
                    $date = new DateTime($date_expire);
                    $now = new DateTime();
                    $diff=$date->diff($now)->format("%d days, %h hours and %i minuts");?>
                    @if($video->remain_storage_duration=='')
                    <td>0 Days</td>
                    @else
                    <td>
                      @if($video->remain_storage_duration-$diff <= 0)
                      Finished
                      @elseif((($video->remain_storage_duration)-($diff)) == 0)
                      Finished
                      @else  
                      {{$video->remain_storage_duration-$diff}} Days
                      @endif
                    </td>
                    @endif
                    

                    
                <!-- <td>{{$video->requestby}}</td>
                <td>{{$video->uploadedby}}</td> -->
              </tr>
              @endforeach

            </tbody>

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
<script>
  $(document).ready(function() {
    $('#example_paginate').show();
  });
</script>
</body>
</html>