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

                            <td><table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" style="border-collapse:collapse;font-family:Helvetica;height:100%;margin:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;width:600px;background-color:rgb(255,255,255);table-layout:auto;border: solid 2px #b135cd;" bgcolor="#ffffff">

                                    <tbody>

                                        <tr>

                                            <td align="center" valign="top" style="font-family:Helvetica;height:100%;margin:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%; " height="100%" width="100%"><table border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="border-collapse:collapse;margin:0px;max-width:600px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;width:100%;table-layout:auto" width="100%">

                                                    <tbody>

                                                        <tr>

                                                            <td align="center" valign="top" style="margin:0;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;width:100%" height="100%" width="100%"><a href="#" target="_blank"> <img src="{{URL::to('/')}}/images/vrl_logo_nav.png" style="border-top:0;border-right:0;border-bottom:0;border-left:0;display:inline-block;min-height:auto;outline:none;" height="60" border="0" > </a></td>

                                                        </tr>

                                                    </tbody>

                                                </table></td>

                                        </tr>

                                        <tr>

                                            <td align="center" valign="top" style="font-family:Helvetica;height:100%;margin:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%" height="100%" width="100%"><table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin:0px;max-width:600px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;width:100%;table-layout:auto" width="100%">

                                                    <tbody>

                                                        <tr>

                                                            <td  colspan="3" style="padding:25px 20px 15px 20px;"> 

                                                                <h2 style="margin:0px 0 10px;font-weight:600;line-height: 40px;">Your gift {{ $data->artist_id }}-{{ $data->video_id }} has been sent to  {{ $data->reciever_name }} </h2>

                                                                <p>Thanks for requesting a personalized video from {{ $data->artistName }}.</p>

                                                                <b>The video has been delivered.</b>
                                                                <p>You may view or download the video below.</p>

                                                            </td>

                                                        </tr>



                                                        <tr>

                                                            <td colspan="1" style="padding:5px 20px;"><strong>Desired Song</strong></td>

                                                            <td width="6%"><strong>:</strong> </td>

                                                            <td width="53%" style="padding:5px 10px;">
                                                                {{ $data->song_name }} 
                                                            </td>

                                                        </tr>



                                                        <tr>

                                                            <td style="padding:5px 20px;"><strong>To</strong></td>

                                                            <td><strong>:</strong></td>

                                                            <td style="padding:5px 10px;">{{ $data->reciever_name }} / {{ $data->reciever_email }} </td>

                                                        </tr>


                                                        <tr>

                                                            <td style="padding:5px 20px;"><strong>From</strong></td>

                                                            <td><strong>:</strong></td>

                                                            <td style="padding:5px 10px;">  {{ $data->sender_name }}  / {{ $data->sender_email }}  </td>

                                                        </tr>



                                                        <tr>

                                                            <td style="padding:5px 20px;"><strong>Occasion</strong></td>

                                                            <td><strong>:</strong></td>

                                                            <td style="padding:5px 10px;">{{ $data->occassion }} </td>

                                                        </tr>


                                                        <tr>

                                                            <td style="padding:5px 20px;"><strong>Message</strong></td>

                                                            <td><strong>:</strong></td>

                                                            <td style="padding:5px 10px;">{{ $data->message }} </td>

                                                        </tr>

                                                        <tr>
                                                            <td style="padding:5px 20px;">

                                                                <p><strong>Your video detail </strong>  </p>
                                                            </td>
                                                            <td><strong>:</strong></td>

                                                            <td>
                                                                To  {{ $data->reciever_name }}   On  {{ $data->deliveryDate     }}  
                                                            </td>



                                                        </tr>

                                                  
                                                        <tr>

                                                            <td colspan="3" style="padding:25px 20px 5px 20px;"> 
                                                                <a href="{{URL('download_video/'.$data->videoUrl)}}" style="padding:10px 30px;
                                                                   background-color:green;
                                                                   color:#fff;
                                                                   border-radius: 4px;
                                                                   text-decoration: none;
                                                                   margin-right: 30px;">
                                                                    Download
                                                                </a>
                                                                <?php $fileName = 'requested_video/watermark/'.$data->videoUrl;
                                                                $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')->url($fileName);
                                                                ?>
                                                                <a href="{{ $imageUrl }}" style="padding:10px 30px;
                                                                   background-color:blue;
                                                                   color:#fff;
                                                                   border-radius: 4px;
                                                                   text-decoration: none;
                                                                   margin-right: 15px;">
                                                                    View
                                                                </a>
                                                            </td>

                                                        </tr>
                                                              <tr>
                                                            <td colspan="3" style="padding:25px 20px 5px 20px;">
                                                                <p>
                                                                    You may view or resend the video to the original recipient using your login at <a href="{{url('/')}}"> {{url('/')}}</a>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        



                                                        <tr>
                                                            <td colspan="3" style="padding:0px 20px 5px 20px;">
                                                                <p><span style="font-style: italic">Thank you for your request! </span>  We hope  {{ $data->reciever_name }} Loves it!
                                                                </p> 
                                                            </td>

                                                        </tr>
                                                        
                                                       <tr>
                                                            <td colspan="3" style="padding:25px 20px 5px 20px;">
                                                                <strong>VRL</strong> 
                                                                <p>
                                                                <a href="{{ URL::to("/") }}">{{ URL::to("/") }}</a>

                                                                </p>
                                                                
                                                            </td>

                                                        </tr>








                                                    </tbody>

                                                </table></td>

                                        </tr>

                                        <tr>

                                            <td align="center" valign="top" style="font-family:Helvetica;height:100%;margin:0;;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%" height="100%" width="100%">
                                                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin:0px;margin-bottom:0px;max-width:600px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;width:100%;table-layout:auto; background:#b135cd; color:#fff;" bgcolor="#b135cd" width="100%">

                                                    <tbody>

                                                        <tr>

                                                            <td align="center" valign="top" style="margin:0;max-width:600px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%; font-size:12px;" height="100%" width="100%"><p>© <?php echo date("Y"); ?> Video Request Line</p> </td>

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

