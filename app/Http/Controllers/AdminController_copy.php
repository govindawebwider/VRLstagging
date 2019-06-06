<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Requests;
use DB;
use App\Admin;
use App\Video;
use App\User;
use App\Slider;
use App\Profile;
use App\Payment;
use App\Requestvideo;
use App\RequestedVideo;
use App\Testimonial;
use Auth;
use Carbon\Carbon;
use Validator;
use Hash;
use Mail;
use Session;
use Crypt;
use Snipe\BanBuilder\CensorWords;
class AdminController_copy extends Controller{
	public function __construct()
	{
		$this->middleware('user_active');
		$this->middleware('revalidate');

	}

	public function video_upload(){

		foreach(array('video', 'audio') as $type) {
			if (isset($_FILES["${type}-blob"])) {

				$fileName = $_POST["${type}-filename"];
				$uploadDirectory = base_path().'/uploads/'.$fileName;

				if (!move_uploaded_file($_FILES["${type}-blob"]["tmp_name"], $uploadDirectory)) {
					echo(" problem moving uploaded file");
				}

				echo($uploadDirectory);
			}
		}
	}

	public function hide_comp_video($id){
    	//echo $id;
		if( DB::table('requested_videos')
			->where('id',$id)
			->update(array('remain_storage_duration' => 'disable' ))){
			return redirect( url()->previous());
	}
}
public function video_purge(){
	if(Auth::check()){
		if(Auth::user()->type=="Admin"){
			$video_request = DB::table('requested_videos')
			->select('*')
			->orderBy('id', 'desc')
			// ->paginate(5);
			->get();
			return view('admin.video_purge',['videos'=>$video_request]);
		}else{
			return redirect('/admin');	
		}
	}else{
		return redirect('/admin');
	}

}
public function set_purge(){
	if(Auth::check()){
		if(Auth::user()->type=="Admin"){
			$purge_data = DB::table('setting')->select('status')->where('name','=','purge')->first();
			$data['purge_data']=$purge_data;
			return view('admin.SetPurgeSetting',$data);
		}
	}else{
		return redirect('/admin');;
	}

}
public function post_set_purge(Request $request){
	//dd($request->all());
	$data=$request->all();
	$validator = Validator::make($data,
		array(
			'Purge_val' =>'required|integer',
			)
		);
	if($validator->fails()){
		return redirect('/set_purge')
		->withErrors($validator)
		->withInput();
	}
	else{
		$purge_data = DB::table('setting')->select('status')->where('name','=',"purge")->get();
		if($purge_data==null){
			//echo "insert";
			DB::table('setting')->insert(
				array('status' => $request->Purge_val,'name'=>'purge'));
			return redirect('/set_purge')->with('success',"Successfully Added ");	
		}else{
			DB::table('setting')->where('name','purge')->update(array('status' => $request->Purge_val ));
			return redirect('/set_purge')->with('success',"Successfully Updated ");
			//echo "update";
		}
	}

}
public function threshold_setting(){
	if(Auth::check()){
		if(Auth::user()->type=="Admin"){
			$signup_data = DB::table('setting')->select('status')->where('name','=','threshold')->first();
			$data['threshold']=$signup_data;
			return view('admin.threshold_setting',$data);
		}
	}else{
		return redirect('/admin');;
	}

}

public function post_threshold_setting(Request $request){
	//dd($request->all());
	$data = $request->all();
		//dd($data);
	$validator = Validator::make($data,
		array(
			'threshold_val' =>'required|integer',
			)
		);
	if($validator->fails()){
		return redirect('/threshold_setting')
		->withErrors($validator)
		->withInput();
	}
	else{
		$threshold_data = DB::table('setting')->select('status')->where('name','=',"threshold")->get();
		if($threshold_data==null){
			DB::table('setting')->insert(
				array('status' => $request->threshold_val,'name'=>'threshold'));
			return redirect('/threshold_setting')->with('success',"Successfully Added ");	
		}else{
			DB::table('setting')->where('name','threshold')->update(array('status' => $request->threshold_val ));
			return redirect('/threshold_setting')->with('success',"Successfully Updated ");
		}
	}
}
public function admin_videoPrice(){
	if(Auth::check()){
		if(Auth::user()->type=="Admin"){
			$video_price = DB::table('setting')->where('name','=','video_price')->first();
			$data['signup_data']=$video_price;
			return view('admin.default_videoPrice',$data);
		}
	}else{
		return redirect('/admin');;
	}
}
public function signup_setting(){
	if(Auth::check()){
		if(Auth::user()->type=="Admin"){
			$signup_data = DB::table('setting')->select('status')->where('name','=','signup')->first();
			$data['signup_data']=$signup_data;
			return view('admin.signup_setting',$data);
		}
	}else{
		return redirect('/admin');;
	}
}
public function post_admin_videoPrice(Request $request){
     	//dd($request->all());
	$data = $request->all();
		//dd($data);
	$validator = Validator::make($data,
		array(
			'videoPrice' =>'required',
			)
		);
	if($validator->fails()){
		return redirect('/admin_videoPrice')
		->withErrors($validator)
		->withInput();
	}else{
		$video_price = DB::table('setting')->where('name','=','video_price')->first();
		//dd($video_price);
		if($video_price==null){
			DB::table('setting')->insert(
				array('status' => $request->videoPrice,'name'=>'video_price'));
			return redirect('/admin_videoPrice')->with('success',"Successfully Added ");	
		}else{
			DB::table('setting')->where('name','video_price')->update(array('status' => $request->videoPrice ));
			return redirect('/admin_videoPrice')->with('success',"Successfully Updated ");
		}
	}
}

public function post_signup_setting(Request $request){
     	//dd($request->all());
	$data = $request->all();
		//dd($data);
	$validator = Validator::make($data,
		array(
			'SignUp' =>'required',
			)
		);
	if($validator->fails()){
		return redirect('/signup_setting')
		->withErrors($validator)
		->withInput();
	}
	else{
		$signup_data = DB::table('setting')->select('status')->get();
		if($signup_data==null){
			DB::table('setting')->insert(
				array('status' => $request->SignUp,'name'=>'signup'));
			return redirect('/signup_setting')->with('success',"Successfully Added ");	
		}else{
			DB::table('setting')->where('name','signup')->update(array('status' => $request->SignUp ));
			return redirect('/signup_setting')->with('success',"Successfully Updated ");
		}
	}
}
public function add_help(){
    	//echo "about";
	if(Auth::check()){
		if(Auth::user()->type=="Admin"){
			$help_data=DB::table('footer_content')->where('type', 'help')->select('content')
			->first();
			$artist_data['help_data']=$help_data;
				//dd($about_data->content);
			return view('admin.help',$artist_data);
		}
	}else{
		return redirect('/admin');;
	}
}
public function post_add_help(Request $request){
	    //dd($request->all());
	if(DB::table('footer_content')->where('type', 'help')->update(['content' => $request->help_content])){
		return redirect('add_help')->with('success',"Successfully updated ");
	}
	else
		{   //dd($request->all());
			DB::table('footer_content')->insert(
				array('type' => 'help', 'content' => $request->help_content));
			return redirect('add_help')->with('success',"Successfully updated ");
		}
	}
	public function add_about(){
    	//echo "about";
		if(Auth::check()){
			if(Auth::user()->type=="Admin"){
				$about_data=DB::table('footer_content')->where('type', 'About')->select('content')
				->first();
				$artist_data['about_data']=$about_data;
				//dd($about_data->content);
				return view('admin.about',$artist_data);
			}
		}else{
			return redirect('/admin');;
		}
	}
	public function post_add_about(Request $request){
	    //dd($request->all());
		if(DB::table('footer_content')->where('type', 'About')->update(['content' => $request->about_content])){
			return redirect('add_about')->with('success',"successfully updated ");
		}
		else
		{	
			DB::table('footer_content')->insert(
				array('type' => 'About', 'content' => $request->about_content));
			return redirect('add_about')->with('success',"successfully updated ");
		}
	}
	public function add_terms(){
		if(Auth::check()){
			if(Auth::user()->type=="Admin"){
				$term_data=DB::table('footer_content')->where('type', 'Terms and conditions')->select('content')
				->first();
				//dd($term_data);
				$artist_data['term_data']=$term_data;
				return view('admin.terms',$artist_data);
			}
		}else{
			return redirect('/admin');;
		}
	}
	public function post_add_terms(Request $request){
	    //dd($request->all());
		if(DB::table('footer_content')->where('type', 'Terms and conditions')->update(['content' => $request->term_content])){
			return redirect('add_terms')->with('success',"successfully updated ");
		}
		else
		{
			DB::table('footer_content')->insert(
				array('type' => 'Terms and conditions', 'content' => $request->term_content));
			return redirect('add_terms')->with('success',"successfully updated ");
		}
	}
	public function add_privacy(){
		if(Auth::check()){
			if(Auth::user()->type=="Admin"){
				$privacy_data=DB::table('footer_content')->where('type', 'Privacy policy')->select('content')
				->first();
				//dd($term_data);
				$artist_data['privacy_data']=$privacy_data;
				return view('admin.privacy',$artist_data);
			}
		}else{
			return redirect('/admin');;
		}
	}
	public function post_add_privacy(Request $request){
	    //dd($request->all());
		if(DB::table('footer_content')->where('type', 'Privacy policy')->update(['content' => $request->privacy_content])){
			return redirect('add_privacy')->with('success',"successfully updated ");
		}
		else
		{
			DB::table('footer_content')->insert(
				array('type' => 'Privacy policy', 'content' => $request->privacy_content));
			return redirect('add_privacy')->with('success',"successfully updated ");
		}
	}
	public function create_artist(){
		if(Auth::check()){
			if(Auth::user()->type=="Admin"){
				return view('admin.create_artist');
			}
		}else{
			return redirect('/admin');;
		}

	}
	public function invite_artist(){
		if(Auth::check()){
			if(Auth::user()->type=="Admin"){
				return view('admin.invite_artist');
			}
		}else{
			return redirect('/admin');;
		}

	}
	public function post_invite_artist(Request $request){
		/*$auth_pass = str_random(15);

		$confirmation_code['confirmation_code'] = encrypt($auth_pass);
		$confirmation_code['artist_pass'] ="123";
		$confirmation_code['artist_email'] ='codingbrains6@gmail.com';
		Mail::send('emails.invite_artist', $confirmation_code, function ($message) use ($request) {
			$message->from('noreply@videorequestline.com', 'Registration Confirmation');
			$message->to('codingbrains6@gmail.com', 'rock');
			$message->subject('Email Confirmation');
		});*/
		//dd($request->all());
		$messages = [
		'username.required' => 'The Artist name field is required.',
		'username.unique'=> 'User name has already been taken. Please enter another name',
		'artistEmail.email' =>'Use valid Email address (as xyz@gmail.com)'
		];
		$validator = Validator::make(
			array(
				'username' =>$request->username,
				'artistEmail' =>$request->artistEmail,
				'gender' =>$request->gender,
				),
			array(
				'username' =>'required|regex:/^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-0-9-, ])*$/|unique:users,user_name',
				'artistEmail' =>'required|email|unique:users,email',
				'gender' =>'required',
				),$messages
			);
		if($validator->fails()){
			return redirect('/invite_artist')
			->withErrors($validator)
			->withInput();
		}else{
			$email = DB::table('profiles')->where('EmailId',$request->email)->first();
			$name= DB::table('profiles')->where('Name',$request->username)->first();
			if(count($email) > 0){
				return redirect('create_artist')->with('register_error',"Email Already Exist");
			}else{
				$users = new User();
				$Profile = new Profile();
				$is_account_active=0;
				$is_email_active=0;
				$type='Artist';
				$users->user_name= $request->username;
				$users->email= $request->artistEmail;
				$users->remember_token = $request->_token;
				$users->is_account_active = $is_account_active;
				$users->is_email_active = $is_email_active;
				$users->gender = $request->gender;
				$users->type = $type;
				$artistPassword = str_random(15);
				$users->password= Hash::make($artistPassword);

				$Profile->Name= $request->username;
				$Profile->EmailId= $request->artistEmail;
				$Profile->Type = $type;
				$Profile->Gender = $request->gender;
				$Profile->profile_url= strtolower($request->username);
				$Profile->profile_path ="images/Artist/default-artist.png";
				$Profile->BannerImg ="images/vrl_bg.jpg";
				$Profile->header_image ="/images/default_header.jpg";
				$Profile->video_background ="images/vrl_bg.jpg";
				$video_price = DB::table('setting')->where('name','=','video_price')->first();
				$Profile->VideoPrice =$video_price->status;
				$Profile->timestamp =15;
				$Profile->save();

				$profile_id = $Profile->ProfileId;
				$users->profile_id = $profile_id;
				$auth_pass = str_random(15);
				$users->auth_pass = $auth_pass;
				$confirmation_code['confirmation_code'] = encrypt($auth_pass);
				$confirmation_code['artist_pass'] =$artistPassword;
				$confirmation_code['artist_email'] =$request->artistEmail;

				if($users->save()){
					Mail::send('emails.invite_artist', $confirmation_code, function ($message) use ($request) {
						$message->from('noreply@videorequestline.com', 'Registration Confirmation');
						$message->to($request->artistEmail, 'ROCK');
						$message->subject('Email Confirmation');
					});
				}
			}
			return redirect('/invite_artist')->with('success','Invitation Successfully send to Artist Email');
		}
		
	}
	
