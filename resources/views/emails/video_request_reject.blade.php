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
                                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin:0px;max-width:600px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;width:100%;table-layout:auto" width="100%">

                                    <tbody>

                                    <tr>

                                        <td colspan="3  " style="padding:25px 20px 15px 20px;">
                                            <p>Hi {{ $sender_name }}</p>
                                            <p>We regret to inform you that {{ $artist }} is unable to fulfill your request. you will receive a full refund.</p>
                                            <p>In the meanwhile, please go to the VRL and select another celebrity.
                                                <a href="{{ URL::to('/') }}" target="_blank">{{ URL::to('/') }}</a>
                                            </p>
                                            <p>Reason : {{ $rejected_reason }} </p>
                                            <p>Your VRL order number is {{ $identifier }}</p>

                                            <h3 style="padding-bottom:0;margin:0;font-weight:600; font-style:italic;">Request Details</h3>

                                        </td>

                                    </tr>

                                    <tr>
                                        <td width="43%" style="padding:5px 20px;"><strong>Recipient Name</strong></td>

                                        <td width="5%"><strong>:</strong></td>

                                        <td width="52%" style="padding:5px 10px; text-decoration: line-through; color:red">{{ $recipient_name }}</td>
                                    </tr>

                                    <tr>
                                        <td width="43%" style="padding:5px 20px;"><strong>Recipient Email</strong></td>

                                        <td width="5%"><strong>:</strong></td>

                                        <td width="52%" style="padding:5px 10px; text-decoration: line-through; color:red">{{ $recipient_email }}</td>
                                    </tr>

                                    @if($songName != null)
                                        <tr>
                                            <td width="43%" style="padding:5px 20px;"><strong>Song Name</strong></td>
                                            <td width="5%"><strong>:</strong></td>
                                            <td width="52%" style="padding:5px 10px;  text-decoration: line-through; color:red">{{$songName}}</td>
                                        </tr>
                                    @endif

                                    <tr>

                                        <td width="43%" style="padding:5px 20px;"><strong>Occasion</strong></td>

                                        <td width="5%"><strong>:</strong></td>

                                        <td width="52%" style="padding:5px 10px; text-decoration: line-through; color:red">{{$occasion}}</td>

                                    </tr>

                                    <tr>
                                        <td width="43%" style="padding:5px 20px;    padding-bottom: 30px;
"><strong>Personalize Message</strong></td>
                                        <td width="5%"><strong>:</strong></td>
                                        <td width="52%" style="padding:5px 10px;
                         padding-bottom: 30px;
text-decoration: line-through; color:red">{{$personalizedMessage}}</td>
                                    </tr>

                                    <tr>
                                        <td width="43%" style="padding:5px 20px;    padding-bottom: 30px;"><strong>Artist Fulfillment Turnaround</strong></td>
                                        <td width="5%"><strong>:</strong></td>
                                        <td width="52%" style="padding:5px 10px;padding-bottom: 30px;text-decoration: line-through; color:red">{{ $turnaroundTime }}</td>
                                    </tr>

                                    <tr>
                                        <td style="padding:5px 20px;"><strong>Delivery Date</strong></td>
                                        <td><strong>:</strong></td>
                                        <td style="padding:5px 10px;text-decoration: line-through; color:red">{{$complitionDate}}</td>
                                    </tr>

                                    @if(!empty($rejected_reason) && array_key_exists($rejected_reason, Config::get('constants.REJECTED_REASONS')))
                                        <tr>
                                            <td style="padding:5px 20px;"><strong>{{\Lang::get('views.rejected_reason')}}</strong></td>
                                            <td><strong>:</strong></td>
                                            <td style="padding:5px 10px;">{{Config::get('constants.REJECTED_REASONS')[$rejected_reason]}}</td>
                                        </tr>
                                    @endif @if(!empty($rejected_comment))
                                        <tr>
                                            <td style="padding:5px 20px;"><strong>{{\Lang::get('views.rejected_comment')}}</strong></td>
                                            <td><strong>:</strong></td>
                                            <td style="padding:5px 10px;">{{$rejected_comment}}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td colspan="3" style="padding:25px 20px 5px 20px; line-height:1.3; font-style:italic; padding-bottom:20px;"> This is an automated email generated from our server. For customer support, please email support@videorequestline.com.
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

                                        <td align="center" valign="top" style="margin:0;max-width:600px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%; font-size:12px;" height="100%" width="100%">
                                            <p>© <?php echo date("Y"); ?> Video Request Line</p>
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