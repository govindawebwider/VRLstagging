<!-- category_list view created by sandeep -->
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
            
             <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/categories">Category List</a> </div> 
            <h1 class="heading">Category List</h1>

            <p class="desc-title"></p>
            @if(Session::has('success'))
            <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} 
            </div>
            @endif
            @if(Session::has('error'))
            <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} 
            </div>
            @endif
            <?php //dd($categories);?>
            <div class="artists">
              <div class="table-responsive dataTables_wrapper">
                <table class="table" id="example">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Title</th>
                      <!-- <th>Status</th> -->
                      <th>Action</th>
                      <th>Edit</th>
                      <th>Remove</th>
                    </tr>
                  </thead>
                  <tbody>

                    @foreach ($categories as $category)
                    <tr>
                        <td>
                          {{$category->id}}
                        
                         <td>
                          {{$category->title}}
                        </td>
                        <!-- <td>{{$category->status == 0 ? "Disabled":"Activated"}}</td> -->
                         @if ($category->status == 0)
                        <td><a class="btn btn-success" href="{{URL('enable_category/'.$category->id)}}">Activate</a></td>
                        @endif
                        @if ($category->status == 1)
                        <td><a class="btn btn-warning" href="{{URL('disable_category/'.$category->id)}}">Deactivate</a></td>
                        @endif
                        <td><a class="btn btn-primary" href="{{URL('edit_category/'.$category->id)}}">Edit</a> </td>
                        <td><a onClick="delete_req({{$category->id}})" class="btn btn-danger req_video_del" href="javascript:void(0)">Remove</a></td>

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
      <div class="main_body message">Are you sure, you want to remove this Category.</div>
      <div class="footer_wrap"><a href="javascript:void(0)" class="btn_no ms_close">No</a>
        <a href="" class="btn_yes delete_url">Yes</a></div>
      </div>
    </div>
  </div>
  @include('admin.common.footer')
  <script>
    function delete_req(id){
   // alert(id);
    var baseurl = "{{URL('/')}}";
    var url = baseurl+'/delete_category/'+id;
    $('.delete_url').attr('href',url);

  }

  $('#example').DataTable( {

    "columnDefs": [
        { "targets": [2,3,4], "searchable": false }
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