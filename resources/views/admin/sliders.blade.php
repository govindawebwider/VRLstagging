@include('admin.common.header')

<body class="admin slider">

  <section class="main-page-wrapper">

    <div class="main-content">

      <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>

      @include('admin.layouts.header')

      <div class="slider_wrap">

        <div  class="col-md-12 ">

          <div id="page-wrapper">

            <div class="graphs">
            <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/sliders">Slider</a> </div> 
              <h1 class="heading">Slider</h1>
              <p class="desc-title"></p>

              <div class="artists">

                <div class="table-responsive dataTables_wrapper">

                  <table class="table" id="table_slider">

                    <thead>

                      <tr>

                        <th>Slider Id</th>

                        <th>Title</th>

                        <th>Artist</th>

                        <th>Slider Visibility</th>

                        <th>Action</th>

                      </tr>

                    </thead>

                    <tbody>



                      @foreach ($sliders as $slider)

                      <tr>

                        <td>{{$slider->id}}</td>

                        <td>{{$slider->slider_title}}</td>

                        <td>
                          <?php $artist = \App\Profile::find($slider->artist_id); ?>
                          @if(!is_null($artist))
                          {{$artist->Name}}
                          @else
                          Not Available
                          @endif</td>

                          <td>{{$slider->slider_visibility == 0 ? "Off":"On"}}</td>

                          @if ($slider->slider_visibility == 0)

                          <td>

                            <a class="btn btn-primary" href="{{URL('enable_slider/'.$slider->id)}}">Show</a>

                            <a class="btn btn-primary" href="{{URL('view_slider/'.$slider->id)}}">Edit</a>
                            <a class="btn btn-primary" href="{{URL('del_slider/'.$slider->id)}}">Delete</a></td>
                          </td>

                          @endif


                          @if ($slider->slider_visibility == 1)

                          <td><a class="btn btn-danger" href="{{URL('disable_slider/'.$slider->id)}}">Hide</a>

                            <a class="btn btn-primary" href="{{URL('view_slider/'.$slider->id)}}">Edit</a>
                            <a class="btn btn-primary" href="{{URL('del_slider/'.$slider->id)}}">Delete</a></td>

                            @endif </tr>

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

      </body>

      </html>