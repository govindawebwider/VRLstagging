<?php
namespace App\Http\Controllers;
use Excel;
use Auth;
use App\User;
use Illuminate\Http\Request;

use Illuminate\Routing\Controller;
use DB;
use FFMpeg;
use App\Http\Requests;
use Hash;
use Validator;
use Carbon\Carbon;
use Mail;
use App\Profile;
use App\Video;
use App\OriginalVideo;
use App\Requestvideo;
use Session;
use Crypt;
class ArtistController extends Controller
{
	public function test()
	{
		phpinfo();
	}
	public function artist_videos(){
		if(Auth::check()){
			if(Auth::user()->type=="Artist" || (Auth::user()->type=="Admin" || session('current_type') == "Artist")){
				$user =  User::where('email',Auth::user()->email)->first();
				$videos = DB::table('video')
				->join('profiles', function($join)
				{
					$join->on('profiles.ProfileId', '=', 'video.ProfileId')
					->where('profiles.ProfileId','=',Auth::user()->profile_id);
				})
				->orderBy('VideoId','desc')
				->paginate(15);
				$artist = Profile::find(Auth::user()->profile_id);
				$video_data['user'] = $user;
				$video_data['video'] = $videos;
				$video_data['artist'] = $artist;
				return view('frontend.artistDashboard.artist_videos',$video_data); 
			}else{
				return redirect('/login');
			}
		}else{
			return redirect('/login'); 
		} 
	}

	public function emailupdate(Request $request){
		$user = Auth::user();
		$current_user = DB::table('users')->where('email',$user->email)->first();

		if (Hash::check($request->password,$current_user->password)) {

			$is_exist = DB::table('users')->where('email',$request->reqEmail)->first();
			if(count($is_exist) == 0){
				$email = $request->reqEmail;
				$rand = rand(10000,10);
				$token = Crypt::encrypt($rand);
				DB::table('password_resets')->insert(['email'=>$user->profile_id,'token'=>$rand,'type'=>'emailupdate','newMail'=>$request->reqEmail]);
				Mail::send('emails.emailUpdate',['user'=>$user,'token'=>$token],function($msg) use ($request){
					$msg->from('noreply@videorequestline.com');
					$msg->to($request->reqEmail)->subject('Email update request.');
					$msg->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
				});
				$data['success']="success";
				$data['msg']="Email update request have been successfully send.";
			}else{
				$data['error']="error";
				$data['msg']="Email already exists.";
			} 

		}else{

			$data['error']="error";
			$data['msg']="Invailid Password ! Please enter correct password to update your email.";
		}	
		return json_encode($data);
	}

	public function emailreset($token){
		$rand = Crypt::decrypt($token);
		$is_token = DB::table('password_resets')->where('token',$rand)->where('type','emailupdate')->first();
		if(count($is_token)){
			$is_exist = DB::table('users')->where('email',$is_token->newMail)->first();
			if(count($is_exist) == 0){
				DB::table('users')->where('profile_id',$is_token->email)->update(['email'=>$is_token->newMail]);
				DB::table('profiles')->where('profileId',$is_token->email)->update(['EmailId'=>$is_token->newMail]);
				DB::table('password_resets')->where('token',$rand)->where('type','emailupdate')->delete();
				return redirect('/successMessage')->with('success','Email updated successfully');

			}else{
				return redirect('/successMessage')->with('error','Email already used.Please try with new email.');
			}

		}else{

			return redirect('/successMessage')->with('error','Token expire ! please create new email update token.');
		}
	}

	public function successMessage(){
		return view('frontend.successMessage');
	}

