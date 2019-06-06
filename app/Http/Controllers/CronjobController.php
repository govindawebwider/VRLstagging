<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Illuminate\Support\Facades\Storage;
use Mail;
use Carbon\Carbon;
use Log;

class CronjobController extends Controller
{
    /*
     * This Controller is used for All Cron jobs in the system
     * Please if you found any cronjob functions dosn't related to this controller update it to here
     */
    
    
    /*
     * Send Requested Video on delivary Date
     * Get all videos for the delavery date is today and status is comleted
     * start sends email to recipent and sender 
     * the recipient recieve his email 
     * sender will recieve the reminder email for that your request has been sent
     */
    public function sendRequestVideoOnDelivaryDate(){
        //date_default_timezone_set('America/Los_Angeles');


        $today = strtotime(date("m/d/Y"));

Log::error('Today Date: ' . $today . '/' . date("m/d/Y"));        
        
        // Get All Requests For the Current Date and select sender and reciver emails  and email data 
        $allRequests  =  DB::table('requested_videos')
                ->where(['RequestStatus'=>'Completed'])
                ->where('is_email_sent','<>',1)
                ->where(DB::raw('UNIX_TIMESTAMP(DATE_FORMAT(STR_TO_DATE(complitionDate, "%m/%d/%Y"), "%Y-%m-%d"))'), $today)
                 ->join('requestvideos', 'requestvideos.VideoReqId', '=', 'requested_videos.request_id')
                 ->join('profiles', 'profiles.ProfileId', '=', 'requested_videos.uploadedby')
                ->select([
                    "requestvideos.complitionDate as deliveryDate ",
                    "requestvideos.VideoReqId",
                    "requestvideos.requestor_email as reciever_email",
                    "requestvideos.Name as reciever_name",
                    "requestvideos.sender_email",
                    "requestvideos.sender_name" ,
                    "requestvideos.Description as message",
                    "requestvideos.song_name",
                    "requestvideos.Title as occassion",
                    "requested_videos.id as ID",
                    "requested_videos.url as videoUrl",
                    "requested_videos.thumbnail as videoThumbnail",
                    "requested_videos.token as request_token",
                    "profiles.Name as artistName",
                    "profiles.ProfileId"
                    ])
                ->get();
        
Log::error($allRequests);
        $idsArray = [];
        $s3 = Storage::disk('s3');
                foreach ($allRequests as $m){

                     //$m->videoUrl = $m->videoUrl;
                     $m->videoThumbnail = $s3->url('images/thumbnails/'.$m->videoThumbnail);
                     $m->artist_id =  $m->ProfileId;
                     $m->video_id = $m->VideoReqId;
                    // die;
                     //  Send Email to recipient
                     
                    if ($m->reciever_email != $m->sender_email) {
                        Mail::send('emails.cronDeliveryRecipientEmail', ['data' => $m], function ($message) use ($m) {
                            $message->from('noreply@videorequestline.com', 'Gift videorequestline');
                            $message->to($m->reciever_email, $m->reciever_name);
                            $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                            $message->subject('You have received new gift from : ' . $m->sender_name);
                        });
                    }
//                    
                    
//                  Send Email to sender
                    Mail::send('emails.cronDeliverySenderEmail', ['data'=> $m] , function ($message) use($m) {
                        $message->from('noreply@videorequestline.com', 'Gift videorequestline');
                        $message->to($m->sender_email, $m->sender_name);
                        $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                        $message->subject('Your request has been sent : '. $m->sender_name);
                    });
                    
                    $idsArray[] = $m->ID;
                    
                }
                DB::table('requested_videos')->whereIn('id',$idsArray)
                                ->update(['is_email_sent' => 1]);
        // Loop over the requests and start sending the emails with the right contents 
        
        
        echo 'Cronjob worked fine ! ';
        
   
        
    }
    
}
