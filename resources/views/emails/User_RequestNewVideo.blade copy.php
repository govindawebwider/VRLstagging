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
          <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" style="border-collapse:collapse;font-family:Helvetica;height:100%;margin:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;width:600px;background-color:rgb(255,255,255);table-layout:auto;border:solid 1px #999;" bgcolor="#ffffff">
            <tbody>
              <tr>
                <td align="center" valign="top" style="font-family:Helvetica;height:100%;margin:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%; " height="100%" width="100%"><table border="0" cellpadding="0" cellspacing="0" bgcolor="#000000" style="border-collapse:collapse;margin:0px;max-width:600px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;width:100%;table-layout:auto" width="100%">
                  <tbody>
                    <tr>
                      <td align="center" valign="top" style="margin:0;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;width:100%" height="100%" width="100%"><a href="#" target="_blank"> <img src="https://www.videorequestline.com/images/logo1.png" style="border-top:0;border-right:0;border-bottom:0;border-left:0;display:inline-block;min-height:auto;outline:none;" height="60" border="0" > </a></td>
                      
                    </tr>
                  </tbody>
                </table></td>
              </tr>
              <tr>
                <td align="center" valign="top" style="font-family:Helvetica;height:100%;margin:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%" height="100%" width="100%"><table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin:0px;max-width:600px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;width:100%;table-layout:auto;margin-bottom:20px;" width="100%">
                  <tbody>
                    
                    <tr>
                      <td  colspan="3" style="padding:25px 20px 15px 20px;"> 
                        <h4 style="margin:10px 0 15px;font-weight:600;">Thank You {{$username}} for requesting a personalized Video Request {{$artist_id}}-{{$video_request_id}}</h4>
                        <h4 style="padding-bottom:0;margin:0;font-weight:600"> Please Login to Check the Video Fulfillment Status, Details are : </h4>
                      </td>
                    </tr>  
                    
                    <tr>
                     <td colspan="3" style="padding:5px 20px;">
                       
                       {{--*/ 
                       
                       $email = explode('@', $user_email);
                       
                       $email2 = explode('.', $email['1']);
                       
                       /*--}}
                       
                     </td>
                   </tr>                          
                   
                   <tr>
                    <td width="36%" style="padding:5px 20px;"><strong>username </strong></td>
                    <td width="5%"><strong>:</strong></td>
                    <td width="59%" style="padding:5px 10px;">{{ $email['0'] }}<span>@</span>{{ $email2['0'] }}<span>.</span><span>com</span></td>
                  </tr>
                  
                  <tr>
                    <td style="padding:5px 20px;"><strong>Password</strong></td>
                    <td><strong>:</strong></td>
                    <td style="padding:5px 10px;">{{$password}}</td>
                  </tr>
                  
                  <tr>
                    <td style="padding:5px 20px;"><strong>Occasion</strong></td>
                    <td><strong>:</strong></td>
                    <td style="padding:5px 10px;">{{$video_title}}</td>
                  </tr>                             
                  
                  <tr>
                    <td style="padding:5px 20px;"><strong>Personalized Message</strong></td>
                    <td><strong>:</strong></td>
                    <td style="padding:5px 10px;">{{$video_description}}</td>
                  </tr>
                  
                  <tr>
                    <td style="padding:5px 20px;"><strong>Status</strong></td>
                    <td><strong>:</strong></td>
                    <td style="padding:5px 10px;">{{$current_status}}</td>
                  </tr>                             
                  
                  <tr>
                    <td style="padding:5px 20px;"><strong>Request Fulfilment Turnaround</strong></td>
                    <td><strong>:</strong></td>
                    <td style="padding:5px 10px;">{{$video_delivery_time}} days</td> 
                  </tr>
                  
                  <tr>
                    <td colspan="3" style="padding:25px 20px 5px 20px;">Request is pending payment.</td>
                  </tr>
                  <tr>
                   <td>Click Here to <a href="{{URL('https://www.videorequestline.com/login')}}">Login</a></td>
                 </tr>
                 
                 
               </table>
             </td>
             
             <tr>
              <td align="center" valign="top" style="font-family:Helvetica;height:100%;margin:0;;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%" height="100%" width="100%"><table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin:0px;margin-bottom:0px;max-width:600px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;width:100%;table-layout:auto; background:#282e31; color:#fff;" bgcolor="#282e31" width="100%">
                <tbody>
                  <tr>
                    <td align="center" valign="top" style="margin:0;max-width:600px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%; font-size:12px;" height="100%" width="100%"><p>© 2017 Video Request Line</p> </td>
                  </tr>
                </tbody>
              </table></td>
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
