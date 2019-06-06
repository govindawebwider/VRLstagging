<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notification;
use App\Testimonial;
use Auth;
use Validator;
use App\User;
use Carbon\Carbon;
use Snipe\BanBuilder\CensorWords;
class TestimonialController extends Controller {
	public function __construct()
	{
		$this->middleware('user_active');
		$this->middleware('revalidate');
	}
	public function comment(Request $request)
	{
		
		if(Auth::check()){
			
			$data = $request->all();
			//echo '<pre>';print_r($data);exit;
			
			$validator = Validator::make($data,
				array(
					'message' =>'required',
					'rate' =>'required'
					)
				);
			if($validator->fails()){
				return redirect( url()->previous())
				->withErrors($validator)
				->withInput();
			}else{
				$artist_id =  User::where('profile_id',$request->to_profile_id)->first();
				if(!is_null($artist_id)){
					if($artist_id->is_account_active==1)
					{
						$user = \App\Profile::find(Auth::user()->profile_id);
						$testimonial =   new Testimonial();
						$testimonial->to_profile_id = $request->to_profile_id;
						$testimonial->Email = $user->EmailId;
						$testimonial->user_name = $user->Name;
						$testimonial->AdminApproval = 0;
						$censor = new CensorWords;
						$string = $censor->censorString($request->message);
						$testimonial->message = $string['clean'];
						$testimonial->rate = $request->rate;
						// dd($string);
						if($testimonial->save()){
							return redirect( url()->previous())->with('success','Your Comment is Under review');
						}
					}
					else{
						return redirect( url()->previous())->with('error','Artist is Deactivated');
					}
				}
				else{
					return redirect('/view-all-artist')->with('error','Artist Account has been deleted');
				}
			}
		}
		
		else{
			return redirect('login')->with('login_error','Please Login to Comment');
		}
	}
}
