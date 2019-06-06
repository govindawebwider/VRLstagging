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

                            <td align="center" valign="top" style="font-family:Helvetica;height:100%;margin:0;padding-top:30px;padding-right:0;padding-bottom:50px;padding-left:0;width:100%" height="100%" width="100%">
                                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin:0px;max-width:600px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;width:100%;table-layout:auto;margin-bottom:20px;" width="100%">

                                    <tbody>

                                    {{--<tr align="left">--}}
                                        {{--<td colspan="3" style="padding:5px 20px;">--}}
                                            {{--<h2 style="line-height: 30px">Here is your Personalized Video From {{ $artistName }}</h2></td>--}}
                                    {{--</tr>--}}

                                    <tr align="left">
                                        <td colspan="3" style="padding:5px 20px;">Your VRL order number is ({{ $identifier }})</td>
                                    </tr>

                                    <tr>
                                        <td style="padding:5px 20px;">Sender name</td>
                                        <td><strong>:</strong></td>
                                        <td style="padding:5px 10px;">{{ $requested_user->sender_name }}</td>
                                    </tr>

                                    <tr>
                                        <td style="padding:5px 20px;">Sender email</td>
                                        <td><strong>:</strong></td>
                                        <td style="padding:5px 10px;">{{ $requested_user->sender_email }}</td>
                                    </tr>

                                    <tr>
                                        <td style="padding:5px 20px;">Recipient name</td>
                                        <td><strong>:</strong></td>
                                        <td style="padding:5px 10px;">{{ $requested_user->Name }}</td>
                                    </tr>

                                    <tr>
                                        <td style="padding:5px 20px;">Recipient email</td>
                                        <td><strong>:</strong></td>
                                        <td style="padding:5px 10px;">{{ $requested_user->requestor_email }}</td>
                                    </tr>

                                    @if($songName != null)
                                        <tr>
                                            <td style="padding:5px 20px;"><strong>Desired Song Name</strong></td>
                                            <td width="11%"><strong>:</strong></td>
                                            <td width="42%" style="padding:5px 10px;">{{ $songName }}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td style="padding:5px 20px;">Occasion</td>
                                        <td width="8%"><strong>:</strong></td>
                                        <td width="51%" style="padding:5px 10px;">{{$requested_user->Title}}</td>
                                    </tr>

                                    <tr>
                                        <td style="padding:5px 20px;">Personlaized Message</td>
                                        <td><strong>:</strong></td>
                                        <td style="padding:5px 10px;">{{$requested_user->Description}}</td>
                                    </tr>

                                    <tr>
                                        <td style="padding:5px 20px;">Status</td>
                                        <td><strong>:</strong></td>
                                        <td style="padding:5px 10px;">{{ $current_status }}</td>
                                    </tr>

                                    <tr>
                                        <td style="padding:5px 20px;"><strong>Artist Fulfillment Turnaround</strong></td>
                                        <td><strong>:</strong></td>
                                        <td width="52%" style="padding:5px 10px;padding-bottom: 30px;text-decoration: line-through; color:red">{{ $turnaroundTime }}</td>
                                    </tr>

                                    <tr>
                                        <td style="padding:5px 20px;">Delivery Date :</td>
                                        <td><strong>:</strong></td>
                                        <td style="padding:5px 10px;">{{$requested_user->complitionDate}}</td>
                                    </tr>

                                    <tr>
                                        <td style="padding:5px 20px;" colspan="3">
                                            <p>We will resend the video, you requested as soon as possible.</p>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="padding:5px 20px;">{{\Lang::get('messages.click_to_download')}}</td>
                                        <td><strong>:</strong></td>
                                        <td style="padding:5px 10px; margin-top:8px;display: inline-block;">
                                            <?php $fileName = 'requested_video/watermark/'.$video_name;
                                            $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')->url($fileName);
                                            ?>
                                            {{--<img src="{{$imageUrl}}" alt="">--}}
                                            <a href="{{URL('download_video/'.$video_name)}}" style="background:#222;padding:5px 10px;color:#fff;border-radius:3px;text-decoration:none;font-weight:normal" download>{{\Lang::get('views.download')}}</a>
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