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
                 <td align="center" valign="top" style="font-family:Helvetica;height:100%;margin:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%; " height="100%" width="100%"><table border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="border-collapse:collapse;margin:0px;max-width:600px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;width:100%;table-layout:auto" width="100%">
                   <tbody>
                     <tr>
                       <td height="100%" align="center" valign="top" style="margin:0;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;width:100%"><a href="#" target="_blank"> <img src="{{URL::to('/')}}/images/vrl_logo_nav.png" style="border-top:0;border-right:0;border-bottom:0;border-left:0;display:inline-block;min-height:auto;outline:none;" height="60" border="0" > </a>                            </td>
                     </tr>
                   </tbody>
                 </table></td>
               </tr>
               <tr>
                 <td align="center" valign="top" style="font-family:Helvetica;height:100%;margin:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;width:100%" height="100%" width="100%"><table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin:0px;max-width:600px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;width:100%;table-layout:auto" width="100%">
                   <tbody>
                    <tr colspan="1">
                      <td width="100%" style="padding:25px 20px 15px 20px;"> <strong>{{$artist_name}}, Here are your Video Request Details</strong></td>
                    </tr>

   <tr>
                    <td style="padding:5px 20px;"><strong>Time to fulfill this request</strong></td>
                    <td><strong>:</strong></td>
                    <td style="padding:5px 10px;">{{$video_delivery_time}} days</td>
                  </tr>   

 <tr> 
 <td style="padding:5px 20px;"><strong>To</strong></td> 
<td width="11%"><strong>:</strong></td> 
 <td width="42%" style="padding:5px 10px;">

{{$user_email}}
</td>
 </tr>

  <tr>  
<td style="padding:5px 20px;"><strong>From</strong></td> 
 <td width="11%"><strong>:</strong></td> 
 <td width="42%" style="padding:5px 10px;">

</td>
 </tr>

<tr>
                     <td style="padding:5px 20px;"><strong>Song Requested</strong></td>
                     <td><strong>:</strong></td>
                     <td style="padding:5px 10px;">

</td>
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
                    <td style="padding:5px 20px;"><strong>Video Request Status</strong></td>
                    <td><strong>:</strong></td>
                    <td style="padding:5px 10px;">{{$current_status}}</td>
                  </tr>
        
                 
                 <tr colspan="1">
                  <td style="padding:25px 20px 5px 20px;"><strong> Login Now to Approve or Reject the request.</strong><br />*Requests that are rejected or the approval time expired will automatically be refunded.</td>
                </tr>
                
                <tr colspan="1">
                  <td style="padding:5px 20px 25px 20px;"> http://videorequestline.com/login/</td>
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