	public function artist_payment_detail(){
		if(Auth::check()){
			if(Auth::user()->type=="Admin" && session('current_type') == "Admin"){
				return redirect('admin_dashboard');
			}
			elseif (Auth::user()->type=="User" && session('current_type') == "User") {
				return redirect('profile');
			}
			else if (Auth::user()->type=="Artist" || (Auth::user()->type=="Admin" || session('current_type') == "Artist")){
				$user =  User::where('email',Auth::user()->email)->first();
				$artist = Profile::find(Auth::user()->profile_id);
				$payment_data['user'] = $user;
				$payment_dtl = DB::table('payments')->select('*')->where('payer_id','=',Auth::user()->profile_id)->join('requestvideos','requestvideos.VideoReqId','=','payments.video_request_id')->orderBy('id','desc')->get();
				$payment_data['payment'] = $payment_dtl;
				$payment_data['artist'] = $artist;
				return view('frontend.artistDashboard.artist_payment_detaile',$payment_data);
			}
		}
		else{
			return redirect(url()->previous());
		}
	}
	public function req_video_csv($artist_id){
		if(Auth::check()){
			if(Auth::user()->type=="Admin" && session('current_type') == "Admin"){
				return redirect('admin_dashboard');
			}
			elseif (Auth::user()->type=="User" && session('current_type') == "User") {
				return redirect('profile');
			}
			else if (Auth::user()->type=="Artist" || (Auth::user()->type=="Admin" || session('current_type') == "Artist")){
				$artist_req_video = Requestvideo::where('requestToProfileId',$artist_id)->get();
				Excel::create('Requested Video List', function($excel) use($artist_req_video) {
					$excel->sheet('Sheet 1', function($sheet) use($artist_req_video) {
						$sheet->fromArray($artist_req_video);
					});
				})->export('xls');
				redirect('video_requests');
			}
		}
		else{
			return redirect(url()->previous());
		}
	}
	/*-----------------------Artist delivered video---------------------------*/
	public function get_deliver_video(){
		if(Auth::check()){
			if(Auth::user()->type=="Artist" || (Auth::user()->type=="Admin" || session('current_type') == "Artist")){
				$deliver_videos = DB::table('requested_videos')->select('*')
				->join('profiles','profiles.ProfileId','=','requested_videos.uploadedby')
				->where('requested_videos.uploadedby','=',Auth::user()->profile_id)
				->paginate(15);
				$user =  User::where('email',Auth::user()->email)->first();
				$artist = Profile::find(Auth::user()->profile_id);
				$video_data['user'] = $user;
				$video_data['video'] = $deliver_videos;
				$video_data['artist'] = $artist;
				//dd($video_data);
				return view('frontend.artistDashboard.deliver_video',$video_data); 
			}else{
				return redirect('/login');
			}
		}else{
			return redirect('/login'); 
		} 
	}
	/*-----------------------Artist Testimonial---------------------------*/
	public function testimonial(Request $request){
		return view('artistDashboard.view_testimonial');
	}
	/*-----------------------Artist Registration------------------------------*/
	public function register(Request $request){
		$email = DB::table('profiles')->where('EmailId',$request->email)->first();
		if(count($email) > 0){
			return "exist";
		}
		else
		{
			$users = new User();
			$Profile = new Profile();
			$status=0;
			$type='Artist';
			$users->user_name= $request->username;
			$Profile->Name= $request->username;
			$users->email= $request->email;
			$Profile->EmailId= $request->email;
			$users->password= Hash::make($request->password);
			$users->remember_token = $request->_token;
			$users->status = $status;
			$users->type = $type;
			$Profile->Type = $type;
			$users->gender = $request->gender;
			$Profile->Gender = $request->gender;
			$users->date_of_birth = $request->dob;
			$Profile->DateOfBirth = $request->dob;
			$users->phone_no = $request->phone;
			$Profile->MobileNo = $request->phone;
			$users->save();
			$Profile->save();
			$users = DB::table('profiles')->where('EmailId',$request->email)->first();
			$profId= $users->ProfileId;
			DB::table('users')
			->where('email', $request->email)
			->update(['profile_id' => $profId]);
			return 'Success';
		}
	}

//Code for Request new video from artist prfile page
	public function RequestNewVideo($profile_id){
	
		$artist_data = \App\Profile::find($profile_id);
		if(is_null($artist_data)){
			return redirect('/view-all-artist')->with('error','Artist not found');
		}else{
			$artist_id =  User::where('profile_id',$profile_id)->first();
			if($artist_id->is_account_active==1)
			{
				if(Auth::check()){
					if(Auth::user()->type=="Artist"){
						return redirect( url()->previous());
					}else{
						$user = Profile::find(Auth::user()->profile_id);
						$req_detail = DB::table('requestvideos')->select('*')->where('requestByProfileId','=',Auth::user()->profile_id)->first();
						$video_list=DB::table('requested_videos')->select('*')->where('uploadedby','=',$profile_id)->get();
						$detail['req_detail'] = $req_detail;
						$detail['user_id'] = Auth::user()->profile_id;
						$detail['user_email'] = Auth::user()->email;
						$detail['phone'] = Auth::user()->phone_no;
						$detail['user'] = $user;
						$detail['is_login'] = 'Yes';
						$user_detail = Profile::find($profile_id);
						if($user_detail!=null){
							$detail['user_detail'] = $user_detail;	
						}
						else{

						}
						$detail['video_list'] = $video_list;
						//dd($detail);
						return view('frontend.RequestNewVideo',$detail);
					}

				}else{
					$user_detail = Profile::find($profile_id);
					$video_list=DB::table('requested_videos')->select('*')->where('uploadedby','=',$profile_id)->get();
					if($user_detail != null){
						$detail['user_detail'] = $user_detail;
						$detail['video_list'] = $video_list;
						$detail['is_login'] = 'No';
						//dd($detail);
						return view('frontend.RequestNewVideo',$detail);
					}else{
						return redirect( url()->previous());
					}
				}
			}else{
				return redirect('/view-all-artist')->with('error','You can not sent any request to Artist because Artist is Deactivated');
			}	
		}
	}
	public function RequestNewVideoForm(Request $request){
		$data = $request->all();
          
		//dd($data);
		$messages = [
		'user_name.regex' => 'Use valid User name (as xyz or xyz1)',
		'sender_name.regex' => 'Use valid User name (as xyz or xyz1)',
		'user_name.required' => 'The receipient name field is required',
		'user_email.required' => 'The receipient email field is required',
		];
		$validator = Validator::make($data,
			array(
				'user_name'=>'required',
				'user_email'=>'required|email',
				'sender_name' =>'required',
				'sender_email'=>'required|email',
				'delivery_date'=>'required',
				),$messages
			);
		if($validator->fails()){
			return redirect("RequestNewVideo/".$request->artist)
			->withErrors($validator)
			->withInput();
		}else{

			$is_artists = User::where('profile_id',$request->artist)->first(); 
			$is_artist_video = Profile::where('ProfileId',$request->artist)->first(); 
			$is_sender_artist = User::where('email',$request->sender_email)->where('type','Artist')->first();
			$is_receipent_artist = User::where('email',$request->user_email)->where('type','Artist')->first();

			$get_timestamp = Profile::where('ProfileId',$request->artist)->first(); 
			$now = new \DateTime();
			$date1=date_create($request->delivery_date);
			$diff=date_diff($date1,$now);
			$diff_date=$diff->format("%a");
			$timesp=$get_timestamp->timestamp;
			$diff_date= $diff_date+1;
			if($diff_date < $timesp)
			{
				return redirect("RequestNewVideo/".$request->artist)
				->with('error',"Delivery Date is not valid,Please select from calender.")->withInput();
				
			}		
			/*else if($request->user_email == $request->sender_email){
				return redirect("RequestNewVideo/".$request->artist)
				->with('error',"Recipient and sender email can not be same.")->withInput();

			}*/else if(count($is_artists) != 1 || $is_artists->is_account_active == 0){
				return redirect("RequestNewVideo/".$request->artist)
				->with('error',"Artist not found or Deactivated ")->withInput();

			}else if(count($is_sender_artist) == 1){
				return redirect("RequestNewVideo/".$request->artist)
				->with('error',"Sender Email already registered as artist. ")->withInput();

			}else if(count($is_receipent_artist) == 1){
				return redirect("RequestNewVideo/".$request->artist)
				->with('error',"Recipient Email already registered as artist. ")->withInput();
			}else{

				//Session::put('_token',$request->_token);
				Session::put('post_user_id',$request->user_id);
				Session::put('post_myusername',$request->user_name);
				Session::put('post_artist',$request->artist);
				Session::put('post_song_name1',$request->song_name1);
				Session::put('post_song_name2',$request->song_name2);
				Session::put('post_song_name3',$request->song_name3);
				Session::put('post_pronun_name',$request->pronun_name);
				Session::put('post_useremail',$request->user_email);
				Session::put('post_password',$request->password);
				Session::put('post_recei_email',$request->recei_email);
				Session::put('post_sender_name',$request->sender_name);
				Session::put('post_sender_name_pronun',$request->sender_name_pronun);
				Session::put('post_sender_email',$request->sender_email);
				Session::put('post_delivery_date',$request->delivery_date);
				Session::put('post_Occassion',$request->Occassion);
				Session::put('post_person_message',$request->person_message);
				Session::put('post_video_price',$is_artist_video->VideoPrice );
				Session::put('post_artist_id',$request->artist);
				return redirect('stripe_payment');
			}			

		}
	}

