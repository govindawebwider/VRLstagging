<?php
namespace App\Http\Controllers;
use Auth;
use App\RequestedVideo;
use App\User;
use App\Video;
use App\OriginalVideo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Input;
use DB;
use App\Http\Requests;
use Hash;
use Validator;
use Mail;
use App\Profile;
use App\Requestvideo;
use FFMpeg;
use Session;
use Carbon\Carbon;
use App\helper;
use Snipe\BanBuilder\CensorWords;
class ApiController extends Controller
{
	public function change_pass_api(Request $request){

		$email = DB::table('users')->where('email',$request->email)->first();
		//echo "test";
		$old_pass=$email->password;
		$pass = Hash::make($request->new_pass);

		if(Hash::check($request->old_pass,$old_pass)){
			if(User::where('email','=',$request->email)->update(array('password' => $pass))){
				$response = array('response_code' => "200",'return_message' => "Change Password Successfully");
			}else{
				$response = array('response_code' => "500",'return_message' => "Change Password not Successfully");
			}
		}else{
				$response = array('response_code' => "500",'return_message' => "Email and Password does not Exists");
		}
		return $response;
	}
	public function ProList(Request $request){
		$type=$request->type;
		if($type=='about'){	
    		$users = DB::table('footer_content')->where('type', 'About')->select('*')->get();
    	}else if($type=='privacy'){
    		$users = DB::table('footer_content')->where('type', 'Privacy policy')->select('*')->get();
    	}else if($type=='terms'){
			$users = DB::table('footer_content')->where('type', 'Terms and conditions')->select('*')->get();
    	}else{

    	}
	
		return json_encode($users);
    }
/*---------------------All video api----------------------*/
    public function view_allVideo(){
    	$videoDetails = DB::table('video')->select('*')->get();
    	if($videoDetails!=null){
    		return json_encode($videoDetails);
    	}else{
    		$response = array('return_message' => "No video found");
    		return json_encode($response);
    	}
    }
    /*
    * API for showing all latest video
    */
    public function view_latestVideo(){
    	$videoDetails = DB::table('video')->select('*')->orderBy('VideoId','desc')->get();
    	if($videoDetails!=null){
    		return json_encode($videoDetails);
    	}else{
    		$response = array('return_message' => "No video found");
    		return json_encode($response);
    	}
    }
	/*
	* API for insert featured video of artist
	*/
	public function ins_featuredVideo(Request $request){
		$video = new Video();
		$file = $request->file('video');
		$extension = $file->getClientOriginalExtension();
		$filename = str_replace(' ', '', $file->getClientOriginalName());
		$filename = str_replace('-', '', $filename);
		$VideoURL = "http://videorequestlive.com/video/".date('U') .$filename ;
		$video->VideoFormat = $file->getClientOriginalExtension();
		$video->VideoSize = ($file->getSize()/1024) . "mb";
		$video->Description = $request->video_description;
		$video->Title = $request->video_title;
		$video->VideoUploadDate = Carbon::now();
		$video->ProfileId	 = $request->profile_id;
		$video->UploadedBy	 = "Artist";
		$ffmpeg = FFMpeg\FFMpeg::create(array(
			'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
			'ffprobe.binaries' => '/usr/bin/ffprobe',
			'timeout'          => 3600,
			'ffmpeg.threads'   => 12,
			));
		/*--------------------------Opening Uploaded Video------------------------------*/
		$rand = rand(11111,99999).date('U');
		$destination = base_path() . '/public/video/original/';
		$fileName = $rand.'.'.$extension;
		$request->file('video')->move($destination,$fileName);
		$destination_path= $destination.$fileName;
		$orginal_video = new OriginalVideo();
		$orginal_video->video_path=$destination_path;
		$orginal_video->save();
		$orginal_video_id= $orginal_video->id;
		$orginal_video = OriginalVideo::find($orginal_video_id);
		$uploaded_video = $ffmpeg->open($orginal_video->video_path);
		/*----------------------------retrieving Thumbnail------------------------------*/
		$video_thumbnail_path = base_path() . '/public/images/thumbnails/'.date('U').'.jpg';
		$uploaded_video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(2))->save($video_thumbnail_path);
		$video->VideoThumbnail	 = $video_thumbnail_path;
		/*----------------------------Applying Watermark----------------------------------*/
		$ffmpegPath = '/usr/bin/ffmpeg';
		$inputPath = $orginal_video->video_path;
		$watermark = '/home/vrl/public_html/public/vrl_logo.png';
		$outPath = '/home/vrl/public_html/public/video/watermark/'.date('U').'.mp4';
		shell_exec("$ffmpegPath  -i $inputPath -i $watermark -filter_complex overlay=main_w-overlay_w-20:main_h-overlay_h-20 $outPath ");
		
