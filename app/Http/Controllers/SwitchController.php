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
use Snipe\BanBuilder\CensorWords;
class SwitchController extends Controller
{
    /**
     * SwitchController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function switch_as_artist($ProfileId)
	{	
		session(['current_type' => 'Artist']);
		$artist = User::where('profile_id',$ProfileId)->first();
		Auth::login($artist);
		return redirect('/Dashboard');
	}

	public function switch_as_user($ProfileId)
	{	
		session(['current_type' => 'User']);
		$user = User::where('profile_id',$ProfileId)->first();
		Auth::login($user);
		return redirect('/user_video');
	}

	public function switch_as_admin()
	{	
		$email = session('email');
		$user = User::where('email',$email)->first();
		Auth::login($user);
		return redirect('/admin_dashboard');
	}


}