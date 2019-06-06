<?php
Route::post('UpdateProfile1', 'TestController@UpdateProfile');
/*---------------------------------Admin  artist list csv route --------------------------*/
Route::get('/artist_csv',function ()
{
	$artist = App\Profile::select('*')->where('Type','Artist')->get();
	Excel::create('Artist List', function($excel) use($artist) {
		$excel->sheet('Sheet 1', function($sheet) use($artist) {
			$sheet->fromArray($artist);
		});
	})->export('xls');
});
/*---------------------------------Admin  User list csv route --------------------------*/
Route::get('/user_csv',function ()
{
	$user = App\User::select('*')->where('Type','User')->get();
	Excel::create('User List', function($excel) use($user) {
		$excel->sheet('Sheet 1', function($sheet) use($user) {
			$sheet->fromArray($user);
		});
	})->export('xls');
});
/*---------------------------------Admin  Video list csv route --------------------------*/
Route::get('/video_csv',function ()
{
	$video = App\Video::select('*')->get();
	Excel::create('Video List', function($excel) use($video) {
		$excel->sheet('Sheet 1', function($sheet) use($video) {
			$sheet->fromArray($video);
		});
	})->export('xls');
});
/*---------------------------------Admin Requested Video list csv route --------------------------*/
Route::get('/video_req_csv',function ()
{
	$video_req = App\Requestvideo::select('*')->get();
	Excel::create('Requested Video List', function($excel) use($video_req) {
		$excel->sheet('Sheet 1', function($sheet) use($video_req) {
			$sheet->fromArray($video_req);
		});
	})->export('xls');
});
/*---------------------------------Admin Testimonial list csv route --------------------------*/
Route::get('/testimonial_csv',function ()
{
	$testimonial = App\Testimonial::select('*')->get();
	Excel::create('Testimonial List', function($excel) use($testimonial) {
		$excel->sheet('Sheet 1', function($sheet) use($testimonial) {
			$sheet->fromArray($testimonial);
		});
	})->export('xls');
});
/*---------------------------------Admin Payment list csv route --------------------------*/
Route::get('/payment_csv',function ()
{
	$payment = App\Payment::select('*')->get();
	Excel::create('Payment List', function($excel) use($payment) {
		$excel->sheet('Sheet 1', function($sheet) use($payment) {
			$sheet->fromArray($payment);
		});
	})->export('xls');
});

Route::get('/req_video_csv/{id}','ArtistController@req_video_csv');
/*---------------------------------Stripe User Payment (web and api) route --------------------------*/

