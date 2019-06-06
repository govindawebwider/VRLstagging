<?php
namespace App\Http\Controllers;
use App\ArtistCategory;
use App\FooterContent;
use App\Http\Requests;
use App\Occasion;
use App\ReactionVideo;
use App\Testimonial;
use Illuminate\Http\Request;
use Stevebauman\Translation\Facades\Translation;
use Validator;
use App\AdminLogin;
use Carbon\Carbon;
use DB;
use Auth;
use App\User;
use Illuminate\Routing\Controller;
use Input;
use Hash;
use Session;
use App\Profile;
use App\Video;
use App\Requestvideo;
use Mail;
use Paypalpayment;
class HomeController extends Controller{
	public function __construct()
	{

		$this->middleware('user_active');
		$this->middleware('revalidate');
	}
	public function date_calculation($days)
	{
		$tot_price =$days*10; 

		return $tot_price;
	}
	public function fecth_data($id)
	{
		$artist = Profile::find($id);
    	/*$video_data = DB::table('video')->select('*')
    		->where('requested_videos.ProfileId','=',$id)
    		->get();*/
        return !is_null($artist) ? $artist->timestamp : null;
    	}
    	public function about()
    	{
    		$about_data=DB::table('footer_content')->where('type', '1')->select('content')
    		->first();
    		$artist_data['about_data']=$about_data;
    		return view('frontend.about',$artist_data);
    	}
    	public function help()
    	{
            $help_data = FooterContent::whereType('2')->first();
            $artist_data['help_data']=$help_data;
    		    return view('frontend.help', $artist_data);
    	}
    	public function terms()
    	{
    		$term_data=DB::table('footer_content')->where('type', '4')->select('content')
    		->first();
    		$artist_data['term_data']=$term_data;
    		return view('frontend.terms',$artist_data);
    	}
    	public function privacy()
    	{
    		$privacy_data=DB::table('footer_content')->where('type', '3')->select('content')
    		->first();
    		$artist_data['privacy_data']=$privacy_data;
    		return view('frontend.privacy',$artist_data);
    	}
    	public function home_data()
    	{
        DB::enableQueryLog();
        $latest_video = DB::select('select
        requested_videos.title video_title, 
        requested_videos.description video_description,
        requested_videos.fileName video_url,
        artist.name artist_name,
        artist.profile_url artist_profile_url,
        user.name user_name
        from requestvideos
        join requested_videos on requestvideos.VideoReqId = requested_videos.request_id 
        inner join profiles artist on artist.ProfileId = requested_videos.uploadedby 
        inner join profiles user on user.ProfileId = requested_videos.requestby
        inner join users on artist.ProfileId = users.profile_id and users.type = "Artist" and users.is_account_active = 1 
        where requested_videos.id IN (select MAX(rv1.id) from requested_videos rv1 inner join requestvideos rv2 ON rv1.request_id=rv2.VideoReqId where rv2.is_hidden=0 AND rv1.is_email_sent=1 group by rv1.uploadedby)
        and requestvideos.is_hidden = 0
        ORDER BY requested_videos.id DESC 
        limit 20');
        $video = DB::table('video')->select('video.*','profiles.*')->orderBy('VideoId','desc')
        ->join('profiles','profiles.ProfileId','=','video.ProfileId')
        ->join('users',function($join){
          $join->on('profiles.ProfileId', '=', 'users.profile_id')
          ->where('users.type', '=','Artist')
          ->where('users.is_account_active', '=','1');
        })
        ->take(7)->get();
        $artist = DB::table('profiles')->select('profiles.*','users.*')
        ->join('users',function($join){
         $join->on('profiles.ProfileId', '=', 'users.profile_id')
         ->where('users.type', '=','Artist')
         ->where('users.is_chart_topper', '=','1')
         ->where('users.is_account_active', '=','1');
       })->get();
        $slider_data = DB::table('sliders')->select('sliders.*','profiles.*')
        ->join('profiles',function($join){
         $join->on('profiles.ProfileId', '=', 'sliders.artist_id')
			//->where('profiles.Type', '=','Artist')
         ->where('sliders.slider_visibility', '=','1');
       })
        ->join('users',function($join){
         $join->on('users.profile_id', '=', 'sliders.artist_id')
         ->where('users.is_account_active', '=','1');
       })->get();


    		/*$testimonials = DB::table('testimonials')
    		->select('profiles.*','testimonials.*')
    		->join('profiles','profiles.ProfileId','=','testimonials.by_profile_id')
    		->join('users','profiles.ProfileId','=','users.profile_id')
    		->where('testimonials.AdminApproval','=',1)
    		->where('users.is_account_active','=',1)*/

        $testimonials  = Testimonial::orderBy('id','desc')
            ->where('show_home',1)
            ->where('testimonials.AdminApproval','=',1)
            ->get();

        $data = array();
        $data['videos'] = $video;
        $data['latest_videos'] = $latest_video;
        $data['artists'] = $artist;
        $data['slider_data'] = $slider_data;
        $data['testimonials'] = $testimonials;
        $postData = [
                       'status' =>1 
                   ];
        $data['users'] = ReactionVideo::where($postData)->get();
        $data['baseurl'] = "https://www.videorequestline.com/";
        return $data;	

      }

      public function fhome(){
        if(Auth::check()){
         if(Auth::user()->type=="User"){
            
          $data = $this->home_data();
          return view('frontend.home',$data); 
				//return redirect('/profile');
        }
        elseif (Auth::user()->type=="Admin") {
          return redirect('admin_dashboard');
        }
        elseif (Auth::user()->type=="Artist") {
          $data = $this->home_data();
          return view('frontend.home',$data); 
				//return redirect('admin_dashboard');
        }

      }
      else{
       $data = $this->home_data();
       return view('frontend.home',$data); 
       
     }
   }
  
/* start new functions*/
  public function booking_checkout(){
      $id = Session::get('post_artist_id');
        //dd($id);
        if ($id == '' || $id == "removed") {
            //echo "yes";
            return redirect('/view-all-artist');
        } else {
            $user_detail = Profile::find($id);
            $occasionTitles = Occasion::whereArtistProfileId($id)->pluck('title', 'id')->toArray();
            $detail['occasions'] = $occasionTitles;
            $detail['user_detail'] = $user_detail;
            return view('frontend.booking_checkout' , $detail);
        }
    
  }
/* end new functions*/
  

   public function home(){
    return view('admin.dashboard');
  }
  public function admin(){
    if(Auth::check()){
     if(Auth::user()->type=="Artist"){
      return redirect('/');
    }
    elseif (Auth::user()->type=="User") {
      return redirect('profile');
    }
    else{
      return redirect('admin_dashboard');
    }
  }else{
   return view('admin.login');
 }
}
public function adminLogin(Request $request){
  $data = $request->all();
  $validator = Validator::make($data,
   array(
    'email' =>'required',
    'password' =>'required'
    )
   );
  if($validator->fails()){
   return redirect('admin')
   ->withErrors($validator)
   ->withInput();
 }else{
   $val = AdminLogin::where(array('email'=>$request->email,'password'=>$request->password))->get();
   if(count($val)>0) {
    $user = DB::table('admin_logins')->where('email', $request->email)->first();
    session(['name' => $user->username,'email'=>$user->email]);
    return view('admin.dashboard');
  }else{
    return redirect('admin')->with('admin',$request->email);  
  }  
}
}
public function signup(){
  if(Auth::check()){
   if(Auth::user()->type=="Artist"){
    return redirect('/');
  }
  elseif (Auth::user()->type=="User") {
    return redirect('profile');
  }
  else{
    return redirect('admin_dashboard');
  }
}else{
 return view('admin.signup');
}
}
public function signupForm(Request $request){
  $data = $request->all();
  $validator = Validator::make($data,
   array(
    'fname' =>'required',
    'lname' =>'required',
    'email' =>'required|email|unique:profiles',
    'password' =>'required|min:10',
    'confirm_password'=>'required|same:password'             
    )
   );
  if($validator->fails())
  {
   return redirect('signup')
   ->withErrors($validator)
   ->withInput();
 }
 else
 {
   $adminLogin = new AdminLogin();
   $adminLogin->username=$request->fname." ". $request->lname;
   $adminLogin->email=$request->email;
   $adminLogin->password=$request->password;
   $adminLogin->created_at=Carbon::createFromFormat('Y-m-d H:i:s a', date('Y-m-d H:i:s a')) ;
   $adminLogin->updated_at=Carbon::createFromFormat('Y-m-d H:i:s a', date('Y-m-d H:i:s a')) ;
   $adminLogin->_token=$request->_token;
   $adminLogin->save();
   if($adminLogin->save()){
    return redirect('signup')
    ->with('success','Registered Successfully! Now Login');
  }
}
}
//End 


public function video_detail_page($id){
    $type = \Illuminate\Support\Facades\Input::get('type');
    if(isset($type) && $type == 'sample') {
        $sample_video = DB::table('video')->where('VideoId', $id)->first();
        $artist_result = [];
    } elseif(isset($type) && $type == 'requested') {
        $artist_result = DB::table('requested_videos')->where('id', $id)->first();
        $sample_video = [];
    } else {
        $artist_result = DB::table('requested_videos')->where('id', $id)->first();
        $sample_video = DB::table('video')->where('VideoId', $id)->first();
    }

    if (empty($artist_result) && empty($sample_video)) {
        return redirect('/');
    }

    $user_name = Auth::user()->user_name;
    $comment_detail = DB::table('testimonials')
  ->orderByRaw("RAND()")
  ->where('by_profile_id','=',Auth::user()->profile_id)
  ->where('video_id', $id)
  ->take(5)->get();
    $related_video=DB::table('requested_videos')->where('requestby',Auth::user()->profile_id)->get();
    $user_detail=DB::table('users')->where('user_id', Auth::user()->user_id)->first();
    $profileId = !empty($artist_result)?$artist_result->uploadedby:$sample_video->ProfileId;
    $artist_path = DB::table('profiles')
  ->where('ProfileId', '=', $profileId)
  ->select('*')
  ->first();
    //dd($artist_path);
    $video_data['video_detail']=$artist_result;
    $video_data['comment_detail'] = $comment_detail;
    $video_data['user_detail'] = $user_detail;
    $video_data['related_video'] = $related_video;
    $video_data['artist_path'] = !is_null($artist_path) ? $artist_path->profile_url : null;
    $video_data['name'] = !is_null($artist_path) ? $artist_path->Name : 'Artist Deleted';
    $video_data['user_name'] =  $user_name;
  $video_data['sample_video'] = $sample_video;
  
  $video_data['reactionvideos'] = ReactionVideo::whereRequestedVideoId($id)->get(); 
  
  return view('frontend.UserVideoDetails',$video_data);


}  
public function video_detail($profile_id,$name){
  $artist_result = DB::table('users')->where('profile_id', $profile_id)->first();

  if($artist_result == null){

   return redirect('/');
 }
 else{
   $result = DB::table('video')->where('Title', $name)->first();
   if(count($result)>0){
    $user_detail = Profile::find($profile_id);
   //  $comment_detail = DB::table('testimonials')->select('testimonials.*','profiles.*')
   //  ->join('profiles',function($join){
   //   $join->on('profiles.ProfileId','=','testimonials.by_profile_id')
   //   ->where('testimonials.AdminApproval', '=', 1);
   // })->where('testimonials.video_id', '=',$result->VideoId)->orderBy('id',"desc")
   //  ->take(5)
   //  ->get();
    $comment_detail = DB::table('testimonials')->where('video_id','=',$result->VideoId)
    ->orderByRaw("RAND()")
    ->where('AdminApproval','=',1)
    ->where('to_profile_id','=',$profile_id)
    ->take(5)->get();
    $other_video = Video::where('ProfileId','=',$profile_id)->take(5)->get();
    $req_pro_id = DB::table('requestvideos')->select('requestToProfileId')->get();
    $user=Auth::user();
    $video['user_detail'] = $user_detail;
    $video['user'] = $user;
    $video['video_detail'] = $result;
    $video['comment_detail'] = $comment_detail;
    $video['other_video'] = $other_video;
    $video['req_pro_id'] = $req_pro_id;
    return view('frontend.VideoDetails',$video);
  }
  else{
    return redirect('/');
  }
}
}

    public function artist_detail($url, Request $request)
    {

  $result = DB::table('profiles')->where('profile_url', $url)->first();
  if($result != null){
    $user = DB::table('users')->where('profile_id', $result->ProfileId)->first();
      if ($user->is_account_active == 0) {
          return redirect('/view-all-artist')->with('error', 'Artist has been deactivated');
    }else{
     $id = $result->ProfileId;
     $user_data = Auth::user();

     $video = DB::select('select 
     requested_videos.title video_title, 
     requested_videos.description video_description,
     requested_videos.fileName video_url,
     artist.name artist_name,
     artist.profile_url artist_profile_url,
     user.name user_name
     from requestvideos 
     join requested_videos on requestvideos.VideoReqId = requested_videos.request_id 
     inner join profiles artist on artist.ProfileId = requested_videos.uploadedby 
     inner join profiles user on user.ProfileId = requested_videos.requestby
     inner join users on artist.ProfileId = users.profile_id and users.type = "Artist" and users.is_account_active = 1
     where requested_videos.uploadedby = '.$id.'
     AND requested_videos.is_email_sent = 1
     AND requestvideos.is_hidden = 0
     ORDER BY requestvideos.VideoReqId DESC         
     limit 5');
     $sample_video = Video::where('ProfileId', $id)->orderByRaw("RAND()")->take(5)->get();
     $social_link = DB::table('social_media')->where('addBy_profileId', $id)->where('is_active','=','Enable')->get();
     $artist_detail = Profile::find($id);
          $testimonials = Testimonial::orderByRaw("RAND()")
         ->where('AdminApproval','=',1)
         ->where('show_artist','=',1)
         ->get();
     $artist_category = DB::table('artist_category')
     ->leftJoin('category', 'artist_category.category_id', '=', 'category.id')
     ->where('artist_category.profile_id', $result->ProfileId);
     $categoryId = $artist_category->pluck('category_id');
     $relatedArtistId = ArtistCategory::whereIn('category_id', $categoryId)
         ->where('profile_id', '!=', $result->ProfileId)->pluck('profile_id');
     $related_artist = Profile::with('testimonial', 'artistCategory.category')
         ->whereIn('ProfileId', $relatedArtistId)
         ->join('users', function ($join) {
             $join->on('users.profile_id', '=', 'profiles.ProfileId')
                 ->where('users.type', '=', 'Artist')
                 ->where('users.is_account_active', '=', '1');
         })
         ->get();
     $artistData['video'] = $video;
     $artistData['artist_detail'] = $artist_detail;
     $artistData['user_data'] = $user_data;
     $artistData['testimonials'] = $testimonials;
     $artistData['id'] = $id;
     $artistData['social_link'] = $social_link;
     $artistData['artist_category'] = $artist_category->get();
     $artistData['related_artist'] = $related_artist;
     $occasionTitles = Occasion::whereArtistProfileId($id)->pluck('title', 'id')->toArray();
     $artistData['occasions'] = $occasionTitles;
     if(Auth::user()!=null && Auth::user()->type=="Artist"){
        $artistData['type'] = 'Artist';
     }else{
      $artistData['type'] = '';
     }
     $artistData['sample_video'] = $sample_video;
     return view('frontend.ArtistVideoList',$artistData);
   }
 }
 else{
      return redirect('/view-all-artist')->with('error','Artist not found ');
 }
}
public function buy_video($video_id){
  if(Auth::check()){
   if(Auth::user()->type=="Artist"){
    return redirect('/');
  }
  elseif (Auth::user()->type=="Admin") {
    return redirect('admin_dashboard');
  }
  else{
    $user_detail = Profile::find(Auth::user()->profile_id);
    $video_detail = Video::find($video_id);
    $buy_info['video_detail'] = $video_detail;
    $buy_info['user_detail'] = $user_detail;
    return view('payment.order',$buy_info);
  }
}
else{
 return redirect('UserLogin');
}
}
//End Admin Signup  
/*--------------------------------------user profile---------------------------------*/
public function user_profile(){
  //dd('test');
  if(Auth::check()){
    if(Auth::user()->type=="Admin" && session('current_type') == "Admin"){
      return redirect('admin_dashboard');
    }else if (Auth::user()->type=="Artist" && session('current_type') == "Artist") {

      return redirect('/');

    }else if(Auth::user()->type=="User" || (Auth::user()->type=="Admin" || session('current_type') == "User")){

      $user_dtl = Profile::find(Auth::user()->profile_id);
      $pageData['user_dtl'] = $user_dtl;
      $user_name = Auth::user()->user_name;
      $pageData['user_name'] = $user_name;
      return view('frontend.UserProfile',$pageData);
    }
  }else{
   return redirect('/');
  			// return view('frontend.login');
 }
}
/*--------------------------------------user profile---------------------------------*/
    public function post_user_profile(Request $request)
    {
        $profile_data = DB::table('users')->where('profile_id', '=', $request->ProfileId)->select('profile_id')->first();
        if (count($profile_data) > 0) {
            if (Auth::check()) {
                if (Auth::user()->type == "Artist" && session('current_type') == "Artist") {
                    return redirect('/');
                } elseif (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                    return redirect('admin_dashboard');
                } elseif (Auth::user()->type == "User" || (Auth::user()->type == "Admin" || session('current_type') == "User")) {
                    $profile_data = DB::table('users')->where('profile_id', '=', $request->ProfileId)->select('*')->first();
                    if ($profile_data->is_account_active == '1') {
                        $data = $request->all();
                        $messages = [
                            // 'phone.regex' => 'Use valid Phone No (as 111-111-1111)',
                        ];
                        $validator = Validator::make($data,
                            array(
                                //'email' =>'required|email',
                                'username' => 'required',
                                'phone' => 'required|min:10',
                                'country' => 'required',
                            ), $messages
                        );
                        if ($validator->fails()) {
                            return redirect('profile')
                                ->withErrors($validator)
                                ->withInput();
                        } else {
                            if (!is_null($request->ProfileId)) {
                                if ($request->date_ofbirth != '') {
                                    $dob = $request->date_ofbirth;
                                } else {
                                    $dob = $request->dob;
                                }
                                Profile::where('ProfileId', $request->ProfileId)
                                    ->update([
                                        'Name' => $request->username,
                                        'DateOfBirth' => $dob,
                                        'MobileNo' => $request->phone,
                                        'NickName' => $request->nickName,
                                        'Address' => $request->address,
                                        'City' => $request->city,
                                        'State' => $request->state,
                                        'country' => $request->country,
                                        'Zip' => $request->zip
                                    ]);
                                User::where('profile_id', $request->ProfileId)
                                    ->update([
                                        'date_of_birth' => $dob,
                                        'phone_no' => $request->phone,
                                    ]);
                                return redirect('profile')->with('success', "successfully updated ");
                            } else {
                                return redirect('profile')->with('error', "Update Not Successfully");
                            }
                        }
                    } else {
                        return redirect('/');
                    }
                }
            } else {
                return redirect('/');
            }


        } else {
            return redirect('/');
        }


    }
/*-----------------------------------User Login-------------------------------------*/ 
public function UserLogin(){
  if(Auth::check()){
   if(Auth::user()->type=="Artist"){
    return redirect('/');
  }
  elseif (Auth::user()->type=="Admin") {
    return redirect('admin_dashboard');
  }
  else{
    $user = Profile::find(Auth::user()->profile_id);
    $video = Video::all();
    $artist = Profile::where('type','User')->get();
    $pageData['user'] = $user;
    $pageData['video'] = $video;
    $pageData['artist'] = $artist;
    $pageData['baseurl'] = "https://www.videorequestline.com/";
    return redirect('profile');
  }
}
else{
 return view('frontend.UserLogin');
}
}
public function UserLoginForm(Request $request){
  $data = $request->all();
  $validator = Validator::make($data,
   array(
    'email' =>'required|email',
    'password' =>'required'
    )
   );
  if($validator->fails()){
   return redirect('UserLogin')
   ->withErrors($validator)
   ->withInput();
 }else{
   $email=$request->email;
   $password=$request->password;
   $is_email_active = User::is_email_active($email);
   $is_account_active = User::is_account_active($email);
   if($is_email_active == "0"){
    return redirect('UserLogin')->with('login_error',"'You need to confirm your account. We have sent you an activation code, please check your email.'");
  }
  elseif ($is_account_active == "0") {
    return redirect('UserLogin')->with('login_error',"Your Account is deactiveated.");
  }
  else{
    $login_result = User::where('email',$email)->first();
    if(count($login_result)>0){
     $user_type = $login_result->type;
     if($user_type=='User'){
      $user = array('email' =>$email,'password' =>$password);
      Auth::attempt($user);
      return redirect('profile');
    }
    else{
      return redirect('UserLogin')->with('login_error',"Invalid email or password");
    }
  }
  else{
   return redirect('UserLogin')->with('login_error',"Invalid email or password");
 }
}
}
}
/*-----------------------------------User register-------------------------------------*/ 
public function UserSignup(){
  if(Auth::check()){
   if(Auth::user()->type=="Artist"){
    return redirect('/');
  }
  elseif (Auth::user()->type=="Admin") {
    return redirect('admin_dashboard');
  }
  elseif (Auth::user()->type=="User") {
    return redirect('mydashboard');
  }
}
else{
 return view('frontend.UserRegistration');
}
}
public function UserSignupFrom(Request $request){
  $data = $request->all();
  $validator = Validator::make($data,
   array(
    'fname' =>'required',
    'lname' =>'required',
    'email' =>'required|email',
    'password' =>'required|min:10',
    'confirm_password'=>'required|same:password'             
    )
   );
  if($validator->fails())
  {
   return redirect('UserSignup')
   ->withErrors($validator)
   ->withInput();
 }
 else
 {
   $email = DB::table('profiles')->where('EmailId',$request->email)->first();
   if(count($email) > 0){
    return redirect('UserSignup')->with('register_error',"Email Already Exist");
  }
  else 
  {
    $users = new User();
    $Profile = new Profile();
    $is_account_active=0;
    $is_email_active=0;
    $type='User';
    $users->user_name= $request->fname.''.$request->lname;
    $Profile->Name= $request->fname;
    $users->email= $request->email;
    $Profile->EmailId= $request->email;
    $users->password= Hash::make($request->password);
    $users->remember_token = $request->_token;
    $users->is_account_active = $is_account_active;
    $users->is_email_active = $is_email_active;
    $users->type = 'User';
    $Profile->Type = 'User';
    $Profile->save();
    $profile_id = $Profile->ProfileId; 
    $users->profile_id = $profile_id;
    $auth_pass = str_random(15);
    $users->auth_pass = $auth_pass;
    $confirmation_code['confirmation_code'] = encrypt($auth_pass);
    if($users->save()){
     Mail::send('emails.UserReminder', $confirmation_code, function ($message) use ($request) {
      $message->from('codingbrains6@gmail.com', 'Confirmation Register');
      $message->to($request->email, $request->fname.''.$request->lname);
      $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
      $message->subject('Email Confirmation');
    });
     return redirect('UserSignup')->with('success','Successfully Registered');
   }
   else{
     return redirect('UserSignup')->with('register_error',"Oops..!Something went wrong");
   }
 }
}
}
public function new_request_video($id){
  if(Auth::check()){
   if(Auth::user()->type=="Artist"){
    return redirect('/');
  }
  elseif (Auth::user()->type=="Admin") {
    return redirect('admin_dashboard');
  }
  elseif (Auth::user()->type=="User") {
    return view('frontend.RequestNewVideo')->with('id',$id);
  }
}
else{
 return redirect('/RequestNewVideo');
}
}
public function new_request_videoForm(Request $request){
  if(Auth::check()){
   $data = $request->all();
   $id=$request->artist_id;
   $url='new_request_video/'.$id;
   $validator = Validator::make($data,
    array(
     'title' =>'required',
     'description' =>'required',
     'delivery' =>'required|date'
     )
    );
   if($validator->fails()){
    return redirect($url)
    ->withErrors($validator)
    ->withInput();
  }else{
    $user = Auth::user();
    $current = Carbon::now();
    $RequestStatus="Pending";
    $Requestvideo= new Requestvideo();
    $Requestvideo->Name=$user->user_name;
    $Requestvideo->requestToProfileId=$id;
    $Requestvideo->RequestStatus=$RequestStatus;
    $Requestvideo->Description=$request->description;
    $Requestvideo->RequestDate=$current;
    $Requestvideo->Title=$request->title;
    $Requestvideo->requestByProfileId=$user->profile_id;
    $Requestvideo->complitionDate=$request->delivery;
    if($Requestvideo->save())
    {
     $msg="You Have Submitted New Request Successfully !!";
     return redirect($url)
     ->withErrors($msg)
     ->withInput();
   }else{
     return "NOt";    
   }
 }
}else{
 return redirect('UserLogin')->with('login_error',"Login to Request video");
}
}
/*---------------------------------Delete video request-------------------------------*/
public function delete_video_request($request_id,$prof_id){
  $profile_data = DB::table('users')->where('profile_id','=',$prof_id)->select('profile_id')->first();
  if(count($profile_data)>0){
   if(Auth::check()){
    if(Auth::user()->type=="Artist" && session('current_type') == "Artist"){
     return redirect('/');
   }
   elseif (Auth::user()->type=="Admin" && session('current_type') == "Admin") {
     return redirect('admin_dashboard');
   }
   elseif (Auth::user()->type=="User" || (Auth::user()->type=="Admin" || session('current_type') == "User")) {
     $profile_data = DB::table('users')->where('profile_id','=',$prof_id)->select('*')->first();
     if($profile_data->is_account_active=='1'){
      DB::table('requestvideos')->where('VideoReqId',$request_id)->update(array('is_delete' => 'true'));
      DB::table('requested_videos')->where('request_id',$request_id)->update(array('is_active' => '0' ));
      return redirect('/user_dashboard');
    }else{
      return redirect('/');
    }
  }
}
else{
  return redirect('/');
}
}else{
 return redirect('/');
}


}
/*------------------------------------Artist Video Details---------------------------------*/
public function ArtistVideos(Request $request){
  $data = DB::table('video')->where('ProfileId',$request->id)->get();
  $artistData['video'] = $data;
  return view('frontend.ArtistVideoList',$artistData);
		//return view('frontend.ArtistVideoList')->with('video_detail',$pageData);
}

    public function changeLocale(Request $request)
    {
        if (array_key_exists($request->local, \Config::get('translation.locales'))) {
            return redirect('/'.$request->local);
        } else {
            return redirect('/');
        }
    }
}
