@include('admin.common.header')

<body class="admin payment">

  <section class="main-page-wrapper">

    <div class="main-content">

      <div id="left-side-wrap"> @include('admin.layouts.lsidebar') </div>

      @include('admin.layouts.header')

      <div class="payment_wrap">

        <div  class="col-md-12 ">

          <div id="page-wrapper">

            <div class="graphs">
            <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/get_payments">Payment</a> </div> 
                @if(Session::has('success'))

                <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>

                @endif   

                @if(Session::has('error'))

                <div class="alert alert-danger"> 

                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>

                  @endif

                  <h1 class="heading">Payment



                   <span><a style="float:right" href="{{URL('/payment_csv')}}">

                    <input class="btn btn-primary" type="button" name="artist_csv" value="Export Payment List">

                  </a></span><h1>
                    <p class="desc-title"></p>
                  

                  

                  

                  <div class="get-paymet-details">

                    <table id="table_payment" class="display" cellspacing="0" width="100%">

                      <thead>

                        <tr>

                          <th>Video Request ID</th>

                          <th>Video Request Date</th>

                          <th>completion Date</th>

                          <th>Video Title</th>

                          <th>Request Status</th>

                          <th>Payment Date</th>

                          <th>Payment Status</th>

                          <th>Video Price</th>

                          <th>Artist Share</th>

                          <th>System Share</th>

                          <th>Video Status</th>

                          <th>Status</th>

                        </tr>

                      </thead>

                      <tfoot>

                        <tr>

                          <th>Video Request ID</th>

                          <th>Video Request Date</th>

                          <th>completion Date</th>

                          <th>Video Title</th>

                          <th>Request Status</th>

                          <th>Payment Date</th>

                          <th>Payment Status</th>

                          <th>Video Price</th>

                          <th>Artist Share</th>

                          <th>System Share</th>

                          <th>Video Status</th>

                          <th>Action</th>

                        </tr>

                      </tfoot> 

                      <tbody>
                        @foreach ($payment as $payment)

                        <tr>

                          <td>{{$payment->video_request_id}}</td>

                          <?php
                          $requestDate = Carbon\Carbon::createFromFormat('m/d/Y', $payment->request_date, 'UTC')
                                  ->setTimezone(session('timezone'))
                                  ->format('m/d/Y');
                          ?>
                          <td>{{$requestDate}}</td>

                          <?php
                          $completionDate = date('m/d/Y', strtotime($payment->complitionDate));
                          ?>
                          <td>{{$completionDate}}</td>

                          <td>{{$payment->Title}}</td>

                          <td>{{$payment->RequestStatus}}</td>

                          <?php
                          $paymentDate = Carbon\Carbon::createFromFormat('m/d/Y', date('m/d/Y', strtotime($payment->payment_date)), 'UTC')
                                  ->setTimezone(session('timezone'))
                                  ->format('m/d/Y');
                          ?>
                          <td>{{$paymentDate}}</td>

                          <td>{{$payment->status}}</td>
                          
                          <td>

                            ${{$payment->ReqVideoPrice}}
                            
                          </td>

                          <td>
                          ${{$payment->artist_share}}  <!-- ${{$pay_price = ($payment->ReqVideoPrice*$share->artist_share)/100}} -->
                          </td>

                          <td>
                          ${{$payment->system_share}}  <!-- ${{$pay_price = ($payment->ReqVideoPrice*$share->admin_share)/100}} -->
                          </td>

                          <td>

                            @if($payment->RequestStatus == "Reject" AND $payment->is_refunded == 1) 
                            <p class="text-warning" >Refunded</p>

                            @elseif($payment->RequestStatus == "Approved" AND $payment->is_refunded == 0 AND $payment->paid == 'Paid') 
                            <p class="text-danger" >Approved But Video is not uploaded</p>


                            @elseif($payment->RequestStatus == "Pending" AND $payment->is_refunded == 0 ) 
                            <p class="text-danger" >Not Approved by Artist</p>


                            @elseif($payment->RequestStatus == "Completed" AND $payment->is_refunded == 0) 
                            <p class="text-primary" >Video Delivered</p>

                            @endif


                          </td>



                          <td>

                            @if($payment->RequestStatus == 'Approved' AND $payment->status == 'succeeded' ) 

                            <a href="{{URL('/send_reminder/'.$payment->ProfileId.'/'.$payment->VideoReqId)}}">

                              <span class="btn btn-primary">Send reminder to artist</span> 

                            </a>
                            @elseif($payment->RequestStatus == 'Completed' AND $payment->status == 'succeeded')
                            <span style="cursor:default;" class="btn btn-primary">Request Completed</span> 
                            @elseif($payment->RequestStatus == 'Reject' AND $payment->is_refunded == 1)
                            <span class="btn btn-primary">Request Rejected</span> 
                            
                            @elseif($payment->RequestStatus == 'Pending' AND $payment->is_refunded == 0)
                            <span style="cursor:default;" class="btn btn-primary">Request Pending from Artist</span> 
                            @endif 

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

      </section>

      @include('admin.common.footer')

    </body>

    </html>