	public function hom() {
        //return redirect('dashboard');
		return view('frontend.dashboard');
	}
	public function artistDash(){
		return redirect('ProfileUpdate');
	}
//--------------------Home Login for Artist----------------------------------------//
	public function login() {
		return redirect('ArtistLogin');
	}
//--------------------------View all artist With Details------------------------------------//
	public function view_all_artist() {
		
		$artists = DB::table('profiles')->select('profiles.*','users.*')
		->join('users',function($join){
			$join->on('profiles.ProfileId', '=', 'users.profile_id')
			->where('users.type', '=','Artist')
			
			->where('users.is_account_active', '=','1');
		})->paginate(16);
		
		$artist_data['artist'] = $artists;
		return view('frontend.viewAllArtist',$artist_data);
	}
	
	public function view_all_video() {
		$videos = DB::table('video')->select('video.*','profiles.*')->where('users.is_account_active', '=','1')
		->join('profiles','profiles.ProfileId','=','video.ProfileId')
		->join('users','profiles.ProfileId','=','users.profile_id')
		->paginate(15);
		$video_data['video'] = $videos;
		return view('frontend.viewAllVideo',$video_data);
	}
	public function my_video() {
		if(Auth::check()){
			if(Auth::user()->type=="Admin" && session('current_type') == "Admin"){
				return redirect('admin_dashboard');
			}
			elseif (Auth::user()->type=="User" && session('current_type') == "User") {
				return redirect('profile');
			}
			else if (Auth::user()->type=="Artist" || (Auth::user()->type=="Admin" || session('current_type') == "Artist")){
				$videos = DB::table('video')
				->join('profiles', function($join)
				{
					$join->on('profiles.ProfileId', '=', 'video.ProfileId')
					->where('profiles.ProfileId','=',Auth::user()->profile_id);
				})
				->paginate(15);
				$artist = Profile::find(Auth::user()->profile_id);
				$video_data['video'] = $videos;
				$video_data['artist'] = $artist;
				return view('frontend.my_video',$video_data);
			}
		}
		else{
			return redirect(url()->previous());
		}
	}


