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
                        <div id="breadcrumb"> <a class="tip-bottom" href="/admin_dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/artist_payments">Artist Payment</a> </div> 
                            @if(Session::has('success'))
                            <div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('success') }} </div>
                            @endif
                            @if(Session::has('error'))
                            <div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{Session::get('error') }} </div>
                            @endif
                            <h1 class="heading">Artist Payment
                                <span><a href="{{URL('/video_req_csv')}}">
                                        <input class="btn btn-primary" type="button" name="artist_csv" value="Export Video Request List" style="float:right;">
                                    </a> </span>
                            </h1> 
                            <p class="desc-title"></p>

                            <div class="users">
                                <div class="table-responsive dataTables_wrapper">
                                    <table id="table_slider" class="display table table-responsive table-striped" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Artist Id</th>
                                                <th>Artist Name</th>
                                                <th>Email Id</th>

                                                <th>System Share($)</th>
                                                <th>Artist Share($)</th>
                                                <!--  <th>Status Message</th> -->
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            //echo $tot_paid=admin_payment(664);
                                            //echo "<br>";
                                            //echo //$tot=(artist_payment(664)*85)/100;
                                            //echo "<br>";
                                            ?>

                                            <?php //dd($threshold->status); ?>
                                            @foreach ($artist_data as $artist)
                                                <td>{{$artist->ProfileId}}</td>
                                                <td>{{$artist->Name}}</td>
                                                <td>{{$artist->EmailId}}</td>
                                                @if(((artist_payment($artist->ProfileId)*$share->admin_share)/100)>1)
                                                <td>{{$price=(artist_payment($artist->ProfileId)*$share->admin_share)/100}}</td>
                                                @else
                                                <td>No Sharing Amt.</td>
                                                @endif
                                                <?php $tot_paid = admin_payment($artist->ProfileId);
                                                ?>
                                                <?php
                                                $artist_payed = artist_payment($artist->ProfileId);
                                                $due_pay = (($artist_payed*$share->artist_share)/100);
                                                $tot_due = $due_pay - $tot_paid;
                                                ?>
                                                @if((((artist_payment($artist->ProfileId)*$share->artist_share)/100)-$tot_paid) > $threshold->status)


                                                <td> 
                                                    <a href="{{URL('pay_to_artist/'.$artist->ProfileId.'/'.$tot_due)}}" class="btn btn-primary">Pay {{($price1=(artist_payment($artist->ProfileId)*$share->artist_share)/100 -$tot_paid )}}
                                                    </a>
                                                </td>
                                                @else
                                                <td>
                                                    Less Amount
                                                </td>
                                                @endif


<!-- <td>
  {{($price1=(artist_payment($artist->ProfileId)*$share->artist_share)/100 -$tot_paid )}}
  rs
  {{$threshold->status}}
</td>  -->
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
        $(document).ready(function () {
            $("#example_length").show();
            $("#example_paginate").show();
        });
    </script>
</body>
</html>