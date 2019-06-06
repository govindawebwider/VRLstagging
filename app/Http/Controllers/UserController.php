<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Mail;
use App\User;
use App\Profile;
use App\Requestvideo;
use App\Video;
use App\Notification;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;
use App\Http\Requests;
use Hash;
use Validator;
use Auth;
use Carbon\Carbon;
use FFMpeg;

class UserController extends Controller {
    /* --------------------------------Request New Video--------------------------------------- */

    public function requestvideo(Request $request) {
        $data = $request->all();
        $validator = Validator::make($data, array(
                    'video_title' => 'required',
                    'user_email' => 'required|email',
                    'video_delivery_time' => 'required',
                    'artist' => 'required',
                    'video_description' => 'required'
                        )
        );
        if ($validator->fails()) {
            $previous_url = url()->previous();
            $findme = 'https://www.videorequestline.com/RequestNewVideo/';
            $pos = strpos($previous_url, $findme);
            if ($pos !== false) {
                return redirect('RequestNewVideo/' . $request->artist)
                                ->withErrors($validator)
                                ->withInput();
            } else {
                return redirect('/')
                                ->withErrors($validator)
                                ->withInput();
            }
        } else {
            $Status = "Pending";
            $Requestvideo = new Requestvideo();
            $Requestvideo->requestToProfileId = $request->artist;
            $Requestvideo->requestor_email = $request->user_email;
            $Requestvideo->Title = $request->video_title;
            $Requestvideo->Description = $request->video_description;
            $Requestvideo->RequestStatus = $Status;
            $Requestvideo->complitionDate = $request->video_delivery_time;
            $Requestvideo->is_active = 0;
            $Requestvideo->save();
            if ($Requestvideo->save()) {
                $confirmation_code['user_email'] = $request->user_email;
                $confirmation_code['video_title'] = $request->video_title;
                $confirmation_code['video_description'] = $request->video_description;
                $confirmation_code['current_status'] = $Status;
                $confirmation_code['video_delivery_time'] = $request->video_delivery_time;
                $artist = Profile::where('ProfileId', $request->artist)->first();
                $confirmation_code['songName'] = $request->song_name;
                $confirmation_code['artist_name'] = $artist->Name;
                $artist_id = $artist->profile_id;
                $requestVideoId = $request->VideoReqId;
                $confirmation_code['identifier'] = $artist_id.'-'.$requestVideoId;
                if ($Requestvideo->save()) {
                    Mail::send('emails.User_RequestNewVideo', $confirmation_code, function ($message) use ($request, $confirmation_code) {
                        $message->from('codingbrains6@gmail.com', 'Confirmation for Video Request');
                        $message->to($request->user_email, $request->user_email);
                        $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                        $message->subject('VRL Video Request Confirmation ('.$confirmation_code['identifier'].')');
                    });
                    Mail::send('emails.admin_RequestNewVideo', $confirmation_code, function ($message) use ($request, $confirmation_code) {
                        $message->from('codingbrains6@gmail.com', 'Confirmation for Video Request');
                        $message->to('codingbrains6@gmail.com', $request->user_email);
                        $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                        $message->subject('VRL Video Request Confirmation ('.$confirmation_code['identifier'].')');
                    });
                }
                $successmsg = "Your Request have been Submitted Successfully.";
                return redirect('/')->with('success', $successmsg);
            }
        }
    }