Route::post('/testdata', 'PaymentController@payment');
Route::post('price_cal', 'LoginController@price_cal');
Route::post('/RePayment', 'PaymentController@RePayment');
Route::post('Payment', 'PaymentController@AppPayment');
Route::get('/payment_success', 'PaymentController@payment_success');
/*---------------------------------Stripe Artist Payment (web) route --------------------------*/
Route::post('/artist_payment', 'PaymentController@ArtistPayment');
/*---------------------------------Email Verification route --------------------------*/
Route::get('verify/{auth_pass}', 'LoginController@verify_email');
Route::get('verify_user/{auth_pass}', 'LoginController@verify_user_email');
/*----------------------------------------API route -----------------------------------*/
Route::post('logout_videoRequest', 'ApiController@unlogin_user_RequestVideo');
Route::post('featuredVideo', 'ApiController@ins_featuredVideo');
Route::post('Add_BankDtl', 'ApiController@Add_BankDtl');
Route::post('search', 'ApiController@search');
Route::post('ArtistList', 'ApiController@ArtistList');
Route::post('ProfileList', 'ApiController@ProfileList');
Route::post('VideoDetails', 'ApiController@VideoDetails');
Route::post('Delete', 'ApiController@delete_request');
Route::post('upload_bank_id_pic', 'ApiController@upload_bank_id_pic');
Route::post('show_testimonial', 'ApiController@show_testimonial');
Route::post('ins_testi', 'ApiController@insert_testimonial');
Route::post('requestList', 'ApiController@requestList');
Route::post('RequestVideo', 'ApiController@RequestVideo');
Route::post('VideoList', 'ApiController@VideoList');
Route::get('logins', 'ApiController@artistlogin');
Route::post('logins', 'ApiController@logins');
Route::post('apiregistration', 'ApiController@apiregister');
Route::post('apiforget', 'ApiController@apiforget');
Route::post('CheckEmailExists', 'ApiController@CheckEmailExists');
Route::post('UpdateProfile', 'ApiController@UpdateProfile');
Route::post('AcceptedVideo', 'ApiController@AcceptedVideo');
Route::post('VideoListByProfileId', 'ApiController@VideoListByProfileId');
Route::post('ArtistSaleDetails', 'ApiController@ArtistSaleDetails');
Route::post('requestToList', 'ApiController@requestToList');
Route::get('accept_video_request/{id}/{artist_id}/{user_id}', 'ApiController@accept_video_request');
Route::get('reject_video_request/{id}/{artist_id}/{user_id}', 'ApiController@reject_video_request');
Route::get('push', 'ApiController@push');
Route::get('sales/{artist_id}', 'ApiController@sales');
Route::post('upload_videos', 'ApiController@upload_videos');
Route::get('view_allVideo', 'ApiController@view_allVideo');
Route::get('view_latestVideo', 'ApiController@view_latestVideo');
Route::get('api/sliders', 'ApiController@sliders');
Route::get('artist/{url}', 'ApiController@artist_detail');


Route::post('privacy_about_term', 'ApiController@ProList');
Route::post('change_pass_api', 'ApiController@change_pass_api');



/*----------------------- Artist Route------------------------------*/

