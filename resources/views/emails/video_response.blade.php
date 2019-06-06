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
                                            <a href="#" target="_blank"> <img src="{{URL::to('/') }}/images/vrl_logo_nav.png" style="border-top:0;border-right:0;border-bottom:0;border-left:0;display:inline-block;min-height:auto;outline:none;" height="60" border="0"> </a>
                                        </td>

                                    </tr>

                                    </tbody>

                                </table>
                            </td>

                        </tr>

                        <tr>

                            <td align="center" valign="top" style="font-family:Helvetica;height:100%;margin:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%" height="100%" width="100%">
                                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin:0px;max-width:600px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;width:100%;table-layout:auto" width="100%">

                                    <tbody>

                                    <tr>

                                        <td colspan="3" style="padding:25px 20px 15px 20px;">

                                            <h4 style="line-height:1.3">Thank you for requesting a personalized video from {{$artist}}</h4>

                                            <p style="line-height:1.3">
                                                Your video request has been Approved and will be delivered on {{$complitionDate}} to {{$user}} at {{$recipient_email}}.
                                            </p>

                                            <p>Your VRL order number is ({{$artist_id}}-{{$video_request_id}})</p>

                                    <tr>
                                        <td style="padding:5px 20px;"><strong>Recipient Name</strong></td>
                                        <td width="11%"><strong>:</strong></td>
                                        <td width="42%" style="padding:5px 10px;">{{$user}}</td>
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
                                        <td width="42%" style="padding:5px 10px;">{{ $complitionDate }}</td>
                                    </tr>

                                    <tr>
                                        <td colspan="3">
                                            <p style="padding:5px 20px 25px 20px; width:100%;">
                                                You will be notified when the video has been uploaded and delivered.
                                            </p>

                                            <p style="padding:5px 20px 25px 20px; width: 100%">Thank you for using Video Request Line. <a href="{{ URL::to('/') }}">{{ URL::to('/') }}</a>
                                            </p>
                                        </td>
                                    </tr>

                                    </td>

                                    </tr>

                                    <tr>

                                        <td colspan="3" style="padding:5px 20px 25px 20px;">
                                            <p>This is an automated email generated from our server. For customer support, please email</p>
                                            support@videorequestline.com.</td>

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

                                        <td align="center" valign="top" style="margin:0;max-width:600px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%; font-size:12px;" height="100%" width="100%">
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