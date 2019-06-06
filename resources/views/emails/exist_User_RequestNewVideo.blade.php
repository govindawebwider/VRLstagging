<!doctype html>

<html>

<head>

    <meta charset="utf-8">

    <title>Untitled Document</title>

</head>

<body>

<div style="font-family:Helvetica;color:#131921;min-height:100%;line-height:100%;font-size:15px;margin:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0; width:100%;">

    <center>

        <table cellpadding="0" cellspacing="0" border="0" align="center" style="border-collapse:collapse; table-layout:auto;">

            <tbody>

            <tr>

                <td>
                    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" style="border-collapse:collapse;font-family:Helvetica;height:100%;margin:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;width:600px;background-color:rgb(255,255,255);table-layout:auto;border: solid 2px #b135cd;" bgcolor="#ffffff">

                        <tbody>

                        <tr>

                            <td align="center" valign="top" style="font-family:Helvetica;height:100%;margin:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%; " height="100%" width="100%">
                                <table border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="border-collapse:collapse;margin:0px;max-width:600px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;width:100%;table-layout:auto" width="100%">

                                    <tbody>

                                    <tr>

                                        <td align="center" valign="top" style="margin:0;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;width:100%" height="100%" width="100%">
                                            <a href="#" target="_blank"> <img src="{{URL::to('/')}}/images/vrl_logo_nav.png" style="border-top:0;border-right:0;border-bottom:0;border-left:0;display:inline-block;min-height:auto;outline:none;" height="60" border="0"> </a>
                                        </td>

                                    </tr>

                                    </tbody>

                                </table>
                            </td>

                        </tr>

                        <tr>

                            <td align="center" valign="top" style="font-family:Helvetica;height:100%;margin:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%" height="100%" width="100%">
                                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin:0px;max-width:600px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;width:100%;table-layout:auto;margin-bottom:20px;" width="100%">

                                    <tbody>

                                    <tr>

                                        <td colspan="3" style="padding:25px 20px 15px 20px;">

                                            <h2 style="margin:10px 0 15px;font-weight:600">Thank you for your Request!</h2>

                                            <h5 style="margin:10px 0 15px;font-weight:600">Here are the details of your video request from {{$artist_name}}.</h5>

                                            <!-- <h4 style="padding-bottom:0;margin:0;font-weight:600"> Please Login to Check Status, Login credential are </h4> -->

                                            <h4>Thank You {{ $username }} for requesting a personalized Video from {{ $artist_name }}</h4>
                                            <h4>Your VRL order number is ({{$artist_id}}-{{$video_request_id}})</h4>

                                        </td>

                                    </tr>

                                    <tr>
                                        <td style="padding:5px 20px;"><strong>Recipient Name</strong></td>
                                        <td width="11%"><strong>:</strong></td>
                                        <td width="42%" style="padding:5px 10px;">{{$recipient_name}}</td>
                                    </tr>

                                    <tr>
                                        <td style="padding:5px 20px;"><strong>Recipient Email</strong></td>
                                        <td width="11%"><strong>:</strong></td>
                                        <td width="42%" style="padding:5px 10px;">{{$recipient_email}} </td>
                                    </tr>

                                    @if($songName != null)
                                        <tr>
                                            <td style="padding:5px 20px;"><strong>Desired Song Name</strong></td>
                                            <td width="11%"><strong>:</strong></td>
                                            <td width="42%" style="padding:5px 10px;">{{ $songName }}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td style="padding:5px 20px;"><strong>Occasion</strong></td>
                                        <td width="11%"><strong>:</strong></td>
                                        <td width="42%" style="padding:5px 10px;">{{ $video_title }}</td>
                                    </tr>

                                    <tr>
                                        <td style="padding:5px 20px;"><strong>Personlized Message</strong></td>
                                        <td width="11%"><strong>:</strong></td>
                                        <td width="42%" style="padding:5px 10px;">{{ $video_description }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:5px 20px;"><strong>Status</strong></td>
                                        <td width="11%"><strong>:</strong></td>
                                        <td width="42%" style="padding:5px 10px;">{{ $current_status }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:5px 20px;"><strong>Delivery Date</strong></td>
                                        <td width="11%"><strong>:</strong></td>
                                        <td width="42%" style="padding:5px 10px;">{{ $delivary_date }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:20px 20px 5px 20px;">
                                            <p>Click Here to <a href="{{ URL::to('https://www.videorequestline.com/login') }}" target="_blank">https://www.videorequestline.com/login</a> Login.</p>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" style="padding:25px 20px 5px 20px;">
                                            <p>You may login to your account and view the status and fulfilled video requests at
                                                <a href="{{ URL::to('/') }}">videorequestline.com</a> </p>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" style="padding:25px 20px 5px 20px;">
                                            <p>This is an automated email generated from our server. For customer support, please email</p>
                                            <a href="mailto:support@videorequestline.com"> support@videorequestline.com</a>
                                        </td>
                                    </tr>

                                    </tbody>

                                </table>
                            </td>

                        </tr>

                        <tr>

                            <td align="center" valign="top" style="font-family:Helvetica;height:100%;margin:0;;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%" height="100%" width="100%">
                                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin:0px;margin-bottom:0px;max-width:600px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;width:100%;table-layout:auto; background:#b135cd; color:#fff;" bgcolor="#b135cd" width="100%">
                                    <tbody>
                                    <tr>
                                        <td align="center" valign="top" style="margin:0;max-width:600px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%; font-size:12px;" height="100%" width="100%" style="padding:10px;">
                                            <p>©
                                                <?php echo date("Y"); ?> Video Request Line</p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </center>
</div>
</body>

</html>