Route::group(['middleware' => ['web']], function () {

	Route::post('getData', 'ArtistController@get_data');

	Route::get('switch_as_artist/{ProfileId}', 'SwitchController@switch_as_artist');
	Route::get('switch_as_user/{ProfileId}', 'SwitchController@switch_as_user');
	Route::get('switch_as_admin', 'SwitchController@switch_as_admin');

	Route::get('artist_video', 'ArtistController@artist_videos');
	Route::get('deliver_video','ArtistController@get_deliver_video');
	Route::get('check_user_auth', 'LoginController@check_user_auth');
	Route::get('check_admin_auth', 'AdminController@check_admin_auth');
	Route::get('register', 'LoginController@registers');
	Route::get('download_video/{video}', 'LoginController@download_video');
	Route::get('download_sample_video/{video}', 'LoginController@download_sample_video');
	Route::get('view_video/{video}', 'LoginController@view_video');
	Route::get('edit_video/{video}/{reqId}', 'LoginController@edit_video');
	Route::post('edit_video', 'LoginController@post_edit_video');
	Route::get('resend_video/{user_id}/{req_id}', 'LoginController@resend_video');
	Route::get('artist_resend_video/{user_id}/{req_id}', 'LoginController@artist_resend_video');
	Route::get('admin_resend_video/{user_id}/{req_id}', 'LoginController@admin_resend_video');
	Route::get('view_testimonial', 'LoginController@view_testimonial');

	Route::get('artist_add_testimonial', 'LoginController@artist_add_testimonial');
	
	Route::get('add_testimonial', 'LoginController@add_testimonial');
	Route::post('add_testimonial', 'LoginController@post_add_testimonial');

	Route::get('edit_testimonial/{testi_id}', 'LoginController@edit_testimonial');
	Route::post('edit_testimonial/{testi_id}', 'LoginController@post_edit_testimonial');
	Route::get('delete_testimonial/{testi_id}', 'LoginController@delete_testimonial');
	Route::get('edit_sample_video/{video_id}', 'ArtistController@edit_sample_video');
	Route::post('edit_sample_video/{video_id}', 'ArtistController@post_edit_sample_video');
	Route::get('delete_sample_video/{testi_id}', 'LoginController@delete_sample_video');
	

	//Route::get('artist_login', 'LoginController@login');
	//Route::post('artist_login', 'LoginController@artist_login');
	Route::get('login', 'LoginController@AllLogin');
	Route::post('login', 'LoginController@post_allLogin');
	
	Route::get('artist_register', 'LoginController@artist_register');
	Route::post('artist_register', 'LoginController@register');
	
	Route::get('video_requests','LoginController@video_requests');
	Route::get('panding_video_requests','LoginController@panding_video_requests');
	Route::get('pending_requests','LoginController@pending_requests');
	Route::get('Dashboard','LoginController@get_dashboard');//->middleware('revalidate');
	Route::get('approve_request/{id}', 'LoginController@approve_request');
	Route::get('reject_request/{id}', 'LoginController@reject_request');
	

	

	Route::get('upload_requested_video/{id}', 'LoginController@upload_requested_video');
	Route::post('upload_requested_video', 'LoginController@upload_requestedVideo');
	Route::get('getLogout', array('as' => 'getLogout', 'uses' => 'LoginController@getLogout'));
	Route::get('/ArtistProfile', array('as' => 'ArtistProfile', 'uses' => 'LoginController@ArtistProfile'));
	Route::get('ProfileUpdate','LoginController@ProfileUpdate');
	Route::post('ProfileUpdate','LoginController@ProfileUpdateForm');
	
	Route::get('/upload_video/{id}','LoginController@upload_video');
	Route::post('/upload_video','LoginController@upload_video');
	Route::get('background_img','LoginController@upload_background');
	Route::post('upload_background_img','LoginController@upload_background_image');
	Route::get('upload_video_background','LoginController@upload_video_background');
	Route::post('upload_video_background','LoginController@uploadVideoBackground');
	Route::get('artist_header_img','LoginController@artist_header_img');
	Route::post('artist_header_img','LoginController@update_artist_header_img');
	Route::get('bank_details','LoginController@get_bank_details');
	Route::post('bank_details','LoginController@bank_details');
	
	
	Route::get('record_video','LoginController@record_video');
	Route::post('record_video','LoginController@record_own_video');
	Route::get('change-password','LoginController@get_change_password');
	Route::post('change-password','LoginController@change_password');
	Route::get('media','LoginController@get_media');
	Route::post('media','LoginController@media');
	Route::get('notifications','LoginController@notification');
	Route::get('view-all-artist','ArtistController@view_all_artist');
	Route::get('view-all-video','ArtistController@view_all_video');
	Route::get('payment_dtl', 'ArtistController@artist_payment_detail');
	
	Route::get('my_video','ArtistController@my_video');
	Route::get('sendmail','ArtistController@sendmail');
	Route::get('forget_pass','LoginController@get_forget_pass');
	Route::post('forget_pass','LoginController@forgetpass');
	Route::get('forget_password_verify/{email}','LoginController@forget_password_verify');
	Route::get('reset','LoginController@password_reset');
	Route::post('reset','LoginController@post_password_reset');
	Route::get('sold_videos','LoginController@sold_videos');
	Route::get('addPrice','LoginController@add_price');
	Route::post('addPrice','LoginController@post_add_price');
	Route::get('turnaround_time','LoginController@turnaround_time');
	Route::post('turnaround_time','LoginController@post_turnaround_time');
	Route::get('get_social_link','LoginController@get_social_link');
	Route::post('add_social_link','LoginController@add_social_link');
	Route::get('edit_url','LoginController@get_edit_url');
	Route::post('update_url','LoginController@update_url');

	Route::get('addMore_social_link','LoginController@add_more_social_link');
	Route::post('addMore_socialLink','LoginController@post_add_more_social_link');
	Route::get('delete_social_link/{id}','LoginController@delete_socialLink');
	Route::get('edit_social_link/{id}','LoginController@edit_socialLink');
	Route::post('edit_social_link/{id}','LoginController@post_edit_socialLink');
	Route::get('enable_social_link/{id}','LoginController@post_enable_socialLink');
	Route::get('disable_social_link/{id}','LoginController@post_disable_socialLink');
	
	Route::get('add_about','AdminController@add_about');
	Route::post('add_about','AdminController@post_add_about');
	Route::get('add_terms','AdminController@add_terms');
	Route::post('add_terms','AdminController@post_add_terms');
	Route::get('add_privacy','AdminController@add_privacy');
	Route::post('add_privacy','AdminController@post_add_privacy');

	Route::get('test','ArtistController@test');
	
	Route::get('add_help','AdminController@add_help');
	Route::post('add_help','AdminController@post_add_help');
	
	Route::get('webcame/{id}','LoginController@webcame');
	Route::get('webcam_video_upload','AdminController@video_upload');
	Route::get('show_testimonial/{testimonial_id}','ArtistController@show_testimonial');
	Route::get('hide_testimonial/{testimonial_id}','ArtistController@hide_testimonial');
	

});
Route::post('webcam_video_upload','AdminController@video_upload');


