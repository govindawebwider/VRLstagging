@include('admin.common.header')
<body class="admin artist_list">
  <section class="main-page-wrapper">
    <div class="main-content">
      <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>
      @include('admin.layouts.header')
      <div class="artist_list_wrap">
        <div  class="col-md-12 ">


         <div id="page-wrapper">

          <div class="graphs">
          <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="#">Join Request</a> </div>
            <h1 class="heading">Join Request</h1>
            <p class="desc-title"></p>
            @if(Session::has('success'))
            <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} 
            </div>
            @endif
            @if(Session::has('error'))
            <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} 
            </div>
            @endif
            <?php //dd($artists);?>
            <div class="artists">
              <div class="table-responsive dataTables_wrapper">
                <table class="table" id="example">
                  <thead>
                    <tr>
                      <th>Artist Id</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Mobile</th>
                      <th>handle</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach ($artists as $artist)
                    <tr>
                      <td>
                        <a class="" href="{{URL('edit_artist/'.$artist->ProfileId)}}">{{$artist->ProfileId}}</a></td>
                        <td><a class="" href="{{URL('edit_artist/'.$artist->ProfileId)}}">{{$artist->Name}}</a></td>
                        <td><a class="" href="{{URL('edit_artist/'.$artist->ProfileId)}}">{{$artist->EmailId}}</a></td>
                        <td><a class="" href="{{URL('edit_artist/'.$artist->ProfileId)}}">{{ $artist->phone_no }}</a></td>
                        <td> {{ $artist->twitter_link }} {{ $artist->facebook_link }} {{ $artist->instagram_link}} </td>
                        <td><a class="btn btn-success" href="{{URL('confirm-joinus-artist/'.$artist->ProfileId)}}">Confirm</a>
                        <a class="btn btn-danger" href="{{URL('deny-joinus-artist/'.$artist->ProfileId)}}">Delete Request</a>
                        <td>
                        
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

<div class="out_mess_del_video">
  <div class="inner_message">
    <div class="head_wrap">Delete Video</div>
    <span class="ms_close"><i></i></span>
    <div>
      <div class="main_body message">Are you sure, you want to remove this Artist.</div>
      <div class="footer_wrap"><a href="javascript:void(0)" class="btn_no ms_close">No</a>
        <a href="" class="btn_yes delete_url">Yes</a></div>
      </div>
    </div>
  </div>
  @include('admin.common.footer')
  <script>
    function delete_req(user_id){
    //alert(user_id);
    var baseurl = "{{URL('/')}}";
    var url = baseurl+'/delete_artist/'+user_id;
    $('.delete_url').attr('href',url);

  }

  $('#example').DataTable( {

    "columnDefs": [
        { "targets": [0,5], "searchable": false }
    ],

    "lengthMenu": [[10,20,30,40,50,-1], ['10 Rows','20 Rows','30 Rows','40 Rows','50 Rows', 'All Rows']],

    "order": [[ 0, "desc" ]],
     "dom": '<f<t>lp>',
       "language": {
           "search": "Search:",
           "searchPlaceholder": ""
       }




  } );
</script>
</body>
</html>