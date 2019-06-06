@include('admin.common.header')
{!! Html::style('css/rating-review.css') !!}

<body class="admin testimonial_list">

  <section class="main-page-wrapper">

    <div class="main-content">

      <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>

      @include('admin.layouts.header')

      <div class="testimonial_list_wrap">

        <div  class="col-md-12 ">

          <div id="page-wrapper">

            <div class="graphs">
            <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/reviews">Reviews</a> </div>

              @if(Session::has('success'))

              <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

              @endif

              @if(Session::has('error'))

              <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

              @endif

              <h1 class="heading">Review List<span><a style="float:right" href="{{URL('/review_csv')}}" >

                <input class="btn btn-primary" type="button" name="artist_csv" value="Export Review List">

              </a> </span></h1>
              <p class="desc-title"></p>

              <div class="table-responsive dataTables_wrapper">

                <table id="table_review" class="display" cellspacing="0" width="100%">

                  <thead>

                    <tr>

                      <th>Id</th>

                      <th>Review By</th>

                      <th>Message</th>

                      <th>Status</th>
                      <th>Rating</th>
                      <th>Type</th>

                      <th>Action</th>


                    </tr>

                  </thead>

                  <tfoot>

                    <tr>

                      <th>Id</th>

                      <th>Review By</th>

                      <th>Message</th>

                      <th>Status</th>
                      <th>Rating</th>
                      <th>Type</th>

                      <th>Action</th>


                    </tr>

                  </tfoot>

                  <tbody>

                    <?php //dd($testimonials);?>

                    @foreach ($testimonials as $testimonial)

                    <tr>

                      <td>{{$testimonial->id}}</td>

                      <td>{{$testimonial->user_name }} </td>

                      <td>{{$testimonial->Message}}</td>

                      <td><?php if($testimonial->AdminApproval==1)echo "Approved";else echo "Not Approved"?></td>
                      <td><span class="rating-static rating-{{str_replace('.', '', $testimonial->rate)}}"></span></td>
                      <td>@if($testimonial->video_id == 0)
                        <span  style="cursor: default;">Comment to artist</span>
                        @else
                        <span style="cursor: default;">Comment to Video</span>
                        @endif</td>

                        <td> 
                          @if($testimonial->AdminApproval==1) 
                          <a class="btn btn-info" href="{{URL('reject_review/'.$testimonial->id)}}"> Reject </a> 
                          @else 
                          <a class="btn btn-info" href="{{URL('approve_review/'.$testimonial->id)}}"> Accept </a> 
                          @endif 
                          @if($testimonial->AdminApproval==1)
                          @if($testimonial->video_id == 0)
                          <a class="btn btn-primary" href="{{URL('edit_admin_review/'.$testimonial->id)}}">Edit</a>
                          @endif
                          @else
                          <span class="btn btn-primary" style="cursor: default;">Edit</span>
                          @endif
                          <a class="btn btn-primary" href="{{URL('del_admin_review/'.$testimonial->id)}}">Delete</a>
                          <!-- @if($testimonial->is_default == 0)
                          <a class="btn btn-primary" href="{{URL('mark_default/'.$testimonial->id)}}">Mark as Default</a>
                          @else
                          <a class="btn btn-danger" href="{{URL('remove_default/'.$testimonial->id)}}">Remove From Default</a>
                          @endif -->
                          @if($testimonial->AdminApproval==1)

                          @if($testimonial->show_home == 0)
                          <a class="btn btn-primary" href="{{URL('set_home/'.$testimonial->id)}}">Display Only on Homepage</a>
                          @else
                          <a class="btn btn-danger" href="{{URL('hide_home/'.$testimonial->id)}}">Hide from Homepage</a>
                          @endif

                          @if($testimonial->show_artist == 0)
                          <a class="btn btn-primary" href="{{URL('set_artist/'.$testimonial->id)}}">Display Only on Artist Page</a>
                          @else
                          <a class="btn btn-danger" href="{{URL('hide_artist/'.$testimonial->id)}}">Hide from Artist Page</a>
                          @endif
                          @else
                          @if($testimonial->show_home == 0)
                          <span class="btn btn-primary" style="cursor: default;" href="{{URL('set_home/'.$testimonial->id)}}">Display Only on Homepage</span>
                          @else
                          <span class="btn btn-danger" style="cursor: default;" href="{{URL('hide_home/'.$testimonial->id)}}">Hide from Homepage</span>
                          @endif
                          @if($testimonial->show_artist == 0)
                          <span class="btn btn-primary" style="cursor: default;" href="{{URL('set_artist/'.$testimonial->id)}}">Display Only on Artist Page</span>
                          @else
                          <span class="btn btn-danger" style="cursor: default;" href="{{URL('hide_artist/'.$testimonial->id)}}">Hide from Artist Page</span>
                          @endif
                          @endif

                          


                        </td>

                      </tr>

                      @endforeach

                    </tbody>



                  </table>

                  {{--  <a href="{{URL('default_testimonial')}}" class="btn btn-primary">Add Default Review</a> --}} </div>

                </div>

              </div>

            </div>

          </div>

        </div>

      </section>

      @extends('admin.common.footer')

    </body>

    </html>
    