/*--------------------------------- User Route---------------------------------------*/
Route::group(['middleware' => ['web']], function () {
	Route::get('UserLogin', array('as' => 'UserLogin', 'uses' => 'HomeController@UserLogin'));
	Route::post('UserLogin', array('as' => 'UserLoginForm', 'uses' => 'HomeController@UserLoginForm'));
	Route::group(['middleware' => ['auth']], function () {
		Route::get('mydashboard','UserController@user_dashboard');
	});

	Route::get('profile_update','UserController@get_profile_update');
	Route::post('profile_update','UserController@profile_update');
	Route::get('upload_banner','UserController@upload_banner');
	Route::post('upload_banner','UserController@upload_banner_image');
	Route::get('upload_background','UserController@upload_background');
	Route::post('upload_background','UserController@upload_background_image');
	Route::get('change_password','UserController@get_change_password');
	Route::post('change_password','UserController@change_password');
	Route::get('notifications','UserController@notification');
	Route::get('RequestNewVideo/{id}', array('as' => 'RequestNewVideo', 'uses' => 'ArtistController@RequestNewVideo'));
	Route::post('RequestNewVideo/{id}', array('as' => 'RequestNewVideoForm', 'uses' => 'ArtistController@RequestNewVideoForm'));
	Route::get('new_request_video/{id}', array('as' => 'new_request_video/{id}', 'uses' => 'HomeController@new_request_video'));
	Route::post('new_request_video','HomeController@new_request_videoForm');
	Route::get('search','LoginController@search_result');
	Route::get('artist_search','LoginController@artist_search');
	Route::get('search_video','LoginController@search_video');
	Route::get('save_video_request_payment','PaymentController@save_video_request_payment');
	Route::post('purchase_request', 'PaymentController@purchase_request');
	Route::post('purchased_requested', 'PaymentController@purchased_requested');
	Route::post('store', 'PaymentController@store');
	Route::post('pay', 'PaymentController@pay');
	Route::post('comment','TestimonialController@comment');
	Route::get('delete/{request_id}/{prof_id}','HomeController@delete_video_request');
	Route::post('requestvideo','LoginController@requestvideo');
	Route::get('my_slider','LoginController@my_slider');
	Route::post('update_my_slider','LoginController@update_my_slider');
	Route::post('create_slider','LoginController@create_slider');
	Route::get('success_payment','LoginController@success_payment');
	Route::get('success_request','LoginController@success_request');
	Route::get('success_register','LoginController@success_register');

	Route::post('video_testimonial','LoginController@post_video_testimonial');
	Route::get('pay_extend_storage','LoginController@pay_extend_storage');
	Route::get('user_change_password','UserController@user_change_password');
	Route::post('user_change_password','UserController@post_user_change_password');
	
});