	public function add_admin_slider(){
		if(Auth::check()){
			if(Auth::user()->type=="Admin"){
				return view('admin.add_slider');
			}
		}else{
			return redirect('/admin');;
		}
	}
	public function post_add_admin_slider(Request $request){
		$data = $request->all();

		$validator = Validator::make($data,
			array(
				'slider_title' =>'required|max:100',
				//'slider_description' =>'required',
				'slider' =>  'mimes:jpeg,png'
				)
			);
		if($validator->fails()){
			return redirect('add_admin_slider/')
			->withErrors($validator)
			->withInput();
		}
		else{
			$file = $_FILES["slider"]['tmp_name'];
			list($width, $height) = getimagesize($file);
			if($width < "400" || $height < "400") {
				return redirect('add_admin_slider')->with('error','image size must be 400 x 400 pixels.');

			}else{
				if($request->file('slider') != ""){
					$file = $request->file('slider');
					$filename = $file->getClientOriginalName();
					$slider_path = "images/Sliders/".date('U').'.jpeg';
					$destinationPath = base_path() . '/public/images/Sliders/';
					$request->file('slider')->move($destinationPath, $slider_path);
				}
				if(Slider::where('id','=',$request->slider_id)->update(array('slider_title' => $request->slider_title ,'slider_description' => $request->slider_description,'slider_path' =>$slider_path))){
					return redirect('add_admin_slider')->with('success','Successfully Updated!');
				}else{
					DB::table('sliders')->insert(array('slider_visibility' => 1, 'slider_description' => $request->slider_description,'slider_path' =>$slider_path,'slider_title' =>$request->slider_title ,'artist_id'=>Auth::user()->profile_id));
					return redirect('add_admin_slider')->with('success','Successfully Updated!');
				}

			}	
		}
	}
	

	public function add_admin_testimonial(){
		//dd("test");
		$admin =  Profile::where('EmailId',Auth::user()->email)->first();
		$artist = DB::table('users')
		->join('profiles', 'users.profile_id', '=', 'profiles.ProfileId')
		->select('profiles.*', 'users.*')
		->where('profiles.Type','=','Artist')
		->where('users.is_account_active','=','1')
		->get();
		$admin_data['admin'] = $admin;
		$admin_data['artist'] = $artist;
		return view('admin.admin_testimonial',$admin_data);
	}
	public function post_add_admin_testimonial(Request $request){
		$data = $request->all();
		//dd($data);
		
		$messages = [
		'required' => 'The :attribute field is required.',

		'name.regex' => 'Use valid User name (as xyz or xyz1)',


		];
		$validator = Validator::make(
			array(
				'user_name' =>$request->user_name,
				'message' =>$request->message,
				'email' =>$request->email,

				),
			array(
				'user_name' =>'required|regex:/^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-0-9-, ])*$/',
				'email' =>'required|email',
				'message' =>'required',

				),$messages
			);
		if($validator->fails()){
			return redirect( url()->previous())
			->withErrors($validator)
			->withInput();
		}else{
			$artist_data=DB::table('users')->where('profile_id','=',$request->artist_id)->first();
			if($artist_data->is_account_active == 1){
				$testimonial =   new Testimonial();
				$testimonial->user_name = $request->user_name;
				$testimonial->Email = $request->email;
				$testimonial->to_profile_id = $request->artist_id;
				$testimonial->AdminApproval = 1;
				$censor = new CensorWords;
				$string = $censor->censorString($request->message);
				$testimonial->message = $string['clean'];
				if($testimonial->save()){
					return redirect( url()->previous())->with('success','Your Comment added successfully');
				}
			}else{
				return redirect( url()->previous())->with('success','Artist is deactivated');
			}
		}
		
	}
	public function default_testimonial(){
		$admin =  Profile::where('EmailId',Auth::user()->email)->first();
		$artist = DB::table('users')
		->join('profiles', 'users.profile_id', '=', 'profiles.ProfileId')
		->select('profiles.*', 'users.*')
		->where('profiles.Type','=','Artist')
		->where('users.is_account_active','=','1')
		->get();
		$admin_data['admin'] = $admin;
		$admin_data['artist'] = $artist;
		return view('admin.add_default_testimonial',$admin_data);
	}
	public function post_default_testimonial(Request $request){
		$data = $request->all();
		$validator = Validator::make($data,
			array(
				'message' =>'required'
				)
			);
		if($validator->fails()){
			return redirect( url()->previous())
			->withErrors($validator)
			->withInput();
		}else{
			//$user = Auth::user();
			$artist_data=DB::table('users')->where('profile_id','=',$request->artist_id)->first();
			if($artist_data->is_account_active == 1){
				$testimonial =   new Testimonial();
				$testimonial->by_profile_id = $request->to_profile_id;
				$testimonial->to_profile_id = $request->artist_id;
				
				//$testimonial->video_id = $request->video_id;
				$testimonial->AdminApproval = 1;
				$censor = new CensorWords;
				$string = $censor->censorString($request->message);
				$testimonial->message = $string['clean'];
				if($testimonial->save()){
					return redirect( url()->previous())->with('success','Your Comment added successfully');
				}
			}else{
				return redirect( url()->previous())->with('success','Artist is deactive by admin');
			}
		}
	}
	public function post_create_artist(Request $request){
		$messages = [
		'required' => 'The :attribute field is required.',
		'username.required' => 'The Artist Name field is required.',
		'confirmpassword.required'=> 'The Confirm password field is required.',
		'username.regex' => 'Use valid User name (as xyz or xyz1)',
		'artistPassword.regex' => ' Use at least one letter, one numeral & one special character',
		'profile.required' => 'The profile image field is required',
		'username.unique'=> 'User name has already been taken. Please enter another name',
		// 'phone.regex' => 'Use valid Phone No (as 111-111-1111)',
		'artistEmail.email' =>'Use valid Email address (as xyz@gmail.com)'
		];
		$validator = Validator::make(
			array(
				'username' =>$request->username,
				'artistEmail' =>$request->artistEmail,
				'artistPassword' =>$request->artistPassword,
				'confirmpassword' =>$request->confirmpassword,
				'phone' =>$request->phone,
				'gender' =>$request->gender,
				),
			array(
				'username' =>'required|regex:/^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-0-9-, ])*$/|unique:users,user_name',
				'artistEmail' =>'required|email|unique:users,email',
				'artistPassword' =>'required|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/',
				'confirmpassword' =>'required|same:artistPassword',
				'phone' =>'required',
				'gender' =>'required',
				),$messages
			);
		if($validator->fails()){
			return redirect('/create_artist')
			->withErrors($validator)
			->withInput();
		}else{
			$email = DB::table('profiles')->where('EmailId',$request->email)->first();
			$name= DB::table('profiles')->where('Name',$request->username)->first();
			if(count($email) > 0){
				return redirect('create_artist')->with('register_error',"Email Already Exist");
			}else{
				$users = new User();
				$Profile = new Profile();
				$is_account_active=0;
				$is_email_active=0;
				$type='Artist';
				$users->user_name= $request->username;
				$users->email= $request->artistEmail;
				$users->password= Hash::make($request->artistPassword);
				$users->remember_token = $request->_token;
				$users->is_account_active = $is_account_active;
				$users->is_email_active = $is_email_active;
				$users->gender = $request->gender;
				$users->type = $type;
				$users->phone_no = $request->phone;
				$Profile->Name= $request->username;
				$Profile->EmailId= $request->artistEmail;
				$Profile->Type = $type;
				$Profile->Gender = $request->gender;
				$Profile->MobileNo = $request->phone;
				$video_price = DB::table('setting')->where('name','=','video_price')->first();
				$Profile->VideoPrice = $video_price->status;
				$Profile->timestamp = 15;
				if($request->profile ==""){
					$profile_path="images/Artist/default-artist.png";
					$Profile->profile_path= $profile_path;
				}else{
					$profile_path="images/Artist/default-artist.png";
					$Profile->profile_path= $profile_path;
				}
				$Profile->profile_url= strtolower($request->username);
				$Profile->BannerImg ="images/vrl_bg.jpg";
				$Profile->header_image ="/images/default_header.jpg";
				$Profile->video_background ="images/vrl_bg.jpg";
				$Profile->VideoPrice =0.99;
				$Profile->save();
				$profile_id = $Profile->ProfileId;
				$users->profile_id = $profile_id;
				$auth_pass = str_random(15);
				$users->auth_pass = $auth_pass;
				$confirmation_code['confirmation_code'] = encrypt($auth_pass);
				if($users->save()){
					Mail::send('emails.reminder', $confirmation_code, function ($message) use ($request) {
						$message->from('noreply@videorequestline.com', 'Registration Confirmation');
						$message->to($request->artistEmail, $request->username);
						$message->subject('Email Confirmation');
					});
				}
			}
			return redirect('/success_create_artist')->with('success','Successfully Registered');
		}
	}
	public function success_create_artist(){
		if(Auth::check()){
			if(Auth::user()->type=="Admin"){
				return view('admin.success_create_artist');
			}
		}else{
			return redirect('/admin');
		}

	}
	

