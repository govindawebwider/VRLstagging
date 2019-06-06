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
                        <td height="100%" align="center" valign="top" style="margin:0;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;width:100%">
                            <a href="#" target="_blank"> <img src="{{URL::to('/')}}/images/vrl_logo_nav.png" style="border-top:0;border-right:0;border-bottom:0;border-left:0;display:inline-block;min-height:auto;outline:none;" height="60" border="0" > </a>                            </td>
                      </tr>
                    </tbody>
                  </table></td>
                </tr>
                <tr>
                  <td align="center" valign="top" style="font-family:Helvetica;height:100%;margin:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%" height="100%" width="100%"><table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin:0px;max-width:600px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;width:100%;table-layout:auto" width="100%">
                    <tbody>
                      
                      <tr>
                       <td colspan="3" width="47%" style="padding:25px 20px 15px 20px;"> <strong> Dear {{$Name}},</strong>
                       <p>You have video request awaiting fulfilment. Please <a href="{{ URL::to('/login') }}"><span>login</span></a>
                       to upload the requested video.</p>
                       <p>Your VRL order number is {{ $identifier }} </p>
                       </td>
                     </tr>
                     
                     
                     <tr >
                       <td colspan="3" style="padding:0px 20px 5px 20px;"><strong>Request Details </strong></td>
                     </tr>

                      <tr>
                          <td style="padding:5px 20px;"><strong>Sender Name</strong></td>
                          <td><strong>:</strong></td>
                          <td style="padding:5px 10px;">{{ $requested_user->sender_name }}</td>
{{--                          <td style="padding:5px 10px;">{{$requested_user->sender_name}}</td>--}}
                      </tr>

                      <tr>
                          <td style="padding:5px 20px;"><strong>Recipient Name</strong></td>
                          <td width="11%"><strong>:</strong></td>
                          <td width="42%" style="padding:5px 10px;">{{$requested_user->Name}}</td>
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
                       <td style="padding:5px 20px;">Personalized Message</td>
                       <td><strong>:</strong></td>
                       <td style="padding:5px 10px;">{{$requested_user->Description}}</td>
                     </tr>

                      <tr>
                          <td style="padding:5px 20px;"><strong>Status</strong></td>
                          <td width="11%"><strong>:</strong></td>
                          <td width="42%" style="padding:5px 10px;">{{ $current_status }}</td>
                      </tr>

                      <tr>
                          <td style="padding:5px 20px;"><strong>Artist Fulfillment Turnaround</strong></td>
                          <td width="11%"><strong>:</strong></td>
                          <td width="42%" style="padding:5px 10px;">{{ $turnaroundTime }}</td>
                      </tr>

                      <tr>
                          <td style="padding:5px 20px;"><strong>Delivery Date</strong></td>
                          <td width="11%"><strong>:</strong></td>
                          <td width="42%" style="padding:5px 10px;">{{ $delivery_date }}</td>
                      </tr>
                      <tr>
                          <td style="padding:5px 20px;">Price </td>
                          <td width="8%"><strong>:</strong></td>
                          <td width="51%" style="padding:5px 10px; color:red">{{ $price }}</td>
                      </tr>
                     
                     <tr >
                         <td colspan="3" style="padding:25px 20px 5px 20px;">
                             <p> Regards,</p>
                             <p>VRL TEAM</p>
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