		/*	----------------------------------Saving Video-------------------------------*/
		$watermark_video_destination = substr($outPath,28);
		$video->VideoURL	 = $outPath;
		$video->originalVideoUrl	 = $orginal_video->video_path;
		$ffprobe = FFMpeg\FFProbe::create(array(
			'ffmpeg.binaries'  => '/usr/lbin/ffmpeg',
			'ffprobe.binaries' => '/usr/bin/ffprobe',
			'timeout'          => 3600,
			'ffmpeg.threads'   => 12,
			));
		/*-------------------------retrieving Video Duration----------------------------*/
		$video->VideoLength = $ffprobe->streams($orginal_video->video_path)
		->videos()
		->first()
		->get('duration');
		if($video->save())
		{
			
			$response = array('return_message' => "Submitted Successfully");
				return json_encode($response);
		}
	}
	/*
	* API for show testimonial by profile id
	*/
	public function show_testimonial(Request $request){
		$testimonial_data = DB::table('testimonials')
		->select('profiles.Name','testimonials.*')
		->where('AdminApproval','=',1)
		->where('to_profile_id','=',$request->profileId)
		->join('profiles','profiles.ProfileId','=','testimonials.by_profile_id')
		->get();
		return $testimonial_data;
	}
	/*
	* API for show testimonial by profile id
	*/
	public function insert_testimonial(Request $request){
		$censor = new CensorWords;
		$string = $censor->censorString($request->message);
		$values = array('by_profile_id' =>$request->by_profile_id,'to_profile_id' => $request->to_profile_id,'Message' => $string['clean'],'AdminApproval' =>0);
		if(DB::table('testimonials')->insert($values)){
			$response = array('return_message' => "Submitted Successfully");
				return json_encode($response);
		}else{
			$response = array('return_message' => "Not Submitted Successfully");
				return json_encode($response);
		}
	}
	/*
	* API for upload address proof id picture
	*/
	public function upload_bank_id_pic(Request $request){
		$imageName=$request->randomFilename;
		$updateType = $request->update;
		$fileExtension = $request->extension;
		$target = "images".'/'.'Artist'.'/'.'address_proof_pic'.'/'. str_replace(" ","",$_POST['randomFilename']);
		if (move_uploaded_file($_FILES['media']['tmp_name'], $target)) {
			$filename = $target;
			list($current_width, $current_height) = getimagesize($filename);
			$left = 0; $top  = 0;
			$crop_width = $current_width;
			$crop_height = $current_height;
			$canvas = imagecreatetruecolor($crop_width, $crop_height);
			$current_image = imagecreatefromjpeg($filename);
			imagecopy($canvas, $current_image, 0, 0, $left, $top, $current_width, $current_height);
			imagejpeg($canvas, $filename, 100);
			$msg=$target;
			$profilePath=$msg;
			
			if(Profile::where('ProfileId',$request->profileId)->update(array('id_pic' => $profilePath))){
				$response = array('return_message' => "Update successfully");
				return json_encode($response);
			}else{
				$response = array('return_message' => "Update Not successfully");
				return json_encode($response);
			}
		}
		
		
	}
	/*
	* API for delete request
	*/
	public function delete_request(Request $request){
		if(DB::table('requestvideos')->where('VideoReqId', '=', $request->VideoReqId)->delete()){
			$response = array('return_message' => "Delete successfully");
			return json_encode($response);
		}else{
			$response = array('return_message' => "Delete Not successfully");
			return json_encode($response);
		}
		
	}
	/*
	* API for Search by name and type
	*/
	public function Add_BankDtl(Request $request){
		if(Profile::where('ProfileId',$request->profileId)->update(array('account_number' => $request->account_number,'routing_number' => $request->routing_number,'ssn_number' => $request->ssn_number,'id_pic' => $request->id_pic,'pin' =>$request->pin))){
			$response = array('return_message' => "Added successfully");
			return json_encode($response);
		}else{
			$response = array('return_message' => "Added Not successfully");
			return json_encode($response);
		}
		
	}
	/*
	* API for Search by name and type
	*/
	public function search(Request $request){
		$search = $request->Name;
		$users = DB::table('profiles')->where('Name','LIKE',"%{$search}%")
		->where('Type','Artist')
		->select('ProfileId','Name','EmailId','Address','City','State','PaypalId','RegisterDate','LastLogin','Zip','profile_path','BannerImg','NickName','Gender','DateOfBirth','MobileNo','profile_description','Type','country','created_at')
		->get();
		return $users;
	}
	/*
	* API for Artist List
	*/
	public function ArtistList(){
		$users = DB::table('profiles')->where('Type', 'Artist')->select('ProfileId','profile_description','profile_path','Name','VideoPrice')->get();
		return $users;
	}
	/*
	* API for Profile Details
	*/
	public function ProfileList(Request $request){
		$users = DB::table('profiles')->where('ProfileId', $request->ProfileId)->select('ProfileId','Name','EmailId','Address','City','State','PaypalId','RegisterDate','LastLogin','Zip','profile_path','BannerImg','NickName','Gender','DateOfBirth','MobileNo','profile_description','Type','country','created_at','VideoPrice','timestamp')->get();
	return $users;
	}
	/*
    * API for Video List According to video id
    */
    public function VideoDetails(Request $request){
    	$videoDetails = DB::table('video')->where('VideoId', $request->videoId)->select('VideoId','VideoFormat','VideoLength','VideoSize','VideoURL','VideoThumbnail','UploadedBy','ProfileId','VideoPrice' ,'VideoUploadDate','Description','Title','comments','review')->get();
    	return json_encode($videoDetails);
    }
	/*
	* API for push notification
	*/
	public function push(Request $request){
	$passphrase = '12345';
  // Put your alert message here:
  //This message will popup on user device
$deviceToken = 'ffd93ec07d8e92aeb72051ef75fd844477d663ff6e1e84d88420babf68f90d1e';
$ctx = stream_context_create();
$test = stream_context_set_option($ctx, 'ssl', 'local_cert', 'VRL.pem');
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
  // Open a connection to the APNS server
$fp = stream_socket_client(
 'ssl://gateway.sandbox.push.apple.com:2195', $err,
 $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
  // Create the payload body
$body['aps'] = array(
 'alert' => 'Testing notification',
 'sound' => 'default',
 'badge' => 1,
      // 'type' =>  'Newsroom',
 );
   // Encode the payload as JSON
$payload = json_encode($body);
  // Build the binary notification
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
  // Send it to the server
$result = fwrite($fp, $msg, strlen($msg));
if (!$result) {
 return '0'; 
}
else {
  return '1'; 
}
  // Close the connection to the server
fclose($fp);
	}
	/*
    * API for Video Request
    */
    public function unlogin_user_RequestVideo(Request $request){
    	
		$user_favorites = DB::table('users')
	    ->where('email', '=', $request->email)
	    ->first();
	    if (! is_null($user_favorites)) {
	    	if($user_favorites->type=='Artist'){
				
				$response = array('return_message' => "You can not send request by this email");
			return json_encode($response);
			}
			elseif($user_favorites->type=='User'){
				$artist = DB::table('profiles')
	    		->where('ProfileId', '=', $request->requestToProfileId)
	    		->first();
	    		$timestamp = date('m-d-Y',strtotime(Carbon::now()->format('m-d-Y')));
				$complitionDate =  date('m-d-Y', strtotime($timestamp. ' + '.$artist->timestamp. 'days'));
				$Requestvideo= new Requestvideo();
		    	$Requestvideo->Name=$request->Name;
		    	$Requestvideo->requestToProfileId=$request->requestToProfileId;
		    	$Requestvideo->RequestStatus="Pending";
		    	$Requestvideo->Description=$request->Description;
		    	$Requestvideo->ReqVideoPrice=$artist->VideoPrice;
		    	$Requestvideo->Title=$request->Title;
				$Requestvideo->requestByProfileId=$user_favorites->profile_id;
		    	$Requestvideo->requestor_email=$request->email;
		    	$mydate = date('d-m-Y');
				$daystosum = $artist->timestamp;
				$datesum = date('d-m-Y', strtotime($mydate.' +'.$daystosum.' days'));
		    	$Requestvideo->complitionDate=$datesum;
				$Requestvideo->request_date=Carbon::now();
		    	
		    	if($Requestvideo->save()){
		    		$confirmation_code['user_email'] =$request->email;
					$confirmation_code['video_title'] =$request->Title;
					$confirmation_code['video_description'] = $request->Description;
					$confirmation_code['current_status'] = "Pending";
					$artist =  Profile::where('ProfileId',$request->requestToProfileId)->first();
					
					$mydate = date('d-m-Y');
					$daystosum = $artist->timestamp;
					$datesum = date('d-m-Y', strtotime($mydate.' +'.$daystosum.' days'));
					$confirmation_code['video_delivery_time'] = $daystosum;
					
					$confirmation_code['artist_name']=$artist->Name;
					$confirmation_code['artist_email']=$artist->EmailId;
					$confirmation_code['username']=$request->Name;
					$confirmation_code['timeStamp']=$artist->timestamp;
					//dd($artist->timestamp);
					$confirmation_code['password']=Hash::make(str_random(8));
					Mail::send('emails.exist_User_RequestNewVideo', $confirmation_code, function ($message) use ($request) {
						$message->from('noreply@videorequestline.com', 'Confirmation for Video Request');
						$message->to($request->email, $request->email);
						$message->subject('Successfully requested video');
					});
					Mail::send('emails.admin_RequestNewVideo', $confirmation_code, function ($message) use ($request) {
						$artist =  Profile::where('ProfileId',$request->requestToProfileId)->first();
						//$confirmation_code['artist_email']=$artist->EmailId;
						$message->from('noreply@videorequestline.com', 'Confirmation for Video Request');
						$message->to($artist->EmailId, $request->user_email);
						$message->subject('Requested New video');
					});
		    	}
				$response = array('return_message' => "Your Request have been Submitted Successfully.Please check you Email to see details");
				return json_encode($response);
			}
	    }else{
	    	$artist = DB::table('profiles')
    		->where('ProfileId', '=', $request->requestToProfileId)
    		->first();
	    	$Requestvideo= new Requestvideo();
	    	$Requestvideo->Name=$request->Name;
	    	$Requestvideo->requestToProfileId=$request->requestToProfileId;
	    	$Requestvideo->RequestStatus="Pending";
	    	$Requestvideo->Description=$request->Description;
	    	$Requestvideo->ReqVideoPrice=$artist->VideoPrice;
	    	$Requestvideo->Title=$request->Title;
	    	$Requestvideo->requestor_email=$request->email;
	    	$mydate = date('d-m-Y');
			$daystosum = $artist->timestamp;
			$datesum = date('d-m-Y', strtotime($mydate.' +'.$daystosum.' days'));
	    	$Requestvideo->complitionDate=$datesum;
	    	//$Requestvideo->complitionDate=$request->RequestDate;
			$Requestvideo->request_date=Carbon::now();
			$Requestvideo->save();
			$Profile = new Profile();
			$users = new User();
			$users->user_name= $request->Name;
			$users->email= $request->email;
			$password=str_random(10);
			$users->password= Hash::make($password);
	        $users->type = 'User';
			$users->is_account_active='1';
			$users->is_email_active='1';
			$users->phone_no  =$request->phone;
			$Profile->EmailId= $request->email;
			$Profile->MobileNo = $request->phone;
			$Profile->Address= $request->city;
			$Profile->Type = "User";
			$Profile->Name=$request->Name;
			$Profile->City=$request->city;
			$Profile->Zip=$request->zip;
			$Profile->State=$request->state;
			$Profile->country=$request->country;
			$Profile->save();
			$users->save();
			$user_id = DB::table('profiles')->where('EmailId',$request->email)->first();
        	$profId= $user_id->ProfileId;
			DB::table('users')
	        ->where('email', $request->email)
	        ->update(['profile_id' => $profId]);
	        DB::table('requestvideos')
	        ->where('requestor_email',$request->email)
	        ->update(array('requestByProfileId' => $profId ));
	        $confirmation_code['user_email'] =$request->email;
			$confirmation_code['video_title'] =$request->Title;
			$confirmation_code['video_description'] = $request->Description;
			$confirmation_code['current_status'] = "Pending";
			$artist =  Profile::where('ProfileId',$request->requestToProfileId)->first();
			$mydate = date('d-m-Y');
			$daystosum = $artist->timestamp;
			$datesum = date('d-m-Y', strtotime($mydate.' +'.$daystosum.' days'));
			$confirmation_code['video_delivery_time'] = $daystosum;
			
			$confirmation_code['artist_name']=$artist->Name;
			$confirmation_code['artist_email']=$artist->EmailId;
			$confirmation_code['username']=$request->Name;
			$confirmation_code['timeStamp']=$artist->timestamp;
			$confirmation_code['password']=$password;
			Mail::send('emails.User_RequestNewVideo', $confirmation_code, function ($message) use ($request) {
					$message->from('noreply@videorequestline.com', 'Confirmation for Video Request');
					$message->to($request->email, $request->email);
					$message->subject('Successfully requested video');
			});
			Mail::send('emails.admin_RequestNewVideo', $confirmation_code, function ($message) use ($request) {
				$artist =  Profile::where('ProfileId',$request->requestToProfileId)->first();
				//$confirmation_code['artist_email']=$artist->EmailId;
				$message->from('noreply@videorequestline.com', 'Confirmation for Video Request');
				$message->to($artist->EmailId, $request->EmailId);
				$message->subject('Requested New video');
			});
			$response = array('return_message' => "Your Request have been Submitted Successfully.Please check you Email to see details");
			return json_encode($response);
    	}
	}
    /*
    * API for Video Request
    */
    public function RequestVideo(Request $request){
    	$Status="Pending";
        $type='User';
        $password=str_random(8);
        $hashed_random_password = Hash::make(str_random(8));
        $user_favorites = DB::table('users')->where('email', '=', $request->user_email)->first();
        //dd($user_favorites);
        if (! is_null($user_favorites)) { 
            if($user_favorites->type=='Artist'){
            	//echo "Artist";
                $response = array('return_message' => "Artist can't send request by this email");
				return json_encode($response);
            }else{
            	$Requestvideo= new Requestvideo();
            	//$requesttoprofile=Profile::where('ProfileId',$request->ArtistProfileId)->first();
                $Requestvideo->requestToProfileId=$request->ArtistProfileId;
                $Requestvideo->song_name=$request->song_name;
                $Requestvideo->Name=$request->user_name;
            	$requestbyprofile=Profile::where('EmailId',$request->user_email)->first();
                $Requestvideo->requestByProfileId=$requestbyprofile->ProfileId;
                $Requestvideo->receipient_pronunciation=$request->pronun_name;
                $Requestvideo->requestor_email=$request->user_email;
                $Requestvideo->sender_name=$request->sender_name;
                //$Requestvideo->sender_name_pronunciation=$request->sender_name_pronun;
                $Requestvideo->sender_email=$request->sender_email;
                $Requestvideo->complitionDate=$request->delivery_date;
                $Requestvideo->Title=$request->Occassion;
                $Requestvideo->Description=$request->person_message;
                $Requestvideo->RequestStatus=$Status;
                $Requestvideo->is_active=1;
                $Requestvideo->request_date=Carbon::now()->format('m-d-Y');
                $artist =  Profile::where('ProfileId',$request->ArtistProfileId)->first();
                $users = DB::table('profiles')->where('EmailId',$request->user_email)->first();
                $profId= $users->ProfileId;
                $Requestvideo->ReqVideoPrice=$artist->VideoPrice;
                $mydate = date('m-d-Y');
                $profname= $user_favorites->user_name;
                if(DB::table('requestvideos')->where('requestor_email',$request->user_email)->update(array('requestByProfileId' => $profId ))){
                	
				}else{
				}
                if($Requestvideo->save()){
                	$confirmation_code['user_email'] =$request->user_email;
                    $confirmation_code['video_title'] =$request->Occassion;
                    $confirmation_code['video_description'] = $request->person_message;
                    $confirmation_code['current_status'] = $Status;
                    $confirmation_code['video_delivery_time'] = $artist->timestamp;
                    $confirmation_code['artist_name']=$artist->Name;
                    $confirmation_code['artist_email']=$artist->EmailId;
                    $confirmation_code['username']=$user_favorites->user_name;
                    $confirmation_code['password']=$password;
                    Mail::send('emails.exist_User_RequestNewVideo', $confirmation_code, function ($message) use ($request) {
                       $message->from('noreply@videorequestline.com', 'Confirmation for Video Request');
                       $message->to($request->user_email, $request->user_email);
                       $message->subject('Successfully requested video');
                    });
                    Mail::send('emails.admin_RequestNewVideo', $confirmation_code, function ($message) use ($request) {
                       $artist =  Profile::where('ProfileId',$request->ArtistProfileId)->first();
                       $message->from('noreply@videorequestline.com', 'Confirmation for Video Request');
                        $message->to($artist->EmailId, 'codingbrains6@gmail.com');
                        $message->subject('Requested New video');
                    });

						$target = "pron_name".'/'.$_FILES['sender_name_pronun']['name'];
						if (move_uploaded_file( $_FILES["sender_name_pronun"]["tmp_name"],$target)) {

						DB::table('requestvideos')->where('requestor_email',$request->user_email)->update(['sender_voice_pronunciation' => $target]);
							
						}else {
							$response = array('response_code' => "500",'return_message' => "File not Updated ! Please Try Again With different file");
							return json_encode($response); 
						}

						$target1 = "pron_name".'/'.$_FILES['pronun_name']['name'];
						if (move_uploaded_file( $_FILES["pronun_name"]["tmp_name"],$target1)) {

						DB::table('requestvideos')->where('requestor_email',$request->user_email)->update(['receipient_voice_pronunciation' => $target1]);
							 
						}else {
							$response = array('response_code' => "500",'return_message' => "File not Updated ! Please Try Again With different file");
							return json_encode($response); 
						}

                    	$passphrase = '12345';
						$user_detail=DB::table('users')->where('profile_id','=',$request->ArtistProfileId)->first();
						$deviceToken =$user_detail->device_token;
						if($deviceToken!=''){
						$ctx = stream_context_create();
						
						$test = stream_context_set_option($ctx, 'ssl', 'local_cert', 'VRL.pem');
						
						stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
						
						$fp = stream_socket_client(
						 'ssl://gateway.sandbox.push.apple.com:2195', $err,
						 $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
						
						$body['aps'] = array(
						 'alert' => 'You have receive a video request by '.$request->sender_name ,
						 'sound' => 'default',
						 'badge' => 1,
						 );
						
						$payload = json_encode($body);
						
						$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
						
						$result = fwrite($fp, $msg, strlen($msg));
						fclose($fp);
						$response = array('response_code' => "200",'return_message' => "Your Request have been Submitted Successfully.Please check you Email to see details");
						return json_encode($response); 
					}else{
						$response = array('response_code' => "500",'return_message' => "Device token not found");
						return json_encode($response);
					}
                   
                }else{
                	$response = array('response_code' => "500",'return_message' => "Your Request have been failed");
				return json_encode($response); 
                }
            }
        }else{
          	$Requestvideo= new Requestvideo();
            $Requestvideo->requestToProfileId=$request->artist;
            //$requesttoprofile=Profile::where('Name',$request->artist)->first();
            $Requestvideo->requestToProfileId=$request->ArtistProfileId;
            $Requestvideo->song_name=$request->song_name;
            $Requestvideo->Name=$request->user_name;
            $Requestvideo->receipient_pronunciation=$request->pronun_name;
            $Requestvideo->requestor_email=$request->user_email;
            $Requestvideo->sender_name=$request->sender_name;
            $Requestvideo->sender_name_pronunciation=$request->sender_name_pronun;
            $Requestvideo->sender_email=$request->sender_email;
            $Requestvideo->Title=$request->Occassion;
            $Requestvideo->Description=$request->person_message;
            $Requestvideo->RequestStatus=$Status;
            $Requestvideo->is_active=1;
            $Requestvideo->request_date=Carbon::now()->format('m-d-Y');
            $Profile = new Profile();
            $users = new User();
            $users->user_name= $request->user_name;
            $users->email= $request->user_email;
            $users->password= Hash::make($password);
            $users->type = $type;
            $users->is_account_active='1';
            $users->is_email_active='1';
            $Profile->EmailId= $request->user_email;
            $Profile->Type = $type;
            $Profile->Name=$request->user_name;
            $Profile->save();
            $users->save();
            $users = DB::table('profiles')->where('EmailId',$request->user_email)->first();
            $profId= $users->ProfileId;
            $artist_data= Profile::where('ProfileId',$request->ArtistProfileId)->first();
            $Requestvideo->complitionDate=$request->delivery_date;
            $Requestvideo->ReqVideoPrice=$artist_data->VideoPrice;
            $Requestvideo->save();
            $profname= $request->user_name;
            if(DB::table('users')->where('email', $request->user_email)->update(['profile_id' => $profId])){
            }else{
            }
            if(DB::table('requestvideos')->where('Name',$profname)->update(array('requestByProfileId' => $profId ))){
            }else{
            }
            if($Requestvideo->save()){
                $confirmation_code['user_email'] =$request->user_email;
                $confirmation_code['video_title'] =$request->Occassion;
                $confirmation_code['video_description'] = $request->person_message;
                $confirmation_code['current_status'] = $Status;
                $confirmation_code['video_delivery_time'] = $artist_data->timestamp;
                $artist =  Profile::where('ProfileId',$request->ArtistProfileId)->first();
                $confirmation_code['artist_name']=$artist_data->Name;
                $confirmation_code['artist_email']=$artist_data->EmailId;
                $confirmation_code['username']=$request->user_name;
                $confirmation_code['password']=$password;
                Mail::send('emails.User_RequestNewVideo', $confirmation_code, function ($message) use ($request) {
                    $message->from('noreply@videorequestline.com', 'Confirmation for Video Request');
                    $message->to($request->user_email, $request->user_email);
                    $message->subject('Successfully requested video');
                });
                Mail::send('emails.admin_RequestNewVideo', $confirmation_code, function ($message) use ($request) {
                    $artist =  Profile::where('ProfileId',$request->ArtistProfileId)->first();
                    $message->from('noreply@videorequestline.com', 'Confirmation for Video Request');
                    $message->to($artist->EmailId, 'Artist');
                    $message->subject('Requested New video');
                });
                //
                    	$passphrase = '12345';
						$user_detail=DB::table('users')->where('profile_id','=',$request->ArtistProfileId)->first();
						$deviceToken =$user_detail->device_token;
						if($deviceToken!=''){
						$ctx = stream_context_create();
						
						$test = stream_context_set_option($ctx, 'ssl', 'local_cert', 'VRL.pem');
						
						stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
						
						$fp = stream_socket_client(
						 'ssl://gateway.sandbox.push.apple.com:2195', $err,
						 $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
						
						$body['aps'] = array(
						 'alert' => 'You have receive a video request by '.$request->sender_name ,
						 'sound' => 'default',
						 'badge' => 1,
						 );
						
						$payload = json_encode($body);
						
						$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
						
						$result = fwrite($fp, $msg, strlen($msg));
						//if (!$result) {
							// return '0'; 
							//}
							//else {
							//  return '1'; 
							//}
						fclose($fp);
						$response = array('response_code' => "200",'return_message' => "Your Request have been Submitted Successfully.Please check you Email to see details...");
						return json_encode($response);
					}else{
						$response = array('response_code' => "200",'return_message' => "Device token not found");
						return json_encode($response);
					}
                    //
                 
            }else{
            	$response = array('response_code' => "500",'return_message' => "Your Request have been failed");
				return json_encode($response); 
            }

        }    
    }
    /*
    * API for Video List According to Profile id
    */
    public function VideoList(Request $request){
    	$videoDetails = DB::table('video')->where('ProfileId', $request->profileId)->select('*')
    	->get();
    	$profiles= DB::table('profiles')->where('ProfileId', $request->profileId)->select('*')
    	->get();
    	$data['profile']=$profiles;
    	//if(!$videoDetails){
    	$data['video']=$videoDetails;
    	//}
    	return json_encode($data);    
    }
    
    /*
    * API for request List
    */
    public function requestList(Request $request){
    	$requestList = DB::table('requestvideos')->
    	where('requestor_email', $request->email)->
    	select('*')->get();
    	return json_encode($requestList);
    }
    /*
    * API for request List by requesttoProfileId
    */
    public function requestToList(Request $request){
    	$requestList = DB::table('requestvideos')
    	->join('profiles', 'profiles.ProfileId', '=', 'requestvideos.requestToProfileId')
    	->select('requestvideos.*','profiles.*')
    	->where('requestvideos.requestToProfileId','=', $request->requestToProfileId)
    	->get();
    	
    	return json_encode($requestList);
    	
    }
    /*-------------API for Accepting Video Request-------------------*/
public function accept_video_request($id,$artist_id,$user_id)
{
		$requestvideo = Requestvideo::find($id);
		$artist =  Profile::find($artist_id);
		$data['artist']=$artist->Name;
		$data['user']=$requestvideo->Name;
		$data['price']=$artist->VideoPrice;
		$data['complitionDate']=$requestvideo->complitionDate;
		$requestvideo->RequestStatus = "Approved";
		$requestvideo->ReqVideoPrice = $artist->VideoPrice;
		$user_email = $requestvideo->requestor_email;
		//dd($requestvideo);
		if($requestvideo->save()){
				$user = $user_email;
				Mail::send('emails.video_response', $data, function ($message) use ($requestvideo) {
					$message->from('noreply@videorequestline.com','VRL');
					$message->to($requestvideo->requestor_email,'User');
					$message->cc('noreply@videorequestline.com', 'Super Administrator');
					$message->subject('Your Request have been Approved Successfully by Artist');
				});
			$passphrase = '12345';
			$user_detail=DB::table('users')->where('profile_id','=',$user_id)->first();
			$deviceToken =$user_detail->device_token;
			$ctx = stream_context_create();
			$test = stream_context_set_option($ctx, 'ssl', 'local_cert', 'VRL.pem');
			stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
			$fp = stream_socket_client(
			 'ssl://gateway.sandbox.push.apple.com:2195', $err,
			 $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
			$body['aps'] = array(
			 'alert' => 'Your Video Request has been Approved by '.$artist->Name ,
			 'sound' => 'default',
			 'badge' => 1,
			 );
			$payload = json_encode($body);
			$msg = chr(0).pack('n',32).pack('H*',$deviceToken).pack('n',strlen($payload)).$payload;
			//dd($msg);
			$result = fwrite($fp, $msg, strlen($msg));
			fclose($fp);
			$response = array('return_message' => "Approved");
				return json_encode($response);
		}
		else{
			$response = array('return_message' => " not Approved");
				return json_encode($response);
		}
}
    /*-------------API for Rejecting Video Request-------------------*/
public function reject_video_request($id,$artist_id,$user_id)
{
	
		$requestvideo = Requestvideo::find($id);
		$artist =  Profile::find($artist_id);
		$data['artist']=$artist->Name;
		$data['user']=$requestvideo->Name;
		$data['price']=$artist->VideoPrice;
		$data['complitionDate']=$requestvideo->complitionDate;
		$requestvideo->RequestStatus = "Reject";
		$requestvideo->ReqVideoPrice = $artist->VideoPrice;
		$user_email = $requestvideo->requestor_email;
		if($requestvideo->save()){
				$user = $user_email;
				Mail::send('emails.video_response', $data, function ($message) use ($user) {
					$message->from('noreply@videorequestline.com','VRL');
					$message->to($user,'User');
					$message->cc('noreply@videorequestline.com', 'Super Administrator');
					$message->subject('Your Request have been Approved Successfully by Artist');
				});
			
			
			$passphrase = '12345';
			$user_detail=DB::table('users')->where('profile_id','=',$user_id)->first();
			$deviceToken =$user_detail->device_token;
			$ctx = stream_context_create();
			
			$test = stream_context_set_option($ctx, 'ssl', 'local_cert', 'VRL.pem');
			
			stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
			
			$fp = stream_socket_client(
			 'ssl://gateway.sandbox.push.apple.com:2195', $err,
			 $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
			
			$body['aps'] = array(
			 'alert' => 'Your Video Request has been Rejected by '.$artist->Name ,
			 'sound' => 'default',
			 'badge' => 1,
			 );
			
			$payload = json_encode($body);
			
			$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
			
			$result = fwrite($fp, $msg, strlen($msg));
			//if (!$result) {
				// return '0'; 
				//}
				//else {
				//  return '1'; 
				//}
			fclose($fp);
			$response = array('return_message' => "Rejected");
				return json_encode($response);
		}
		else{
			$response = array('return_message' => " not Rejected");
				return json_encode($response);
		}
}
	
	public function UpdateProfile(Request $request){
		$updateType = $request->update;
		if($updateType=="Profile"){
			if(Profile::where('ProfileId',$request->profileId)->update(array('Name' => $request->username,'EmailId' => $request->email,'DateOfBirth' => $request->dob,'MobileNo' => $request->phone,'NickName' => $request->NickName,'Address' => $request->Address,'City' => $request->City,'State' => $request->State,'country' => $request->Country,'Zip' => $request->Zip,'Gender' => $request->gender,'PaypalId'=>$request->PaypalId,'profile_description'=>$request->Description,'timestamp'=>$request->timestamp,'VideoPrice'=>$request->price))){
				$flag=1;
				if(DB::table('users')->where('profile_id', $request->profileId)->update(['user_name' => $request->username])){
					$flag=1;  
				}
				if($flag==1){
					$response = array('return_message' => "Update Successfully ");
					return json_encode($response);
				}	       		
			}else{
				$response = array('return_message' => "Not Updated");
				return json_encode($response);
			}
		}
		if($updateType=="Picture"){
			if($request->type=="User"){
				$target = "images".'/'.'User'.'/'.$_FILES['media']['name'];
			}else{
				 $target = "images".'/'.'Artist'.'/'.$_FILES['media']['name'];
			}
			if (move_uploaded_file( $_FILES["media"]["tmp_name"],$target)) {
				/*list($current_width, $current_height) = getimagesize($filename);
				$left = 0; $top  = 0;
				$crop_width = $current_width;
				$crop_height = $current_height;
				$canvas = imagecreatetruecolor($crop_width, $crop_height);
				$current_image = imagecreatefromjpeg($filename);
				imagecopy($canvas, $current_image, 0, 0, $left, $top, $current_width, $current_height);
				imagejpeg($canvas, $filename, 100);*/
				
				if(Profile::where('ProfileId',$request->profileId)->update(array('profile_path'=>$target))){
					$msg='Updated successfully';
					return json_encode($target);
				}else{
					$msg='Updated not successfully';
					return json_encode($msg);
				}	
			}else {
				$msg='Image not Updated ! Please Try Again With different Image';
				return json_encode($msg);
			}
		}
		if($updateType=="Banner"){
			if($request->type=="User"){
				$target = "banner".'/'.'User'.'/'. $_FILES['media']['name'];
			}else{
				$target = "banner".'/'.'Artist'.'/'.$_FILES['media']['name'];
			}
			if (move_uploaded_file( $_FILES["media"]["tmp_name"],$target)) {
				/*$filename = $target;
				list($current_width, $current_height) = getimagesize($filename);
				$left = 0; $top  = 0;
				$crop_width = $current_width;
				$crop_height = $current_height;
				$canvas = imagecreatetruecolor($crop_width, $crop_height);
				$current_image = imagecreatefromjpeg($filename);
				imagecopy($canvas, $current_image, 0, 0, $left, $top, $current_width, $current_height);
				imagejpeg($canvas, $filename, 100);
				$msg=$target;
				$profilePath=$msg;*/
				if(Profile::where('ProfileId',$request->profileId)->update(array('BannerImg'=>$target))){
					$msg='Updated successfully';
					return json_encode($target);
				}else{
					$msg='Updated not successfully';
					return json_encode($msg);
				}	
			}else {
				$msg='Image not Updated ! Please Try Again With different Image';
				return json_encode($msg);
			}
		}      
	}

	/*
	* API for login
	*/
	public function artistlogin() {
		return view('frontend.test');
	}
	public function logins(Request $request){
		$Uname=$request->email;
		$Password=$request->password;
		$data = $request->all();
		$validator = Validator::make($data,
			array(
				'email' =>'required|email',
				'password' =>'required'
				)
			);
		if($validator->fails()){
		}else{
			$user = array('email' =>$Uname,'password' =>$Password);
		
			if(Auth::attempt($user)){
				$device_token = $request->device_token ;
				$device_type = $request->device_type ;
				$user_id = Auth::user()->user_id;
				//echo $device_type;
				//if (($device_token == $request->device_token) && ($device_type == $request->device_type)) {
					
				DB::table('users')->where('user_id',$user_id)->update(array('device_token'=>$device_token,'device_type'=>$device_type));
				//}
				//else{
					//DB::table('users')->where('user_id',$user_id)->update(array('device_token'=>$request->device_token,'device_type'=>$device_type));
				//}
				$type = Auth::user()->type;
				$userName = Auth::user()->user_name;
				$profileId = Auth::user()->profile_id;
				$users = DB::table('profiles')->where('ProfileId',$profileId)->first();
				if($users->profile_path==""){
					$prof_img = "http://videorequestlive.com/images/Artist/default.jpg";
				}else{
					$prof_img = $users->profile_path;
				}
				$prof_id=$users->ProfileId;
				$arrayName = array('ProfileId'=>$prof_id,'profile_path'=>$prof_img,'Name'=>$userName,'Email' =>$Uname ,'Password' =>$Password , 'Type' =>$type ,'Status' =>'Ok' , 'Error' =>'No Error' );
				print_r(json_encode($arrayName));
			}
			else{
				$CheckEmail = User::where('email',$Uname)->get();
				$CheckPass = User::where('password',$Password)->get();
				if(count($CheckEmail) == 0){
					$arrayNames = array('Status' =>'Not Ok' , 'Error' =>'Email does Not Match' );
					print_r(json_encode($arrayNames));
				}else if(count($CheckPass) == 0){
					$arrayNames = array('Status' =>'Not Ok' , 'Error' =>'Password does Not Match' );
					print_r(json_encode($arrayNames));  
				}
				else{
					$arrayNames = array('Status' =>'Not Ok' , 'Error' =>'Email does Not Match' );
					print_r(json_encode($arrayNames)); 
				}
			}
	}
}
	/*
	* API Code for Registration
	*/
	public function apiregister(Request $request){
		
		$email = User::where('email',$request->email)->get();
		if(count($email) == 0){
			$users = new User();
			$Profile = new Profile();
			$status=1;
			$users->user_name= $request->username;
			$Profile->Name= $request->username;
			$users->email= $request->email;
			$Profile->EmailId= $request->email;
			$users->password= Hash::make($request->password);
			$users->remember_token = $request->_token;
			$users->is_account_active = "1";
			$users->is_email_active = "1";
			$users->type = $request->type;
			$Profile->Type = $request->type;
			$users->gender = $request->gender;
			$Profile->Gender = $request->gender;
			$users->date_of_birth = $request->dob;
			$users->device_token = $request->device_token;
			$Profile->DateOfBirth = $request->dob;
			$Profile->VideoPrice = 30;
			$profile_path="images/Artist/default-artist.png";
			$Profile->profile_url= strtolower($request->username);
			$Profile->profile_path= $profile_path;
			$Profile->BannerImg ="images/vrl_bg.jpg";
			$Profile->header_image ="/images/default_header.jpg";
			$Profile->video_background ="images/vrl_bg.jpg";

			$users->save();
			$Profile->save();
			$users = DB::table('profiles')->where('EmailId',$request->email)->first();
			$profId= $users->ProfileId;
			DB::table('users')
			->where('email', $request->email)
			->update(['profile_id' => $profId]);
			$confirmation_code=array();
			$confirmation_code['email'] =$request->email;
			$confirmation_code['password'] =$request->password;
			Mail::send('emails.api_reminder',$confirmation_code, function ($message) use ($request) {
				$message->from('noreply@videorequestline.com', 'Confirmation Register');
				$message->to($request->email, $request->username);
				$message->subject('Your are successfully register on vrl');
			});
			$response = array('token'=>$profId,'return_message' => "success");
			return json_encode($response);
		}
		else
		{
			$response = array('return_message' => "Email exists");
			return json_encode($response);
		}
	}
	/*
	* API code formCheck Email Exists
	*/
	public function CheckEmailExists(Request $request){
		$email = User::where('email',$request->email)->get();
		if(count($email) == 0)
		{
			$response = array('return_message' => "NO Email Found");
			return json_encode($response);
		}else{
			$response = array('return_message' => "An Email Founded");
			return json_encode($response);
		}
	}
	/*
	* API code  for forget Password
	*/
	public function apiforget(Request $request){
		
		///$response=$request->email_id;
		///$response="ra";
		
			$email = DB::table('profiles')->where('EmailId',$request->email)->first();
			$auth_pass = str_random(15);
			$confirmation_code['confirmation_code'] = encrypt($auth_pass);
			
			DB::table('users')->where('email', $request->email)->update(array('auth_reset_pass' => $auth_pass));
		
			if(count($email) > 0){
		
				Mail::send('emails.forget_reminder',$confirmation_code, function ($message) use ($request) {
		
					$message->from('noreply@videorequestline.com', 'Reset Password');
		
					$message->to($request->email,'rajesh');
		
					$message->subject('Reset Password');
				
				});
				$responses = array('return_message' => "Please Check Your Email to get Password");
			}else{
				
				$responses = array('return_message' => "The email field is required");
				//echo "Not send"
			}
			
			
			return json_encode($responses);
		
	}
	/*
	* API for Video List According to profile id and paid=yes
	*/
	public function VideoListByProfileId(Request $request){
		$videoDetails = DB::table('requestvideos')
		->join('requested_videos', 'requested_videos.request_id', '=', 'requestvideos.VideoReqId')
		->select('*')
		->where('requestvideos.requestByProfileId','=', $request->profileId)
		->where('requestvideos.RequestStatus', '=' ,'Completed')
		->get();
		//$videoDetails="test";
		return json_encode($videoDetails);
	}
    /*
    * API For Sale details
	*/
    public function ArtistSaleDetails(Request $request){
    	/*$videoDetails = DB::table('video')
    	->join('requestvideos', 'video.VideoId', '=', 'requestvideos.VideoId')
    	->select('VideoThumbnail')
    	->where('requestvideos.requestByProfileId','=', $request->profileId)
    	->where('requestvideos.paid', '=' ,'yes')
    	->get();*/
    	$videoDetails="test";
    	return json_encode($videoDetails);
    }
	
	/*---------------------------Artist sales details-------------------------*/
public function sales($artist_id){
	
	$noOfRequests =Requestvideo::where('requestToProfileId',$artist_id)->get();
	$noOfCompleteRequests = Requestvideo::where('requestToProfileId',$artist_id)->where('RequestStatus','Completed')->get();
	$amounts = Requestvideo::where('requestToProfileId',$artist_id)->where('RequestStatus','Approved')->where('paid','Paid')->get();
	$total_amount =0;
	foreach($amounts as $amount){
	$total_amount = $total_amount+ $amount->ReqVideoPrice ;
	}
	$amountsPaidToArtist = DB::table('admin_payments')->where('payment_to',$artist_id)->where('status','paid')->get();
	$total_amount_paid =0;
	foreach($amountsPaidToArtist as $amountPaidToArtist){
	$total_amount_paid = $total_amount_paid+ $amountPaidToArtist->paid_amount ;
	}
	$message['noOfRequests'] = count($noOfRequests);
	$message['noOfCompleteRequests'] = count($noOfCompleteRequests);
	$message['total_amount_gathered'] = $total_amount;
	$message['total_amount_paid'] = $total_amount_paid;
	$response = array('return_message' => $message);
	return json_encode($response);
	
	
	}
	
	public function upload_videos(Request $request){
		
		//dd($request->video);
		
		$requested_video = new RequestedVideo();
		$file = $request->file('video');
		$extension = $file->getClientOriginalExtension();
		$filename = str_replace(' ', '', $file->getClientOriginalName());
		$filename = str_replace('-', '', $filename);
		$VideoURL = "http://videorequestlive.com/requested_video/";
		$requested_video->description = $request->requested_video_description;
		$requested_video->title = $request->requested_video_title;
		$requested_video->request_id = $request->requested_video_id;
		$requested_video->requestby	 = $request->requestedby;
		$requested_video->uploadedby	 =$request->uploadedby;
		$rand = rand(11111,99999).date('U');
		$destination = base_path() . '/public/requested_video/';
		$fileName = $rand.'.'.$extension;
		$request->file('video')->move($destination,$fileName);
		$destination_path= $destination.$fileName;
		$requested_video->url	 =$VideoURL.$fileName;
		$ffmpeg = FFMpeg\FFMpeg::create(array(
					'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
					'ffprobe.binaries' => '/usr/bin/ffprobe',
					'timeout'          => 3600,
					'ffmpeg.threads'   => 12,
					));		
		$uploaded_video = $ffmpeg->open($destination.$fileName);
		$ffprobe = FFMpeg\FFProbe::create(array(
			'ffmpeg.binaries'  => '/usr/lbin/ffmpeg',
			'ffprobe.binaries' => '/usr/bin/ffprobe',
			'timeout'          => 3600,
			'ffmpeg.threads'   => 12,
			));
		/*--------------------retrieving Video Duration--------------------*/
		$video_length = $ffprobe->streams($destination.$fileName)
		->videos()
		->first()
		->get('duration');
		if($video_length < 15){
			$message = "Video duration must be above 15 seconds";
				$response = array('return_message' => $message);
			return json_encode($response);
			}
			else{
		/*-----------------------retrieving Thumbnail----------------------*/
		$video_thumbnail_path = base_path() . '/public/requested_video/thumbnail/'.date('U').'.jpg';
		$uploaded_video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(2))->save($video_thumbnail_path);
		$requested_video->thumbnail	 = $video_thumbnail_path;
		if($requested_video->save())
		{
			$video_requests = Requestvideo::find($request->requested_video_id);
			$video_requests->RequestStatus = "Completed";
			$video_requests->save();
			$video_requests = Requestvideo::find($request->requested_video_id);
			$data['video_name'] =$fileName;
			$data['thumbnail'] =$video_thumbnail_path;
			$data['video_title'] =$request->requested_video_title;
			$data['video_description'] = $request->requested_video_description;
			
				Mail::send('emails.download_email', $data, function ($message) use ($request) {
					$message->from('noreply@videorequestline.com', 'Download Video');
					$message->to($request->user_email, $request->user_email);
					$message->subject('Please download Your requested Video');
				});
				$artist = Profile::find($request->uploadedby);
				$user_detail=DB::table('users')->where('profile_id','=',$request->requestedby)->first();
				$admin_data['user_name'] = $user_detail->user_name;
				$admin_data['artist_name'] = $artist->Name;
				$admin_data['video_price'] = $artist->VideoPrice;
				$admin_data['video_title'] = $request->requested_video_title;
				$admin_data['video_description'] = $request->requested_video_description;
				$admin_data['video_completion'] = $video_requests->ComplitionDate;
				$admin_data['thumbnail'] =$video_thumbnail_path;
				Mail::send('emails.admin_download_email', $admin_data, function ($message) use ($request) {
					$message->from('noreply@videorequestline.com', 'Video Upload');
					$message->to('admin@videorequestline.com', 'admin@videorequestline.com');
					$message->subject('Artist Uploaded Video To user');
				});
				$artist_data['user_name'] = $user_detail->user_name;
				$artist_data['video_price'] = $artist->VideoPrice;
				$artist_data['video_title'] = $request->requested_video_title;
				$artist_data['video_description'] = $request->requested_video_description;
				$artist_data['video_completion'] = $video_requests->ComplitionDate;
				$artist_data['thumbnail'] =$video_thumbnail_path;
				
				Mail::send('emails.artist_download_email', $artist_data, function ($message) use ($request) {
					$message->from('noreply@videorequestline.com', 'Download Video');
					$message->to($request->artist_email,$request->artist_email);
					$message->subject('Video Uploaded Successfully');
				});
				$passphrase = '12345';
				$deviceToken =$user_detail->device_token;
				$ctx = stream_context_create();
				$test = stream_context_set_option($ctx, 'ssl', 'local_cert', 'VRL.pem');
				stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
				$fp = stream_socket_client(
				 'ssl://gateway.sandbox.push.apple.com:2195', $err,
				 $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
				$body['aps'] = array(
				 'alert' => 'Your Requested Video Is completed by '.$artist->Name.'. Please Check Your email',
				 'sound' => 'default',
				 'badge' => 1,
				 );
				$payload = json_encode($body);
				$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
				$result = fwrite($fp, $msg, strlen($msg));
				fclose($fp);
				$message = array();
				$message['response'] = "Success";
				$message['deviceToken'] =$deviceToken ;
				$response = array('return_message' => $message);
	return json_encode($response);
		}
		}
	}
}//End Class
