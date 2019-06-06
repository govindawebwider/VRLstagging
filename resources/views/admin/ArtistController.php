<?php
namespace App\Http\Controllers;
use Auth;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;
use App\Http\Requests;
use Hash;
use Validator;
use Mail;
use App\Profile;
use App\Video;
use App\Requestvideo;
use Session;

class ArtistController extends Controller
{
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
//End

//Code for Artist details like Artist pic and all video's   
    public function RequestNewVideo($profile_id){
        $user_detail = Profile::find($profile_id);
        $detail['user_detail'] = $user_detail;
        return view('frontend.RequestNewVideo',$detail);
    }
    public function RequestNewVideoForm(Request $request,$id){
        $data = $request->all();
           // print_r($request->email);
        $validator = Validator::make($data,
            array(
                'title' =>'required',
                'description' =>'required',
                'delivery' =>'required',
                'username' =>'required',
                'email' =>'required|email|Between:3,64',
                'phone' =>'required|Min:10'
                )
            );
        if($validator->fails()){
            return redirect('/RequestNewVideo')
            ->withErrors($validator)
            ->withInput();
        }else{
                    //print_r($request);
            $email = DB::table('profiles')->where('EmailId',$request->email)->first();
            if(count($email) == 0)
            {
                $Status="Pending";
                $status=0;
                $type='User';
                $password='1234';
                $Requestvideo= new Requestvideo();
                $users = new User();
                $Profile = new Profile();
                $users->user_name= $request->username;
                $Profile->Name= $request->username;
                $users->email= $request->email;
                $Profile->EmailId= $request->email;
                $users->password= Hash::make($password);
                $users->remember_token = $request->_token;
                $users->type = $type;
                $Profile->Type = $type;
                $profTo=$id;
                $Requestvideo->Title=$request->title;
                $Requestvideo->Description=$request->description;
                $Requestvideo->Name=$request->username;
                $Requestvideo->RequestStatus=$Status;
                $Requestvideo->complitionDate=$request->delivery;
                $users->save();
                $Profile->save();
                $Requestvideo->save();
                $users = DB::table('profiles')->where('EmailId',$request->email)->first();
                $profId= $users->ProfileId;
                $profname= $request->username;;
                DB::table('users')
                ->where('email', $request->email)
                ->update(['profile_id' => $profId]);
                DB::table('requestvideos')
                ->where('Name',$profname)
                ->update(array('requestByProfileId' => $profId ,'requestToProfileId' => $profTo));






                if($Requestvideo->save()){
                    $successmsg="Your request have been submitted successfully and you are registered successfully. Your password is (".$password.") .";
                    return redirect('/')->withErrors($successmsg);

                }
            }else{
                return redirect('/RequestNewVideo')
                ->withErrors("Email Id is already Exists ! Please Try another Email Id.")
                ->withInput();

            }
        }
    }
//End  videoDetailsForm  

//Logout for FrontEnd
    
//End 

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
        $artists =  Profile::where('Type','Artist')->paginate(12);;

        $artist_data['artist'] = $artists;
        return view('frontend.viewAllArtist',$artist_data);
    }
    public function view_all_video() {

$videos = DB::table('video')->select('video.*','profiles.*')
            ->join('profiles','profiles.ProfileId','=','video.ProfileId')->paginate(12);
        $video_data['video'] = $videos;
        return view('frontend.viewAllVideo',$video_data);
    }


    public function sendmail(Request $request) {
        $id=1;
        $user = User::findOrFail($id);
        Mail::send('frontend.reminder', ['user' => $user], function ($m) use ($user) {

            $m->from('codingbrains123@gmail.com', 'codingbrains123@gmail.com');
            $m->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
            $m->to($user->email, $user->name,'Your Mail is send for Check Your Mail is send for Check')
            ->cc('codingbrains40@gmail.com', 'Shishir Yadav')

            ->subject('Your Mail is send for Check');

        });
    }


}