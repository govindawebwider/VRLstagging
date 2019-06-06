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

                                                            <td  colspan="3" style="padding:25px 20px 0px 20px;"> 

                                                                <h2 style="margin:10px 0 15px;font-weight:600">
   Account Email Verification                                              
                                                                </h2>

<p>
Hey {{$user['user_name']}}
</p>
<p>

We received a request to modify the email account used for {{$user['user_name']}} on {{URL::to('/')}} .

</p>

<p>
If you wish to proceed with the change, please Click 
<a href="{{URL::to('/update/confirm-email-change')}}?request_token={{$user['token']}}">Here</a> to Activate your new email address.
</p>

<br>
 <i> 
 If you did not make this request, please notify us at <a href="mailto:support@videorequestline.com">support@videorequestline.com</a>

 </i>

                                                            </td>

                                                        </tr>


             <tr>



                                                            <td  colspan="3" style="padding:15px 20px 5px 20px;">
                                                                <p>This is an automated email generated from our server. For customer support, please email</p>
                                                                <a href="mailto:support@videorequestline.com" > support@videorequestline.com</a>


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

                                                            <td align="center" valign="top" style="margin:0;max-width:600px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%; font-size:12px;" height="100%" width="100%"><p>©<?php echo date("Y"); ?> Video Request Line</p> </td>

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

