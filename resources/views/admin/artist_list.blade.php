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
          <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/artists">Artist List</a> </div> 
            <h1 class="heading">Artist List <span><a style="richness:;" href="{{URL('/artist_csv')}}" ><input class="btn btn-primary" type="button" name="artist_csv" value="Export Artist List" style="float:right;"></a> </span></h1>
            <p class="desc-title"></p>
            @if(Session::has('success'))
            <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} 
            </div>
            @endif
            @if(Session::has('error'))
            <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} 
            </div>
            @endif
            <?php //dd($chart_topper_count);?>
            <div class="artists">
              <div class="table-responsive dataTables_wrapper">
                <table class="table" id="example">
                  <thead>
                    <tr>
                      <th>Artist Id</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Account Status</th>
                      <th>Chart Topper</th>
                      <th>Action</th>
                      <th>Profile Edit</th>
                      <th>Remove</th>
                      <th>Bank A/c Status</th>
                      <th>Action</th>
                      <th>Category</th>
                      <th>Main Category</th>
                    </tr>
                  </thead>
                  <tbody>

                    @foreach ($artists as $artist)
                    <tr>
                      <td>
                        <a class="" href="{{URL('edit_artist/'.$artist->ProfileId)}}">{{$artist->ProfileId}}</a></td>
                        <td><a class="" href="{{URL('edit_artist/'.$artist->ProfileId)}}">{{$artist->Name}}</a></td>
                        <td><a class="" href="{{URL('edit_artist/'.$artist->ProfileId)}}">{{$artist->EmailId}}</a></td>

                        <td>{{$artist->is_account_active == 0 ? "Disabled":"Activated"}}</td>
                        

                        <td>
                          @if ($artist->is_chart_topper == 0)
                           
                              <a class="btn btn-success" href="{{URL('enable_chart_topper/'.$artist->ProfileId)}}">Activate</a>
                           
                          @else
                            <a class="btn btn-warning" href="{{URL('disable_chart_topper/'.$artist->ProfileId)}}">Deactivate</a>
                          @endif
                        </td>
                        

                        @if ($artist->is_account_active == 0)
                        <td><a class="btn btn-success" href="{{URL('enable_artist/'.$artist->ProfileId)}}">Activate</a><a class="btn btn-success" href="{{URL('resend_varifi_code/'.$artist->ProfileId)}}">Resend verification code</a></td>
                        @endif



                        @if ($artist->is_account_active == 1)
                        <td><a class="btn btn-warning" href="{{URL('disable_artist/'.$artist->ProfileId)}}">Deactivate</a></td>
                        @endif
                        <td>
                        <a class="btn btn-primary" href="{{URL('edit_artist/'.$artist->ProfileId)}}">Edit</a> <!-- <a class="btn btn-primary" href="{{URL('view_artist/'.$artist->ProfileId)}}">View</a> -->
                        </td>
                        <td><a onClick="delete_req({{$artist->ProfileId}})" class="btn btn-danger req_video_del" href="javascript:void(0)">Remove</a></td>
                        
                        <td>
                          @if ($artist->is_bank_updated == 0)
                          <a href="{{URL('update_artist_dtl/'.$artist->ProfileId)}}">
                           <span class="btn btn-warning">Details Not Updated</span>
                         </a>
                          @endif
                       
                       @if ($artist->is_bank_updated == 1)
                       <a class="btn btn-danger" href="{{URL('create_connected_account/'.$artist->ProfileId)}}">Create</a>
                       @endif

                       @if ($artist->stripe_account_id != "")
                       <span class="btn btn-primary">Account Created</span>
                       @endif 

                     </td>

                    <td><a class="btn btn-success" href="{{URL('/switch_as_artist/'.$artist->ProfileId)}}">Switch as {{$artist->Name}}</a></td>

                    <?php
                      $artistCategory = \App\ArtistCategory::whereProfileId($artist->ProfileId)->where('main_category' , '!=' , 1)->pluck('category_id');
                    ?>
                    <td>
                      @foreach($artistCategory as $category)
                        <?php
                            $categoryName =\App\Category::whereId($category)->first();
                        ?>
                        @if(!is_null($categoryName))
                            <div class="badge"> {{ $categoryName->title }} </div>
                        @endif
                      @endforeach
                    </td>
                    <?php
                      $artistCategory = \App\ArtistCategory::whereProfileId($artist->ProfileId)->whereMainCategory(1)->pluck('category_id');
                    ?>
                    <td>
                      @foreach($artistCategory as $category)
                        <?php
                           $categoryName = \App\Category::whereId($category)->first();
                        ?>
                        <div class="badge"> {{ !is_null($categoryName) ? $categoryName->title : null}} </div>
                      @endforeach
                    </td>

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
        { "targets": [3,4,5,6,7,8,9], "searchable": false }
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