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
                        <td align="right" valign="center" style="margin:0;padding-top:10px;padding-right:20px;padding-bottom:10px;padding-left:10px;width:100%;color:#fff" height="100%" width="100%"><p></p></td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td align="center" valign="top" style="font-family:Helvetica;height:100%;margin:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%" height="100%" width="100%"><table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin:0px;max-width:600px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;width:100%;table-layout:auto" width="100%">
                    <tbody>
                      <tr>
                        <td align="center" valign="top" style="margin:0;max-width:600px;padding-top:30px;padding-right:0;padding-bottom:30px;padding-left:0;width:100%" height="100%" width="100%">
                          <h2>You've been invited to Join us!
</h2>
                          <p>Hey {{$artist_name}}, we've created a new profile for you to start selling personalized videos for your fans. 
</p>
<p>
Click on the Verify Account button below and login with the provided information to finish setting up your account.
</p>
                          <div>
                            <p>username :{{$artist_email}}</p>
                            <p>password :{{$artist_pass}}</p>
                            <p style="line-height:22px;">Thanks for creating an account with the VRL.<br>Please follow the link below to Login an account with the VRL.</p>
                            <p> <a href="{{URL('verify/' . $confirmation_code)}}" target="_blank" style="background:#222;padding:5px 10px;color:#fff;border-radius:3px;text-decoration:none;font-weight:normal;display: inline-block;">Verify Account </a> </p>
                          </div></td>
                        </tr>
                      </tbody>
                    </table></td>
                  </tr>
                  
                  <tr>
                  <td  align="center" valign="top" style="margin:0;max-width:600px;padding-right:0;padding-bottom:30px;padding-left:0;width:100%" height="100%" width="100%">
                  This is an automated email generated from our server. For customer support, please email support@videorequestline.com.

                  </td>
                  </tr>
                  
                  
                  <tr>
                    <td align="center" valign="top" style="font-family:Helvetica;height:100%;margin:0;;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%" height="100%" width="100%">
                        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin:0px;margin-bottom:0px;max-width:600px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;width:100%;table-layout:auto; background:#b135cd; color:#fff;" bgcolor="#b135cd" width="100%">
                      <tbody>
                        <tr>
                          <td align="center" valign="top" style="margin:0;max-width:600px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%; font-size:12px;" height="100%" width="100%"><p>© <?php echo date("Y"); ?> Video Request Line</p></td>
                        </tr>
                      </tbody>
                    </table></td>
                  </tr>
                </tbody>
              </table></td>
            </tr>
          </tbody>
        </table>
      </center>
    </div>
  </body>
  </html>
