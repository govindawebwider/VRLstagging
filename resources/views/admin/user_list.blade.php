@include('admin.common.header')
<body class="admin usert_list">
  <section class="main-page-wrapper">
    <div class="main-content">
      <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>
      @include('admin.layouts.header')
      <div class="usert_list_wrap">
        <div  class="col-md-12 ">
          <div id="page-wrapper">
            <div class="graphs">
            <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="#">User List</a> </div>
              <h1 class="heading">User List<span><a style="float:right" href="{{URL('/user_csv')}}" >  <input class="btn btn-primary" type="button" name="artist_csv" value="Export User List"></a> </span></h1>
              <p class="desc-title"></p>
              @if(Session::has('success'))
              <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} 
              </div>
              @endif
              @if(Session::has('error'))
              <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} 
              </div>
              @endif
              
              <div class="users">
                <div class="table-responsive  dataTables_wrapper">
                  <table class="table" id="table_id">
                    <thead>
                      <tr>
                        <th>User Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Account Status</th>
                        <th>Action</th>
                        <th>Remove</th>
                        <th>Admin as User</th>
                      </tr>
                    </thead>
                    <tbody>

                      @foreach ($users as $user)
                      <tr>
                        <td>{{$user->ProfileId}}</td>
                        <td>{{$user->Name}}</td>
                        <td>{{$user->EmailId}}</td>
                        <td>{{$user->is_account_active == 0 ? "Disabled":"Activated"}}</td>
                        @if ($user->is_account_active == 0)
                        <td><a class="btn btn-success" href="{{URL('enable_user/'.$user->ProfileId)}}">Activate</a> <a class="btn btn-success" href="{{URL('view_user/'.$user->ProfileId)}}">View</a></td>
                        @endif
                        
                        @if ($user->is_account_active == 1)
                        <td><a class="btn btn-warning" href="{{URL('disable_user/'.$user->ProfileId)}}">Deactivate</a> <a class="btn btn-success" href="{{URL('view_user/'.$user->ProfileId)}}">View</a>
                          <a class="btn btn-success" href="{{URL('edit_user/'.$user->ProfileId)}}">Edit</a></td>
                          @endif
                          <td><a onClick="delete_req({{$user->ProfileId}})" class="btn btn-danger req_video_del" href="javascript:void(0)">Remove</a></td>
                          <td><a class="btn btn-success" href="{{URL('/switch_as_user/'.$user->ProfileId)}}">Switch as {{$user->Name}}</a></td>
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
    @extends('admin.common.footer')
    <div class="out_mess_del_video">
      <div class="inner_message">
        <div class="head_wrap">Delete Video</div>
        <span class="ms_close"><i></i></span>
        <div>
          <div class="main_body message">Are you sure, you want to remove this User.</div>
          <div class="footer_wrap"><a href="javascript:void(0)" class="btn_no ms_close">No</a>
            <a href="" class="btn_yes delete_url">Yes</a></div>
          </div>
        </div>
      </div>
      <script>
        function delete_req(user_id){
    //alert(user_id);
    var baseurl = "{{URL('/')}}";
    var url = baseurl+'/delete_user/'+user_id;
    $('.delete_url').attr('href',url);
  };



</script>
</body>
</html>