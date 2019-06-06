<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Review;
use App\Testimonial;
use App\RequestedVideo;
use App\Requestvideo;
use DB;
use Session;
use Mail;
use Snipe\BanBuilder\CensorWords;
class ReviewController extends Controller {

    public function create(Request $request) { 
        /*
         * Check if this request token is in valid or used before return back to home with error session
         */
        if(!empty($request->request_token)){
        $requested_video =   DB::table('requested_videos')
                ->where(['token'=>$request->request_token ,
                        'token_used'=> 0
                    ])
                 ->join('requestvideos', 'requestvideos.VideoReqId', '=', 'requested_videos.request_id')
                 ->join('profiles', 'profiles.ProfileId', '=', 'requested_videos.uploadedby')
                ->select([
                    "requestvideos.sender_name" ,
                     "requestvideos.Name as reciever_name",
                    "requestvideos.sender_email" ,
                    "profiles.Name as artistName"
                    
                    ])
                ->first ();
        
               if(count($requested_video) > 0){
                   return view('frontend.reviews.create')->with([
                            'request_token'=>$request->request_token,
                             'data' => $requested_video
                           ]);
               }
               else{
                    // this request has been reviewed before 
                    Session::flash('error-review', 'Sorry This request already reviewed');
                    return redirect('/view-all-video');
               }
        }
          else{
                    // this request has been reviewed before 
                    Session::flash('error-review', 'Sorry This request already reviewed');
                    return redirect('/view-all-video');
               }
        
    }

    public function store(Request $request) {
       
        $request_token = $request->request_token;
        // First we will save the unique data in the 
        $requested_video = RequestedVideo::where('token',$request_token)->first();
        if($requested_video->token_used == 0){
            
            $artist = \App\Profile::where('ProfileId',$requested_video->uploadedby)->first();
            $review = new Review();
            
            $review->artist_id = $requested_video->uploadedby; // artist id 
            
            $review->review = $request->artist_review;
            
            $review->message_to_sender = $request->message_to_sender;
            $review->rate = $request->rate;
            $review->save();
            $review->reciever_name = $request->reciever_name;
            $review->artist_name = $artist->name;
            //    send the message to the sender 
               Mail::send('emails.sendReplayMessageFromRecipient', ['data' => $review] , function ($message) use($request) {
                        $message->from('noreply@videorequestline.com', $request->reciever_name. ' Reply videorequestline');
                        $message->to($request->sender_email, $request->sender_name);
                        $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                        $message->subject('You have recieved From : '. $request->reciever_name);
                    });
                    
            // update the used token to be 1 
            
             $requested_video->token_used=1;
             $requested_video->save();
            
             
              // add this review to testimonails 
            $request = Requestvideo::where('VideoReqId',$requested_video->request_id)->first();
            $testimonailData = array();  
            
            $testimonailData['to_profile_id'] = $review->artist_id;
            $testimonailData['name'] = $request->Name;
            $testimonailData['email'] = $request->requestor_email;
            $testimonailData['review'] =  $review->review;
            
            $this->storeReviewAsTestimonials($testimonailData);
             
            Session::flash('success-review', 'Thank you for your review');
            return redirect('/view-all-video');
        }
        
        else{
            
            // this request has been reviewed before 
            Session::flash('error-review', 'Sorry This request already reviewed');
            return redirect('/view-all-video');

        }
        
        
        
    }
    
    
    
    public function storeReviewAsTestimonials($testimonailData){
        
        $testimonial =   new Testimonial();
        $testimonial->to_profile_id = $testimonailData['to_profile_id'];
        $testimonial->Email = $testimonailData['email'];
        $testimonial->user_name = $testimonailData['name'];
        $testimonial->AdminApproval = 0;
        $censor = new CensorWords;
        $string = $censor->censorString($testimonailData['review']);
        $testimonial->message = $string['clean'];
        
        $testimonial->save();
        
    }

}