	public function view_slider($slider_id){
		if(Auth::check()){
			if(Auth::user()->type=="Admin"){
				$sliders = Slider::find($slider_id);
				return view('admin.edit_slider',['sliders'=>$sliders]);
			}
		}else{
			return redirect('/admin');
		}

	}
	public function post_update_slider(Request $request){
		$data = $request->all();
    //dd($data);
		$validator = Validator::make($data,
			array(
				'slider_title' =>'required|max:100',
				//'slider_description' =>'required',
				'slider' =>  'mimes:jpeg,png'
				)
			);
		if($validator->fails()){
			return redirect('view_slider/'.$request->slider_id)
			->withErrors($validator)
			->withInput();
		}
		else{

			if($request->file('slider') != ""){
				$file = $_FILES["slider"]['tmp_name'];
				list($width, $height) = getimagesize($file);
				if($width < "400" || $height < "400") {
					return redirect('view_slider/'.$request->slider_id)->with('error','image size must be 400 x 400 pixels.');
				}else{

					$file = $request->file('slider');
					$filename = $file->getClientOriginalName();
					$slider_path = "images/Sliders/".date('U').'.jpeg';
					$destinationPath = base_path() . '/public/images/Sliders/';
					$request->file('slider')->move($destinationPath, $slider_path);

					if(Slider::where('id','=',$request->slider_id)->update(array('slider_title' => $request->slider_title ,'slider_description' => $request->slider_description,'slider_path' =>$slider_path))){

						return redirect('view_slider/'.$request->slider_id)->with('success','Successfully Updated!');

					}
				}

			}else{
				if(Slider::where('id','=',$request->slider_id)->update(array('slider_title' => $request->slider_title ,'slider_description' => $request->slider_description))){

					return redirect('view_slider/'.$request->slider_id)->with('success','Successfully Updated!');

				}
			}
		}
	}

	/*------------------------Artist profile Delete---------------------*/
	public function delete_artist($id){

		
		//$videourl[]=$videos_dtl->VideoURL;
		//$videoThumbnail[]=$videos_dtl->VideoThumbnail;
		//$originalVideoUrl[]=$videos_dtl->originalVideoUrl;


		//dd($videos_dtl);
		if(Auth::check()){
			if(Auth::user()->type=="Admin"){
				DB::table('users')->where('profile_id',$id)->delete();
				DB::table('profiles')->where('ProfileId',$id)->delete();
				DB::table('sliders')->where('artist_id',$id)->delete();
				DB::table('testimonials')->where('id',$id)->delete();

				$videos_dtl = DB::table('video')->where('ProfileId','=',$id)->get();
				if($videos_dtl != null){
					foreach ($videos_dtl as $VideouRL) {
						unlink($VideouRL->VideoURL);
						unlink($VideouRL->VideoThumbnail);
						unlink($VideouRL->originalVideoUrl);
					}
					
				}
			//return view('admin.artist_list',);
				return redirect('artists');
			}
		}
	}
	

	/*--------------------Update Artist Account----------------*/
	public function update_artist_dtl($id){
		if(Auth::user()->type=="Admin"){
			$profileData =  Profile::where('ProfileId',$id)->first();
			//dd($profileData);
			return view('admin.update_artist_dtl',['profileData'=>$profileData]);

		}
	}

	public function admin_post_update_artist_dtl(Request $request){
		$data = $request->all();
		$profileData =  Profile::where('ProfileId',$request->profileId)->first();
		if($profileData->Zip == null){
			return redirect(url()->previous())->with('error','Please go to edit profile option and fill zip code');	
		}else{
			$validator = Validator::make(
				array(
					'routing_number' =>$request->routing_number,
					'account_number' =>$request->account_number,
					'confirm_account_number' =>$request->confirm_account_number,
					'ssn_number' =>$request->ssn_number,
					'pin' =>$request->pin,
					'id_pic' =>$request->file('id_pic')
					),
				array(
					'routing_number' =>'required|min:9|max:9',
					'account_number' =>'required|min:12|max:12',
					'confirm_account_number' =>'required|same:account_number',
					'ssn_number' =>'required|min:11|max:11',
					'pin' =>'required',
					'id_pic' =>'required|mimes:jpeg,png',

					));
			if($validator->fails())
			{
				return redirect(url()->previous())
				->withErrors($validator)
				->withInput();
			}
			elseif(Substr($request->pin,-11) !== $request->ssn_number ){
				return redirect(url()->previous())->with('error','Personal id number and ssn number must match');
			}
			else
			{
				//$id  = Auth::user()->profile_id;
				//$request->profileId;
				$artist_bank_detail = Profile::find($request->profileId);

				if($artist_bank_detail->is_bank_updated == 1){
					if($artist_bank_detail->stripe_account_id == 0){
						return redirect(url()->previous())->with('error','You can not update Because your Account Is not Created yet !');
					}
				}
				else{
					if($artist_bank_detail->is_bank_updated == 0){

						$artist_bank_detail->is_bank_updated= 1;
					}
					else{
						$artist_bank_detail->is_bank_updated= 2;
					}
					$artist_bank_detail->routing_number = Crypt::encrypt($request->routing_number);
					$artist_bank_detail->account_number= Crypt::encrypt($request->account_number);
					$artist_bank_detail->ssn_number= Crypt::encrypt($request->ssn_number);
					$artist_bank_detail->pin= Crypt::encrypt($request->pin);

					$id_pic_path = "images/Artist/id/".date('U').'.jpg';
					$artist_bank_detail->id_pic= $id_pic_path;
					$destinationPath = base_path() . '/public/images/Artist/id/';
					$request->file('id_pic')->move($destinationPath, $id_pic_path);

					if($artist_bank_detail->save()){
						return redirect(url()->previous())->with('success','Successfully Updated! ');

					}
				}

			}
		}
	}
	