	public function edit_sample_video($id){
		if(Auth::check()){
			if(Auth::user()->type=="Admin" && session('current_type') == "Admin"){
				return redirect('admin_dashboard');
			}
			elseif (Auth::user()->type=="User" && session('current_type') == "User") {
				return redirect('/');
			}
			else if (Auth::user()->type=="Artist" || (Auth::user()->type=="Admin" || session('current_type') == "Artist")){
				$artist =  Profile::where('EmailId',Auth::user()->email)->first();
				$artist_data['artist'] = $artist;
				$video_data=DB::table('video')->where('VideoId',$id)->first();
				//dd($video_data);
				$artist_data['video_data'] = $video_data;
				return view('frontend.artistDashboard.edit_sample_video',$artist_data);
			}
		}else{
			return redirect('/login');
		}	
	}

	public function post_edit_sample_video(Request $request){
    	//dd($request->all());
		$validator = Validator::make(
			array(
				'video_title' =>$request->video_title,
				'video_description' => $request->video_description,
				'video' =>$request->file('video'),
				),
			array(
				'video_title' =>'required',
    			//'video_description' =>'required|min:80',
				'video_description' =>'required',
				'video' => 'mimes:mp4,mpeg',
				)
			);
		if($validator->fails())
		{
			return redirect('edit_sample_video/'.$request->video_id)
			->withErrors($validator)
			->withInput();
		}
		else
		{
    		//dd($request->all());
			$video =  Video::find($request->video_id);
			$file = $request->file('video');
			if($file != ''){
				$extension = $file->getClientOriginalExtension();
				$filename = str_replace(' ', '', $file->getClientOriginalName());
				$filename = str_replace('-', '', $filename);
				$VideoURL = "https://www.videorequestline.com/video/".date('U') .$filename ;
				$video->VideoFormat = $file->getClientOriginalExtension();
				$video->VideoSize = ($file->getSize()/1024) . "mb";
				$video->download_status	 = $request->download_status;
    			//$video->home_auto_play_status = $request->autoPlay_status;
				$video->profile_auto_play_status = $request->profile_autoPlay_status;
    			//$video->video_auto_play_status = $request->video_autoPlay_status;

				$ffmpeg = FFMpeg\FFMpeg::create(array(
					'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
					'ffprobe.binaries' => '/usr/local/bin/ffprobe',
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
				$ffprobe = FFMpeg\FFProbe::create(array(
					'ffmpeg.binaries'  => '/usr/local/ffmpeg',
					'ffprobe.binaries' => '/usr/local/bin/ffprobe',
					'timeout'          => 3600,
					'ffmpeg.threads'   => 12,
					));
				/*-------------------------retrieving Video Duration----------------------------*/
				$video_length = $ffprobe->streams($destination.$fileName)
				->videos()
				->first()
				->get('duration');

    			// if($video_length < 15){
    			// 	return redirect()->back()->with('error','Video duration must be of 15 seconds');
    			// }
    			// else{
				/*----------------------------retrieving Thumbnail------------------------------*/
				$video_thumbnail_path = base_path() . '/public/images/thumbnails/'.date('U').'.jpg';
				$uploaded_video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(2))->save($video_thumbnail_path);
				$video->VideoThumbnail	 = $video_thumbnail_path;
				/*----------------------------Applying Watermark----------------------------------*/
				$ffmpegPath = '/usr/local/bin/ffmpeg';
				$inputPath = $orginal_video->video_path;
				$watermark = '/home/vrl/public_html/public/vrl_logo.png';
				$outPath = '/home/vrl/public_html/public/video/watermark/'.date('U').'.mp4';
				shell_exec("$ffmpegPath  -i $inputPath -i $watermark -filter_complex overlay=main_w-overlay_w-20:main_h-overlay_h-20 $outPath ");

				/*	----------------------------------Saving Video-------------------------------*/
				$watermark_video_destination = substr($outPath,28);
				$video->VideoURL	 = $outPath;
				$video->originalVideoUrl	 = $orginal_video->video_path;
				$ffprobe = FFMpeg\FFProbe::create(array(
					'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
					'ffprobe.binaries' => '/usr/local/bin/ffprobe',
					'timeout'          => 3600,
					'ffmpeg.threads'   => 12,
					));
				/*-------------------------retrieving Video Duration----------------------------*/
				$video->VideoLength = $ffprobe->streams($orginal_video->video_path)
				->videos()
				->first()
				->get('duration');
    			// }
			}

			$video->Description = $request->video_description;
			$video->Title = $request->video_title;
			$video->VideoUploadDate = Carbon::now()->format('m-d-Y');
			$video->download_status	 = $request->download_status;
			$video->home_auto_play_status = $request->autoPlay_status;
			$video->profile_auto_play_status = $request->profile_autoPlay_status;
			$video->video_auto_play_status = $request->video_autoPlay_status;
			if($video->save()){
				return redirect('edit_sample_video/'.$request->video_id)->with('success','Video updated successfully');

			}

		}
	}


	public function show_testimonial($testimonial_id)
	{
		if(Auth::check()){
			if(Auth::user()->type=="Admin" && session('current_type') == "Admin"){
				return redirect('artist_dashboard');
			}
			elseif (Auth::user()->type=="User" && session('current_type') == "User") {
				return redirect('profile');
			}
			else if (Auth::user()->type=="Artist" || (Auth::user()->type=="Admin" || session('current_type') == "Artist")){
				$testimonial = \App\Testimonial::find($testimonial_id);
				$testimonial->AdminApproval = 1;
				if($testimonial->save()){
					return redirect('view_testimonial')->with('success','Testimonial accepted successfully');;
				}
			}
		}
		else{
			return redirect('/');
		}
	}

	public function hide_testimonial($testimonial_id)
	{
		if(Auth::check()){
			if(Auth::user()->type=="Admin" && session('current_type') == "Admin"){
				return redirect('artist_dashboard');
			}
			elseif (Auth::user()->type=="User" && session('current_type') == "User") {
				return redirect('profile');
			}
			else if (Auth::user()->type=="Artist" || (Auth::user()->type=="Admin" || session('current_type') == "Artist")){
				$testimonial = \App\Testimonial::find($testimonial_id);
				$testimonial->AdminApproval = 0;
				if($testimonial->save()){
					return redirect('view_testimonial')->with('success','Testimonial is hidden now');;
				}
			}
		}
		else{
			return redirect('/');
		}
	}

	public function get_data(Request $request){

		if($request->type == "Artist"){

		}else if($request->type == "User"){

		}
	}
}