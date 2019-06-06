<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class uploadRequestedVideo extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var $inputs
     */
    private $inputs;

    /**
     * @var $data
     */
    private $data;

    /**
     * @var $artistData
     */
    private $artistData;

    /**
     * Create a new job instance.
     *
     * @param $inputs
     * @param $data
     * @param $artistData
     */
    public function __construct($inputs, $data, $artistData)
    {
        $this->inputs = $inputs;
        $this->data = $data;
        $this->artistData = $artistData;
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {
        \Log::useDailyFiles(public_path().'/logs/upload-request-handler.log');
        \Log::info("-Watermark Generation Handler Started [Identifier:".$this->inputs['requested_video_id']." - ".$this->artistData['artist_id']." ]-");
        try {
            $ffmpegPath = '/usr/bin/ffmpeg';
            $watermark = base_path() . '/public/vrl_logo.png';
            $inputPath = base_path() . '/public/'.$this->inputs['inputPath'];
            $outPath = base_path() . '/public/'.$this->inputs['outPath'];
            $thumbnailVideo = base_path() . '/public/'.$this->inputs['thumbnailVideo'];
            if (file_exists($outPath))  {
                unlink($outPath);
            }
            if (file_exists($thumbnailVideo)) {
                unlink($thumbnailVideo);
            }
            shell_exec("$ffmpegPath  -i $inputPath -i $watermark -filter_complex \"[1][0]scale2ref=(262/204)*ih/12:ih/12[wm][vid];[vid][wm]overlay=x=(W-w-20):y=(H-h-20)\" $outPath ");
            shell_exec("$ffmpegPath  -i $inputPath -i $watermark  -filter_complex \"[0:v]scale=640:360[bg];[bg][1:v]overlay=x=(W-w-20):y=(H-h-20)\" $thumbnailVideo ");
            $waterMarkPath = $this->inputs['outPath'];
            $thumbnailVideoPath = $this->inputs['thumbnailVideo'];
            /*multipartUpload($thumbnailVideo);
            multipartUpload($outPath);*/
            $disk = \Illuminate\Support\Facades\Storage::disk('s3');
            $disk->put($waterMarkPath, file_get_contents($outPath), 'public');
            $disk->put($thumbnailVideoPath, file_get_contents($thumbnailVideo), 'public');
            $data = $this->data;

            $user_email = $this->inputs['user_email'];
            \Mail::send('emails.download_email', $data, function ($message) use ($user_email,$data) {
                $message->from('noreply@videorequestline.com', 'Download Video');
                $message->to($user_email, $user_email);
                $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                $message->subject('VRL request uploaded ('.$data['identifier'].')');
            });


            if(strtotime($this->inputs['complitionDate']) == strtotime(date('Y-m-d'))) {
                $m  =  \DB::table('requested_videos')
                    ->where('VideoReqId',$this->inputs['requested_video_id'])
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
                    ->first();

                //$m->videoUrl = $s3->url('requested_video/watermark/'.$m->videoUrl);
                $m->videoThumbnail = $disk->url('images/thumbnails/'.$m->videoThumbnail);
                $m->artist_id =  $m->ProfileId;
                $m->video_id = $m->VideoReqId;

                if ($m->reciever_email != $m->sender_email) {
                    \Mail::send('emails.cronDeliveryRecipientEmail', ['data' => $m], function ($message) use ($m) {
                        $message->from('noreply@videorequestline.com', 'Gift videorequestline');
                        $message->to($m->reciever_email, $m->reciever_name);
                        $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                        $message->subject('You have received new gift from : ' . $m->sender_name);
                    });
                }

                // Send Gift Email to sender
                \Mail::send('emails.cronDeliverySenderEmail', ['data'=> $m] , function ($message) use($m) {
                    $message->from('noreply@videorequestline.com', 'Gift videorequestline');
                    $message->to($m->sender_email, $m->sender_name);
                    $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                    $message->subject('Your request has been sent : '. $m->sender_name);
                });



                \DB::table('requested_videos')->where('id',$m->ID)
                    ->update(['is_email_sent' => 1]);
            }


            // Send Mail To Artist That His Video Uploaded Successfully
            $artistData = $this->artistData;
            $email = $this->inputs['email'];
            \Mail::send('emails.upload_artist_download_email', $artistData, function ($message) use($email){
                $message->from('noreply@videorequestline.com', 'Download Video');
                $message->to($email, $email);
                $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                $message->subject('Video Submitted Successfully');
            });
            unlink($thumbnailVideo);
            unlink($outPath);
            unlink($inputPath);
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            throw new \Exception($exception->getMessage());
             
        }
    }

    /**
     * Handle a job failure.
     *
     * @return void
     */
    public function failed()
    {
        \Log::useDailyFiles(public_path().'/logs/upload-request-handler.log');
        \Log::info("-Watermark Generation Handler Failed Acknowledged [Identifier:".$this->inputs['requested_video_id']." - ".$this->artistData['artist_id']." ]-");
        $inputPath = base_path() . '/public/'.$this->inputs['inputPath'];
        $outPath = base_path() . '/public/'.$this->inputs['outPath'];
        $thumbnailVideo = base_path() . '/public/'.$this->inputs['thumbnailVideo'];
        if (file_exists($outPath))  {
            unlink($outPath);
        }
        if (file_exists($thumbnailVideo)) {
            unlink($thumbnailVideo);
        }
        $disk = \Illuminate\Support\Facades\Storage::disk('s3');
        $failedData = $this->data;
        $user_email = $this->inputs['user_email'];
        \Mail::send('emails.download_email', $failedData, function ($message) use ($user_email,$failedData) {
            $message->from('noreply@videorequestline.com', 'Download Video');
            $message->to($user_email, $user_email);
            $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
            $message->subject('VRL request uploaded ('.$failedData['identifier'].')');
        });


        if(strtotime($this->inputs['complitionDate']) == strtotime(date('Y-m-d'))) {
            $m  =  \DB::table('requested_videos')
                ->where('VideoReqId',$this->inputs['requested_video_id'])
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
                ->first();

            //$m->videoUrl = $s3->url('requested_video/watermark/'.$m->videoUrl);
            $m->videoThumbnail = $disk->url('images/thumbnails/'.$m->videoThumbnail);
            $m->artist_id =  $m->ProfileId;
            $m->video_id = $m->VideoReqId;

            if ($m->reciever_email != $m->sender_email) {
                \Mail::send('emails.cronDeliveryRecipientEmail', ['data' => $m], function ($message) use ($m) {
                    $message->from('noreply@videorequestline.com', 'Gift videorequestline');
                    $message->to($m->reciever_email, $m->reciever_name);
                    $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                    $message->subject('You have received new gift from : ' . $m->sender_name);
                });
            }

            // Send Gift Email to sender
            \Mail::send('emails.cronDeliverySenderEmail', ['data'=> $m] , function ($message) use($m) {
                $message->from('noreply@videorequestline.com', 'Gift videorequestline');
                $message->to($m->sender_email, $m->sender_name);
                $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                $message->subject('Your request has been sent : '. $m->sender_name);
            });



            \DB::table('requested_videos')->where('id',$m->ID)
                ->update(['is_email_sent' => 1]);
        }


        // Send Mail To Artist That His Video Uploaded Successfully
        $artistData = $this->artistData;
        $email = $this->inputs['email'];
        \Mail::send('emails.upload_artist_download_email', $artistData, function ($message) use($email) {
            $message->from('noreply@videorequestline.com', 'Download Video');
            $message->to($email, $email);
            $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
            $message->subject('Video Submitted Successfully');
        });
        
        $this->sendErrorMessage(
            'CRITICAL: WATERMARK GENERATION HANDLER FAILED: ' . url('/'), 
            'Please check the application logs.'
        );
        
        if (file_exists($thumbnailVideo)) {
            unlink($thumbnailVideo);
        }
        if (file_exists($outPath)) {
            unlink($outPath);
        }
        if (file_exists($inputPath)) {
            unlink($inputPath);
        }

        \Log::info("-Watermark Generation Handler Failed Acknowledgement Ended [Identifier:".$this->inputs['requested_video_id']." - ".$this->artistData['artist_id']." ]-");
    }

    /**
     * send exception email
     *
     */
    public function sendErrorMessage($errorTitle, $errorDescription='') {
        \Log::info("-Sending Watermark Generation Handler Failed Email-");
        try {
            $confirmationCode = ['error_title' => $errorTitle, 'error_description' => $errorDescription];
            \Mail::send('emails.notify_email_when_exception_occurs', $confirmationCode, function ($message) {
                $message->from('noreply@videorequestline.com', 'VRL');
                $message->to(config('constants.EXCEPTION_OCCURS.exception_email'), config('constants.EXCEPTION_OCCURS.exception_name'));
                $message->subject('Thumbnail generation process failed : ' . url('/'));
            });
            \Log::info("-Watermark Generation Handler Failed Email sent-");
        } catch (\Exception $e) {
            \Log::error("-Watermark Generation Handler Failed Email trigger failed-");
        }
    }
}