	/*--------------------Create Artist Connected Account----------------*/
	public function create_connected_account($id){

		if(Auth::check()){
			if(Auth::user()->type=="Admin"){

				try{

					$artist = Profile::find($id);
					//dd($artist);
					$dob = explode('-',$artist->DateOfBirth);

					/*------------------------------create--------------------------*/
					//\Stripe\Stripe::setApiKey('sk_test_CtVU3fFCOkPs7AbQDLLJmU1n');
					\Stripe\Stripe::setApiKey('sk_live_AuLjanpEXm7L1Iq22XhDBzyR');
					
					$a  = \Stripe\Account::create(
						array(
							"country" => "US",
							"managed" => true,
							"external_account" => array(
								"object" => "bank_account",
								"country" => "US",
								"currency" => "usd",
								"routing_number" =>Crypt::decrypt($artist->routing_number),
								"account_number" => Crypt::decrypt($artist->account_number),
								),
							)
						);
					

					

					$strip_id = $a->id;
					$sn = Crypt::decrypt($artist->ssn_number);
					//$sna = explode('-', $sn);
					/*------------------------------Update---------------------------*/
					$account = \Stripe\Account::retrieve($a->id);
					$account->legal_entity->first_name = $artist->Name;
					$account->legal_entity->last_name = " ";
					$account->legal_entity->dob->day = $dob[1];
					$account->legal_entity->dob->month =$dob[0];
					$account->legal_entity->dob->year = $dob[2];
					$account->legal_entity->type = 'individual';
					$account->tos_acceptance->date = time();
					$account->tos_acceptance->ip = $_SERVER['REMOTE_ADDR'];
					$account->legal_entity->ssn_last_4 = $sn;
				//dd($account);
					if($artist->Zip!=null || $artist->Zip!="")
					{
						if($artist->Address == ""){
							$account->legal_entity->address->line1 = null;
						}else{
							$account->legal_entity->address->line1 = $artist->Address;
						}
						if($artist->City == ""){
							$account->legal_entity->address->city = null;
						}else{
							$account->legal_entity->address->city = $artist->City;
						}

						if($artist->Zip == ""){
							$account->legal_entity->address->postal_code = null;
						}else{
							$account->legal_entity->address->postal_code = $artist->Zip;
						}
						if($artist->State == ""){
							$account->legal_entity->address->state = null;
						}else{
							$account->legal_entity->address->state = $artist->State;
						}
					//$account->legal_entity->personal_id_number=$artist->pin;
						$account->legal_entity->additional_owners =null;
						$account->save();
					//dd();			
						/*------------------------------Verify-----------------------------*/

						$file = \Stripe\FileUpload::create(
							array(
								"purpose" => "identity_document",
								"file" => fopen(public_path().'/'.$artist->id_pic, 'r')
								),
							array("stripe_account" =>$a->id)
							);

						$img_id = $file->id;
						$account1 = \Stripe\Account::retrieve($a->id);
						$account1->legal_entity->verification->document = $img_id;
						if($account1->save()){	
							$artist = Profile::find($id);	
							$artist->stripe_account_id = $strip_id;
							$artist->is_bank_updated = 2;
							$artist->bank_id = $a->external_accounts->data[0]->id;
							$artist->save();
							return redirect('artists')->with('success','Connected Account Created Successfully');
						}
					}else{
						return redirect('artists')->with('error','Artist Zip code is not Valid');
					}


				} catch(\Stripe\Error\Card $e) {
				    // Since it's a decline, \Stripe\Error\Card will be caught
					$err_msg=$e->getMessage();
					return redirect('artists')->with('error',$err_msg)->withInput();
					
				} catch (\Stripe\Error\InvalidRequest $e) {
					$err_msg=$e->getMessage();
					return redirect('artists')->with('error',$err_msg)->withInput();
				    // Invalid parameters were supplied to Stripe's API
				} catch (\Stripe\Error\Authentication $e) {
					$err_msg=$e->getMessage();
					return redirect('artists')->with('error',$err_msg)->withInput();
				    // Authentication with Stripe's API failed
				    // (maybe you changed API keys recently)
				} catch (\Stripe\Error\ApiConnection $e) {
					$err_msg=$e->getMessage();
					return redirect('artists')->with('error',$err_msg)->withInput();
				    // Network communication with Stripe failed
				} catch (\Stripe\Error\Base $e) {
					$err_msg=$e->getMessage();
					return redirect('artists')->with('error',$err_msg)->withInput();
				    // Display a very generic error to the user, and maybe send
				    // yourself an email
				} catch (Exception $e) {
					$err_msg=$e->getMessage();
					return redirect('artists')->with('error',$err_msg)->withInput();
				    // Something else happened, completely unrelated to Stripe
				}
			}
		}
	}
	/*-----------------------------------Update Connected bank details-------------------*/
	public function update_connected_account($id){
		if(Auth::check()){
			if(Auth::user()->type=="Admin"){
				try{
					$artist = Profile::find($id);
					//\Stripe\Stripe::setApiKey('sk_test_CtVU3fFCOkPs7AbQDLLJmU1n');
					\Stripe\Stripe::setApiKey('sk_live_AuLjanpEXm7L1Iq22XhDBzyR');
					$account = \Stripe\Account::retrieve($artist->stripe_account_id);

					$bank_account = $account->external_accounts->retrieve($artist->bank_id);
					$bank_account->last4 = $artist->ssn_number;
					if($bank_account->save()){
						return redirect('artists')->with('success','Connectec Account Updated Successfully');
					}
				} catch(\Stripe\Error\Card $e) {
				    // Since it's a decline, \Stripe\Error\Card will be caught
					$err_msg=$e->getMessage();
					return redirect('artists')->with('error',$err_msg)->withInput();
					
				} catch (\Stripe\Error\InvalidRequest $e) {
					$err_msg=$e->getMessage();
					return redirect('artists')->with('error',$err_msg)->withInput();
				    // Invalid parameters were supplied to Stripe's API
				} catch (\Stripe\Error\Authentication $e) {
					$err_msg=$e->getMessage();
					return redirect('artists')->with('error',$err_msg)->withInput();
				    // Authentication with Stripe's API failed
				    // (maybe you changed API keys recently)
				} catch (\Stripe\Error\ApiConnection $e) {
					$err_msg=$e->getMessage();
					return redirect('artists')->with('error',$err_msg)->withInput();
				    // Network communication with Stripe failed
				} catch (\Stripe\Error\Base $e) {
					$err_msg=$e->getMessage();
					return redirect('artists')->with('error',$err_msg)->withInput();
				    // Display a very generic error to the user, and maybe send
				    // yourself an email
				} catch (Exception $e) {
					$err_msg=$e->getMessage();
					return redirect('artists')->with('error',$err_msg)->withInput();
				    // Something else happened, completely unrelated to Stripe
				}
			}
		}
	}
	/*-----------------------------------User profile Delete-------------------------------------*/
	public function delete_user($id){
		if(Auth::check()){
			if(Auth::user()->type=="Admin"){
				DB::table('users')->where('profile_id',$id)->delete();
				DB::table('profiles')->where('ProfileId',$id)->delete();
				DB::table('requestvideos')->where('requestByProfileId',$id)->delete();
			//return view('admin.artist_list',);
				return redirect('users');
			}
		}
	}
	/*-----------------------------------User profile Update-------------------------------------*/
	public function get_edit_user($id){
		if(Auth::check()){
			if(Auth::user()->type=="Admin"){
				$userData =  Profile::where('ProfileId',$id)->first();
				$profileData =  Profile::where('type',"Admin")->first();
				$artistData['userData'] = $userData;
				$artistData['profileData'] = $profileData;
				$artistData['baseurl'] = "https://www.videorequestline.com/";
				return view('admin.get_edit_user',$artistData);
			}
		}
	}
	/*-----------------------------------Artist profile Update-------------------------------------*/
	public function get_edit_artist($id){
		if(Auth::check()){
			if(Auth::user()->type=="Admin"){
				$userData =  Profile::where('ProfileId',$id)->first();
				$profileData =  Profile::where('type',"Admin")->first();
				$artistData['userData'] = $userData;
				$artistData['profileData'] = $profileData;
				$artistData['baseurl'] = "https://www.videorequestline.com/";
				return view('admin.get_edit_artist',$artistData);
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$userData =  User::where('email',Auth::user()->email)->first();
				$profileData =  Profile::where('EmailId',Auth::user()->email)->first();
				$artistData['userData'] = $userData;
				$artistData['profileData'] = $profileData;
				$artistData['baseurl'] = "https://www.videorequestline.com/";
				return view('frontend.artistDashboard.ProfileUpdate',$artistData);
			}
		}
		else{
			return redirect('admin');
		}
	}
	/*----------------------------------- view Single Artist -------------------------------------*/
	public function view_artist($id){
		if(Auth::check()){
			if(Auth::user()->type=="Admin"){
				$userData =  Profile::where('ProfileId',$id)->first();
				$profileData =  Profile::where('type',"Admin")->first();
				$artistData['artist'] = $userData;
				$artistData['profileData'] = $profileData;
				return view('admin.view_artist',$artistData);
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$userData =  User::where('email',Auth::user()->email)->first();
				$profileData =  Profile::where('EmailId',Auth::user()->email)->first();
				$artistData['userData'] = $userData;
				$artistData['profileData'] = $profileData;
				$artistData['baseurl'] = "https://www.videorequestline.com/";
				return view('frontend.artistDashboard.ProfileUpdate',$artistData);
			}
		}
		else{
			return redirect('admin');
		}
	}
	/*----------------------------------- view Single User -------------------------------------*/
	public function view_user($id){
		if(Auth::check()){
			if(Auth::user()->type=="Admin"){
				$userData =  Profile::where('ProfileId',$id)->first();
				$profileData =  Profile::where('type',"Admin")->first();
				$artistData['user'] = $userData;
				$artistData['profileData'] = $profileData;
				return view('admin.view_user',$artistData);
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$userData =  User::where('email',Auth::user()->email)->first();
				$profileData =  Profile::where('EmailId',Auth::user()->email)->first();
				$artistData['userData'] = $userData;
				$artistData['profileData'] = $profileData;
				$artistData['baseurl'] = "https://www.videorequestline.com/";
				return view('frontend.artistDashboard.ProfileUpdate',$artistData);
			}
		}
		else{
			return redirect('admin');
		}
	}/*------------------Edit User Profile----------------------*/
	public function edit_user_profile(Request $request){
		$data = $request->all();
		//dd($data);
		$messages = [
		'required' => 'The :attribute field is required.',
		'username.regex' => ' :attribute should not contain number or special character',
		'password.regex' => ' Use at least one letter, one numeral & one special character',
		'password.min' => 'The password must be at least 8 characters'
		];
		$validator = Validator::make($data,
			array(
				'username' =>'required|regex:/^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-0-9-, ])*$/',
				'phone' =>'required|min:10|max:10',
				'address' =>'required',
				'city' =>'required',
				'state' =>'required',
				'country' =>'required',
				'zip'=>'required|min:5|max:5',
				'password'=>'min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/',
				'cpassword' =>'same:password',

				),$messages
			);
		if($validator->fails()){
			return redirect('edit_user/'.$request->user_id)
			->withErrors($validator)
			->withInput();
		}else{
			$profile_data = Profile::find($request->user_id);
			$profile_data->Name = $request->username;
			$profile_data->MobileNo = $request->phone;
			// $profile_data->Name = $request->Name;
			$profile_data->Address = $request->address;
			$profile_data->City = $request->city;
			$profile_data->State = $request->state;
			$profile_data->country = $request->country;
			$profile_data->Zip = $request->zip;
			if($request->password!=null){
				$user_password = Hash::make($request->password);
				DB::table('users')
				->where('profile_id', $request->user_id)
				->update(['user_name' => $request->username,'phone_no'=>$request->phone,'password'=>$user_password]);
			}
			if($profile_data->save()) {
				return redirect('edit_user/'.$request->user_id)->with('success', 'Successfully Updated!');
			}else{
				return redirect('edit_user/'.$request->user_id)->with('error', 'Updated Not Successfully !');
			}
		}
	}
	/*___________________________________________________________*/
	public function edit_profile(Request $request){
		$data = $request->all();
		//dd($data);
		$messages = [
		'required' => 'The :attribute field is required.',
		'username.regex' => ' :attribute should not contain number or special character',
		'phone.regex' => ' Phone Should contain Numbers only',
		'password.regex' => ' Use at least one letter, one numeral & one special character',
		];

		$validator = Validator::make($data,
			array(
				'username' =>'required|regex:/^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-0-9-, ])*$/',
				//'year' =>'required',
				//'month' =>'required',
				//'day' =>'required',
				//'dateofbirth' =>'required',
				'phone' => 'required|regex:/[0-9]/|digits:10',
				'nickName'=>'regex:/^[\pL\s\-]+$/u',
				'city' =>'required',
				'state' =>'required',
				'country' =>'required',
				'zip'=>'required|min:5|max:5',
				'profile' => 'mimes:jpeg,png',
				'password'=>'min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/',
				'cpassword' =>'same:password'
				),$messages
			);
		if($validator->fails()){
			return redirect('/edit_artist/'.$request->ProfileId)
			->withErrors($validator)
			->withInput();
		}else{

			if($request->date_ofbirth != ''){
				$dob= $request->date_ofbirth;
			}else{
				$dob=$request->profile_date_ofbirth1;
			}
			$birthday = \DateTime::createFromFormat('m-d-Y', $dob);
			$diff = $birthday->diff(new \DateTime());

			if ($diff->y < 18) {
				return redirect('edit_artist/'.$request->ProfileId)->with('error',"Age must be 18 or above 18 years");
			}else{

				$artist = Profile::find($request->ProfileId);
				$artist->Name = $request->username;
			// $dob=$request->month.'/'.$request->day.'/'.$request->year;



				if($request->date_ofbirth != ''){
					$artist->DateOfBirth = $request->date_ofbirth;
				}else{
					$artist->DateOfBirth=$request->profile_date_ofbirth1;
				}

				$artist->MobileNo = $request->phone;
				$artist->NickName = $request->nickName;
				$artist->Address = $request->address;
				$artist->City = $request->city;
				$artist->State = $request->state;
				$artist->profile_description = $request->description;
				$artist->Gender = $request->gender;
				if($request->file('profile') != ""){
					$file = $_FILES["profile"]['tmp_name'];
					list($width, $height) = getimagesize($file);
					if($width < "400" || $height < "400") {
						return redirect('edit_artist/'.$request->ProfileId)->with('error','image size must be 400 x 400 pixels.');
					}else{

						$file = $request->file('profile');
						$filename = $file->getClientOriginalName();
						$profile_path = "images/Artist/". date('U').'.jpg';
						$destinationPath = base_path() . '/public/images/Artist/';
						$request->file('profile')->move($destinationPath, $profile_path);
						$artist->profile_path = $profile_path;
					}
				}
				$artist->country = $request->country;
				$artist->Zip = $request->zip;
				if($request->password!=null){
					$artist_password = Hash::make($request->password);
					DB::table('users')
					->where('profile_id', $request->ProfileId)
					->update(['user_name' => $request->username,'phone_no'=>$request->phone,'password'=>$artist_password]);
				}
				if($artist->save()) {
					return redirect('edit_artist/'.$request->ProfileId)->with('success', 'Successfully Updated!');
				}else{
					return redirect('edit_artist/'.$request->ProfileId)->with('error', 'Something error!');
				}
			}
		}
	}
	/*---------------------------------------Admin login----------------------------------*/
	public function home_data()
	{
		$latest_video = DB::table('video')->select('video.*','profiles.*')->orderByRaw("RAND()")->orderBy('VideoId','desc')
		->join('profiles','profiles.ProfileId','=','video.ProfileId')->take(4)->get();
		$video = DB::table('video')->select('video.*','profiles.*')
		->join('profiles','profiles.ProfileId','=','video.ProfileId')->orderByRaw("RAND()")->take(7)->get();
		$artist = DB::table('profiles')->select('profiles.*','users.*')
		->join('users',function($join){
			$join->on('profiles.ProfileId', '=', 'users.profile_id')
			->where('users.type', '=','Artist')->where('users.is_account_active', '=','1');
		})->get();
		$slider_data = DB::table('sliders')->select('sliders.*','profiles.*')
		->join('profiles',function($join){
			$join->on('profiles.ProfileId', '=', 'sliders.artist_id')
			->where('profiles.Type', '=','Artist')->where('sliders.slider_visibility', '=','1');
		})
		->join('users',function($join){
			$join->on('users.profile_id', '=', 'sliders.artist_id')
			->where('users.is_account_active', '=','1');
		})->get();
		$testimonials = DB::table('testimonials')->select('message')->orderByRaw("RAND()")->orderBy('created_at','desc')->take(5)->get();
		$data = array();
		$data['videos'] = $video;
		$data['latest_videos'] = $latest_video;
		$data['artists'] = $artist;
		$data['slider_data'] = $slider_data;
		$data['testimonials'] = $testimonials;
		$data['baseurl'] = "https://www.videorequestline.com/";
		return $data;
	}
	public function admin_login_form()
	{
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return view('admin.login');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				return redirect('admin_dashboard');
			}
		}
		else{
			return view('admin.login');
		}
	}
	public function admin_login(Request $request){
		$data = $request->all();
		$messages = [
		'email.required' => 'The Username field is required.',
		];
		$validator = Validator::make($data,
			array(
				'email' =>'required|email',
				'password' =>'required'
				),$messages
			);
		if($validator->fails()){
			return redirect('admin')
			->withErrors($validator)
			->withInput();
		}
		else{
			$email=$request->email;
			$password=$request->password;
			$user = array('email' =>$email,'password' =>$password,'type'=>'Admin');
			if(Auth::attempt($user)){

				Session::put('password',$request->password);
				Session::put('name',Auth::user()->user_name);
				Session::put('email',Auth::user()->email);
				Session::put('type',Auth::user()->type);

				return redirect('admin_dashboard');
			}
			else
			{
				return redirect('admin')->with('login_error',"Invalid email or password");
			}
		}
	}
	/*-------------------------------------Forget pass----------------------------------------*/
	public function get_forget_pass() {
		if(Auth::check()){
			if(Auth::user()->type=="Admin"){
				return redirect('admin_dashboard');
			}
		}
		else{
			return view('admin.forget');
		}
	}
	public function forgetpass(Request $request){
		$email = DB::table('profiles')->where('EmailId',$request->email)->first();
		$auth_pass = str_random(15);
		$confirmation_code['confirmation_code'] = encrypt($auth_pass);
		DB::table('users')->where('email', $request->email)->update(array('auth_reset_pass' => $auth_pass));
		if(count($email) > 0){
			Mail::send('emails.forget_reminder',$confirmation_code, function ($message) use ($request) {
				$message->from('codingbrains6@gmail.com', 'Reset Password');
				$message->to($request->email,'rajesh');
				$message->subject('Reset Password');
			});
			return redirect('admin')->with('login_error',"Please Check Your Email to get Password");
		}
		else{
			return redirect('forget')->with('forget_error',"Email doesn't Exist");
		}
	}
	public function forget_password_verify($email) {
		$email = decrypt($email);
		$result = DB::table('users')->where('auth_reset_pass', $email)->first();
		if(count($result)>0){
			return redirect('reset')->with('email',$result->email);
		}
		else{
			return redirect('reset');
		}
	}
	/*-------------------------------------Admin Dashboard--------------------------------*/
	public function get_dashboard()
	{

		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('/');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$admin = Auth::user();

				//$latest_video = Video::take(12)->orderBy('VideoId','desc')->get();
				// $total_video = Video::all();
				$latest_video = DB::table('video')->select('video.*')
				->join('profiles','profiles.ProfileId','=','video.ProfileId')->orderBy('VideoId','desc')->get();

				$total_video = DB::table('video')->select('video.*','profiles.*')
				->join('profiles',function($join){
					$join->on('profiles.ProfileId', '=', 'video.ProfileId');
				})->orderBy('VideoId', 'desc')->get();

				$all_video_request = Requestvideo::all();

				//$latest_testimonial = Testimonial::take(10)->orderBy('id','desc')->get();
				$latest_testimonial = DB::table('testimonials')->where('AdminApproval','=',0)
				->join('profiles', 'testimonials.by_profile_id', '=', 'profiles.ProfileId')
				->select('testimonials.*', 'profiles.*')
				->take(10)->orderBy('id','desc')->get();



				$total_testimonial = Testimonial::all();

				$latest_artist = Profile::where('Type','Artist')->take(12)->orderBy('ProfileId')->get();
				$total_artist = Profile::where('Type','Artist')->get();

				$latest_user = Profile::where('Type','User')->take(12)->orderBy('ProfileId')->get();
				$total_user = Profile::where('Type','User')->get();

				$admin_data = array();
				$admin_data['admin'] = $admin;

				$admin_data['artists'] = $latest_artist;
				$admin_data['total_artist'] = $total_artist;

				$admin_data['users'] = $latest_user;
				$admin_data['total_user'] = $total_user;

				$admin_data['videos'] = $latest_video;
				$admin_data['total_video'] = $total_video;
				$admin_data['video_requests'] = $all_video_request;
				$admin_data['testimonials'] = $latest_testimonial;
				$admin_data['total_testimonial'] = $total_testimonial;

				return view('admin.dashboard',$admin_data);
			}
		}
		else{
			return redirect('/login');
		}
	}
	/*-----------------------------------Admin Change password-------------------------------------*/
	public function get_change_password() {
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('Dashboard');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$profileData =  Profile::where('EmailId',Auth::user()->email)->first();
				$adminData['profileData'] = $profileData;
				$adminData['baseurl'] = "https://vrl.projects-codingbrains.com/";
				return view("admin.change_password",$adminData);
			}
		}
		else{
			return redirect('/');
		}
	}
	public function change_password(Request $request){
		$messages = [
		'required' => 'The :attribute field is required.',
		'new_password.regex' => ' Use at least one letter, one numeral & one special character',
		'old_password.regex' => ' Use at least one letter, one numeral & one special character',
		'new_password.min' => ' New password should be at least 8 characters ',
		'old_password.min' => ' Old password should be at least 8 characters',
		'confirm_password.min' => ' Confirm password should be at least 8 characters',
		];
		$validator = Validator::make(
			array(
				'old_password' =>$request->old_password,
				'new_password' =>$request->new_password,
				'confirm_password' =>$request->confirm_password
				),
			array(

				'old_password' =>'required|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/',
				'new_password' =>'required|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/',
				'confirm_password' =>'required|min:8|same:new_password'
				),$messages
			);
		if($validator->fails())
		{
			return redirect('change_pass')
			->withErrors($validator)
			->withInput();
		}
		else
		{
			$user = User::find(Auth::user()->user_id);
			$old_password=$request->old_password;
			$new_password=$request->new_password;
			if(Hash::check($old_password, $user->getAuthPassword())){
				$user->password = Hash::make($new_password);
				if($user->save()){
					\Session::put('password',$request->new_password);
					return redirect('change_pass')->with('success',"Password Changed Successfully");
				}
			}
			else{
				return redirect('change_pass')->with('error',"Invalid  password");
			}
		}
	}
	/*---------------------------------------Artist List---------------------------------------*/
	public function artist_list()
	{
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('Dashboard');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$artists = DB::table('profiles')->select('profiles.*','users.*')
				->join('users',function($join){
					$join->on('profiles.ProfileId', '=', 'users.profile_id')
					->where('users.type', '=','Artist');
				})->orderBy('ProfileId', 'desc')->get();
				return view('admin.artist_list',['artists'=>$artists]);
			}
		}
		else{
			return redirect('/');
		}
	}
	/*---------------------------------------User List---------------------------------------*/
	public function user_list()
	{
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('Dashboard');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$users = DB::table('profiles')->select('profiles.*','users.*')
				->join('users',function($join){
					$join->on('profiles.ProfileId', '=', 'users.profile_id')
					->where('users.type', '=','user');
				})->orderBy('ProfileId', 'desc')->get();

				return view('admin.user_list',['users'=>$users]);
			}
		}
		else{
			return redirect(url()->previous());
		}
	}




	/*---------------------------------------Video List---------------------------------------*/
	public function videos_list()
	{
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('Dashboard');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$videos = DB::table('video')->select('video.*','profiles.*')
				->join('profiles',function($join){
					$join->on('profiles.ProfileId', '=', 'video.ProfileId');
				})->orderBy('VideoId', 'desc')->get();
				return view('admin.video_list',['videos'=>$videos]);
			}
		}
		else{
			return redirect('/');
		}
	}
	/*---------------------------------------delete video---------------------------------------*/
	public function delete_video($video_id)
	{
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('Dashboard');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$video = Video::find($video_id);
				unlink($video->originalVideoUrl);
				unlink($video->VideoThumbnail );
				unlink($video->VideoURL);

				if($video->delete()){
					return redirect('videos')->with('success','Video Deleted Successfully!');
				}
			}
		}
		else{
			return redirect('/');
		}
	}
	/*---------------------------------------Artist Disable---------------------------------------*/
	public function disable_artist($artist_id)
	{
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('Dashboard');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$video_requests = \App\Requestvideo::where('requestToProfileId',$artist_id)
				->where('paid','Paid')
				->where('RequestStatus','=','Approved')
				->get();
				if(count($video_requests)> 0){
					return \Redirect()->back()->with('error','This artist has paid but not delivered video request');
				}else{

					$artist = User::where('profile_id',$artist_id)->first();
					$artist->is_account_active = 0;
					if($artist->save()){
						DB::table('requestvideos')->where('requestToProfileId',$artist_id)
						->where('paid','Unpaid')
						->where('RequestStatus','=','Pending')
						->delete();
						$confirmation_code['name'] =$artist->user_name;
						Mail::send('emails.deactivate_artist',$confirmation_code, function ($message)use ($artist_id) {
							$artist = User::where('profile_id',$artist_id)->first();
							$message->from('noreply@videorequestline.com', 'Reset Password');
							$message->to($artist->email,'rajesh');
							$message->subject('Deactivated Account by Administrator');
						});
						return redirect('artists')->with('success','Artist deactivated successfully');;
					}
				}
			}
		}
		else{
			return redirect('/');
		}
	}
	/*---------------------------------------Artist Enable---------------------------------------*/
	public function resend_varifi_code($artist_id)
	{
		//$users = new User();
		$auth_pass = str_random(15);
		//$users->auth_pass = $auth_pass;
		$confirmation_code['confirmation_code'] = encrypt($auth_pass);
		if(User::where('profile_id','=',$artist_id)->update(array('auth_pass' =>$auth_pass))){
			Mail::send('emails.reminder', $confirmation_code, function ($message) use ($artist_id) {
				$profile=Profile::find($artist_id);
				$message->from('noreply@videorequestline.com', 'Registration Confirmation');
				$message->to($profile->EmailId, 'Resend verification code');
				$message->subject('Resend verification code');
			});
			return redirect('artists')->with('success','Resend verification code Successfully');
		}else{
			return redirect('artists')->with('success','Resend verification code not Successfully');
		}

	}
	/*---------------------------------------Artist Enable---------------------------------------*/
	public function enable_artist($artist_id)
	{
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('Dashboard');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$artist = User::where('profile_id',$artist_id)->first();
				$artist->is_account_active = 1;
				$artist->is_email_active=1;
				if($artist->save()){
					$confirmation_code['name'] =$artist->user_name;
					Mail::send('emails.activate_artist',$confirmation_code, function ($message)use ($artist_id) {
						$artist = User::where('profile_id',$artist_id)->first();
						$message->from('noreply@videorequestline.com', 'Reset Password');
						$message->to($artist->email,'rajesh');
						$message->subject('Account Deactivated  by Administrator');
					});
					return redirect('artists');
				}
			}
		}
		else{
			return redirect ('/');
		}
	}
	/*---------------------------------------User Disable---------------------------------------*/
	public function disable_user($user_id)
	{
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('Dashboard');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$video_requests = \App\Requestvideo::where('requestByProfileId',$user_id)
				->where('paid','Paid')
				->where('RequestStatus','=','Approved')
				->get();
				if(count($video_requests)> 0){
					return \Redirect()->back()->with('error','This User has paid but not delivered video request');
				}else{


					$user = User::where('profile_id',$user_id)->first();
					$user->is_account_active = 0;
					if($user->save()){
						$confirmation_code['name'] =$user->user_name;
						Mail::send('emails.deactivate_user',$confirmation_code, function ($message)use ($user_id) {
							$user = User::where('profile_id',$user_id)->first();
							$message->from('noreply@videorequestline.com', 'Reset Password');
							$message->to($user->email,'rajesh');
							$message->subject('Account Deactivated  by Administrator');
						});
						return redirect('users');
					}
				}
			}
		}
		else{
			return redirect('/');
		}
	}
	/*---------------------------------------User Enable---------------------------------------*/
	public function enable_user($user_id)
	{
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('Dashboard');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$user = User::where('profile_id',$user_id)->first();

				$user->is_account_active = 1;
				if($user->save()){
					$confirmation_code['name'] =$user->user_name;
					//$confirmation_code['status'] ='Activated';
					Mail::send('emails.activate_user',$confirmation_code, function ($message)use ($user_id) {
						$user = User::where('profile_id',$user_id)->first();
						$message->from('noreply@videorequestline.com', 'Reset Password');
						$message->to($user->email,'rajesh');
						$message->subject('Deactivated Account by Administrator');
					});
					return redirect('users');
				}
			}
		}
		else{
			return redirect('/');
		}
	}
	/*--------------------------------------Testimonial List-------------------------------------*/
	public function testimonials_list()
	{
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('Dashboard');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				// $testimonials = DB::table('testimonials')->select('testimonials.*','profiles.*')
				// ->join('profiles',function($join){
				// 	$join->on('profiles.ProfileId','=','testimonials.to_profile_id')
				// 	;
				// })->orderBy('testimonials.id','desc')->get();

				$testimonials = \App\Testimonial::orderBy('testimonials.id','desc')->get();
				return view('admin.testimonial_list',['testimonials'=>$testimonials]);
			}
		}
		else{
			return redirect('/');
		}
	}
	/*---------------------------------------Approve Testimonial-----------------------*/
	public function approve_testimonial($testimonial_id)
	{
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('Dashboard');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$testimonial = Testimonial::find($testimonial_id);
				$testimonial->AdminApproval = 1;
				if($testimonial->save()){
					return redirect('testimonials')->with('success','Testimonial Accepted Successfully');
				}
			}
		}
		else{
			return redirect('/');
		}
	}

	public function reject_testimonial($testimonial_id)
	{
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('Dashboard');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$testimonial = Testimonial::find($testimonial_id);
				$testimonial->AdminApproval = 0;
				$testimonial->show_home = 0;
				$testimonial->show_artist = 0;

				if($testimonial->save()){
					return redirect('testimonials')->with('success','Testimonial Rejected Successfully');
				}
			}
		}
		else{
			return redirect('/');
		}
	}
	public function del_admin_testimonial($testimonial_id){
		if(Auth::check()){
			if(Auth::user()->type=="Admin"){

				if(DB::table('testimonials')->where('id',$testimonial_id)->delete()){
					return redirect('testimonials')->with('success','Testimonial Deleted Successfully');
				}
			}
		}
	}
	public function get_admin_testimonial($testimonial_id){
		if(Auth::check()){
			if(Auth::user()->type=="Admin"){
				$artist = DB::table('users')
				->join('profiles', 'users.profile_id', '=', 'profiles.ProfileId')
				->select('profiles.*', 'users.*')
				->where('profiles.Type','=','Artist')
				->where('users.is_account_active','=','1')
				->get();
				$testimonial = DB::table('testimonials')->where('id','=',$testimonial_id)->first();
				$data['artist']=$artist;
				$data['testimonial']=$testimonial;
				return view('admin.edit_testimonial',$data);
			}
		}
	}
	public function update_admin_testimonial(Request $request){
		$data = $request->all();
		$validator = Validator::make($data,
			array(
				'message' =>'required',
				'user_name' => 'required',
				"artist_id" => "required"
				)
			);
		if($validator->fails()){
			return redirect('edit_admin_testimonial/'.$request->test_id)
			->withErrors($validator)
			->withInput();
		}
		else{
			$testimonial = \App\Testimonial::find($request->test_id);
			if(!is_null($testimonial)){
				$testimonial->to_profile_id = $request->artist_id;
				$testimonial->user_name = $request->user_name;
				$testimonial->Message = $request->message;
				$testimonial->Email = $request->user_email;
				$testimonial->save();
				return redirect('edit_admin_testimonial/'.$request->test_id)->with('success','Testimonial Updated Successfully');
			}else{
				return redirect('/testimonials')->with('success','Testimonial Not found');
			}


		}
	}
	/*----------------------login as artist-----------------------------*/
	public function get_login_artist(){
		if(Auth::check()){		

			session(['current_type' => 'Artist']);

			return redirect('Dashboard');
		}
	}

	// public function get_login_artist(){

	// 	if(Auth::check()){

	// 		if(Auth::user()->type=="Artist"){

	// 			return redirect('Dashboard');

	// 		}elseif (Auth::user()->type=="User") {

	// 			return redirect('profile');

	// 		}else{

	// 			$email=Auth::user()->email;
	// 			$password='test@123';

	// 			$is_email_active = User::is_email_active($email);
	// 			$is_account_active = User::is_account_active($email);

	// 			if($is_email_active == "0"){

	// 				return redirect('login')->with('login_error',"'You need to confirm your account. We have sent you an activation code, please check your email.'");

	// 			} else if ($is_account_active == "0") {

	// 				return redirect('login')->with('login_error',"Your Account is deactivated.");

	// 			}else{



	// 				$login_result = User::where('email',$email)->first();

	// 				if(count($login_result)>0){

	// 					$user_type = $login_result->type;

	// 					if($user_type=='Artist'){

	// 						$user = array('email' =>$email,'password' =>$password);

	// 						if(Auth::attempt($user)){

	// 							//Auth::attempt($user);
	// 							return redirect('/Dashboard');

	// 						}else{

	// 							return redirect('login')->with('login_error',"Invalid email or password");
	// 						}


	// 					}else if($user_type=='User'){

	// 						$user = array('email' =>$email,'password' =>$password);
	// 						if(Auth::attempt($user)){
	// 							Auth::attempt($user);
	// 							return redirect('/user_video');
	// 						}
	// 						else{
	// 							return redirect('login')->with('login_error',"Invalid email or password");
	// 						}
	// 					}else {
	// 						return redirect('login')->with('login_error',"Invalid email or password");
	// 					}
	// 				}
	// 				else{
	// 					return redirect('login')->with('login_error',"Invalid email or password");
	// 				}
	// 			}
	// 		}
	// 	}
	// 	else{
	// 		return redirect('/');
	// 	}
	// }
	/*----------------------login as user-----------------------------*/
	public function get_login_user(){

		if(Auth::check()){

			session(['current_type' => 'User']);
			//dd(session('current_type'));
			return redirect('profile');


			// if(Auth::user()->type=="Artist"){
			// 	return redirect('Dashboard');
			// }elseif (Auth::user()->type=="User") {
			// 	return redirect('profile');
			// }else{
			// 	$email='codingbrains16@gmail.com';
			// 	$password='test@123';


			// 	$login_result = User::where('email',$email)->first();
			// 	if(count($login_result)>0){
			// 		$user_type = $login_result->type;

			// 		if($user_type=='Artist'){
			// 			$user = array('email' =>$email,'password' =>$password);
			// 			if(Auth::attempt($user)){
			// 				Auth::attempt($user);
			// 				return redirect('/Dashboard');
			// 			}else{
			// 				return redirect('login')->with('login_error',"Invalid email or password");
			// 			}
			// 		}else if($user_type=='User'){
			// 				//return redirect('/hi');
			// 				//dd('test');
			// 			$user = array('email' =>$email,'password' =>$password);
			// 			if(Auth::attempt($user)){
			// 					//Auth::attempt($user);
			// 				dd(Auth::user()->type);
			// 				return redirect('/profile');
			// 			}
			// 			else{
			// 				return redirect('login')->with('login_error',"Invalid email or password");
			// 			}
			// 		}else {
			// 			return redirect('login')->with('login_error',"Invalid email or password");
			// 		}
			// 	}
			// 	else{
			// 		return redirect('login')->with('login_error',"Invalid email or password");
			// 	}

			// }

		}else{

			return redirect('/');
		}
	}

	/*---------------------login as admin ------------------------------------*/

	public function get_login_admin(){
		if(Auth::check()){
			session(['current_type' => 'Admin']);

			return redirect('admin_dashboard');
		}	

		// $email='vrlAdmin@gmail.com';
		// $password='testdemo@123';
		// $user = array('email' =>$email,'password' =>$password,'type'=>'Admin');
		// if(Auth::attempt($user)){
		// 	\Session::put('password',$password);
		// 	\Session::put('name',Auth::user()->user_name);
		// 	\Session::put('email',Auth::user()->email);
		// 	\Session::put('type',Auth::user()->type);
		// 	return redirect('admin_dashboard');
		// }
		// else
		// {
		// 	//return redirect('admin')->with('login_error',"Invalid email or password");
		// }
	}


	/*-------------------Get testimonial form----------------------------------*/
	public function get_testimonial($testimonial_id)
	{
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('Dashboard');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$testimonials = Testimonial::find($testimonial_id);
				return view('admin.edit_testimonial',['testimonial'=>$testimonials]);
			}
		}
		else{
			return redirect('/');
		}
	}
	/*----------------------------------------Update testimonial---------------------------------------*/
	public function update_testimonial($testimonial_id,Request $request)
	{
		$data = $request->all();
		$validator = Validator::make($data,
			array(
				'testimonial' =>'required'
				)
			);
		if($validator->fails()){
			return redirect('edit_testimonial')
			->withErrors($validator)
			->withInput();
		}
		else{
			$testimonial = Testimonial::find($testimonial_id);
			$testimonial->Message = $request->testimonial;
			if($testimonial->save()){
				return redirect('edit_testimonial/'.$testimonial_id)->with('success','Testimonial Updated Successfully');
			}
		}
	}
	/*---------------------------------------Slider List---------------------------------------*/
	public function sliders()
	{
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('Dashboard');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$sliders = DB::table('sliders')->orderBy('id','desc')->get();
				return view('admin.sliders',['sliders'=>$sliders]);
			}
		}
		else{
			return redirect('/');
		}
	}
	/*-------------------------------------Get slider form-------------------------------------*/
	public function get_slider()
	{
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('/');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$artists = Profile::where('type','Artist')->get();
				return view('admin.upload_slider',['artists'=>$artists]);
			}
		}
		else{
			return redirect('/');
		}
	}
	/*----------------------------------------Upload Slider------------------------------------*/
	public function upload_slider(Request $request)
	{
		$data = $request->all();
		$validator = Validator::make($data,
			array(
				// 'artist_id' =>'required',
				'slider_title' =>'required|max:100',
				'slider_description' =>'required|min:200|max:500',
				'slider' => 'required | mimes:jpeg,png'
				)
			);
		if($validator->fails()){
			return redirect('upload_sliders')
			->withErrors($validator)
			->withInput();
		}
		else{
			$slider = new Slider();
			$slider->slider_visibility = 0;
			$slider->slider_title = $request->slider_title;
			$slider->slider_description = $request->slider_description;
			$slider->artist_id = $request->artist_id;
			if($request->file('slider') != ""){
				$file = $request->file('slider');
				$filename = $file->getClientOriginalName();
				$slider_path = "images/Sliders/".date('U').'.jpeg';
				$destinationPath = base_path() . '/public/images/Sliders/';
				$request->file('slider')->move($destinationPath, $slider_path);
				$slider->slider_path = $slider_path;
			}
			if($slider->save()){
				return redirect('edit_slider/'.$slider->id)->with('success','Slider Uploaded Successfully');
			}
		}
	}
	/*---------------------------------------edit slider form---------------------------------------*/
	public function edit_slider($slider_id)
	{
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('Dashboard');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$slider = Slider::find($slider_id);
				$artists = Profile::where('type','Artist')->get();
				$data['slider'] = $slider;
				$data['artists'] = $artists;
				$data['baseurl'] = 'https://vrl.projects-codingbrains.com/';
				return view('admin.edit_slider',$data);
			}
		}
		else{
			return redirect('/');
		}
	}
	/*----------------------------------------Update Slider---------------------------------------*/
	public function update_slider($slider_id,Request $request)
	{
		$data = $request->all();
		$validator = Validator::make($data,
			array(
				'slider_title' =>'required|max:100',
				'slider_description' =>'required|min:200|max:500',
				'slider' =>  'mimes:jpeg,png'
				)
			);
		if($validator->fails()){
			return redirect('upload_sliders')
			->withErrors($validator)
			->withInput();
		}
		else{
			$slider = Slider::find($slider_id);
			$slider->slider_title = $request->slider_title;
			$slider->slider_description = $request->slider_description;
			$slider->artist_id = $request->artist_id;
			if($request->file('slider') != ""){
				$file = $request->file('slider');
				$filename = $file->getClientOriginalName();
				$slider_path = "images/Sliders/".date('U').'.jpeg';
				$destinationPath = base_path() . '/public/images/Sliders/';
				$request->file('slider')->move($destinationPath, $slider_path);
				$slider->slider_path = $slider_path;
			}
			if($slider->save()){
				return redirect('edit_slider/'.$slider->id)->with('success','Slider Updated Successfully');
			}
		}
	}
	/*-----------------------edit artist slider--------------------*/
	public function atrist_slider(){
		return view('admin.edit_slider');
		//return redirect('/edit_slider');
		/*if(Auth::check()){
			if(Auth::user()->type=="Admin"){
				return redirect('admin_dashboard');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$artist =  Profile::where('EmailId',Auth::user()->email)->first();
				$slider = Slider::where('artist_id',Auth::user()->profile_id)->first();
				$artist_data['artist'] = $artist;
				$artist_data['slider'] = $slider;
				if(count($slider)>0){
					return view('frontend.artistDashboard.edit_slider',$artist_data);
				}
				else{
					return view('frontend.artistDashboard.slider',$artist_data);
				}
			}
		}
		else{
			return redirect('/login');
		}*/
	}
	
	/*-----------------------enable slider--------------------*/
	public function enable_slider($slider_id)
	{
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('Dashboard');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$slider = Slider::find($slider_id);
				$slider->slider_visibility = 1;
				if($slider->save()){
					return redirect('sliders');
				}
			}
		}
		else
		{
			return redirect('/');
		}
	}
	/*---------------------------------------Disable slider---------------------------------------*/
	public function disable_slider($slider_id)
	{
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('Dashboard');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$slider = Slider::find($slider_id);
				$slider->slider_visibility = 0;
				if($slider->save()){
					return redirect('sliders');
				}
			}
		}
		else{
			return redirect('/');
		}
	}
	/*------------------------------Artist Payments---------------------------*/
	public function artist_payments()
	{
		$all_artist=DB::table('profiles')->select("*")->where('Type','=','artist')
		->get();
		$threshold = DB::table('setting')->select('status')->where('name','=','threshold')->first();
		$data['threshold']=$threshold;
		$artist_data['artist_data'] = $all_artist;
		$artist_data['threshold'] = $threshold;
		//$artist_data['payment_dtl'] = $payment_dtl;
		//dd($artist_data);
		return view('admin.artist_payment',$artist_data);
		
	}
	/*---------------------------------------All Payments-------------------------------------*/
	public function payments_list()
	{
		// $payment = Payment::all();
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('Dashboard');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$payment = DB::table('payments')->join('requestvideos','payments.video_request_id','=','requestvideos.VideoReqId')
				->leftJoin('requested_videos','requested_videos.request_id','=','requestvideos.VideoReqId')
				->join('profiles','profiles.ProfileId','=','requestvideos.requestToProfileId')
				->orderBy('requested_videos.id', 'desc')
				->get();
				//dd($payment);

				$video_data['payment'] = $payment;
				return view('admin.payments',$video_data);
			}
		}
		else{
			return redirect('/');
		}
	}
	/*--------------------------------All Video Requests-----------------------------*/
	public function get_video_requests()
	{
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('/');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}
			else{
				$video_request = DB::table('requestvideos')
				->leftJoin('requested_videos','requested_videos.request_id','=','requestvideos.VideoReqId')
				->join('profiles','profiles.ProfileId','=','requestvideos.requestToProfileId')
				->leftJoin('admin_payments', 'admin_payments.video_request_id', '=', 'requestvideos.requestToProfileId')
				->select('requestvideos.*','requested_videos.id AS Rid','profiles.*','admin_payments.*')
				->orderBy('requestvideos.VideoReqId', 'desc')
				->get();

				//dd($video_request);
				return view('admin.video_requests',['videos'=>$video_request]);
			}
		}
		else{
			return redirect('/');
		}
	}
	public function admin_view_video($id){
		if(Auth::check()){
			if(Auth::user()->type=="Artist"){
				return redirect('/');
			}elseif (Auth::user()->type=="User") {
				return redirect('profile');
			}elseif(Auth::user()->type=="Admin"){
				$request_video_dtl = RequestedVideo::find($id);
				return view('admin.admin_view_video',['videos'=>$request_video_dtl]);
			}
		}else{
			return redirect('/');
		}
	}
	public function enable_video_request($id){
		$request_video = Requestvideo::find($id);
		$request_video->is_active = 1;
		if($request_video->save()){
			return redirect('get_video_requests')->with('success','Video Request Activated Successfully!');
		}
	}
	public function disable_video_request($id){
		$request_video = Requestvideo::find($id);
		$request_video->is_active = 0;
		if($request_video->save()){
			return redirect('get_video_requests')->with('success','Video Request Deactivated Successfully!');
		}
	}
	public function del_slider($id){
		//echo $id;
		/*$slider = Sliders::find($id);*/
		//DB::table('sliders')->where('id', $id)->delete();
		if(DB::table('sliders')->where('id', $id)->delete()){
			return redirect('sliders')->with('success','Slider Deleted Successfully!');
		}
	}
	public function delete_video_request($id){
		$request_video = Requestvideo::find($id);
		if($request_video->delete()){
			return redirect('get_video_requests')->with('success','Video Request Deleted Successfully!');
		}
	}
	public function check_admin_auth()
	{

		$email = \Session::get('email');
		$password = \Session::get('password');
//dd($password);
		$user = array('email'=>$email);
		$result = \DB::table('users')->where($user)->first();
		if(Hash::check($password, $result->password)){
			echo"true";
		}else{
			echo"false";
		}
		

	}
	/*public function mark_default($id)
	{
		$testimonial = \App\Testimonial::find($id);
		if(!is_null($testimonial)){
			$testimonial->is_default = 1;;
			$testimonial->save();
			return redirect('/testimonials')->with('success','Testimonial is Marked as default');

		}else{
			return redirect('/testimonials')->with('error','Testimonial Not found');
		}
	}*/
	public function set_home($id)
	{
		//dd($id);
		$testimonial = \App\Testimonial::find($id);
		if(!is_null($testimonial)){
			$testimonial->show_home = 1;
			$testimonial->save();
			return redirect('/testimonials')->with('success','Testimonial is Displaying Only on Homepage');

		}else{
			return redirect('/testimonials')->with('error','Testimonial Not found');
		}
	}
	public function hide_home($id)
	{
		//dd($id);
		$testimonial = \App\Testimonial::find($id);
		if(!is_null($testimonial)){
			$testimonial->show_home = 0;
			$testimonial->save();
			return redirect('/testimonials')->with('success','Testimonial hide from Homepage');

		}else{
			return redirect('/testimonials')->with('error','Testimonial Not found');
		}
	}

	public function set_artist($id)
	{
		//dd($id);
		$testimonial = \App\Testimonial::find($id);
		if(!is_null($testimonial)){
			$testimonial->show_artist = 1;
			$testimonial->save();
			return redirect('/testimonials')->with('success','Testimonial is Displaying Only on Artist Page');

		}else{
			return redirect('/testimonials')->with('error','Testimonial Not found');
		}
	}
	public function hide_artist($id)
	{
		//dd($id);
		$testimonial = \App\Testimonial::find($id);
		if(!is_null($testimonial)){
			$testimonial->show_artist = 0;
			$testimonial->save();
			return redirect('/testimonials')->with('success','Testimonial hide from Artist Page');

		}else{
			return redirect('/testimonials')->with('error','Testimonial Not found');
		}
	}

	/*public function remove_default($id)
	{
		$testimonial = \App\Testimonial::find($id);
		if(!is_null($testimonial)){
			$testimonial->is_default = 0;
			$testimonial->save();
			return redirect('/testimonials')->with('success','Testimonial is removed from default');

		}else{
			return redirect('/testimonials')->with('error','Testimonial Not found');
		}
	}*/
}