    public function user_change_password() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "Artist" && session('current_type') == "Artist") {
                return redirect('/Dashboard');
            } elseif (Auth::user()->type == "User" || (Auth::user()->type == "Admin" || session('current_type') == "User")) {
                $user_name = Auth::user()->user_name;
                $pageData['user_name'] = $user_name;
                return view('frontend.change_password', $pageData);
            }
        } else {
            return redirect('/');
        }
    }

    public function post_user_change_password(Request $request) {
        if (Auth::check()) {
            $data = $request->all();
            $messages = [
                'new_pass.regex' => ' Use at least one letter, one numeral & one special character',
                // 'old_pass.regex' => ' Use at least one letter, one numeral & one special character',
                'new_pass.min' => ' New password should be at least 8 characters ',
                // 'old_pass.min' => ' Old password should be at least 8 characters',
                'conf_pass.min' => ' Confirm password should be at least 8 characters',
            ];
            $validator = Validator::make($data, array(
                        'old_pass' => 'required',
                        'new_pass' => 'required|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/',
                        'conf_pass' => 'required|min:8|same:new_pass',
                            ), $messages
            );

            if ($validator->fails()) {
                return redirect('user_change_password')
                                ->withErrors($validator)
                                ->withInput();
            } else {
                $user = User::find(Auth::user()->user_id);
                $old_password = $request->old_pass;
                $new_password = $request->new_pass;
                if (Hash::check($old_password, $user->getAuthPassword())) {
                    $user->password = Hash::make($new_password);
                    $user->access_token = null;
                    $user->mobile_login_count = 0;
                    if ($user->save()) {
                        \Session::put('password', $new_password);
                        return redirect('user_change_password')->with('pass_success', "Password Changed Successfully");
                    }
                } else {
                    return redirect('user_change_password')->with('error', "Invalid password");
                }
            }
        } else {
            return \Redirect::back();
        }
    }
    /**
       * user reaction video display
       * 
       * @return \Illuminate\Http\Response
       */
    public function userReactionVideos() {
        if (Auth::check()) {
            return view('frontend.userDashboard.user-reaction-edit');
        } else {
            return \Redirect::back();
        }
    }
    /**
     * user reaction video upload
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function videoUpload(Request $request) {
        if (Auth::check()) {
            if ($request->hasFile('file')){
                $file = $request->file('file');
                $mime = $file->getMimeType();
                $filesize = $request->file('file')->getSize();
                $s3 = Storage::disk('s3');
                if($mime === "video/mp4" ){
                    if($filesize <= 2000000){ //2000000 
                        $filename = $file->getClientOriginalName();
                        $extention = $request->file('file')->getClientOriginalExtension();
                        $newfilename = time() . $filename.'.'.$extention;
                        $path = 'uploads/reaction-videos/';
                        $db_path = 'uploads/reaction-videos/' . $newfilename;
                            if ($file->move($path,$newfilename)){
                                $postData = [
                                    'UserId' => Auth::user()->user_id,
                                    'VideoName' => $newfilename,
                                    'VideoFormat' => $extention,
                                    'VideoURL' => $db_path,
                                    'status' => 0,
                                    'VideoUploadDate' => date('Y-m-d H:i:s')
                                ];
                                $id = DB::table('reactionvideos')->insert($postData);
                                multipartUpload($db_path);
                                unlink($db_path);
                                return redirect('userAccount/reaction-upload')->with('success', 'Your video have been Uploaded Successfully.');
                            }else{
                                return redirect('userAccount/reaction-upload')->with('error', 'Error uploading file');
                            } 
                }else{
                        return redirect('userAccount/reaction-upload')->with('error', 'Maximum file upload size is of 2MB'); 
                    }
                }else{
                   return redirect('userAccount/reaction-upload')->with('error', 'Only MP4 files are allowed'); 
                }
            }
        }else {
            return \Redirect::back();
        }
    }
    
      /**
       * get user reaction video
       * 
       * @return \Illuminate\Http\Response
       */
     public function viewReactionUpload(){
         if (Auth::check()) {
            $postData = [
                        'UserId' => Auth::user()->user_id 
                    ];
            $users = DB::table('reactionvideos')->where($postData)->get(); 
            return view('frontend.userDashboard.user-reaction-view',['reactionvideos' => $users]);  
        } else {
            return \Redirect::back();
        }
     }
     
     public function ReactionVideoUpload(Request $request){
         if (Auth::check()) {
             $s3 = Storage::disk('s3');
            if ($request->hasFile('file')){
                $file = $request->file('file');
                $mime = $file->getMimeType();
                $filesize = $request->file('file')->getSize();  
                if($mime == "video/mp4" || $mime == "video/quicktime" || $mime == "video/x-msvideo"
                || $mime == "video/x-flv" || $mime == "application/x-mpegURL" || $mime == "video/3gpp"
                || $mime == "video/x-ms-wmv"){
            
                $filename = $file->getClientOriginalName();
                $extention = $file->getClientOriginalExtension();
                $rand = rand(11111, 99999) . date('U');
                $newfilename = $rand . '.' . $extention;
                $path =  'uploads/reaction-videos/';
                //$move = $s3->put($path.$newfilename, file_get_contents($file), 'public');
                $move = $file->move($path, $newfilename);
                multipartUpload($path.$newfilename);
                $ffmpeg = FFMpeg\FFMpeg::create([
                    'ffmpeg.binaries' => '/usr/bin/ffmpeg',
                    'ffprobe.binaries' => '/usr/bin/ffprobe',
                    'timeout' => 3600,
                    'ffmpeg.threads' => 12,
                ]);
                $ffprobe = FFMpeg\FFProbe::create([
                    'ffmpeg.binaries' => '/usr/bin/ffmpeg',
                    'ffprobe.binaries' => '/usr/bin/ffprobe',
                    'timeout' => 3600,
                    'ffmpeg.threads' => 12,
                ]);
                $thumbnailName = $rand. '.jpg';
                $video_thumbnail_path = 'images/thumbnails/' . $thumbnailName;
                $requested_video_id = $request->get('requested_video_id');
                $uploaded_video = $ffmpeg->open($path. $newfilename);
                $uploaded_video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(2))->save($video_thumbnail_path);
                    //$s3->put($video_thumbnail_path, file_get_contents($video_thumbnail_path));
                    multipartUpload($video_thumbnail_path);
                    unlink($video_thumbnail_path);
                if ($move) {
                        $postData = [
                            'UserId' => Auth::user()->user_id,
                            'VideoName' => $newfilename,
                            'VideoFormat' => $extention,
                            'VideoURL' => $newfilename,
                            'status' => 0,
                            'VideoUploadDate' => date('Y-m-d H:i:s'),
                            'thumbnail' => $thumbnailName,
                            'requested_video_id' => $requested_video_id
                        ];
                    unlink($path.$newfilename);
                        $id = DB::table('reactionvideos')->insert($postData);
                        return \Redirect::back()->with('success', \Lang::get('message.request_submitted_successfully'));
                    }else{
                        return redirect('userAccount/reaction-upload')->with('error', \Lang::get('message.error_uploading_file'));
                    }             
                }else{
                   return redirect('userAccount/reaction-upload')->with('error', \Lang::get('message.mp4_allowed')); 
                }
            }
        }else {
            return \Redirect::back();
        }
        
    }

     
}