/*---------------------------- Video recording Route-----------------------------*/


Route::get('new_webcame','VideoRecodeController@webcame');
Route::post('new_webcam_video_upload','VideoRecodeController@video_upload');
Route::get('new_water_mark','VideoRecodeController@water_mark');




/*--------------------------------- Admin Route---------------------------------------*/

Route::group(['middleware' => 'web'], function () {
	Route::get('refund_payments','PaymentController@refund_payments');

	/*----------------------------Sample Video recording Route-----------------------------*/
	Route::get('sample_webcame','VideoRecodeController@sample_webcame');
	Route::get('del_slider/{id}','AdminController@del_slider');
	Route::post('sample_webcam_video_upload','VideoRecodeController@sample_video_upload');

});

Route::group(['middleware' => 'web'], function () {
	Route::get('view_artist/{id}','AdminController@view_artist');
	Route::get('view_user/{id}','AdminController@view_user');
	Route::get('edit_artist/{id}','AdminController@get_edit_artist');
	Route::post('edit_profile','AdminController@edit_profile');
	Route::get('edit_user/{id}','AdminController@get_edit_user');
	Route::post('edit_user_profile','AdminController@edit_user_profile');
	Route::get('delete_artist/{id}','AdminController@delete_artist');
	Route::get('create_connected_account/{id}','AdminController@create_connected_account');

	Route::get('update_artist_dtl/{id}','AdminController@update_artist_dtl');
	Route::post('update_artist','AdminController@admin_post_update_artist_dtl');

	Route::get('update_connected_account/{id}','AdminController@update_connected_account');
	Route::get('delete_user/{id}','AdminController@delete_user');
	
	Route::get('resend_varifi_code/{id}','AdminController@resend_varifi_code');
	
	Route::get('admin','AdminController@admin_login_form');
	Route::post('admin','AdminController@admin_login');
	Route::get('admin_dashboard','AdminController@get_dashboard');
	Route::get('artists','AdminController@artist_list');
	Route::get('users','AdminController@user_list');
	Route::get('videos','AdminController@videos_list');
	Route::get('delete_video/{video_id}','AdminController@delete_video');
	Route::get('disable_artist/{artist_id}','AdminController@disable_artist');
	Route::get('disable_user/{user_id}','AdminController@disable_user');
	Route::get('create_artist','AdminController@create_artist');
	Route::post('create_artist','AdminController@post_create_artist');
	Route::get('invite_artist','AdminController@invite_artist');
	Route::post('invite_artist','AdminController@post_invite_artist');
	Route::get('add_admin_testimonial','AdminController@add_admin_testimonial');
	Route::post('add_admin_testimonial','AdminController@post_add_admin_testimonial');
	Route::get('add_admin_slider','AdminController@add_admin_slider');
	Route::post('add_admin_slider','AdminController@post_add_admin_slider');
	Route::get('success_create_artist','AdminController@success_create_artist');
	Route::get('enable_artist/{artist_id}','AdminController@enable_artist');
	Route::get('enable_user/{user_id}','AdminController@enable_user');
	Route::get('enable_slider/{slider_id}','AdminController@enable_slider');
	Route::get('artist_slider/{slider_id}','AdminController@artist_slider');
	Route::post('update_artist_slider/{slider_id}','AdminController@update_artist_slider');
	Route::post('atrist_slider','AdminController@atrist_slider');
	Route::get('view_slider/{slider_id}','AdminController@view_slider');
	Route::post('update_slider','AdminController@post_update_slider');
	Route::get('disable_slider/{slider_id}','AdminController@disable_slider');
	Route::get('testimonials','AdminController@testimonials_list');
	Route::get('approve_testimonial/{testimonial_id}','AdminController@approve_testimonial');
	Route::get('reject_testimonial/{testimonial_id}','AdminController@reject_testimonial');
	Route::get('mark_default/{testimonial_id}','AdminController@mark_default');
	Route::get('remove_default/{testimonial_id}','AdminController@remove_default');

	Route::get('admin_view_video/{id}','AdminController@admin_view_video');
	Route::get('edit_admin_testimonial/{testimonial_id}','AdminController@get_admin_testimonial');
	Route::post('update_admin_testimonial/{testimonial_id}','AdminController@update_admin_testimonial');
	Route::get('edit_testimonial/{testimonial_id}','AdminController@get_testimonial');
	Route::post('update_testimonial/{testimonial_id}','AdminController@update_testimonial');
	Route::get('Adminsignup','HomeController@signup');
	Route::post('Adminsignup','HomeController@signupForm');
	Route::post('update_video_request','LoginController@update_video_request');
	Route::get('videoDetails/{id}','LoginController@videoDetails');
	Route::post('videoDetails','LoginController@videoDetailsForm');
	Route::get('sliders','AdminController@sliders');
	Route::get('get_payments','AdminController@payments_list');
	Route::get('artist_payments','AdminController@artist_payments');
	Route::get('refund_user/{video_request_id}/{charge_id}','PaymentController@refund_user');
	Route::get('paymentById/{payment_id}','PaymentController@paymentById');
	Route::get('change_pass','AdminController@get_change_password');
	Route::post('change_pass','AdminController@change_password');
	Route::get('get_video_requests','AdminController@get_video_requests');
	Route::get('enable_video_request/{id}','AdminController@enable_video_request');
	Route::get('disable_video_request/{id}','AdminController@disable_video_request');
	Route::get('delete_video_request/{id}','AdminController@delete_video_request');
	Route::get('pay_artist/{artist}/{videoId}','PaymentController@pay_artist');
	Route::get('pay_to_artist/{artist}/{price}','PaymentController@pay_to_artist');
	Route::get('default_testimonial','AdminController@default_testimonial');
	Route::post('default_testimonial','AdminController@post_default_testimonial');
	Route::get('user_video','LoginController@post_user_video');
	Route::get('userDashboard','LoginController@post_user_dashboard');
	Route::get('change_email','LoginController@user_change_email');
	Route::get('/testby/{id}','HomeController@fecth_data');
	Route::get('/date_calculation/{days}','HomeController@date_calculation');
	Route::get('/video_purge','AdminController@video_purge');
	Route::get('/hide_comp_video/{id}','AdminController@hide_comp_video');
	Route::get('/signup_setting','AdminController@signup_setting');
	Route::post('/signup_setting','AdminController@post_signup_setting');

	Route::get('/admin_videoPrice','AdminController@admin_videoPrice');
	Route::post('/admin_videoPrice','AdminController@post_admin_videoPrice');

	Route::get('/threshold_setting','AdminController@threshold_setting');
	Route::post('/threshold_setting','AdminController@post_threshold_setting');
	Route::get('/extend_price','LoginController@extend_price');
	Route::post('/extend_price','LoginController@post_extend_price');
	Route::get('/set_purge','AdminController@set_purge');
	Route::post('/set_purge','AdminController@post_set_purge');
	Route::get('/login_artist','AdminController@get_login_artist');
	Route::get('/login_user','AdminController@get_login_user');
	Route::get('/login_admin','AdminController@get_login_admin');
	Route::get('user_status/{user_id}', 'ApiController@user_status');
	Route::get('/artist_reminder/{artist_id}',function($artist_id){
		$artist_data = DB::table('profiles')->select('*')->where('ProfileId',$artist_id)->first();
		if($artist_data!=null){
			$confirmation_code['Name'] = $artist_data->Name;
			$confirmation_code['EmailId'] = $artist_data->EmailId;
			Mail::send('emails.artist_reminder',$confirmation_code, function ($m) use ($artist_data) {
				$m->from('noreply@videorequestline.com', 'Request Video Reminder');
				$m->to($artist_data->EmailId, 'Rock')->subject('Your Reminder!');
				$m->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
				//$m->cc('admin@videorequestline.com', 'Rock')->subject('Your Reminder!');
				//$m->cc('codingbrains6@gmail.com', 'Rock')->subject('Your Reminder!');
				$m->subject('Reminder Message');
			});
			return redirect( url()->previous())->with('success',"Successfully sent reminder to Artist ");
		}else{
			return redirect( url()->previous())->with('error'," Reminder not sent successfully, Artist not found");
		}
		
	});

	Route::get('/user_payment_reminder/{user_id}',function($user_id)
	{
		$user_data = DB::table('profiles')->select('*')->where('ProfileId',$user_id)->first();
		if($user_data!=null){
			$confirmation_code['Name'] = $user_data->Name;
			$confirmation_code['EmailId'] = $user_data->EmailId;
			//dd($confirmation_code);
			Mail::send('emails.user_payment_reminder',$confirmation_code, function ($m) use ($user_data) {
				$m->from('noreply@videorequestline.com', 'Request Video Reminder');
				$m->to($user_data->EmailId, 'Rock')->subject('Your Reminder!');
				//$m->cc('admin@videorequestline.com', 'Rock')->subject('Your Reminder!');
				$m->cc('codingbrains6@gmail.com', 'Rock')->subject('Your Reminder!');
				$m->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
				$m->subject('Reminder Message');
			});
			return redirect( url()->previous())->with('success',"Successfully sent reminder to user ");
		}else{
			return redirect( url()->previous())->with('error'," Payment reminder not sent successfully, user not found");
		}
	});
	Route::get('/send_reminder/{ProfileId}/{RequestId}',function($ProfileId,$requestId)
	{

		$user='rock';
		$profile_data = DB::table('profiles')->select('*')->where('ProfileId',$ProfileId)->first();
		$request_data = DB::table('requestvideos')->select('*')->where('VideoReqId',$requestId)->first();
		//dd($profile_data);
		$confirmation_code['Name'] = $profile_data->Name;
		$confirmation_code['request_id'] = $requestId;
		$confirmation_code['title'] = $request_data->Title;
		$confirmation_code['description'] = $request_data->Description;
		//$confirmation_code['request_id'] = $profile_data->request_id;
		

		Mail::send('emails.test',$confirmation_code, function ($m) use ($profile_data) {
			$m->from('noreply@videorequestline.com', 'Request Video Reminder');
			$m->to($profile_data->EmailId, 'Rock')->subject('Your Reminder!');
			$m->cc('admin@videorequestline.com', 'Rock')->subject('Your Reminder!');
			$m->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
			//$m->cc('codingbrains6@gmail.com', 'Rock')->subject('Your Reminder!');
			$m->subject('Reminder Message');
		});
		//dd($profile_data->EmailId);
		return redirect( url()->previous())->with('success',"Successfully sent reminder to Artist ");
	});

	Route::get('move_file','LoginController@move_file');
});
Route::group(['middleware' => ['web']], function () {
	Route::get('/','HomeController@fhome')->middleware('revalidate');
	Route::get('/profile','HomeController@user_profile');
	Route::post('/profile','HomeController@post_user_profile');
	Route::get('about-us','HomeController@about');
	Route::get('help','HomeController@help');
	Route::get('privacy','HomeController@privacy');
	Route::get('terms','HomeController@terms');
	Route::get('ArtistDash', array('as' => 'artistDash', 'uses' => 'ArtistController@artistDash'));
	Route::get('video/{profile_id}/{name}', array('as' => 'videoDetails', 'uses' => 'HomeController@video_detail'));
	Route::get('video_detail/{id}', array('as' => 'videoDetails', 'uses' => 'HomeController@video_detail_page'));
	Route::get('{url}', array('as' => 'ArtistVideos', 'uses' => 'HomeController@artist_detail'));
	
});
//Route::resource('payment', 'PaymentController');

