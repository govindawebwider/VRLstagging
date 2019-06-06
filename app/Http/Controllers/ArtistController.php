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
use Illuminate\Support\Facades\Storage;
use Validator;
use Carbon\Carbon;
use Mail;
use App\Profile;
use App\Video;
use App\OriginalVideo;
use App\Requestvideo;
use App\RequestedVideo;
use Session;
use Crypt;
use App\Payment;
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
				return redirect('/');
			}
		}else{
			return redirect('/'); 
		} 
	}
        
        /*
         * -- Mohamed Mamdouh
         * Delete Video Completed Requests From Artist Side
         * 1. This will not totally delete the request it will mark it as delete and 
         * will not be showen on it's page but will exists on the database
         * 
         */
        
        public function deleteCompletedRequest($request_id){
            if(Auth::check()){
			if(Auth::user()->type=="Artist" || (Auth::user()->type=="Admin" || session('current_type') == "Artist")){
				/*
                                 * Get the Video 
                                 * Make sure This Video Related to that artist
                                 * Mark it as deleted 
                                 * Redirect back with success or error message
                                 * 
                                 */
                            $video = RequestedVideo::where([
                                'id'=>$request_id,
                                'uploadedby'=>Auth::user()->profile_id,
                                'deleted_from_artist'=>0
                                    ])->first();
                            if(count($video)>0){
                                $video->deleted_from_artist = 1;
                                $video->save();
                                Session::flash('success','Succufully deleted');
                                return redirect()->back();
                            }
                            else{
                               Session::flash('error','Error happens please contact the admin');
                                return redirect()->back();
                            }
                            
                            
			}
                        else{
				return redirect('/');
			}
		}
                
                else{
			return redirect('/'); 
		} 
        }
        
        
        
        /*
         * Mohamed Mamdouh
         * Check Email Address if exist or not
         * 
         */
        
        public function checkEmailAddress($email){
            $exist = DB::table('users')->where('email',$email)->first();
          
            
            if(count($exist)>0){
                 return json_encode(['status' => false]);
            }
           
            return json_encode(['status' => true]);
        }
        
        public function checkPassword($password){
            $user = Auth::user();
        
            $current_user = DB::table('users')->where('email',$user->email)->first();

            if(Hash::check($password,$current_user->password)){
                   return json_encode(['status' => true]);
            }
             return json_encode(['status' => false]);
        }
        
        public function updateEmailAddressRequest(Request $request){
            $emailResponse = json_decode($this->checkEmailAddress($request->email),true);
              $passwordResponse = json_decode($this->checkPassword($request->password),true);
            
           
            
            if($emailResponse['status'] && $passwordResponse['status'] ){
              /*
               * Add the Requested Email to the Request Email Col
               * Create Hash Link By Old Email and new Email
               * Send this Hash URL to the Current Email
               */
               $ran_token = Hash::make(rand(11111, 99999) . date('U'));

               $user = Auth::user();
             
               $current_user = DB::table('users')->where('email',$user->email)->update(
                       ['newEmailRequest'=>$request->email,
                           'updateEmailToken'=>$ran_token
                       ]);
               
               /*
                 * Make Hash URL and send to Email User
                 */
               $Updateuser['user_name'] = $user->user_name;
               $Updateuser['token'] = $ran_token;

                Mail::send('emails.updateEmail', ['user'=>$Updateuser], function ($message) use($user) {
                        $message->from('noreply@videorequestline.com', 'Gift videorequestline');
                        $message->to($user->email, $user->user_name);
                        $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                        $message->subject('New Email Change Request');
                    });
             
             
               
                return response()->json(['status' => true]);
            }
                
            
             return response()->json(['status' => false]);
            
        }
        
        public function confirmUpdateEmailAddress(Request $request){
              $token = $request->request_token;
              $updatedUser = DB::table('users')->where('updateEmailToken',$token)->first();
               
              if(empty($token) || !count($updatedUser) > 0){
                Session::flash('error-review', 'Sorry this link is expired');
                    return redirect('/view-all-video');  
              }
         
              if(count($updatedUser) > 0){
                 return view('frontend.artistDashboard.profile.confirmEmail')->with(['user'=>$updatedUser,'token'=>$token]);
              }
            
              
            
        }
        
        public function updateEmailAddress(Request $request){
            // Update Email Address Request
             $token = $request->userToken;
              $updatedUser = User::where(['newEmailRequest'=>$request->email,
                 'updateEmailToken'=>$token ])
                      ->first();
              
              
              if(count($updatedUser) > 0){
                  DB::table('profiles')->where('EmailId',$updatedUser->email)->update(['EmailId'=>$request->email]);
                  $updatedUser->email = $request->email;
                  $updatedUser->save();
                  
                  Session::flash('success-review', 'Sorry this link is expired');
                    return redirect('/');
              }
              
              else{
                  
                    Session::flash('error-review', 'Sorry this link is expired');
                    return redirect('/view-all-video');
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
				return redirect('/successMessage')->with('success','Email updated Successfully');

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

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
	public function artist_payment_detail()
	{
		if (Auth::check()) {
			if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
				return redirect('admin_dashboard');
			} elseif (Auth::user()->type == "User" && session('current_type') == "User") {
				return redirect('profile');
			} else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
				$user = User::where('email', Auth::user()->email)->first();
				$artist = Profile::find(Auth::user()->profile_id);
				$payment_data['user'] = $user;
				$payment_dtl = Payment::select('*')
					->wherePayerId(Auth::user()->profile_id)
					->where('video_status', '!=', 'Pending')
					->join('requestvideos', 'requestvideos.VideoReqId', '=', 'payments.video_request_id')
					->orderBy('id', 'desc')
					->get();
				$share = DB::table('shares')->where('id', '1')->first();
                $payment_data['share'] = $share;	
				$payment_data['payment'] = $payment_dtl;
				$payment_data['artist'] = $artist;
				return view('frontend.artistDashboard.artist_payment_detaile', $payment_data);
			}
		} else {
			return redirect(url()->previous());
		}
	}

	/**
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function artist_payment_detail_export()
	{
		if (Auth::check()) {
			if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
				return redirect('admin_dashboard');
			} elseif (Auth::user()->type == "User" && session('current_type') == "User") {
				return redirect('profile');
			} else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
				$payment_dtl = Payment::select('*')
					->where('payer_id', '=', Auth::user()->profile_id)
					->join('requestvideos', 'requestvideos.VideoReqId', '=', 'payments.video_request_id')
					->orderBy('id', 'desc')
					->get();
				Excel::create('Payment Details', function ($excel) use ($payment_dtl) {
					$excel->sheet('Sheet 1', function ($sheet) use ($payment_dtl) {
						$sheet->fromArray($payment_dtl);
					});
				})->export('xls');
			}
		} else {
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
                                ->join('requestvideos','requestvideos.VideoReqId','=','requested_videos.request_id')        
				->where('requested_videos.uploadedby','=',Auth::user()->profile_id)
                                ->where('requested_videos.deleted_from_artist','=',0)
                ->orderBy('Upload_date', 'DESC')
				->paginate(15);

				$user =  User::where('email',Auth::user()->email)->first();
				$artist = Profile::find(Auth::user()->profile_id);
				$video_data['user'] = $user;
				$video_data['video'] = $deliver_videos;
				$video_data['artist'] = $artist;
				//dd($video_data);
				return view('frontend.artistDashboard.deliver_video',$video_data); 
			}else{
				return redirect('/');
			}
		}else{
			return redirect('/'); 
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
		}
                else{
			$artist_id =  User::where('profile_id',$profile_id)->first();
			if($artist_id->is_account_active==1)
			{
				if(Auth::check()){
					if(Auth::user()->type=="Artist"){
                                            Session::flash('error','Sorry you cannot request video as artist');
						return redirect()->back();
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
                'song_name1.required' => 'The Song Name field is required',
                'person_message.min' => 'The Personal Message must be at least 5 characthers ',
                'person_message.max' => 'The Personal Message must be at at most 500 characthers ',
		];
                
		$validator = Validator::make($data,
			array(
				'user_name'=>'required|min:4|max:255',
				'user_email'=>'required|email',
				'sender_name' =>'required|min:4|max:255',
				'sender_email'=>'required|email',
				'delivery_date'=>'required',
                                'song_name1' => 'required|min:4|max:255',
                                'person_message' => 'min:5|max:255'
				),$messages
			);
		if($validator->fails()){
                    
                   return ['proccess' => "false" , "errors" => $validator->errors() ];

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
                            return ['proccess' => "false" , "message" => "Delivery Date is not valid,Please select from calender" ];

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
                                /*
                                 *    22-12-2017
                                 * -- Editing this function
                                 * -- Function Description 
                                 *         This function is triggert when the new  video requested before adding the billing method
                                 * -- Developer :: Mohamed Mamdouh
                                 *          [ Adding some comments and seprate each section ]
                                 * 
                                 */
                                 
                            if(isset($_FILES["sender-record"])){
                              $rand = rand(11111, 99999) . date('U').'sender';
                              $destination = base_path() . '/public/usersRecords/';
                              $fileName = $rand . '.wav';
                              
                              Session::put('sender-record', $fileName);

                              /* Moving Normal Video To the requested_video File */
                              move_uploaded_file($_FILES["sender-record"]["tmp_name"], $destination.$fileName);
                            } 
                            
                                  
                            if(isset($_FILES["recipient-record"])){
                              $rand = rand(11111, 99999) . date('U').'recipient';
                              $destination = base_path() . '/public/usersRecords/';
                              $fileName = $rand . '.wav';
                              Session::put('recipient-record',$fileName);
                               
                              /* Moving Normal Video To the requested_video File */
                              move_uploaded_file($_FILES["recipient-record"]["tmp_name"], $destination.$fileName);
                            }
                            
                 
                                    
                                // Song Names Not sure why there are 3 variables 
                             
                                Session::put('post_song_name1',$request->song_name1);
				
                                /* -- Those Two Variable Are not Used --  */
                                Session::put('post_song_name2',$request->song_name2);
				Session::put('post_song_name3',$request->song_name3);
                                
       
                                // who will receive the video 
                                Session::put('post_myusername',$request->user_name);
				Session::put('post_pronun_name',$request->pronun_name);
				Session::put('post_useremail',$request->user_email);
				Session::put('post_recei_email',$request->recei_email);  // who will receive the video
                                
              
                                 // Who make the request
				Session::put('post_sender_name',$request->sender_name); // who make the request 
				Session::put('post_sender_name_pronun',$request->sender_name_pronun);
				Session::put('post_sender_email',$request->sender_email);
				
                                
                                // Video Specifications 
                                Session::put('post_delivery_date',$request->delivery_date);
				Session::put('post_Occassion',$request->Occassion);
				Session::put('post_person_message',$request->person_message);
				
                                
                                // Not Sure why he used this twice 
                                Session::put('post_artist',$request->artist);
				Session::put('post_artist_id',$request->artist);
                                
                                
                                // for Logged in User
                                Session::put('post_user_id',$request->user_id);
                                
                                
                                // Not sure why he using password and video price
                                Session::put('post_password',$request->password);
                                Session::put('post_video_price',$is_artist_video->VideoPrice );
				
                                
                                return ['proccess' => "success"];
 
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
	public function view_all_artist(Request $request)
	{
		$search_query = $request->title;
		if (!is_null($search_query) && !empty($request->category_id)) {
			$artists = DB::table('category')->select('artist_category.profile_id', 'artist_category.category_id', 'profiles.*')
				->distinct('artist_category.profile_id')
				->where('category.id', '=', $request->category_id)
				->where('category.status', 1)
				->join('artist_category', function ($join) {
					$join->on('artist_category.category_id', '=', 'category.id');
				})
				->join('profiles', function ($join) {
					$join->on('profiles.ProfileId', '=', 'artist_category.profile_id');
				})
				->join('users', function ($join) {
					$join->on('users.profile_id', '=', 'artist_category.profile_id')
						->where('users.type', '=', 'Artist')
						->where('users.is_account_active', '=', '1');
				})->paginate(16);
		} elseif (!is_null($search_query)) {
			$artists = DB::table('category')->select('artist_category.profile_id', 'profiles.*')
				->distinct('artist_category.profile_id')
				->where('category.title', 'LIKE', "%{$search_query}%")
				->where('category.status', 1)
				->join('artist_category', function ($join) {
					$join->on('artist_category.category_id', '=', 'category.id');
				})
				->join('profiles', function ($join) {
					$join->on('profiles.ProfileId', '=', 'artist_category.profile_id');
				})
				->join('users', function ($join) {
					$join->on('users.profile_id', '=', 'artist_category.profile_id')
						->where('users.type', '=', 'Artist')
						->where('users.is_account_active', '=', '1');
				})->paginate(16);
		} else {
			$artists = DB::table('profiles')->select('profiles.*', 'users.*')
				->join('users', function ($join) {
					$join->on('profiles.ProfileId', '=', 'users.profile_id')
						->where('users.type', '=', 'Artist')
						->where('users.is_account_active', '=', '1');
				})->paginate(16);
		}
		foreach ($artists as $key => $artist) {
			$main_category = DB::table('artist_category')->select('category.title')
				->where('artist_category.profile_id', '=', $artist->profile_id)
				->join('category', function ($join) {
					$join->on('artist_category.category_id', '=', 'category.id')
						->where('artist_category.main_category', '=', 1);
				})->first();
			$testimonials = DB::table('testimonials')->where('to_profile_id', $artist->profile_id)
				// ->select('testimonials.*','profiles.*')
				->orderByRaw("RAND()")
				->where('AdminApproval', '=', 1)
				->where('show_artist', '=', 1)
				// ->join('profiles','profiles.ProfileId','=','testimonials.by_profile_id')
				->take(5)->get();
			$artists[$key]->main_category = !empty($main_category) ? $main_category->title : '';
			$artists[$key]->testimonials = $testimonials;
		}

		$artist_data['artist'] = $artists;
		$categoryData = DB::table('category')
            //->join('artist_category', 'artist_category.category_id', '=', 'category.id')
            ->where('category.status', 1)
            ->select('category.title')
            ->distinct()
            /*->join('users', function ($join) {
                $join->on('users.profile_id', '=', 'artist_category.profile_id')
                    ->where('users.type', '=', 'Artist')
                    ->where('users.is_account_active', '=', '1');
            })*/
            ->get();

		$artist_data['catData'] = $categoryData;

		return view('frontend.viewAllArtist', $artist_data);
	}



	/**
	* Search function to search artist using its name.
	* @param  $request
    * @return \Illuminate\Http\Request
	*/

	public function searchArtist(Request $request) {
        $search_query = $request->search_query;
        if ($search_query != "") {
			$search_result = DB::table('profiles')
			->where('profiles.Name','LIKE',"%{$search_query}%")		
			->join('users',function($join){
				$join->on('profiles.ProfileId', '=', 'users.profile_id')
				->where('users.type', '=','Artist')
				->where('users.is_account_active', '=','1');
			})->paginate(20);
            if (!is_null($search_result) ) {
            	foreach ($search_result as $key => $artist) 
		        { 
		        	$main_category = DB::table('artist_category')->select('category.title')
		        	->where('artist_category.profile_id', '=', $artist->profile_id)
					->join('category',function($join){
						$join->on('artist_category.category_id', '=', 'category.id')
						->where('artist_category.main_category', '=', 1);
					})->first();
					$testimonials = DB::table('testimonials')->where('to_profile_id',$artist->profile_id)
						// ->select('testimonials.*','profiles.*')
						->orderByRaw("RAND()")
						->where('AdminApproval','=',1)
						->where('show_artist','=',1)
						// ->join('profiles','profiles.ProfileId','=','testimonials.by_profile_id')
						->take(5)->get();
					$search_result[$key]->main_category = !is_null($main_category) ? $main_category->title : null;
					$search_result[$key]->testimonials = $testimonials;
		        }
                $artist_data['artist'] = $search_result;
                $categoryData = DB::table('category')->where('status', 1)->get();
        		$artist_data['catData'] = $categoryData;
                return view('frontend.viewAllArtist', $artist_data);
            } else {
                return redirect('/');
            }
        } else {
			return redirect('/view-all-artist');
        }
    }
	
	public function view_all_video() {
		$videos = DB::table('video')->select('video.*','profiles.*')
                        ->where('users.is_account_active', '=','1')
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


    public function edit_sample_video($id)
    {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('/');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $artist = Profile::where('EmailId', Auth::user()->email)->first();
                $artist_data['artist'] = $artist;
                $video_data = DB::table('video')->where('VideoId', $id)->first();
                //dd($video_data);
                $artist_data['video_data'] = $video_data;
                return view('frontend.artistDashboard.edit_sample_video', $artist_data);
            }
        } else {
            return redirect('/');
        }
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function post_edit_sample_video(Request $request)
    {
        $validator = Validator::make([
            'video_title' => $request->video_title,
            'video_description' => $request->video_description,
        ], [
                'video_title' => 'required',
                'video_description' => 'required',
            ]
        );
        if ($validator->fails()) {
            return redirect('edit_sample_video/' . $request->video_id)
                ->withErrors($validator)
                ->withInput();
        } else {
            $video = Video::find($request->video_id);
            if ($request->hasfile('video')) {
                $file = $request->file('video');
                $mime = $file->getMimeType();
                $filesize = $request->file('video')->getSize();
                if (($mime == "video/mp4" || $mime == "video/quicktime" || $mime == "video/x-msvideo"
						|| $mime == "video/x-flv" || $mime == "application/x-mpegURL" || $mime == "video/3gpp"
						|| $mime == "video/x-ms-wmv")/* && number_format($filesize / 1024) < 20*/) {
                    $extension = $file->getClientOriginalExtension();
                    $video->VideoFormat = $file->getClientOriginalExtension();
                    $video->VideoSize = ($file->getSize() / 1024) . ".mb";
                    $video->download_status = $request->download_status;
                    //$video->home_auto_play_status = $request->autoPlay_status;
                    $video->profile_auto_play_status = $request->profile_autoPlay_status;
                    //$video->video_auto_play_status = $request->video_autoPlay_status;

                    $ffmpeg = FFMpeg\FFMpeg::create(array(
                        'ffmpeg.binaries' => '/usr/bin/ffmpeg',
                        'ffprobe.binaries' => '/usr/bin/ffprobe',
                        'timeout' => 3600,
                        'ffmpeg.threads' => 12,
                    ));
                    /*--------------------------Opening Uploaded Video------------------------------*/
                    $rand = rand(11111, 99999) . date('U');
                    $destination = 'video/original/';
                    $fileName = $rand . '.' . $extension;
                    $request->file('video')->move($destination, $fileName);
                    $destination_path = $destination . $fileName;
					$s3 = Storage::disk('s3');
					//$s3->put($destination_path, file_get_contents($file), 'public');
					multipartUpload($destination_path);
                    $orginal_video = new OriginalVideo();
                    $orginal_video->video_path = $fileName;
                    $orginal_video->save();
                    $orginal_video_id = $orginal_video->id;
                    $orginal_video = OriginalVideo::find($orginal_video_id);
                    $uploaded_video = $ffmpeg->open($destination_path);
                    $ffprobe = FFMpeg\FFProbe::create(array(
                        'ffmpeg.binaries' => '/usr/bin/ffmpeg',
                        'ffprobe.binaries' => '/usr/bin/ffprobe',
                        'timeout' => 3600,
                        'ffmpeg.threads' => 12,
                    ));
                    /*-------------------------retrieving Video Duration----------------------------*/
                    $video_length = $ffprobe->streams($destination_path)
                        ->videos()
                        ->first()
                        ->get('duration');

                    // if($video_length < 15){
                    // 	return redirect()->back()->with('error','Video duration must be of 15 seconds');
                    // }
                    // else{
                    /*----------------------------retrieving Thumbnail------------------------------*/
					$videoThumbnail = $rand . '.jpg';
                    $video_thumbnail_path = 'images/thumbnails/' . $videoThumbnail;
                    $uploaded_video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(2))->save($video_thumbnail_path);
					//$s3->put($video_thumbnail_path, file_get_contents($video_thumbnail_path));
					multipartUpload($video_thumbnail_path);
					unlink($video_thumbnail_path);
                    $video->VideoThumbnail = $videoThumbnail;
                    /*----------------------------Applying Watermark----------------------------------*/
                    $ffmpegPath = '/usr/bin/ffmpeg';
                    //$inputPath = $orginal_video->video_path;
                    $watermark = base_path() . '/public/vrl_logo.png';
                    $outPath =  'video/watermark/' . $rand . '.' . $extension;
					$thumbnailVideo =  'video/watermark/thumbnail/' . $rand . '.' . $extension;
                    shell_exec("$ffmpegPath  -i $destination_path -i $watermark -filter_complex \"[1][0]scale2ref=(262/204)*ih/12:ih/12[wm][vid];[vid][wm]overlay=x=(W-w-20):y=(H-h-20)\" $outPath ");
					//$s3->put($outPath, file_get_contents($outPath));
					multipartUpload($outPath);
					unlink($outPath);
					shell_exec("$ffmpegPath  -i $destination_path -i $watermark  -filter_complex \"[0:v]scale=640:360[bg];[bg][1:v]overlay=x=(W-w-20):y=(H-h-20)\" $thumbnailVideo ");
					//$s3->put($thumbnailVideo, file_get_contents($thumbnailVideo));
					multipartUpload($thumbnailVideo);
					unlink($thumbnailVideo);

                    /*	----------------------------------Saving Video-------------------------------*/
                    $watermark_video_destination = substr($outPath, 28);
                    $video->VideoURL = $rand . '.' . $extension;
                    $video->originalVideoUrl = $orginal_video->video_path;
                    $ffprobe = FFMpeg\FFProbe::create(array(
                        'ffmpeg.binaries' => '/usr/bin/ffmpeg',
                        'ffprobe.binaries' => '/usr/bin/ffprobe',
                        'timeout' => 3600,
                        'ffmpeg.threads' => 12,
                    ));
                    /*-------------------------retrieving Video Duration----------------------------*/
                    $video->VideoLength = $ffprobe->streams($destination_path)
                        ->videos()
                        ->first()
                        ->get('duration');
					unlink($destination_path);
                } else {
                    return redirect('edit_sample_video/' . $request->video_id)
                        ->with('error', 'mp4, .mov, avi, wmv, 3gp, flv are allowed')->withInput();
                }
            }
            $video->Description = $request->video_description;
            $video->Title = $request->video_title;
            $video->VideoUploadDate = Carbon::now()->format('m-d-Y');
            $video->download_status = $request->download_status;
            $video->home_auto_play_status = $request->autoPlay_status;
            $video->profile_auto_play_status = $request->profile_autoPlay_status;
            $video->video_auto_play_status = $request->video_autoPlay_status;
            if ($video->save()) {
                return redirect('edit_sample_video/' . $request->video_id)->with('success', 'Video updated Successfully');

            }

        }
    }


	public function show_review($testimonial_id)
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
					return redirect('view_review')->with('success','Review Accepted Successfully');;
				}
			}
		}
		else{
			return redirect('/');
		}
	}

	public function hide_review($testimonial_id)
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
					return redirect('view_review')->with('success','Review is hidden now');;
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
