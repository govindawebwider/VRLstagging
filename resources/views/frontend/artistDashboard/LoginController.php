<?php
namespace App\Http\Controllers;

use App;
use App\User;
use App\Profile;
use App\Requestvideo;
use App\RequestedVideo;
use App\Video;
use App\OriginalVideo;
use App\Slider;
use App\Notification;
use App\Message;
use App\Occasion;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;
use App\Http\Requests;
use Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Validator;
use Mail;
use Auth;
use Carbon\Carbon;
use FFMpeg;
use Session;
use Snipe\BanBuilder\CensorWords;
use App\Testimonial;
use Crypt;
use App\Payment;
use File;
use Illuminate\Support\Facades\Config;
use Log;


class LoginController extends Controller {

    public function __construct() {

        $this->middleware('user_active');
        $this->middleware('revalidate');
    }

    public function webcame($id) {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User") {
                return redirect('/');
            } else {
                $requested_video = Requestvideo::find($id);
                $requested_user = DB::table('requestvideos')
                        ->join('profiles', 'requestvideos.requestByProfileId', '=', 'profiles.ProfileId')
                        ->select('*')
                        ->get();
                if ($requested_video == null) {
                    return redirect(url()->previous());
                } else if ($requested_user == null) {
                    return redirect('/video_requests')->with('success', 'user not exist!');
                } else {
                    $user_id = $requested_video->requestByProfileId;
                    //$profileData =  Profile::where('EmailId',Auth::user()->email)->first();
                    $user = Profile::find($user_id);
                    $artist = Profile::where('EmailId', Auth::user()->email)->first();
                    $artist_data['artist'] = $artist;
                    $artist_data['user'] = $user;
                    $artist_data['requested_video'] = $requested_video;
                    return view('frontend.artistDashboard.webcame', $artist_data);
                }
            }
        } else {
            return redirect('/');
            // return view('frontend.login');
        }
    }

    public function logins() {
        return view('frontend.login');
    }

    public function registers() {
        return view('frontend.register');
    }

    public function error_handling() {
        //echo "test";
        return view('frontend.error');
    }

    public function edit_sample_video($id) {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User") {
                return redirect('/');
            } else {
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

    public function post_edit_sample_video(Request $request) {
        $validator = Validator::make(
                        array(
                    'video_title' => $request->video_title,
                    'video_description' => $request->video_description,
                    'video' => $request->file('video'),
                        ), array(
                    'video_title' => 'required',
                    'video_description' => 'required|min:80',
                    'video' => 'mimes:mp4,mpeg',
                        )
        );
        if ($validator->fails()) {
            return redirect('edit_sample_video/' . $request->video_id)
                            ->withErrors($validator)
                            ->withInput();
        } else {
            //dd($request->all());
            $video = Video::find($request->video_id);
            $file = $request->file('video');
            if ($file != '') {
                $extension = $file->getClientOriginalExtension();
                $filename = str_replace(' ', '', $file->getClientOriginalName());
                $filename = str_replace('-', '', $filename);
                $VideoURL = "https://www.videorequestline.com/video/" . date('U') . $filename;
                $video->VideoFormat = $file->getClientOriginalExtension();
                $video->VideoSize = ($file->getSize() / 1024) . "mb";
            }

            $video->Description = $request->video_description;
            $video->Title = $request->video_title;
            $video->VideoUploadDate = Carbon::now()->format('m-d-Y');
            if ($video->save()) {
                return redirect('edit_sample_video/' . $request->video_id)->with('success', 'Video updated Successfully');
            }
        }
    }

    public function edit_socialLink($id) {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User") {
                return redirect('/');
            } else {
                //echo "hello";
                $artist = Profile::where('EmailId', Auth::user()->email)->first();
                $artist_data['artist'] = $artist;
                $social_data = DB::table('social_media')->where('id', $id)->first();
                $artist_data['social_data'] = $social_data;
                return view('frontend.artistDashboard.edit_socialLink', $artist_data);
            }
        } else {
            return redirect('/');
        }
    }

    public function post_enable_socialLink($id) {
        if (DB::table('social_media')
                        ->where('id', $id)
                        ->update(array('is_active' => 'Enable'))) {
            return redirect('/get_social_link')->with('success', "Enable Successfully");
        } else {
            return redirect('/get_social_link')->with('success', "Update Not Successfully");
        }
    }

    public function post_disable_socialLink($id) {

        if (DB::table('social_media')
                        ->where('id', $id)
                        ->update(array('is_active' => 'Disable'))) {
            return redirect('/get_social_link')->with('success', "Disable Successfully");
        } else {
            return redirect('/get_social_link')->with('success', "Update Not Successfully");
        }
    }

    public function post_edit_socialLink(Request $request) {
        if (Auth::check()) {
            $data = $request->all();
            $validator = Validator::make($data, array(
                        'name' => 'required',
                        'social_img' => 'required||mimes:jpeg,png',
                        'social_url' => 'required',
                            )
            );
            if ($validator->fails()) {
                return redirect(url()->previous())
                                ->withErrors($validator)
                                ->withInput();
            } else {
                if ($request->social_img == "") {
                    $profile_path = $request->image_path;
                } else {
                    if ($request->file('social_img') != "") {
                        $file = $_FILES["social_img"]['tmp_name'];
                        list($width, $height) = getimagesize($file);
                        if ($width > 80 || $height > 80) {
                            return redirect('/edit_social_link/' . $request->SocialId)->with('success', 'image size must be 80 x 80 pixels.');
                        } else {
                            $file = $request->file('social_img');
                            //$filename = $file->getClientOriginalName();
                            $extension = $file->getClientOriginalExtension();
                            $filename = date('U').'.'.$extension;
                            $profile_path = "socialLink/" . $filename;
                            //$s3 = Storage::disk('s3');
                            //$s3->put($profile_path, file_get_contents($file));
                            //$destinationPath = base_path() . '/public/socialLink/';
                            $request->file('social_img')->move("socialLink/", $filename);
                            multipartUpload($profile_path);
                            unlink("socialLink/", $filename);
                        }
                    }
                }


                if (DB::table('social_media')->where('id', '=', $request->SocialId)->update(
                                array('social_name' => $request->name, 'social_img' => $filename, 'social_url' => $request->social_url))) {
                    return redirect('/edit_social_link/' . $request->SocialId)->with('success', "Successfully Updated ");
                } else {
                    return redirect('/edit_social_link/' . $request->SocialId)->with('error', "Not Successfully Updated ");
                }
            }
        } else {
            return redirect('/');
        }
    }

    public function delete_sample_video($id) {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('/');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                //echo $id;
                $video = DB::table('video')->where('VideoId', $id)->first();
                if (DB::table('video')->where('VideoId', $id)->delete()) {
                    $s3 = Storage::disk('s3');
                    $s3->delete('video/original/'.$video->originalVideoUrl);
                    $s3->delete('images/thumbnails/'.$video->VideoThumbnail);
                    $s3->delete('video/watermark/'.$video->VideoURL);
                    return redirect('/artist_video')->with('success', "Delete Successfully");
                } else {
                    return redirect('/artist_video')->with('error', "Deletion Not Successfully ");
                }
            }
        } else {
            return redirect('/');
            // return view('frontend.login');
        }
    }

    public function delete_socialLink($id) {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User") {
                return redirect('/');
            } else {
                if (DB::table('social_media')->where('id', $id)->delete()) {
                    return redirect('/get_social_link')->with('success', "Delete Successfully");
                } else {
                    return redirect('/get_social_link')->with('error', "Deletion Not Successfully ");
                }
            }
        } else {
            return redirect('/');
            // return view('frontend.login');
        }
    }

    public function add_more_social_link() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $artist = Profile::where('EmailId', Auth::user()->email)->first();
                $artist_data['artist'] = $artist;
                //dd($artist);
                return view('frontend.artistDashboard.addMore_socialLink', $artist_data);
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
    }

    public function post_add_more_social_link(Request $request) {
        //dd($request->all());
        if (Auth::check()) {
            $data = $request->all();
            //dd($data);
            $validator = Validator::make($data, array(
                        'name' => 'required',
                        'social_img' => 'required||mimes:jpeg,png',
                        'social_url' => 'required',
                            )
            );
            if ($validator->fails()) {
                return redirect(url()->previous())
                                ->withErrors($validator)
                                ->withInput();
            } else {
                //dd($request->all());
                if ($request->social_img == "") {
                    $profile_path = "images/Artist/default-artist.png";
                } else {
                    if ($request->file('social_img') != "") {
                        $file = $_FILES["social_img"]['tmp_name'];
                        list($width, $height) = getimagesize($file);
                        if ($width > 1250 || $height > 1250) {
                            return redirect('/addMore_social_link')->with('success', 'image size must be 1250 x 1250 pixels.');
                        } else {
                            $file = $request->file('social_img');
                            $filename = $file->getClientOriginalName();
                            $extension = $file->getClientOriginalExtension();
                            $filename = date('U').'.'.$extension;
                            $profile_path = "socialLink/" . $filename;
                            //$s3 = Storage::disk('s3');
                            //$s3->put($profile_path, file_get_contents($file));

                            //$destinationPath = base_path() . '/public/socialLink/';
                            $request->file('social_img')->move("socialLink/", $filename);
                            multipartUpload($profile_path);
                            unlink($profile_path);
                        }
                    }
                }


                if (DB::table('social_media')->insert(
                                array('social_name' => $request->name, 'social_img' => $filename, 'social_url' => $request->social_url, 'addBy_profileId' => $request->ProfileId, 'is_active' => 'Enable'))) {
                    return redirect('/get_social_link')->with('success', "Successfully Added ");
                } else {
                    return redirect('/get_social_link')->with('error', "Not Successfully Added ");
                }
            }
        } else {
            return redirect('/');
        }
    }

    /* --------------------------------User Change Email-------------------- */

    public function user_change_email() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "Artist") {
                return redirect('/');
            } else {
                $id = Auth::user()->profile_id;
                $name = Auth::user()->user_name;
                $profile_id = Auth::user()->profile_id;
                $user = Profile::find(Auth::user()->profile_id);
                $artist = Profile::where('type', 'Artist')->get();
                $pageData['user'] = $user;
                $pageData['artist'] = $artist;
                return view('frontend.UserChangeEmail', $pageData);
            }
        } else {
            return redirect('/');
        }
    }

   
    /*-----------------------Author:Vishal , Section:user-dashboard view-----------*/
    public function post_user_dashboard() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "Artist" && session('current_type') == "Artist") {
                return redirect('/');
            } elseif (Auth::user()->type == "User" || (Auth::user()->type == "Admin" || session('current_type') == "User")) {
                $id = Auth::user()->profile_id;
                $name = Auth::user()->user_name;
                $profile_id = Auth::user()->profile_id;
                $user = Profile::find(Auth::user()->profile_id);
                $my_videos = DB::table('requested_videos')->where('requestBy', $profile_id)
                        ->where('is_active', '<>', '1')->orderBy('id', 'desc')
                        ->get();

                $artist = Profile::where('type', 'Artist')->get();

                $request_details = DB::table('requestvideos')
                        ->join('profiles', 'profiles.ProfileId', '=', 'requestvideos.requestToProfileId')
                        ->where('requestvideos.requestByProfileId', $profile_id)
                        ->where(function($query) {
                            $query->where('requestvideos.is_delete', '=', 'false')
                            ->orWhere('requestvideos.is_delete', '=', '');
                        })
                        
                        ->orderBy('VideoReqId', 'desc')
                        ->get();
//dd($request_details);
                $pageData['user'] = $user;
                $pageData['my_videos'] = $my_videos;
                $user_name = Auth::user()->user_name;
                $pageData['user_name'] = $user_name; 
                $pageData['artist'] = $artist;
                $pageData['request_details'] = $request_details;
                $pageData['baseurl'] = "https://videorequestline.com/";
                return view('frontend.UserDashboard', $pageData);
            }
        } else {
            return redirect('/login');
        }
    }
   
    /* -------------------------------Price calculation-------------------- */

    public function extend_price() {
        if (Auth::check()) {

            if (Auth::user()->type == "Admin") {
                $userData = Profile::where('EmailId', Auth::user()->email)->first();
                $artist_data['userData'] = $userData;
                $storage_data = DB::table('setting')->select('status')->where('name', '=', 'storage')->first();
                $artist_data['storage_data'] = $storage_data;
                return view('admin.extend_storage', $artist_data);
            }
        } else {
            return redirect('/admin');
        }
    }

    /* -------------------------------Price calculation-------------------- */

    public function post_extend_price(Request $request) {
        $data = $request->all();
        //dd($data);
        $messages = [
            'stor_price.integer' => ' The Storage Price must be an integer',
            'stor_price.required' => ' The Storage Price field is required',
        ];
        $validator = Validator::make($data, array(
                    //regex:/^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-0-9-, ])*$/
                    'stor_price' => 'required|between:0,10000000000',
                        ), $messages
        );
        if ($validator->fails()) {
            return redirect(url()->previous())
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $storage_data = DB::table('setting')->select('status')->where('name', '=', "storage")->get();
            if ($storage_data == null) {
                //echo "insert";
                DB::table('setting')->insert(
                        array('status' => $request->stor_price, 'name' => 'storage'));
                return redirect(url()->previous())->with('success', "Successfully Added ");
            } else {
                DB::table('setting')->where('name', 'storage')->update(array('status' => $request->stor_price));
                return redirect(url()->previous())->with('success', "Successfully Updated ");
                //echo "update";
            }
        }
    }

    /* -------------------------------Price calculation-------------------- */

    public function price_cal() {
        echo "test";
    }

    /* ---------------------- User video show ----------------------------- */

    public function post_user_video() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "Artist" && session('current_type') == "Artist") {
                return redirect('/');
            } elseif (Auth::user()->type == "User" || (Auth::user()->type == "Admin" || session('current_type') == "User")) {
                $profile_id = Auth::user()->profile_id;
                $user_email = Auth::user()->email;
                $user_name = Auth::user()->user_name;
                $my_videos = DB::table('requested_videos')->where('requestBy', $profile_id)
                        ->where('is_active', '=', '1')->where('remain_storage_duration', '!=', 'disable')->orderBy('id', 'desc')
                        ->get();
                $purge_data = DB::table('setting')->select('status')->where('name', '=', "purge")->first();
                $storage_data = DB::table('setting')->select('status')->where('name', '=', "storage")->first();
                //dd($purge_data->status);
                if (!is_null($my_videos)) {
                    foreach ($my_videos as $my_video) {
                        if ($my_video->url != 'removed') {
                            $now = new \DateTime();
                            $date1 = date_create($my_video->purchase_date);
                            $diff = date_diff($date1, $now);
                            $diff_date = $diff->format("%a");
                            if ($my_video->remain_storage_duration - $diff_date <= 0) {

                                if ($my_video->desti_url == 'removed') {
                                    $source = "requested_video/";
                                    $destination = "requested_video/backup_video/";

                                    $st = substr($my_video->url, 49);
                                    $file = $st;

                                    $confirmation_code['id'] = $my_video->id;
                                    $confirmation_code['title'] = $my_video->title;
                                    $confirmation_code['description'] = $my_video->description;
                                    Mail::send('emails.delete_video', $confirmation_code, function ($message) {
                                        $user_email = Auth::user()->email;
                                        //dd($user_email);
                                        $message->from('noreply@videorequestline.com', 'Storage duration ended');
                                        $message->to($user_email, 'rajesh');
                                        $message->from('codingbrains6@gmail.com', 'Video Storage Duration');
                                        $message->to(Auth::user()->email);

                                        $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));

                                        $message->subject('Your video storage duration is ended , please pay to extend storage of video.');
                                    });
                                }
                                DB::table('requested_videos')->where('id', $my_video->id)->update(array('desti_url' => $my_video->url, 'url' => 'removed', 'remain_storage_duration' => 0, 'desti_thumbnail' => $my_video->thumbnail, 'thumbnail' => 'removed', 'thumbnail_status' => '0'));
                            }
                        }
                    }
                }

                $pageData['my_videos'] = $my_videos;
                $pageData['purge_data'] = $purge_data;
                $pageData['storage_data'] = $storage_data;
                $pageData['user_email'] = $user_email;
                $pageData['user_name'] = $user_name;
                return view('frontend.UserVideo', $pageData);
               
            }
        } else {
            return redirect('/');
        }
    }

    /* ----------------------Artist testimonial view------------------- */

    public function view_review() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $user = User::where('email', Auth::user()->email)->first();

                $artist = Profile::where('EmailId', Auth::user()->email)->first();
                $artist_data['user'] = $user;
                $artist_data['artist'] = $artist;
                $testi_data = Testimonial::where('to_profile_id', Auth::user()->profile_id)->orderBy('id', 'desc')->get();
                $artist_data['testi_data'] = $testi_data;
                return view('frontend.artistDashboard.view_review', $artist_data);
            }
        } else {
            return redirect('/');
        }
    }

    /* ----------------------Artist testimonial add------------------- */

    public function add_testimonial() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } elseif (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $artist = Profile::where('EmailId', Auth::user()->email)->first();
                $artist_data['artist'] = $artist;

                return view('frontend.artistDashboard.add_testimonial', $artist_data);
            } else {
                
            }
        } else {
            return redirect('/');
        }
    }

    public function post_add_testimonial(Request $request) {
        if (Auth::check()) {
            $data = $request->all();
            //dd($data);
            $validator = Validator::make($data, array(
                        'message' => 'required'
                            )
            );
            if ($validator->fails()) {
                return redirect(url()->previous())
                                ->withErrors($validator)
                                ->withInput();
            } else {
                $artist_id = User::where('profile_id', Auth::user()->profile_id)->first();
                if ($artist_id->is_account_active == 1) {
                    $user = Auth::user();
                    $testimonial = new Testimonial();
                    $testimonial->by_profile_id = $user->profile_id;
                    $testimonial->to_profile_id = $user->profile_id;
                    //$testimonial->video_id = $request->video_id;
                    $testimonial->AdminApproval = 0;
                    $testimonial->testi_date = date('d-m-Y');
                    $censor = new CensorWords;
                    $string = $censor->censorString($request->message);
                    $testimonial->message = $string['clean'];
                    if ($testimonial->save()) {
                        return redirect(url()->previous())->with('success', 'Your Comment is Under review');
                    }
                } else {
                    return redirect(url()->previous())->with('error', 'You can not sent any request to Artist because Artist is Deactivated');
                }
            }
        } else {
            return redirect('/');
        }
    }

    /* ----------------------Artist testimonial Edit------------------- */

    public function edit_testimonial($testi_id) {

        $testi_data = Testimonial::where('id', $testi_id)->first();


        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $artist = Profile::where('EmailId', Auth::user()->email)->first();
                $artist_data['artist'] = $artist;
                $testi_data = Testimonial::where('id', $testi_id)
                        ->join('profiles', 'profiles.ProfileId', '=', 'testimonials.by_profile_id')
                        ->first();
                $artist_data['testi_data'] = $testi_data;
                //dd($artist_data['testi_data']);
                //$artist_data['artist'] = $artist;
                return view('frontend.artistDashboard.edit_testimonial', $artist_data);
            }
        } else {
            return redirect('/');
        }
    }

    public function post_edit_testimonial(Request $request, $testi_id) {
        if (Auth::check()) {
            $data = $request->all();
            //dd($data);
            $validator = Validator::make($data, array(
                        'message' => 'required'
                            )
            );
            if ($validator->fails()) {
                return redirect(url()->previous())
                                ->withErrors($validator)
                                ->withInput();
            } else {

                if (DB::table('testimonials')->where('id', $testi_id)->update(['message' => $request->message])) {
                    return redirect('/edit_testimonial/' . $testi_id)->with('success', "successfully updated ");
                } else {
                    return redirect('/edit_testimonial/' . $testi_id)->with('success', "No change here,successfully updated");
                }
            }
        } else {
            return redirect('/');
        }
    }

    /* ----------------------Artist testimonial Delete------------------- */

    public function delete_review($testi_id) {
        if (Auth::check()) {
            //$data = $request->all();
            if (DB::table('testimonials')->where('id', $testi_id)->delete()) {
                return redirect('/view_review')->with('success', "successfully deleted ");
            } else {
                return redirect('/view_review')->with('error', "delete not successfully");
            }
        } else {
            return redirect('/');
        }
    }

    /* --------------------video comment by user ------------------------ */

    public function post_video_testimonial(Request $request) {

        $data = $request->all();
        //dd($data);
        if (Auth::check()) {

            $data = $request->all();
            $validator = Validator::make($data, array(
                        'message' => 'required',
                        'rate' => 'required'
                            )
            );
            if ($validator->fails()) {
                return redirect(url()->previous())
                                ->withErrors($validator)
                                ->withInput();
            } else {
                $user = \App\Profile::find($request->by_profile_id);
                $artist = \App\User::where('profile_id', $request->to_profile_id)->first();
                if (!is_null($artist)) {
                    if ($artist->is_account_active == 1) {
                        $testimonial = new Testimonial();
                        $testimonial->user_name = $user->Name;
                        $testimonial->to_profile_id = $request->to_profile_id;
                        if ($request->by_profile_id != null) {
                            $testimonial->by_profile_id = $request->by_profile_id;
                        }
                        $testimonial->video_id = $request->video_id;
                        $testimonial->Email = $user->EmailId;
                        $testimonial->AdminApproval = 0;
                        $censor = new CensorWords;
                        $string = $censor->censorString($request->message);
                        $testimonial->message = $string['clean'];
                        $testimonial->rate = $request->rate;
                        if ($testimonial->save()) {
                            return redirect(url()->previous())->with('success', 'Your Comment is Under review');
                        }
                    } else {
                        return redirect(url()->previous())->with('error', 'Artist is Deactivated');
                    }
                } else {
                    return redirect('/view-all-artist')->with('error', 'Artist Account has been deleted');
                }
            }
        } else {
            return redirect('login')->with('login_error', 'Please Login to Comment');
        }
    }

    /* ------------------------------Success payment------------------------------- */

    public function success_payment() {
        return view('frontend.success_payment');
    }

    /* ------------------------------Success request------------------------------- */

    public function success_request() {
        return view('frontend.success_request');
    }

    /* ------------------------------Success Register------------------------------- */

    public function success_register() {
        return view('frontend.success_register');
    }

    /* ------------------------------Add Price------------------------------- */

    public function add_price() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $user = User::where('email', Auth::user()->email)->with('profile')->first();

                $artist = Profile::where('EmailId', Auth::user()->email)->first();
                //dd($artist);
                $artist_data['artist'] = $artist;
                $artist_data['user'] = $user;
                $artist_data['baseurl'] = "https://videorequestline.com/";


                 $occasions = Occasion::whereArtistProfileId($user->profile_id)->orderBy('id', 'DESC')->get();
                $artist_data['occasions'] = $occasions;
                $artist_data['users'] = $user;
                $artist_data['current_date'] = $date = date('d-m-Y');


                return view('frontend.artistDashboard.video_price', $artist_data);
            }
        } else {
            return redirect('/');
        }
    }

    public function post_add_price(Request $request) {
        $data = $request->all();
        $validator = Validator::make(
                        array(
                    'video_price' => $request->video_price,
                        ), array(
                    'video_price' => 'required|integer',
                        )
        );
        if ($validator->fails()) {
            return redirect('/addPrice')
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $id = Auth::user()->profile_id;
            if ($request->video_price <= '500' and $request->video_price >= '1') {
                if (DB::table('profiles')->where('ProfileId', $id)->update(['VideoPrice' => $request->video_price])) {
                    return redirect('addPrice')->with('success', "successfully updated ");
                } else {
                    return redirect('addPrice')->with('success', "successfully updated ");
                }
            } else {
                return redirect('addPrice')->with('error', 'Price must be between $1 to $500');
            }
        }
    }

    /* ------------------------------Add Timestamp------------------------------- */

    public function turnaround_time() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $user = User::where('email', Auth::user()->email)->first();

                $artist = Profile::where('EmailId', Auth::user()->email)->first();
                $artist_data['user'] = $user;
                $artist_data['artist'] = $artist;
                return view('frontend.artistDashboard.timestamp', $artist_data);
            }
        } else {
            return redirect('/');
        }
    }

    public function post_turnaround_time(Request $request) {
        $data = $request->all();
        $messages = [
            'timestamp.required' => 'The fulfillment duration field is required.',
            'timestamp.integer' => 'The fulfillment duration must be an integer.',
            'timestamp.between' => 'The fulfillment duration must be greater than 0.',
        ];
        $validator = Validator::make(
                        array(
                    'timestamp' => $request->timestamp,
                        ), array(
                    'timestamp' => 'required|integer|between:1,10000000',
                        ), $messages
        );
        if ($validator->fails()) {
            return redirect('turnaround_time')
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $id = Auth::user()->profile_id;
            $artist = \App\Profile::find($id);
            $artist->timestamp = $request->timestamp;

            if ($artist->save()) {
                return redirect('turnaround_time')->with('success', "successfully updated ");
            } else {
                return redirect('turnaround_time')->with('error', 'Oops..! Something went go Wrong');
            }
        }
    }

    /* ------------------------------Media CSs------------------------------- */

    public function get_media(Request $request) {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $user = User::where('email', Auth::user()->email)->first();
                $artistData['user'] = $user;

                $profileData = Profile::where('EmailId', Auth::user()->email)->first();
                $text_color = DB::table('profiles')->where('EmailId', Auth::user()->email)->first();
                $artistData['profileData'] = $profileData;
                $artistData['text_color'] = $text_color;
                return view('frontend.artistDashboard.media', $artistData);
            }
        } else {
            return redirect('/');
        }
    }

    public function media(Request $request) {
        if (DB::table('profiles')->
                        where('EmailId', Auth::user()->email)->
                        update(array('text_color' => $request->text_color, 'title_color' => $request->title_color, 'name_heading_size' => $request->name_heading_size, 'description_color' => $request->description_color, 'custom_css' => $request->custom_css))) {
            return redirect('media')->with('success', 'Successfully Updated');
            ;
        }
    }

    /* ------------------------------bank details------------------------------- */

    public function get_bank_details(Request $request) {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $user = User::where('email', Auth::user()->email)->first();
                $profileData = Profile::where('EmailId', Auth::user()->email)->first();
                return view('frontend.artistDashboard.update_bank', ['profileData' => $profileData, 'user' => $user]);
            }
        } else {
            return redirect('/');
        }
    }

    public function bank_details(Request $request) {
        $profileData = Profile::where('EmailId', Auth::user()->email)->first();
        if ($profileData->Zip == " ") {
            return redirect('bank_details')->with('error', 'Please go to edit profile option and fill zip code');
        } else {
            $messages = [
                'account_number.regex' => ' Account No Should contain Numbers only',
                'confirm_account_number.regex' => 'Confirmation Account No Should contain Numbers only',
                'account_number.digits' => ' Account No Should contain Numbers only',
                'confirm_account_number.digits' => 'Confirmation Account No Should contain Numbers only',
                'pin.required' => 'The confirmation SSN field is required.',
                'pin.same' => 'The confirmation SSN No Should be same as SSN no',
                'pin.min' => 'The Confirmation SSN No must be at least 4 characters.',
                'pin.max' => 'The Confirmation SSN No must be at least 4 characters.',
            ];
            $validator = Validator::make(
                            array(
                        'routing_number' => $request->routing_number,
                        'account_number' => $request->account_number,
                        'confirm_account_number' => $request->confirm_account_number,
                        'ssn_number' => $request->ssn_number,
                        'pin' => $request->pin,
                            //'id_pic' =>$request->file('id_pic')
                            ), array(
                        //'routing_number' =>'required|min:9|max:9',
                        'routing_number' => 'required|min:9|max:9',
                        'account_number' => 'required|min:9|max:12',
                        //'account_number' =>'required|regex:/[0-9]/|digits:32',
                        'confirm_account_number' => 'required|same:account_number',
                        //'confirm_account_number' =>'required|regex:/[0-9]/|digits:32|same:account_number',
                        //'ssn_number' =>'required|min:4|max:4',
                        'ssn_number' => 'required|min:4|max:4',
                        //'pin' =>'required',
                        'pin' => 'required|min:4|max:4|same:ssn_number',
                            //'id_pic' =>'required|mimes:jpeg,png',
                            //'id_pic' =>'',
                            ), $messages
            );

            if ($validator->fails()) {
                return redirect('bank_details')
                                ->withErrors($validator)
                                ->withInput();
            } else if (Substr($request->pin, -11) !== $request->ssn_number) {
                return redirect('bank_details')->with('error', 'Personal id number and ssn number must match')->withInput();
            } else {
                $id = Auth::user()->profile_id;
                $artist_bank_detail = Profile::find($id);

                if ($artist_bank_detail->is_bank_updated == 2) {

                    if ($artist_bank_detail->stripe_account_id != null) {
                        return redirect('bank_details')->with('error', 'You can not update Because your Account Is Created !')->withInput();
                    }
                } elseif ($artist_bank_detail->account_number != null) {

                    $artist_routing_number = Crypt::encrypt($request->routing_number);
                    $artist_account_number = Crypt::encrypt($request->account_number);
                    $artist_ssn_number = Crypt::encrypt($request->ssn_number);
                    $artist_pin = Crypt::encrypt($request->pin);

                    if (DB::table('profiles')
                                    ->where('ProfileId', Auth::user()->profile_id)
                                    ->update(['routing_number' => $artist_routing_number, 'account_number' => $artist_account_number, 'ssn_number' => $artist_ssn_number, 'pin' => $artist_pin])) {

                        return redirect('bank_details')->with('success', 'Successfully Updated! ');
                    }
                } else {
                    if ($artist_bank_detail->is_bank_updated == 0) {

                        $artist_bank_detail->is_bank_updated = 1;
                    } else {
                        $artist_bank_detail->is_bank_updated = 2;
                    }

                    $artist_bank_detail->routing_number = Crypt::encrypt($request->routing_number);
                    $artist_bank_detail->account_number = Crypt::encrypt($request->account_number);
                    $artist_bank_detail->ssn_number = Crypt::encrypt($request->ssn_number);
                    $artist_bank_detail->pin = Crypt::encrypt($request->pin);


                    if ($request->file('id_pic') != null) {
                        $id_pic_path = "images/Artist/id/" . date('U') . '.jpg';
                        $artist_bank_detail->id_pic = $id_pic_path;
                        $destinationPath = base_path() . '/public/images/Artist/id/';
                        $request->file('id_pic')->move($destinationPath, $id_pic_path);

                        if ($artist_bank_detail->save()) {
                            $data['artist_id'] = Auth::user()->profile_id;
                            $data['artist_name'] = Auth::user()->user_name;
                            Mail::send('emails.Artist_id', $data, function($message) use ($id_pic_path) {
                                $message->to('admin@videorequestline.com');
                                $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                                $message->subject('Artist Driver License');
                                $message->from('noreply@videorequestline.com');
                                $message->attach(public_path() . '/' . $id_pic_path);
                            });
                            return redirect('bank_details')->with('success', 'Successfully Updated! ');
                        }
                    } else {
                        if ($artist_bank_detail->save()) {
                            $data['artist_id'] = Auth::user()->profile_id;
                            $data['artist_name'] = Auth::user()->user_name;
                            Mail::send('emails.Artist_id', $data, function($message) {
                                $message->to('admin@videorequestline.com');
                                $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                                $message->subject('Artist Driver License');
                                $message->from('noreply@videorequestline.com');
                                //$message->attach(public_path().'/'.$id_pic_path);
                            });
                            return redirect('bank_details')->with('success', 'Successfully Updated! ');
                        }
                    }
                }
            }
        }
    }

    /* --------------------------------Reset---------------------------------- */

    public function forget_password_verify(Request $request) {
        $token = $request->userToken;

        if (Auth::check()) {
            if (Auth::user()->type == "Artist") {
                return redirect('/');
            } elseif (Auth::user()->type == "User") {
                return redirect('profile');
            } else {
                return redirect('admin_dashboard');
            }
        } else {
            $token = decrypt($token);
            $result = User::whereAuthResetPass($token)->first();
            if (!is_null($result)) {
                return view('frontend.reset')->with(['token' => encrypt($token)]);
            } else {
                return redirect('login');
            }
        }
    }

    /* -------------------------------------Reset-------------------------- */

    public function password_reset() {
        if (Auth::check()) {
            if (Auth::user()->type == "Artist") {
                return redirect('/');
            } elseif (Auth::user()->type == "User") {
                return redirect('profile');
            } else {
                return redirect('admin_dashboard');
            }
        } else {
            return view('frontend.reset');
        }
    }

    public function post_password_reset(Request $request) {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
            'password.regex' => ' Use at least one letter, one numeral & one special character',
            'password.min' => 'Password must be at least 8 character',
        ];
        $validator = Validator::make($data, array(
                    'password' => 'required|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/',
                    'c_password' => 'required|same:password',
                    'userToken' => 'required'
                        ), $messages
        );

        if ($validator->fails()) {

            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $pass = $request->password;
            $enc_pass = Hash::make($pass);
            $result = DB::table('users')
                    ->where('auth_reset_pass', decrypt($request->userToken))
                    ->first();
            if (DB::table('users')->where('email', $result->email)->update(['password' => $enc_pass])) {
                $auth_pass_re = '';
                User::whereEmail($result->email)->update([
                    'auth_reset_pass' => $auth_pass_re,
                    'access_token' => null,
                    'mobile_login_count' => 0
                ]);
                Session::flush();
                return redirect('/')->with('success', "You have successfully reset your Password");
            } else {
                return redirect()->back();
            }
        }
    }

    /* ----------------------------Forget Password----------------------------------- */

    public function get_forget_pass() {
        if (Auth::check()) {
            if (Auth::user()->type == "Artist") {
                return redirect('/');
            } elseif (Auth::user()->type == "User") {
                return redirect('profile');
            } else {
                return redirect('admin_dashboard');
            }
        } else {
            return view('frontend.forget');
        }
    }

    public function forgetpass(Request $request) {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, array(
                    'email' => 'required'
                        ), $messages
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $email = Profile::where('EmailId', $request->email)->first();
            if (!is_null($email)) {
                $auth_pass = str_random(15);
                User::whereEmail($request->email)->update([
                    'auth_reset_pass' => $auth_pass
                ]);
                $confirmation_code['confirmation_code'] = encrypt($auth_pass);
                Mail::send('emails.forget_reminder', $confirmation_code, function ($message) use ($request) {
                    $message->from('noreply@videorequestline.com', \Lang::get('views.reset_password'));
                    $message->to($request->email, \Lang::get('views.user'));
                    $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                    $message->subject('Reset Password');
                });
                return redirect('login')->with('success', "Please Check Your Email to get Password");
            } else {
                return redirect()->back()->with('forget_error', "Unregistered Email");
            }
        }
    }

    /* -----------------------Artist Registration------------------------------ */

    public function artist_register() {
        if (Auth::check()) {
            if (Auth::user()->type == "Artist") {
                return redirect('/');
            } elseif (Auth::user()->type == "User") {
                return redirect('profile');
            } else {
                return redirect('admin_dashboard');
            }
        } else {
            $signup_data = DB::table('setting')->select('status')->where('name', '=', 'signup')->first();
            //dd($signup_data);
            if ($signup_data->status != 'show')
                return view('frontend.artist_registration');
            else
                return redirect('/');
        }
    }
    public function validate_email(Request $request) {
        $email = DB::table('profiles')->where('EmailId', $request->email)->first();
        if (!empty($email)) {
            $response = array('success' => 0);
            return json_encode($response);
        } else {
            $response = array('success' => 1);
            return json_encode($response);
        }
    }

    public function register(Request $request) {
        $messages = [
            'required' => 'The :attribute field is required.',
            // 'confirmpassword.required' => 'The Confirm password field is required.',
            // 'username.regex' => 'Use valid User name (as xyz or xyz1)',
            // 'username.required' => 'The Artist name field is required',
            // // 'phone.regex' => 'Use valid Phone No (as 111-111-1111)',
            // 'artistPassword.regex' => ' Use at least one letter, one numeral & one special character',
            // 'profile.required' => 'The profile image field is required',
            // 'username.unique' => 'User name has already been taken. Please enter another name',

            'fullname.required' => 'The Full name field is required' ,
            'email.required' => 'Email field is required' ,
            'phone.regex' => 'Use valid Phone No (as 111-111-1111)',
            'main_cat_id.required' => 'Please specify your work',
            'cat_id.required' => 'Category field is required',

        ];
        $validator = Validator::make(
                        // array(
                    // 'username' => $request->username,
                    // 'artistEmail' => $request->artistEmail,
                    // 'artistPassword' => $request->artistPassword,
                    // 'confirmpassword' => $request->confirmpassword,
                    // 'date_of_birth' => $request->date_ofbirth,
                    // 'phone' => $request->phone,
                    // 'gender' => $request->gender,
                    // 'profile' => $request->file('profile'),
                    //     ), array(
                    // 'username' => 'required|regex:/^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-0-9-, ])*$/|unique:users,user_name',
                    // 'artistEmail' => 'required|email|unique:users,email',
                    // 'artistPassword' => 'required|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/',
                    // 'confirmpassword' => 'required|same:artistPassword',
                    // 'date_of_birth' => 'required',
                    // // 'year' =>'required',
                    // // 'month' =>'required',
                    // // 'day' =>'required',
                    // 'phone' => 'required',
                    // 'gender' => 'required',
                    // 'profile' => 'image',
                    
                    array(
                    'fullname' => $request->fullname,
                    'email' => $request->email,
                    'date_of_birth' => $request->date_of_birth,
                    'phone' => $request->phone,
                    'cat_id' => $request->cat_id,
                    'main_cat_id' => $request->main_cat_id,

                    ) , array(
                    'fullname' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'phone' => 'required',
                    'cat_id' => 'required',
                    'main_cat_id' => 'required',
                    'date_of_birth'=>'required',

                        ), $messages
        );
        
        
        if ($validator->fails()) {
            return redirect('/')
                            ->withErrors($validator)
                            ->withInput();
        } else {
            // $birthday = \DateTime::createFromFormat('m-d-Y', $request->date_ofbirth);
            // $diff = $birthday->diff(new \DateTime());

            // if ($diff->y < 18) {
            //     return redirect('artist_register')->with('register_error', "Age must be 18 or above 18 years");
            // } else {
            
                $email = DB::table('profiles')->where('EmailId', $request->email)->first();
                // $name = DB::table('profiles')->where('Name', $request->username)->first();
                if (!empty($email)) {
                    return redirect('/')->with('register_error', "Email Already Exist");
                } else {
                    // $random = rand(100000 ,1000000000);
                    $twitter =strcasecmp($request->finduser , 'twitter');
                    $facebook =strcasecmp($request->finduser , 'facebook');
                    $instagram =strcasecmp($request->finduser , 'instagram');
                    $userName = strtolower(str_replace(' ', '_', $request->fullname)).'_'.time();
                    $users = new User();
                    $Profile = new Profile();
                    $is_account_active = 0;
                    $is_email_active = 0;
                    $new_join_request = 1;
                    $type = 'Artist';
                    $users->user_name = $userName;
                    $users->email = $request->email;
                    // $users->password = Hash::make($random);
                    $users->remember_token = $request->_token;
                    $users->is_account_active = $is_account_active;
                    $users->is_email_active = $is_email_active;
                    // $users->gender = $request->gender;
                    $users->type = $type;
                    // // $dob=$request->month.'/'.$request->day.'/'.$request->year;
                    // // $dob=$request->date_ofbirth;
                    // //dd($dob);
                    $users->date_of_birth = date('m-d-Y', strtotime($request->date_of_birth));
                    $users->phone_no = $request->phone;
                    $users->new_request_join = $new_join_request;

                    $Profile->Name = $request->fullname;
                    $Profile->EmailId = $request->email;
                    $Profile->Type = $type;
                    // $Profile->Gender = $request->gender;
                    // $Profile->DateOfBirth = $request->date_ofbirth;
                    $Profile->MobileNo = $request->phone;
                     $Profile->timestamp = 15;
                    // $Profile->Zip = " ";

                    if( $instagram == 0){
                        $Profile->instagram_link = $request->handle; 
                    } elseif ($facebook == 0){
                        $Profile->facebook_link = $request->handle; 
                    }elseif ($twitter == 0) {
                        $Profile->twitter_link = $request->handle; 
                    }
                    $s3 = Storage::disk('s3');
                    if ($request->profile == "") {
                        $profile_path = "default-artist.png";
                        $Profile->profile_path = $profile_path;
                    } else {
                        $file = $request->file('profile');
                        $filename = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension();
                        $profile_path = date('U') . '.'.$extension;
                        $Profile->profile_path = $profile_path;
                        $destinationPath = 'images/Artist/';
                        //$s3->put($destinationPath.$profile_path, file_get_contents($file));
                        $request->file('profile')->move($destinationPath, $profile_path);
                        multipartUpload($destinationPath.$profile_path);
                        unlink($destinationPath.$profile_path);
                    }
                    $Profile->profile_url = $userName;
                    $Profile->DateOfBirth = date('m-d-Y', strtotime($request->date_of_birth));
                    $Profile->BannerImg = "vrl_bg.jpg";
                    $Profile->header_image = "default_header.jpg";
                    $Profile->video_background = "vrl_bg.jpg";
                    $video_price = DB::table('setting')->whereName('video_price')->first();
                    $Profile->VideoPrice = $video_price->status;



                    /*
                     * Sign up new connected Account and attach the ID to the profile
                     * 
                     */


                    //\Stripe\Stripe::setApiKey('sk_test_CtVU3fFCOkPs7AbQDLLJmU1n');
                    //	\Stripe\Stripe::setApiKey('sk_live_AuLjanpEXm7L1Iq22XhDBzyR');
                    if (\Illuminate\Support\Facades\App::environment('local') || \Illuminate\Support\Facades\App::environment('testing')) {
                        // The environment is local or testing
                        \Stripe\Stripe::setApiKey(Config::get('constants.STRIPE_KEYS.test.secret_key'));
                    } else {
                        // The environment is production
                        \Stripe\Stripe::setApiKey(Config::get('constants.STRIPE_KEYS.live.secret_key'));
                    }
                    \Stripe\Stripe::setApiVersion(Config::get('constants.STRIPE_VERSION'));
                    $stripe_account = \Stripe\Account::create(
                                    array(
                                        "type" => "custom",
                                        "country" => "US",
                                        "email" => $users->email,
                                    )
                    );

                    $Profile->stripe_account_id = $stripe_account->id;
                    $Profile->save();


                    $profile_id = $Profile->ProfileId;
                    $users->profile_id = $profile_id;
                    $auth_pass = str_random(15);
                    $users->auth_pass = $auth_pass;
                    $confirmation_code['confirmation_code'] = encrypt($auth_pass);
                    $category = $request->cat_id;
                    $main_category = $request->main_cat_id;

                    foreach ($category as $category_id) {
                        DB::table('artist_category')->insert(
                            array('profile_id' =>$profile_id , 'category_id' => $category_id));
                    }
                    if (in_array($main_category, $category)) {
                        DB::table('artist_category')
                            ->where('profile_id', $profile_id)
                            ->where('category_id', $main_category)
                            ->update(['main_category' => 1]);
                    }
                    else
                    {
                        DB::table('artist_category')->insert(
                            array('profile_id' => $profile_id, 'category_id' => $main_category, 'main_category' => 1));
                    }
                    if ($users->save()) {
                        Mail::send('emails.requestjoin', $confirmation_code, function ($message) use ($request) {
                            $message->from('noreply@videorequestline.com', 'Request To Join VRL');
                            $message->to($request->email, $request->username);
                            $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                            $message->subject('Request to join');
                        });
                        return redirect('success_register');
                        //return redirect('artist_register')->with('success','Successfully Registered');
                    } else {
                        return redirect('artist_register')->with('register_error', "Oops..!Something went wrong");
                    }
                }
            // }
        }
    }

    /* ----------------------------Artist and User Login------------------------------------- */

    /**
     * @return \Illuminate\Contracts\View\Factory|Redirect|\Illuminate\View\View
     */
    public function AllLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "Artist") {
                return redirect('/');
            } elseif (Auth::user()->type == "User") {
                return redirect('/profile');
            }
        } else {
            $signup_data = DB::table('setting')->select('status')->where('name', '=', 'signup')->first();
            $data['signup_data'] = $signup_data;
            return view('frontend.login', $data);
        }
    }

    public function recaptha() {
        return view('frontend.recaptcha');
        //echo public_path();
        //echo url('/');
    }

    public function stripe_payment() {
        $id = Session::get('post_artist_id');
        if ($id == '' || $id == "removed") {
            //echo "yes";
            return redirect('/view-all-artist');
        } else {
            $user_detail = Profile::find($id);
            $detail['user_detail'] = $user_detail;

            return view('frontend.stripeForm', $detail);
        }
    }

    public function post_stripe_payment(Request $request)
    {
        $request->sender_name = !is_null(Auth::user()) ? Auth::user()->user_name : $request->sender_name;
        $request->sender_email = !is_null(Auth::user()) ? Auth::user()->email : $request->sender_email;
        // Trying To Check that user exist before or not
        $data = $request->all();
        Session::put('post_user_id', $request->user_id);
        Session::put('post_myusername', $request->user_name);
        Session::put('post_artist', $request->artist);
        Session::put('post_song_name', $request->song_name);
        Session::put('post_pronun_name', $request->pronun_name);
        Session::put('post_useremail', $request->user_email);
        Session::put('post_password', $request->password);
        Session::put('post_recei_email', $request->recei_email);
        Session::put('post_sender_name', $request->sender_name);
        Session::put('post_sender_name_pronun', $request->sender_name_pronun);
        Session::put('post_sender_email', $request->sender_email);
        Session::put('post_delivery_date', $request->delivery_date);
        Session::put('post_Occassion', $request->Occassion);
        Session::put('post_person_message', $request->person_message);
        Session::put('post_video_price', $request->artist_vidoe_price);
        Session::put('post_artist_id', $request->artist);
        Session::put('post_phone', $request->phone);
        //if ($request->user_email == $request->sender_email) {
            // $previous_url = url()->previous();
            // $findme = 'https://videorequestlive.com/RequestNewVideo/';
            // $pos = strpos($previous_url, $findme);
            // if ($pos !== false) {
            //     return redirect('RequestNewVideo/' . $request->artist)
            //         ->with('error', "");
            // } else {
               /* return redirect()->back()
                    ->with('error', \Lang::get('messages.sender_receiver_email_error'));*/
            // }
        //} else {

        $messages = [
            'user_email.required' => \Lang::get('messages.required_recipient_email'),
            'user_email.email' => \Lang::get('messages.invalid_recipient_email'),
            'person_message.required' => \Lang::get('messages.person_message'),
            'sender_email.different' => \Lang::get('messages.sender_receiver_email_error'),
            'required' => ':attribute field is required',
            'delivery_date.after' => \Lang::get('messages.invalid_delivery_date'),
            'max' => 'Maximum 50 characters allowed',
            'person_message.max' => 'Maximum 200 characters allowed',
            'song_name.max' => 'Maximum 70 characters allowed',
        ];
        $validator = Validator::make($data, array(
            'artist' => 'required',
            'user_name' => 'max:50',
            'user_email' => 'email',
            'sender_name' => 'required|max:50',
            'sender_email' => 'required|email',
            'song_name' => 'max:70',
            'person_message' => 'required|max:200',
            'delivery_date' => 'required|after:yesterday',
        ), $messages);

            if ($validator->fails()) {
                // $previous_url = url()->previous();
                // $findme = 'https://videorequestlive.com/RequestNewVideo/';
                // $pos = strpos($previous_url, $findme);
                // if ($pos !== false) {
                //     return redirect('RequestNewVideo/' . $request->artist)
                //         ->withErrors($validator)
                //         ->withInput();
                // } else {
                    return redirect()->back()->withErrors($validator)->withInput();
                // }
            }
        //}

        $is_found_user = User::whereEmail($request->sender_email)->whereType('User')->first();
        if ($is_found_user != null) {
            try {
                Log::info('user : '.$is_found_user);
                // $stripe = array(
                //     "secret_key"      => "sk_test_CtVU3fFCOkPs7AbQDLLJmU1n",
                //     "publishable_key" => "pk_test_u2EpaiGskW20KXn5Nw7MMJta"
                //     "secret_key"      => "sk_test_OG7hJumFbYWRkU0A3eIIlyvl",
                //     "publishable_key" => "pk_test_Ibg46zF9sEEMr8EpiunWAlLa"

                //         /* Live key */
                //         // "secret_key" => "sk_live_AuLjanpEXm7L1Iq22XhDBzyR",
                //         // "publishable_key" => "pk_live_ibbVEpwDbfWJAboByQ6Kvygy"
                // );
                // \Stripe\Stripe::setApiKey($stripe['secret_key']);

                if (App::environment('local') || App::environment('testing')) {
                    // The environment is local or testing
                    \Stripe\Stripe::setApiKey(Config::get('constants.STRIPE_KEYS.test.secret_key'));
                } else {
                    // The environment is production
                    \Stripe\Stripe::setApiKey(Config::get('constants.STRIPE_KEYS.live.secret_key'));
                }

                \Stripe\Stripe::setApiVersion(Config::get('constants.STRIPE_VERSION'));
                $price = $request->artist_vidoe_price;
                $tot = $price * 100;

                $customer = \Stripe\Customer::create(array(
                    'email' => $request->sender_email,
                    'source' => $request->stripeToken
                    //'source' => $stripeToken->id
                ));

                $charge = \Stripe\Charge::create(array(
                    'customer' => $customer->id,
                    'amount' => $tot,
                    'currency' => 'usd'
                ));
                Log::info('status : '.$charge['status']);
                if ($charge['status'] == "succeeded") {
                    $Status = "Pending";
                    $Requestvideo = new Requestvideo();

                    // This is the Artist Profile ID
                    $Requestvideo->requestToProfileId = Session::get('post_artist_id');
                    $Requestvideo->song_name = $request->song_name;

                    // Who will Recieve the video  -- momkney
                    $Requestvideo->Name = !empty($request->user_name) ? $request->user_name : $request->sender_name;
                    $Requestvideo->receipient_pronunciation = $request->pronun_name;
                    $Requestvideo->requestor_email = !empty($request->user_email) ? $request->user_email : $request->sender_email;

                    // Who make the request for his friend -- momkney
                    $Requestvideo->sender_name = $request->sender_name;
                    $Requestvideo->sender_name_pronunciation = $request->sender_name_pronun;
                    $Requestvideo->sender_email = $request->sender_email;

                    /* Some Specifications about the request 
                     * Definitions 
                     *       [ Completition Date => delivery date ]  
                     *       [request_date => request made date ]                               
                     */

                    $Requestvideo->request_date = Carbon::now()->format('m/d/Y');
                    $Requestvideo->complitionDate = date('m/d/Y', strtotime($request->delivery_date));

                    $Requestvideo->is_active = 1;
                    $Requestvideo->RequestStatus = $Status;

                    $Requestvideo->Title = $request->Occassion;
                    $Requestvideo->requestByProfileId = $is_found_user->profile_id;
                    $Requestvideo->paid = 'Paid';
                    $Requestvideo->Description = $request->person_message;
                    $Requestvideo->ReqVideoPrice = $request->artist_vidoe_price;
                    $Requestvideo->is_hidden = $request->is_hidden;

                    /*
                     * Records for sender and recipient
                     */

                    $Requestvideo->recipient_record = $request->get('recipient-record');
                    $Requestvideo->sender_record = $request->get('sender-record');
                    $Requestvideo->is_delete = 'false';
                    Log::info('request : '.$Requestvideo);
                    if ($Requestvideo->save()) {
                        Log::info('save : ');
                        $payment = new Payment();
                        $status = 'succeeded';
                        $stripeTokenType = 'card';
                        $payment->profile_id = $is_found_user->profile_id;
                        $payment->video_status = 'Pending';
                        $payment->video_request_id = $Requestvideo->VideoReqId;
                        $payment->stripeTokenType = 'card';
                        //$payment->stripeToken = $stripeToken;
                        $payment->stripeToken = $request->stripeToken;
                        $payment->customerEmail = $request->sender_email;
                        $payment->status = $status;
                        $payment->videoPrice = $request->artist_vidoe_price;
                        $payment->payer_id = Session::get('post_artist_id');
                        $payment->payment_date = Carbon::now()->format('m-d-Y');
                        $payment->token = $request->_token;
                        $payment->charge_id = $charge->id;
                        $payment->payment_type = 'Purchase';
                        $payment->save();
                        Log::info('payment : '.$payment);
                        $confirmation_code['user_email'] = $request->sender_email;
                        $confirmation_code['username'] = $request->sender_name;

                        $confirmation_code['recipient_name'] = !empty($request->user_name) ? $request->user_name : $request->sender_name;
                        $confirmation_code['recipient_email'] = !empty($request->user_email) ? $request->user_email : $request->sender_email;
                        $confirmation_code['delivary_date'] = $request->delivery_date;
                        $confirmation_code['video_title'] = $request->Occassion;
                        $confirmation_code['video_description'] = $request->person_message;
                        $confirmation_code['current_status'] = "Pending";
                        $artist = Profile::where('ProfileId', Session::get('post_artist_id'))->first();
                        $confirmation_code['video_delivery_time'] = $artist->timestamp;
                        $confirmation_code['artist_name'] = $artist->Name;
                        $confirmation_code['artist_email'] = $artist->EmailId;
                        $confirmation_code['artist_id'] = $artist->ProfileId;
                        $confirmation_code['video_request_id'] = $Requestvideo->VideoReqId;
                        $confirmation_code['identifier'] = $artist->ProfileId.'-'.$Requestvideo->VideoReqId;
                        $confirmation_code['songName'] = $request->song_name;

                        $request_video_email['recipient_name'] = !empty($request->user_name) ? $request->user_name : $request->sender_name;
                        $request_video_email['artistName'] = $artist->Name;
                        $request_video_email['video_title'] = $request->Occassion;
                        $request_video_email['requester_name'] = $request->sender_name;
                        $request_video_email['requester_email'] = $request->sender_email;
                        $request_video_email['video_description'] = $request->person_message;
                        $request_video_email['current_status'] = "Pending";
                        $request_video_email['delivery_date'] = $request->delivery_date;
                        $request_video_email['songName'] = $request->song_name;
                        $request_video_email['price'] = $request->artist_vidoe_price;
                        $request_video_email['identifier'] = $artist->ProfileId.'-'.$Requestvideo->VideoReqId;
                        $request_video_email['artist_id'] = $artist->ProfileId;
                        $request_video_email['video_request_id'] = $Requestvideo->VideoReqId;
                        Mail::send('emails.video-request', $request_video_email, function ($message) use ($artist,$request_video_email) {
                            $message->from('noreply@videorequestline.com', 'New Video Request');
                            $message->to($artist->EmailId);
                            $message->subject('VRL Video Request Received ('.$request_video_email['identifier'].')');
                            $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                        });

                        Mail::send('emails.exist_User_RequestNewVideo', $confirmation_code, function ($message) use ($request,$confirmation_code) {
                            $message->from('noreply@videorequestline.com', 'Confirmation for Video Request');
                            $message->to($request->sender_email);
                            $message->subject('VRL Video Request Confirmation ('.$confirmation_code['identifier'].')');
                            $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                        });
                        /*Mail::send('emails.admin_RequestNewVideo', $confirmation_code, function ($message) use ($request) {
                            $artist = Profile::where('ProfileId', $request->artist)->first();
                            $message->from('noreply@videorequestline.com', 'Confirmation for Video Request');
                            $message->to($artist->EmailId, 'Artist');
                            $message->subject('Requested New video');
                        });*/

                        $deviceToken = $artist->device_token;
                        if (is_null($artist->push_notification) || $artist->push_notification == 1) {
                            if ($deviceToken != null) {
                                $message = 'You have received a new video request';
                                push_notification($deviceToken, $message, 4);
                            }
                        }
                        Session::put('post_artist_id', 'removed');

                        if (Auth::check()) {
                            if (Auth::user()->type == "User") {
                                return redirect('/payment_success')->with('msg', "Your payment has been successfully done. Now you can track your video status from dashboard, rest of time you can also request another video.");
                            }
                        } else {
                            return redirect('/payment_success_register');
                        }
                    }
                }
            } catch (\Stripe\Error\Card $e) {
                // Since it's a decline, \Stripe\Error\Card will be caught
                $err_msg = $e->getMessage();
                return redirect('/booking_checkout')->with('error', $err_msg)->withInput();
            } catch (\Stripe\Error\InvalidRequest $e) {
                $err_msg = $e->getMessage();
                return redirect('/booking_checkout')->with('error', $err_msg)->withInput();
                // Invalid parameters were supplied to Stripe's API
            } catch (\Stripe\Error\Authentication $e) {
                $err_msg = $e->getMessage();
                return redirect('/booking_checkout')->with('error', $err_msg)->withInput();
                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
            } catch (\Stripe\Error\ApiConnection $e) {
                $err_msg = $e->getMessage();
                return redirect('/booking_checkout')->with('error', $err_msg)->withInput();
                // Network communication with Stripe failed
            } catch (\Stripe\Error\Base $e) {
                $err_msg = $e->getMessage();
                return redirect('/booking_checkout')->with('error', $err_msg)->withInput();
                // Display a very generic error to the user, and maybe send
                // yourself an email
            } catch (Exception $e) {
                $err_msg = $e->getMessage();
                return redirect('/booking_checkout')->with('error', $err_msg)->withInput();
                // Something else happened, completely unrelated to Stripe
            }
        } else {
            // not register only request send and payment
            $type = "User";
            $password = str_random(8);
            $Profile = new Profile();
            $Profile->EmailId = $request->sender_email;
            $Profile->Type = $type;
            $Profile->Name = $request->sender_name;
            $Profile->save();

            $users = new User();
            $users->user_name = $request->sender_name;
            $users->email = $request->sender_email;
            $users->password = Hash::make($password);
            $users->remember_token = $request->_token;
            $users->type = 'User';
            $users->profile_id = $Profile->ProfileId;
            $users->is_account_active = '1';
            $users->is_email_active = '1';
            $users->save();
            Log::info('newuser : '.$users);
            try {
                $stripe = array(
                    "secret_key" => "sk_test_CtVU3fFCOkPs7AbQDLLJmU1n",
                    "publishable_key" => "pk_test_u2EpaiGskW20KXn5Nw7MMJta"

                    /* Live key */
                    //"secret_key" => "sk_live_AuLjanpEXm7L1Iq22XhDBzyR",
                    //"publishable_key" => " pk_live_ibbVEpwDbfWJAboByQ6Kvygy"
                );
                //\Stripe\Stripe::setApiKey($stripe['secret_key']);
                if (App::environment('local') || App::environment('testing')) {
                    // The environment is local or testing
                    \Stripe\Stripe::setApiKey(Config::get('constants.STRIPE_KEYS.test.secret_key'));
                } else {
                    // The environment is production
                    \Stripe\Stripe::setApiKey(Config::get('constants.STRIPE_KEYS.live.secret_key'));
                }
                \Stripe\Stripe::setApiVersion(Config::get('constants.STRIPE_VERSION'));

                $token = $request->stripeToken;
                $price = $request->artist_vidoe_price;
                $tot = $price * 100;
                $customer = \Stripe\Customer::create(array(
                    'email' => $request->sender_email,
                    'source' => $request->stripeToken
                ));


                $charge = \Stripe\Charge::create(array(
                    'customer' => $customer->id,
                    'amount' => $tot,
                    'currency' => 'usd'
                ));

                if ($charge['status'] == "succeeded") {
                    $Status = "Pending";
                    $Requestvideo = new Requestvideo();
                    $Requestvideo->requestToProfileId = Session::get('post_artist_id');
                    $Requestvideo->song_name = $request->song_name;
                    $Requestvideo->Name = !empty($request->user_name) ? $request->user_name : $request->sender_name;
                    $Requestvideo->receipient_pronunciation = $request->pronun_name;
                    $Requestvideo->requestor_email = !empty($request->user_email) ? $request->user_email : $request->sender_email;
                    $Requestvideo->sender_name = $request->sender_name;
                    $Requestvideo->sender_name_pronunciation = $request->sender_name_pronun;
                    $Requestvideo->sender_email = $request->sender_email;
                    $Requestvideo->ReqVideoPrice = $request->artist_vidoe_price;
                    $Requestvideo->complitionDate = date('m/d/Y', strtotime($request->delivery_date));
                    $Requestvideo->Title = $request->Occassion;
                    $Requestvideo->requestByProfileId = $Profile->ProfileId;
                    $Requestvideo->paid = 'Paid';
                    $Requestvideo->Description = $request->person_message;
                    $Requestvideo->RequestStatus = $Status;
                    $Requestvideo->is_active = 1;
                    $Requestvideo->request_date = Carbon::now()->format('m/d/Y');
                    $Requestvideo->recipient_record = $request->get('recipient-record');
                    $Requestvideo->sender_record = $request->get('sender-record');
                    $Requestvideo->is_delete = 'false';
                    Log::info('requestOne : '.$Requestvideo);
                    if ($Requestvideo->save()) {
                        Log::info('save1 : ');
                        $payment = new Payment();
                        $status = 'succeeded';
                        $stripeTokenType = 'card';
                        $payment->profile_id = $Profile->ProfileId;;
                        $payment->video_status = 'Pending';
                        $payment->video_request_id = $Requestvideo->VideoReqId;
                        $payment->stripeTokenType = 'card';
                        $payment->stripeToken = $request->stripeToken;
                        $payment->customerEmail = $request->sender_email;
                        $payment->status = $status;
                        $payment->videoPrice = $request->artist_vidoe_price;
                        $payment->payer_id = Session::get('post_artist_id');
                        $payment->payment_date = Carbon::now()->format('m-d-Y');
                        $payment->token = $request->_token;
                        $payment->charge_id = $charge->id;
                        $payment->payment_type = 'Purchase';
                        $payment->save();


                        $confirmation_code['user_email'] = $request->sender_email;
                        $confirmation_code['video_title'] = $request->Occassion;
                        $confirmation_code['video_description'] = $request->person_message;
                        $confirmation_code['current_status'] = "Pending";
                        $artist = Profile::where('ProfileId', Session::get('post_artist_id'))->first();
                        $confirmation_code['video_delivery_time'] = $artist->timestamp;
                        $confirmation_code['artist_name'] = $artist->Name;
                        $confirmation_code['artist_email'] = $artist->EmailId;
                        $confirmation_code['username'] = $request->sender_name;
                        $confirmation_code['password'] = $password;
                        $confirmation_code['identifier'] = $artist->ProfileId.'-'.$Requestvideo->VideoReqId;
                        $confirmation_code['recipient_name'] = $request->user_name;
                        $confirmation_code['recipient_email'] = $request->user_email;
                        $confirmation_code['songName'] = $request->song_name;
                        $confirmation_code['request_date']= $request->request_date;


                        // Stop Sending Email to Reciever
//                                        Mail::send('emails.exist_User_RequestNewVideo', $confirmation_code, function ($message) use ($request) {
//						$message->from('noreply@videorequestline.com', 'Confirmation for Video Request');
//						$message->to($request->user_email, $request->user_email);
//						$message->subject('Successfully requested video');
//					});


                        Mail::send('emails.User_RequestNewVideo', $confirmation_code, function ($message) use ($request,$confirmation_code) {
                            $message->from('noreply@videorequestline.com', 'Confirmation for Video Request');
                            $message->to($request->sender_email, $request->sender_email);
                            $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                            $message->subject('VRL Video Request Confirmation ('.$confirmation_code['identifier'].')');
                        });


//                        Mail::send('emails.admin_RequestNewVideo', $confirmation_code, function ($message) use ($request) {
//                            $artist = Profile::where('ProfileId', Session::get('post_artist_id'))->first();
//                            $message->from('noreply@videorequestline.com', 'Confirmation for Video Request');
//                            $message->to($artist->EmailId, 'Artist');
//                            $message->subject('Requested New video');
//                        });


                        Session::forget('post_artist_id');

                        //return redirect('/payment_success');


                        if (Auth::check()) {
                            if (Auth::user()->type == "User") {
                                return redirect('/payment_success')->with('msg', "Your payment has been successfully done. Now you can track your video status from dashboard, rest of time you can also request another video.");
                            }
                        } else {
                            return redirect('/payment_success_register');
                        }
                    }
                }
            } catch (\Stripe\Error\Card $e) {
                // Since it's a decline, \Stripe\Error\Card will be caught
                $err_msg = $e->getMessage();
                return redirect('/booking_checkout')->with('error', $err_msg)->withInput();
            } catch (\Stripe\Error\InvalidRequest $e) {
                $err_msg = $e->getMessage();
                return redirect('/booking_checkout')->with('error', $err_msg)->withInput();
                // Invalid parameters were supplied to Stripe's API
            } catch (\Stripe\Error\Authentication $e) {
                $err_msg = $e->getMessage();
                return redirect('/booking_checkout')->with('error', $err_msg)->withInput();
                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
            } catch (\Stripe\Error\ApiConnection $e) {
                $err_msg = $e->getMessage();
                return redirect('/booking_checkout')->with('error', $err_msg)->withInput();
                // Network communication with Stripe failed
            } catch (\Stripe\Error\Base $e) {
                $err_msg = $e->getMessage();
                return redirect('/booking_checkout')->with('error', $err_msg)->withInput();
                // Display a very generic error to the user, and maybe send
                // yourself an email
            } catch (Exception $e) {
                $err_msg = $e->getMessage();
                return redirect('/booking_checkout')->with('error', $err_msg)->withInput();
                // Something else happened, completely unrelated to Stripe
            }
        }
    }

    public function post_allLogin(Request $request)
    {
        //dd($_POST['g-recaptcha-response']);
        /* require(app_path().'/recaptcha/src/autoload.php'view_video);
          $secret = '6LdERAwUAAAAAHE5dEGFwUVpZOFq8kKTEgjE2EPo';
          $recaptcha = new \ReCaptcha\ReCaptcha($secret);
          $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']); */

        //if (!$resp->isSuccess()){


        $messages = [
            'required' => 'The :attribute field is required.',
        ];


        $validator = Validator::make(
            array(
                'email' => $request->email,
                'password' => $request->password
            ), array(
            'email' => 'required|email',
        ), $messages
        );

        if ($validator->fails()) {
            return redirect('/')
                ->withErrors($validator)
                ->withInput();
        } else {
            $email = $request->email;
            $password = $request->password;
            $is_email_active = User::is_email_active($email);
            $is_account_active = User::is_account_active($email);
            if ($is_email_active == "0") {
                return redirect('/')->with('login_error', "'You need to confirm your account. We have sent you an activation code, please check your email.'");
            } elseif ($is_account_active == "0") {
                return redirect('/')->with('login_error', "Your Account is deactivated.");
            } else {
                $login_result = User::where('email', $email)->first();
                if (count($login_result) > 0) {
                    $user_type = $login_result->type;
                    if ($user_type == 'Artist') {
                        $user = array('email' => $email, 'password' => $password);
                        if (Auth::attempt($user)) {
                            Auth::attempt($user);
                            Session::put('email', $email);
                            Session::put('password', $password);
                            if (!empty($request->timezone)) {
                                Session::put('timezone', $request->timezone);
                            } else {
                                Session::put('timezone', 'UTC');
                                Log::info('Artist email : '.$email.' Timezone : UTC');
                            }
                            return redirect('/Dashboard');
                        } else {
                            return redirect('/')->with('login_error', "Invalid email or password");
                        }
                    } else if ($user_type == 'User') {

                        $user = array('email' => $email, 'password' => $password);

                        if (Auth::attempt($user)) {
                            Auth::attempt($user);
                            Session::put('email', $email);
                            Session::put('password', $password);
                            if (!empty($request->timezone)) {
                                Session::put('timezone', $request->timezone);
                            } else {
                                Session::put('timezone', 'UTC');
                                Log::info('User email : '.$email.' Timezone : UTC');
                            }
                            return redirect('/user_video');
                        } else {
                            return redirect('/')->with('login_error', "Invalid email or password");
                        }
                    } else {

                        return redirect('/')->with('login_error', "Invalid email or password");
                    }
                } else {
                    return redirect('/')->with('login_error', "Invalid email or password");
                }
            }
        }
    }

    /* -----------------------------------Artist Login------------------------------------- */

    public function login() {
        if (Auth::check()) {
            if (Auth::user()->type == "Artist") {
                return redirect('/');
            } elseif (Auth::user()->type == "User") {
                return redirect('profile');
            } else {
                return redirect('admin_dashboard');
            }
        } else {
            return view('frontend.login');
        }
    }

    public function artist_login(Request $request) {
        $validator = Validator::make(
                        array(
                    'email' => $request->email,
                    'password' => $request->password
                        ), array(
                    'email' => 'required|email',
                    'password' => 'required|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/'
                        )
        );
        if ($validator->fails()) {
            return redirect('artist_login')
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $email = $request->email;
            $password = $request->password;
            $is_email_active = User::is_email_active($email);
            $is_account_active = User::is_account_active($email);
            if ($is_email_active == "0") {
                return redirect('artist_login')->with('login_error', "'You need to confirm your account. We have sent you an activation code, please check your email.'");
            } elseif ($is_account_active == "0") {
                return redirect('artist_login')->with('login_error', "Your Account is deactivated.");
            } else {
                $login_result = User::where('email', $email)->first();
                if (count($login_result) > 0) {
                    $user_type = $login_result->type;
                    if ($user_type == 'Artist') {
                        $user = array('email' => $email, 'password' => $password);
                        if (Auth::attempt($user)) {
                            Auth::attempt($user);
                            Session::put('name', Auth::user()->user_name);
                            Session::put('email', Auth::user()->email);
                            return redirect('/');
                        } else {
                            return redirect('artist_login')->with('login_error', "Invalid email or password");
                        }
                    } else {
                        return redirect('artist_login')->with('login_error', "Invalid email or password");
                    }
                } else {
                    return redirect('artist_login')->with('login_error', "Invalid email or password");
                }
            }
        }
    }

    /* -----------------------------------Email Verification----------------------------------- */

    public function verify_email($auth_pass) {
        if (Auth::check()) {
            if (Auth::user()->type == "Artist") {
                return redirect('/');
            } elseif (Auth::user()->type == "User") {
                return redirect('profile');
            } else {
                return redirect('admin_dashboard');
            }
        } else {
            $auth_pass = decrypt($auth_pass);
            $result = DB::table('users')->where('auth_pass', '=', $auth_pass);
            if (count($result) > 0) {
                if (User::where('auth_pass', '=', $auth_pass)->update(array('is_email_active' => 1, 'is_account_active' => 1, 'auth_pass' => ''))) {
                    Session::put('success', "Email Verified ! Now Login");
                    return redirect('login');
                } else {
                    Session::put('login_error', "Oops..! Something Went wrong");
                    return redirect('login');
                }
            }
        }
    }

    public function verify_user_email($auth_pass) {
        if (Auth::check()) {
            if (Auth::user()->type == "Artist") {
                return redirect('/');
            } elseif (Auth::user()->type == "User") {
                return redirect('profile');
            } else {
                return redirect('admin_dashboard');
            }
        } else {
            $auth_pass = decrypt($auth_pass);
            $result = DB::table('users')->where('auth_pass', '=', $auth_pass);
            if (count($result) > 0) {
                if (User::where('auth_pass', '=', $auth_pass)->update(array('is_email_active' => 1, 'is_account_active' => 1, 'auth_pass' => ''))) {
                    return redirect('UserLogin')->with('verify_email', 'verifying email success');
                } else {
                    echo "Oops..! Something Went wrong";
                }
            }
        }
    }

    /* -----------------------------------Artist Dashboard----------------------------------- */

    public function video_requests() {

        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } elseif (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $user = User::where('email', Auth::user()->email)->first();


                $video_request = DB::table('requestvideos')->where('requestToProfileId', $user->profile_id)
                        /*->where('RequestStatus', '<>', 'Reject')
                        ->where('RequestStatus', '<>', 'Completed')
                        ->where('RequestStatus', '<>', 'Pending')*/
                        //->where('is_active','=','1')
                        ->orderBy('VideoReqId', "desc")
                        //->paginate(5);
                        ->get();
                // dd($video_request);
                       // print_r($video_request);
                $image_path = DB::table('profiles')->where('EmailId', $user->email)->first();
                $artist_data = array();
                $artist_data['users'] = $user;
                $artist_data['video_requests'] = $video_request;
                $artist_data['image_paths'] = $image_path;
                $artist_data['current_date'] = $date = date('d-m-Y');

                return view('frontend.artistDashboard.video_requests', $artist_data);
            }
        } else {
            return redirect('/');
        }
    }

    /* -----------------------------------Artist Pending Request----------------------------------- */

    public function pending_requests() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } elseif (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $user = User::where('email', Auth::user()->email)->first();
                $panding_video_request = DB::table('requestvideos')->where('requestToProfileId', $user->profile_id)
                        ->where('RequestStatus', '=', 'Pending')
                        //->where('is_active','=','1')
                        ->orderBy('VideoReqId', "desc")
                        ->get();
                //dd($video_request);
                $image_path = DB::table('profiles')->where('EmailId', $user->email)->first();
                $artist_data = array();
                $artist_data['users'] = $user;
                $artist_data['video_requests'] = $panding_video_request;
                $artist_data['image_paths'] = $image_path;
                $artist_data['current_date'] = $date = date('d-m-Y');
                return view('frontend.artistDashboard.panding_video_requests', $artist_data);
            }
        } else {
            return redirect('/');
        }
    }

    /* -----------------------------------Dashboard------------------------------------- */

    public function get_dashboard() {

        if (Auth::check()) {

            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } else if (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {

                $user = User::where('email', Auth::user()->email)->first();

                $my_video = Video::where('ProfileId', Auth::user()->profile_id)->get();
                $my_delivered_video = DB::table('requested_videos')->where('uploadedby', Auth::user()->profile_id)->get();

                $deliver_videos = DB::table('requested_videos')->select('*')
                        ->join('profiles', 'profiles.ProfileId', '=', 'requested_videos.uploadedby')
                        ->where('requested_videos.uploadedby', '=', Auth::user()->profile_id)->where('deleted_from_artist' , '=', '0')
                        ->get();

                $video_request = DB::table('requestvideos')->where('requestToProfileId', Auth::user()->profile_id)
                                ->where('RequestStatus', '<>', 'Reject')->orderBy('created_at')->get();

                $actual_delivered_videos = DB::table('requested_videos')
                        ->join('profiles', 'profiles.ProfileId', '=', 'requested_videos.uploadedby')
                        ->where('requested_videos.uploadedby', '=', Auth::user()->profile_id)->get();
                $video_request = count($video_request) - count($actual_delivered_videos);

                $panding_video_requests = DB::table('requestvideos')->where('requestToProfileId', $user->profile_id)
                                ->where('RequestStatus', '=', 'Pending')->orderBy('created_at')->get();

                $image_path = DB::table('profiles')->where('EmailId', $user->email)->first();
                $artist_data = array();
                $artist_data['users'] = $user;
                $artist_data['my_videos'] = $my_video;
                $artist_data['my_delivered_videos'] = $my_delivered_video;
                $artist_data['video_requests'] = $video_request;
                $artist_data['panding_video_requests'] = $panding_video_requests;
                $artist_data['image_paths'] = $image_path;
                $artist_data['deliver_videos'] = $deliver_videos;
                //$artistData['profileData'] = $profileData;
                // dd($artist_data);
                return view('frontend.artistDashboard.dashboard', $artist_data);
            }
        } else {
            return redirect('/');
        }
    }

    /* -----------------------Artist Profile------------------------------ */

    public function ArtistProfile() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User") {
                return redirect('profile');
            } else {
                return view('frontend.ArtistProfile');
            }
        } else {
            return redirect('/');
        }
    }

    /* -----------------------Artist uploads Video------------------------------ */

    public function upload_video($id) {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User") {
                return redirect('profile');
            } else {
                $artist = Profile::where('EmailId', Auth::user()->email)->first();
                $Requestor = Profile::where('ProfileId', $id)->first();
                $artist_data['artist'] = $artist;
                $artist_data['requestors'] = $Requestor;
                $artist_data['baseurl'] = "https://videorequestlive.com/";
                return view('frontend.artistDashboard.upload_video', $artist_data);
            }
        } else {
            return redirect('/');
        }
    }

    /* -----------------------Artist Background upload------------------------------ */

    public function upload_background() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $user = User::where('email', Auth::user()->email)->first();

                $artist_data = Profile::where('EmailId', Auth::user()->email)->first();
                return view('frontend.artistDashboard.upload_background', ['artist_data' => $artist_data, 'user' => $user]);
            }
        } else {
            return redirect('/');
        }
    }

    public function upload_background_image(Request $request) {
        if (Auth::check()) {
            $artist_id = User::where('profile_id', $request->profile_id)->first();
            //dd($artist_id);
            if ($artist_id->is_account_active == '1') {
                $validator = Validator::make($request->all(), array(
                            'background' => 'required|mimes:jpeg,png',
                                )
                );
                if ($validator->fails()) {
                    return redirect('/background_img')
                                    ->withErrors($validator)
                                    ->withInput();
                } else {
                    $file = $_FILES["background"]['tmp_name'];
                    list($width, $height) = getimagesize($file);
                    if ($width < "800" || $height < "424") {
                        return redirect('/background_img')->with('error', 'image size must be above or equal to 800 x 424 pixels.');
                    } else {
                        $artist = Profile::find(Auth::user()->profile_id);
                        if ($request->file('background') != "") {
                            $s3 = Storage::disk('s3');
                            if ($artist->BannerImg) {
                                if($s3->exists("images/Artist/".$artist->BannerImg)) {
                                    $s3->delete("images/Artist/".$artist->BannerImg);
                                }
                            }
                            $file = $request->file('background');
                            $filename = $file->getClientOriginalName();
                            $fileName = $request->username . date('U') . $filename;
                            $profile_path = "images/Artist/" . $request->username . date('U') . $filename;
                            //$destinationPath = base_path() . '/public/images/Artist/';
                            //$profile_path = "images/Artist/" . $request->username . date('U') . $filename;
                            //$s3->put($profile_path, file_get_contents($file));
                            $request->file('background')->move("images/Artist/", $fileName);
                            multipartUpload($profile_path);
                            unlink($profile_path);
                            $artist->BannerImg = $fileName;
                        }
                        if ($artist->save()) {
                            return redirect('/background_img')->with('message', 'Successfully Updated!');
                        }
                    }
                }
            } else {
                Redirect::back()->with('new_token', csrf_token());
                echo "not active";
            }
        } else {
            Auth::logout();
            return redirect('login');
        }
    }

    /* -----------------------Artist  video Background Img upload----------------- */

    public function upload_video_background() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $user = User::where('email', Auth::user()->email)->first();

                $artist = Profile::where('EmailId', Auth::user()->email)->first();
                return view('frontend.artistDashboard.upload_video_background', ['artist' => $artist, 'user' => $user]);
            }
        } else {
            return redirect('/');
        }
    }

    public function uploadVideoBackground(Request $request) {

        $validator = Validator::make($request->all(), array(
                    'video_background' => 'required|mimes:jpeg,png',
                        )
        );
        if ($validator->fails()) {
            return redirect('/upload_video_background')
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $file = $_FILES["video_background"]['tmp_name'];
            list($width, $height) = getimagesize($file);
            if ($width < "400" || $height < "400") {
                //echo "Error : image size must be 180 x 70 pixels.";
                return redirect('/upload_video_background')->with('error', 'image size must be 400 x 400 pixels.');
                //return redirect('my_slider')->withErrors('img_error','image size must be 180 x 70 pixels')->withInput();
            } else {
                $artist = Profile::find(Auth::user()->profile_id);
                if ($request->file('video_background') != "") {
                    $file = $request->file('video_background');
                    $filename = $file->getClientOriginalName();
                    $path = $request->username . date('U') . $filename;
                    $destinationPath = 'images/Artist/';
                    $filePath = $destinationPath.$path;
                    $s3 = Storage::disk('s3');
                    if ($artist->video_background) {
                        if($s3->exists("images/Artist/".$artist->video_background)) {
                            $s3->delete("images/Artist/".$artist->video_background);
                        }
                    }
                    //$s3->put($filePath, file_get_contents($file));
                    $request->file('video_background')->move($destinationPath, $path);
                    multipartUpload($filePath);
                    unlink($filePath);
                    //$profile_path = "images/Artist/" . $request->username . date('U') . $filename;
                    $artist->video_background = $path;
                }
                if ($artist->save()) {
                    return redirect('/upload_video_background')->with('message', 'Successfully Updated!');
                }
            }
        }
    }

    /* -----------------------Artist  Header Img upload----------------- */

    public function artist_header_img() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $user = User::where('email', Auth::user()->email)->first();

                $artist_data = Profile::where('EmailId', Auth::user()->email)->first();
                return view('frontend.artistDashboard.artist_header_img', ['artist_data' => $artist_data, 'user' => $user]);
            }
        } else {
            return redirect('/');
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update_artist_header_img(Request $request) {

        //dd("test");


        // $file = $_FILES["header_img"]['tmp_name'];
        // list($width, $height) = getimagesize($file);
        // /* echo $width;
        //   echo "<br>";
        //   echo $height; */
        // if ($width < "1280" || $height < "375") {
        //     //return view('frontend.artistDashboard.artist_header_img')->with('error','image size must be 1280 x 375 pixels.');
        //     return redirect('/artist_header_img')->with('error', 'image size must be 1280 x 375 pixels.');
        // } else {
        //     $validator = Validator::make($request->all(), array(
        //                 'header_img' => 'required|mimes:jpeg,png',
        //                     )
        //     );
        //     if ($validator->fails()) {
        //         return redirect('/artist_header_img')
        //                         ->withErrors($validator)
        //                         ->withInput();
        //     } else {
        //         $artist = Profile::find(Auth::user()->profile_id);
        //         if ($request->file('header_img') != "") {
        //             $file = $request->file('header_img');
        //             $filename = $file->getClientOriginalName();
        //             $path = "/images/Artist/" . $request->username . date('U') . $filename;
        //             $destinationPath = base_path() . '/public/images/Artist/';
        //             $request->file('header_img')->move($destinationPath, $path);
        //             $profile_path = "images/Artist/" . $request->username . date('U') . $filename;
        //             $artist->header_image = $path;
        //         }

        //         if ($artist->save()) {
        //             return redirect('/artist_header_img')->with('message', 'Successfully Updated!');
        //         }
        //     }
        // }

        $messages = [
            'required' => 'The :attribute field is required.',
            'header_img.dimensions' => 'Image dimensions should be 1316 x 250 px',
        ];
        $data = $request->all();
        $validator = Validator::make($data, [
            'header_img' => 'required|dimensions:min_width=1316,min_height=250|mimes:jpeg,png',
        ], $messages
        );
          if($validator->fails())
          {
          return redirect('/artist_header_img')->withErrors($validator)->withInput();
          }
          else
          {
          //$file = $_FILES["header_img"]['tmp_name'];
          /*list($width, $height) = getimagesize($file);
          if($width < "400" || $height < "400") {*/
          //echo "Error : image size must be 180 x 70 pixels.";
          //return redirect('/artist_header_img')->with('error','image size must be 400 x 400 pixels.');

          //}else{
          $artist = Profile::find(Auth::user()->profile_id);
          if ($request->hasFile('header_img')) {
              $rand = rand(11111, 99999) . date('U');
              $destination = 'images/Artist/';
              $file = $request->file('header_img');
              $extension = $file->getClientOriginalExtension();
              $fileName = $rand. '.'.$extension;
              $request->file('header_img')->move($destination, $fileName);
              $filePath = $destination . $fileName;
              $s3 = Storage::disk('s3');
              if ($artist->header_image) {
                  if($s3->exists("images/Artist/".$artist->header_image)) {
                      $s3->delete("images/Artist/".$artist->header_image);
                  }
              }
              //$s3->put($filePath, file_get_contents($file));
              multipartUpload($filePath);
              unlink($filePath);
              $artist->header_image = $fileName;
          }
          //}
          if($artist->save()){
            return redirect('/artist_header_img')->with('message','Successfully Updated!');
          }
          } 
    }

    /* -----------------------Artist Slider----------------------------- */

    public function my_slider() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $user = User::where('email', Auth::user()->email)->first();

                $artist = Profile::where('EmailId', Auth::user()->email)->first();
                $slider = Slider::where('artist_id', Auth::user()->profile_id)->first();
                $artist_data['user'] = $user;
                $artist_data['artist'] = $artist;
                $artist_data['slider'] = $slider;
                if (count($slider) > 0) {
                    return view('frontend.artistDashboard.edit_slider', $artist_data);
                } else {
                    return view('frontend.artistDashboard.slider', $artist_data);
                }
            }
        } else {
            return redirect('/');
        }
    }

    /* ----------------------------------------create Slider------------------------------------ */

    public function create_slider(Request $request) {
        $data = $request->all();
        //dd($data);
        $validator = Validator::make($data, array(
                    'slider_title' => 'required|max:100',
                    //'slider_description' =>'required',
                    'slider_img' => 'required | mimes:jpeg,png'
                        )
        );
        if ($validator->fails()) {
            return redirect('my_slider')
                            ->withErrors($validator)
                            ->withInput();
        } else {
            //
            $file = $_FILES["slider_img"]['tmp_name'];
            list($width, $height) = getimagesize($file);
            if ($width < "400" || $height < "400") {
                //echo "Error : image size must be 180 x 70 pixels.";
                return redirect('my_slider')->with('success', 'image size must be 400 x 400 pixels.');
                //return redirect('my_slider')->withErrors('img_error','image size must be 180 x 70 pixels')->withInput();
            } else {
                $slider = new Slider();
                $slider->slider_visibility = 0;
                $slider->slider_title = $request->slider_title;
                $slider->slider_description = $request->slider_description;
                $slider->artist_id = Auth::user()->profile_id;
                if ($request->file('slider_img') != "") {
                    $s3 = Storage::disk('s3');
                    $file = $request->file('slider_img');
                    $filename = $file->getClientOriginalName();
                    $slider_path = date('U') . '.jpeg';
                    $destinationPath = 'images/Sliders/';
                    $request->file('slider_img')->move($destinationPath, $slider_path);
                    //$s3->put($destinationPath.$slider_path, file_get_contents($file), 'public');
                    multipartUpload('images/Sliders/'.$slider_path);
                    $slider->slider_path = $slider_path;
                    $rand = rand(11111, 99999) . date('U');
                    $width = 460;
                    $height = 320;
                    list($w, $h) = getimagesize('images/Sliders/'.$slider_path);
                    /* calculate new image size with ratio */
                    $ratio = max($width / $w, $height / $h);
                    $h = ceil($height / $ratio);
                    $x = ($w - $width / $ratio) / 2;
                    $w = ceil($width / $ratio);
                    /* new file name */
                    $path = 'images/Sliders/mob/' . $width . 'x' . $height . '_' . $rand . '.jpeg';
                    $pathof = substr($path, 29);
                    $slider->mob_slider_path = $pathof;
                    /* read binary data from image file */
                    $imgString = file_get_contents('images/Sliders/'.$slider_path);
                    /* create image from string */
                    $image = imagecreatefromstring($imgString);
                    $tmp = imagecreatetruecolor($width, $height);
                    imagecopyresampled($tmp, $image, 0, 0, $x, 0, $width, $height, $w, $h);
                    /* Save image */
                    switch ($_FILES['slider_img']['type']) {
                        case 'image/jpeg':
                            imagejpeg($tmp, $path, 100);
                            break;
                        case 'image/png':
                            imagepng($tmp, $path, 0);
                            break;
                        case 'image/gif':
                            imagegif($tmp, $path);
                            break;
                        default:
                            exit;
                            break;
                    }
                    //$s3->put($path, file_get_contents($path));
                    multipartUpload($path);
                    unlink($path);
                    unlink('images/Sliders/'.$slider_path);
                    /* cleanup memory */
                    imagedestroy($image);
                    imagedestroy($tmp);
                }
                if ($slider->save()) {
                    return redirect('my_slider')->with('success', 'Slider Uploaded Successfully !!!!');
                }
            }
        }
    }

    public function update_my_slider(Request $request) {
        $data = $request->all();
        //dd($data);
        $validator = Validator::make($data, array(
                    'slider_title' => 'required|max:100',
                    //'slider_description' =>'required',
                    'slider' => 'image'
                        )
        );
        if ($validator->fails()) {
            return redirect('my_slider')
                            ->withErrors($validator)
                            ->withInput();
        } else {
            if ($request->hasFile('slider')) {
                $file = $_FILES["slider"]['tmp_name'];
                list($width, $height) = getimagesize($file);
                if ($width < "400" || $height < "400") {
                    return redirect('my_slider')->with('success', 'image size must be 400 x 400 pixels.');
                } else {
                    $s3 = Storage::disk('s3');
                    $slider = Slider::where('artist_id', Auth::user()->profile_id)->first();
                    $slider->slider_title = $request->slider_title;
                    $slider->slider_description = $request->slider_description;
                    if ($request->file('slider') != "") {
                        $file = $request->file('slider');
                        $filename = $file->getClientOriginalName();
                        $slider_path =  date('U') . '.jpeg';
                        $destinationPath = 'images/Sliders/';
                        $request->file('slider')->move($destinationPath, $slider_path);
                        $slider->slider_path = $slider_path;
                        //$s3->put($destinationPath.$slider_path, file_get_contents($file), 'public');
                        multipartUpload('images/Sliders/'.$slider_path);
                        ///
                        $rand = rand(11111, 99999) . date('U');
                        $width = 460;
                        $height = 320;
                        list($w, $h) = getimagesize('images/Sliders/'.$slider_path);
                        /* calculate new image size with ratio */
                        $ratio = max($width / $w, $height / $h);
                        $h = ceil($height / $ratio);
                        $x = ($w - $width / $ratio) / 2;
                        $w = ceil($width / $ratio);
                        /* new file name */
                        $path = 'images/Sliders/mob/' . $width . 'x' . $height . '_' . $rand . '.jpeg';
                        $pathof = substr($path, 29);
                        $slider->mob_slider_path = $pathof;
                        //$slider->mob_slider_path = $path;
                        /* read binary data from image file */
                        $imgString = file_get_contents('images/Sliders/'.$slider_path);
                        /* create image from string */
                        $image = imagecreatefromstring($imgString);
                        $tmp = imagecreatetruecolor($width, $height);
                        imagecopyresampled($tmp, $image, 0, 0, $x, 0, $width, $height, $w, $h);
                        /* Save image */
                        switch ($_FILES['slider']['type']) {
                            case 'image/jpeg':
                                imagejpeg($tmp, $path, 100);
                                break;
                            case 'image/png':
                                imagepng($tmp, $path, 0);
                                break;
                            case 'image/gif':
                                imagegif($tmp, $path);
                                break;
                            default:
                                exit;
                                break;
                        }
                        //$s3->put($path, file_get_contents($path));
                        multipartUpload($path);
                        unlink('images/Sliders/'.$slider_path);
                        unlink($path);
                        //$s3->setVisibility($destinationPath.$slider_path, 'private');
                        /* cleanup memory */
                        imagedestroy($image);
                        imagedestroy($tmp);
                        ///
                    }
                    if ($slider->save()) {
                        return redirect('my_slider')->with('success', 'Successfully Updated!');
                    }
                }
            } else {
                $slider = Slider::where('artist_id', Auth::user()->profile_id)->first();
                $slider->slider_title = $request->slider_title;
                $slider->slider_description = $request->slider_description;
                if ($slider->save()) {
                    return redirect('my_slider')->with('success', 'Successfully Updated!');
                }
            }
            //dd($data);
        }
    }

    /* -----------------------------Artist Change password-------------------------- */

    public function get_change_password() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $user = User::where('email', Auth::user()->email)->first();

                $profileData = Profile::where('EmailId', Auth::user()->email)->first();
                $artistData['user'] = $user;
                $artistData['profileData'] = $profileData;
                $artistData['baseurl'] = "https://videorequestlive.com/";
                return view("frontend.artistDashboard.change-password", $artistData);
            }
        } else {
            // return view('frontend.login');
            return redirect('/');
        }
    }

    public function change_password(Request $request) {
        $messages = [
            'required' => 'The :attribute field is required.',
            'new_password.regex' => ' Use at least one letter, one numeral & one special character',
            'new_password.min' => ' New password should be at least 8 characters ',
            'confirm_password.min' => ' Confirm password should be at least 8 characters',
        ];
        $validator = Validator::make(
                        array(
                    'old_password' => $request->old_password,
                    'new_password' => $request->new_password,
                    'confirm_password' => $request->confirm_password
                        ), array(
                    'old_password' => 'required',
                    'new_password' => 'required|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/',
                    'confirm_password' => 'required|min:8|same:new_password'
                        ), $messages
        );
        if ($validator->fails()) {
            return redirect('change-password')
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $user = User::find(Auth::user()->user_id);
            $old_password = $request->old_password;
            $new_password = $request->new_password;
            if (Hash::check($old_password, $user->getAuthPassword())) {
                $user->password = Hash::make($new_password);
                if ($user->save()) {
                    Session::put('password', $new_password);
                    return redirect('change-password')->with('success', "Password Changed Successfully");
                }
            } else {
                return redirect('change-password')->with('error', "Invalid  password");
            }
        }
    }

    /* -----------------------------------notifications------------------------------- */

    public function notification() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $user = User::where('email', Auth::user()->email)->first();
                $notification = DB::table('notification')->select('notification.*', 'profiles.*')
                                ->join('profiles', 'profiles.ProfileId', '=', 'notification.send_to')->get();
                $artist_data['users'] = $user;
                $artist_data['notifications'] = $notification;
                $artist_data['baseurl'] = "https://videorequestlive.com/";
// print_r($artist_data);
                return view('frontend.artistDashboard.notifications', $artist_data);
            }
        } else {
            return redirect("/");
        }
    }

    /* -----------------------------------Artist profile Update------------------------------------- */

    public function ProfileUpdate()
    {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $userData = User::where('email', Auth::user()->email)->first();
                $profileData = Profile::where('EmailId', Auth::user()->email)->first();
                $artistData['userData'] = $userData;
                $artistData['profileData'] = $profileData;
                $artistData['baseurl'] = "https://videorequestlive.com/";
                //echo '<pre>';print_r($userData['profile_id']);exit;
                /*
                 * Mohamed Mamdouh
                 * SSN Transform To ***
                 * 
                 */
                if (!empty($artistData['profileData']['ssn_number'])) {
                    try {
                        $currentSSN = Crypt::decrypt($artistData['profileData']['ssn_number']);
                    } catch (DecryptException $e) {
                       $currentSSN = null;
                    }
                } else {
                    $currentSSN = null;
                }
                if (strlen($currentSSN) > 0) {
                    $hashedSSN = str_repeat('*', strlen($currentSSN) - 2) . substr($currentSSN, -2);
                } else {
                    $hashedSSN = $currentSSN;
                }

                $categoryData = DB::table('category')->where('status', 1)->get();
                $artistData['catData'] = $categoryData;

                $artistCategories = array();
                $newArtistCategoryArray = array();
                $main_category_id = '';
                $artistCategories = DB::table('artist_category')->where('profile_id', $userData['profile_id'])->get();
                foreach ($artistCategories as $artistCategory) {
                    $newArtistCategoryArray[] = $artistCategory->category_id;
                    if ($artistCategory->main_category == 1 && $main_category_id =='' )
                    {
                        $main_category_id = $artistCategory->category_id ;
                    }
                }
                $artistData['artistCategory'] = $newArtistCategoryArray;
                $artistData['main_category_id'] = $main_category_id;
                //echo '<pre>';print_r($artistCategories);exit;

                return view('frontend.artistDashboard.ProfileUpdate', $artistData)->with(['hashedSSN' => $hashedSSN]);
            }
        } else {
            return redirect('/');
        }
    }

    public function ProfileUpdateForm(Request $request) {

        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
            'phone.regex' => ' Phone Should contain Numbers only',
            'username.regex' => 'Use valid User name (as xyz or xyz1)',
            'category_id.required' => 'Select at least one category',
        ];
        $validator = Validator::make($data, array(
                    'username' => 'required|regex:/^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-0-9-, ])*$/',
                    //'year' =>'required',
                    //'month' =>'required',
                    //'day' =>'required',
                    // 'date_ofbirth' =>'required',
                    'phone' => 'required|regex:/[0-9]/|digits:10',
                    'nickName' => 'regex:/^[\pL\s\-]+$/u',
                    /*'address' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'country' => 'required',*/
                    //'description' =>'required|min:400',
                    'zip' => 'required|min:5|max:12',
                    //'zip'=>'required',
                    'profile' => 'mimes:jpeg,png',
                    'category_id' => 'required',
                        ), $messages
        );
        if ($validator->fails()) {
            return redirect('ProfileUpdate')
                            ->withErrors($validator)
                            ->withInput();
        } else {
            if (!empty($data['date_ofbirth_']['month']) && !empty($data['date_ofbirth_']['day']) &&
                !empty($data['date_ofbirth_']['year'])) {
                $dob = $data['date_ofbirth_']['month'].'-'.$data['date_ofbirth_']['day']
                    .'-'.$data['date_ofbirth_']['year'];
            } else {
                $dob = $request->profile_date_ofbirth1;
            }
            $birthDate = $dob;
            $currentDate = date('Y-m-d');
            $diff = abs(strtotime($currentDate) - strtotime($birthDate));
            $years = floor($diff / (365*60*60*24));
            if ((int)$years < 18) {
                return redirect('ProfileUpdate')->with('error', "Age must be 18 or above 18 years");
            } else {
                $artist = Profile::find($request->ProfileId);
                $artist->Name = $request->username;
                $artist->DateOfBirth = $dob;
                $artist->MobileNo = $request->phone;
                $artist->NickName = $request->nickName;
                $artist->Address = $request->address;
                $artist->City = $request->city;
                $artist->State = $request->state;
                $artist->Gender = $request->gender;
                $artist->profile_description = $request->description;

                if (strpos($request->ssn_number, '*') === false) {
                    $artist->ssn_number = Crypt::encrypt($request->ssn_number);
                }
                if ($request->file('profile') != "") {
                    $file = $_FILES["profile"]['tmp_name'];
                    list($width, $height) = getimagesize($file);
                    if ($width < "400" || $height < "400") {
                        //echo "Error : image size must be 180 x 70 pixels.";
                        return redirect('ProfileUpdate')->with('error', \Lang::get('messages.img_size_400_400'));
                        //return redirect('my_slider')->withErrors('img_error','image size must be 180 x 70 pixels')->withInput();
                    } else {
                        $s3 = Storage::disk('s3');
                        if ($artist->profile_path) {
                            if($s3->exists("images/Artist/".$artist->profile_path)) {
                                $s3->delete("images/Artist/".$artist->profile_path);
                            }
                        }
                        $file = $request->file('profile');
                        $extension = $file->getClientOriginalExtension();
                        $rand = rand(11111, 99999) . date('U');
                        $artist->profile_path = $rand . '.'.$extension;
                        //$destinationPath = base_path() . '/public/images/Artist/';
                        $filePath = 'images/Artist/'. $artist->profile_path;
                        $request->file('profile')->move('images/Artist/', $artist->profile_path);
                        //$s3->put($filePath, file_get_contents($file));
                        multipartUpload($filePath);
                        unlink($filePath);
                    }
                }
                $artist->country = $request->country;
                $artist->Zip = $request->zip;
                $artist->Gender = $request->gender;

                DB::table('artist_category')->where('profile_id', $request->ProfileId)
                    ->delete();

                $category_ids = $request->category_id;
                foreach ($category_ids as $category_id) {
                    //$Artist_category->category_id = $category_id;
                    //$Artist_category->save();
                    DB::table('artist_category')->insert(
                        array('profile_id' => $request->ProfileId, 'category_id' => $category_id));
                }

                $main_category_id = $request->main_category_id;

                if (in_array($main_category_id, $category_ids)) {
                    DB::table('artist_category')
                        ->where('profile_id', $request->ProfileId)
                        ->where('category_id', $main_category_id)
                        ->update(['main_category' => 1]);
                }
                else
                {
                    DB::table('artist_category')->insert(
                        array('profile_id' => $request->ProfileId, 'category_id' => $main_category_id, 'main_category' => 1));
                }

                if ($artist->save()) {
                    return redirect('ProfileUpdate')->with('success', 'Successfully Updated!');
                } else {
                    return redirect('ProfileUpdate')->with('error', 'Successfully Updated!');
                }
            }
        }
    }

    /* ---------------------------------approve Video request---------------------------------- */

    public function approve_request($id) {

        $requestvideo = Requestvideo::find($id);
        $artist = Profile::find(Auth::user()->profile_id);


        $data['artist'] = $artist->Name;
        $data['user'] = $requestvideo->Name; // Who will receive the video
        $data['recipient_email'] = $requestvideo->requestor_email;

        $data['price'] = $requestvideo->ReqVideoPrice;
        $data['complitionDate'] = $requestvideo->complitionDate;

        $data['artist_id'] = $artist->ProfileId;
        $data['video_request_id'] = $requestvideo->VideoReqId;

        $data['artistName'] = $artist->Name;
        $data['video_title'] = $requestvideo->Occassion;
        $data['requester_name'] = $requestvideo->sender_name;
        $data['requester_email'] = $requestvideo->sender_email;
        $data['video_description'] = $requestvideo->person_message;
        $data['current_status'] = "Approved";
        $data['delivery_date'] = $requestvideo->delivery_date;
        $data['songName'] = $requestvideo->song_name;
        $data['price'] = $requestvideo->artist_vidoe_price;
        $data['identifier'] = $artist->ProfileId.'-'.$requestvideo->VideoReqId;


        // Get the Change The Status and Approval Date
        $requestvideo->RequestStatus = "Approved";
        $requestvideo->approval_date = date('d-m-Y');


        $user_email = $requestvideo->requestor_email; // Who woll receive the Video as a gift
        $sender_email = $requestvideo->sender_email;   // who will send the request 

        if ($requestvideo->is_active == 0) {
            return redirect(url()->previous())->with('success', 'Request has been rejected previously.');
        } else {
            if ($requestvideo->save()) {
                Payment::whereVideoRequestId($requestvideo->VideoReqId)->update([
                    'video_status'  =>  \Lang::get('views.approved')
                ]);
                $user = $user_email; // Who will Receive the Video
                $sender = $sender_email; // Who will send the Video
                //
                
                // Send mail to sender that his Request Has been Approved
                        
                Mail::send('emails.video_response', $data, function ($message) use ($sender,$data) {
                    $message->from('noreply@videorequestline.com', 'VRL');
                    $message->to($sender, 'User');
                    $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                    $message->subject('Video Request Approved! ('.$data['identifier'].')');
                });

                $user_detail = DB::table('users')->whereProfileId($requestvideo->requestByProfileId)->first();
                $deviceToken = $user_detail->device_token;
                if (is_null($user_detail->push_notification) || $user_detail->push_notification == 1) {
                    if ($deviceToken != null) {
                        $message = 'Congratulations, your video request has been approved by ' . $artist->Name;
                        push_notification($deviceToken, $message, 1);
                    }
                }
                //$passphrase = '12345';


                /* if($deviceType=='iphone' && $deviceToken!=''){
                  $passphrase = '12345';

                  if($deviceToken!=''){
                  $ctx = stream_context_create();

                  $test = stream_context_set_option($ctx, 'ssl', 'local_cert', 'VRL.pem');

                  stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

                  $fp = stream_socket_client(
                  'ssl://gateway.sandbox.push.apple.com:2195', $err,
                  $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

                  $body['aps'] = array(
                  'alert' => 'Approved successfully video request by '.$artist->Name ,
                  'sound' => 'default',
                  'badge' => 1,
                  );

                  $payload = json_encode($body);

                  $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

                  $result = fwrite($fp, $msg, strlen($msg));
                  fclose($fp);
                  return redirect(url()->previous())->with('success','Approved successfully');
                  }else{
                  $successmsg="Device token not found";
                  return redirect(url()->previous())->with('success','Approved successfully');

                  }
                  } */
                /* if($deviceType=='android' && $deviceToken!=''){

                  $to=$deviceToken;
                  $title="Video Request";//
                  $message='Approved successfully video request by '.$artist->Name;
                  define( 'API_ACCESS_KEY','AAAAUezx5KE:APA91bHdeF33VnwpVxrzlK0umno6Cb8sgTDlwmyQITcz9-3_PBBY-RXETQias398AHVqkq45-_Xu0BRopNREelz3n9YBEhI3SkKSo8myUfThTkV4dYOkGdcolMBFpdXHGSVdYnnz9SPXplFAsI7CnYcf54-G8i3bjQ');
                  $registrationIds = array($to);
                  $msg = array
                  (
                  'message' => $message,
                  'title' => $title,
                  'vibrate' => 1,
                  'sound' => 1
                  );
                  $fields = array
                  (
                  'registration_ids' => $registrationIds,
                  'data' => $msg
                  );

                  $headers = array
                  (
                  'Authorization: key='.API_ACCESS_KEY,
                  'Content-Type: application/json'
                  );

                  $ch = curl_init();
                  curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
                  curl_setopt( $ch,CURLOPT_POST, true );
                  curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                  curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                  curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                  curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
                  $result = curl_exec($ch );
                  curl_close( $ch );

                  return redirect(url()->previous())->with('success','Approved successfully');
                  } */

                return redirect(url()->previous())->with('success', 'Approved successfully');
            } else {
                return redirect('Dashboard')->with('error', 'Oops ! Something is wrong');
            }
        }
    }

    /* ---------------------------------Reject Video request---------------------------------- */

    public function reject_request(Request $request) {
        $requestvideo = Requestvideo::find($request->request_id);
        $artist = Profile::find(Auth::user()->profile_id);
        /*
         * Make Some Validation That this Request Belongs to authenticated User
         */
        if (empty($requestvideo) || Auth::user()->profile_id != $requestvideo->requestToProfileId) {
            Session::flash('error', 'Something went wrong please contact the admin');
            return redirect('/pending_requests ');
        }

        $data['artist_id'] = $artist->ProfileId;
        $data['video_request_id'] = $requestvideo->VideoReqId;
        $data['identifier'] = $artist->ProfileId.'-'.$requestvideo->VideoReqId;
        $data['turnaroundTime'] = $artist->timestamp;

        $data['sender_name'] = $requestvideo->sender_name;
        $data['recipient_name'] = $requestvideo->Name; // This is receptient

        $data['sender_email'] = $requestvideo->sender_email;
        $data['recipient_email'] = $requestvideo->requestor_email; // This is receptient

        $data['complitionDate'] = $requestvideo->complitionDate;
        $data['songName'] = $requestvideo->song_name;
        $data['occasion'] = $requestvideo->Title;
        $data['personalizedMessage'] = $requestvideo->Description;

        $data['artist'] = $artist->Name;
        $data['price'] = $requestvideo->ReqVideoPrice;


        if ($request->rejected_reason != '' || $request->rejected_comment != '') {
            $rejected_reason = $request->rejected_reason;
            if(!empty($rejected_reason) && array_key_exists($rejected_reason, Config::get('constants.REJECTED_REASONS')))
            {
                $rejected_reason = Config::get('constants.REJECTED_REASONS')[$rejected_reason];
            }
            $rejected_comment_string  = ($request->rejected_comment != '') ?  ' - ' . $request->rejected_comment : '';
            $data['rejected_reason'] = $rejected_reason  . $rejected_comment_string;
        } else {
            $data['rejected_reason'] = 'No reason provided';
        }

        $requestvideo->RequestStatus = "Reject";
        $requestvideo->rejected_reason = !empty($request->rejected_reason) ? $request->rejected_reason : null;
        $requestvideo->rejected_comment = !empty($request->rejected_comment) ? $request->rejected_comment : null;
        $requestvideo->is_refunded = 1;
        $requestvideo->is_active = 0;
        //$requestvideo->ReqVideoPrice = $artist->VideoPrice;

        $user_email = $requestvideo->requestor_email; // Who woll receive the Video as a gift
        $sender_email = $requestvideo->sender_email;   // who will send the request 

        if ($requestvideo->is_active == 1) {
            return redirect(url()->previous())->with('success', 'Request has been approved previously.');
        } else {

            if ($requestvideo->save()) {

                $payment = \App\Payment::where('video_request_id', $request->request_id)
                    ->where('status', 'succeeded')->first();
                if (!is_null($payment)) {
                    try {

                        //\Stripe\Stripe::setApiKey("sk_test_CtVU3fFCOkPs7AbQDLLJmU1n");
                        //  \Stripe\Stripe::setApiKey("sk_live_AuLjanpEXm7L1Iq22XhDBzyR");
                        if (App::environment('local') || App::environment('testing')) {
                            // The environment is local or testing
                            \Stripe\Stripe::setApiKey(Config::get('constants.STRIPE_KEYS.test.secret_key'));
                        } else {
                            // The environment is production
                            \Stripe\Stripe::setApiKey(Config::get('constants.STRIPE_KEYS.live.secret_key'));
                        }
                        \Stripe\Stripe::setApiVersion(Config::get('constants.STRIPE_VERSION'));

                        $ch = \Stripe\Charge::retrieve($payment->charge_id);

                        if (!$ch->refunded) {
                            $re = $ch->refund();
                            $payment->is_refunded = 1;
                            $payment->save();

                            $refund_details['status'] = 'refunded';
                            $refund_details['request_detail'] = $requestvideo;
                            $refund_details['artist'] = $artist->Name;
                            $refund_details['complitionDate'] = $requestvideo->complitionDate;

                            $refund_details['artist_id'] = $artist->ProfileId;
                            $refund_details['video_request_id'] = $requestvideo->VideoReqId;
                            $refund_details['identifier'] = $artist->ProfileId.'-'.$requestvideo->VideoReqId;
                            $refund_details['turnaroundTime'] = $artist->timestamp;
//                            dd($artist,$refund_details['turnaroundTime']);
                            $refund_details['recipient_name'] = $requestvideo->Name;
                            $refund_details['recipient_email'] = $requestvideo->requestor_email;
                            $refund_details['artistName'] = $artist->Name;
                            $refund_details['video_title'] = $requestvideo->Occassion;
                            $refund_details['requester_name'] = $requestvideo->sender_name;
                            $refund_details['requester_email'] = $requestvideo->sender_email;
                            $refund_details['video_description'] = $requestvideo->person_message;
                            $refund_details['current_status'] = "Rejected";
                            $refund_details['delivery_date'] = $requestvideo->delivery_date;
                            $refund_details['songName'] = $requestvideo->song_name;
                            $refund_details['price'] = $requestvideo->artist_vidoe_price;
                            $refund_details['identifier'] = $artist->ProfileId.'-'.$requestvideo->VideoReqId;
                            $refund_details['rejected_reason'] = $rejected_reason = $request->rejected_reason;

                            if ($request->rejected_reason != '' || $request->rejected_comment != '') {
                                $rejected_reason = $request->rejected_reason;
                                if(!empty($rejected_reason) && array_key_exists($rejected_reason, Config::get('constants.REJECTED_REASONS')))
                                {
                                    $rejected_reason = Config::get('constants.REJECTED_REASONS')[$rejected_reason];
                                }
                                $rejected_comment_string  = ($request->rejected_comment != '') ?  ' - ' . $request->rejected_comment : '';
                                $refund_details['rejected_reason_detail'] = $rejected_reason  . $rejected_comment_string;
                            } else {
                                $refund_details['rejected_reason_detail'] = 'No reason provided';
                            }

                            $user = $user_email;
                            $sender = $sender_email;

                                Mail::send('emails.refund_success', $refund_details, function ($message) use ($sender,$refund_details) {
                                $message->from('noreply@videorequestline.com', 'VRL');
                                $message->to($sender, 'Sender');
                                $message->subject('Payment Refunded Successfully ('.$refund_details['identifier'].')');
                                $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                            });
                        }
                    } catch (\Stripe\Error\Card $e) {//dd('false');
                        // Since it's a decline, \Stripe\Error\Card will be caught
                        $err_msg = $e->getMessage();
                        return redirect('/video_requests')->with('error', $err_msg)->withInput();
                    } catch (\Stripe\Error\InvalidRequest $e) {
                        $err_msg = $e->getMessage();
                        return redirect('/video_requests')->with('error', $err_msg)->withInput();
                        // Invalid parameters were supplied to Stripe's API
                    } catch (\Stripe\Error\Authentication $e) {
                        $err_msg = $e->getMessage();
                        return redirect('/video_requests')->with('error', $err_msg)->withInput();
                        // Authentication with Stripe's API failed
                        // (maybe you changed API keys recently)
                    } catch (\Stripe\Error\ApiConnection $e) {
                        $err_msg = $e->getMessage();
                        return redirect('/video_requests')->with('error', $err_msg)->withInput();
                        // Network communication with Stripe failed
                    } catch (\Stripe\Error\Base $e) {
                        $err_msg = $e->getMessage();
                        return redirect('/video_requests')->with('error', $err_msg)->withInput();
                        // Display a very generic error to the user, and maybe send
                        // yourself an email
                    } catch (Exception $e) {
                        $err_msg = $e->getMessage();
                        return redirect('/video_requests')->with('error', $err_msg)->withInput();
                        // Something else happened, completely unrelated to Stripe
                    }
                }

                $user = $user_email;
                $sender = $sender_email;

                Mail::send('emails.video_request_reject', $data, function ($message) use ($sender,$data) {
                    $message->from('noreply@videorequestline.com', 'VRL');
                    $message->to($sender, 'User');
                    $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                    $message->subject('Sorry we cannot fulfill your request ! ('.$data['identifier'].')');
                });

                //$passphrase = '12345';
                //$user_detail=DB::table('users')->where('profile_id','=',$requestvideo->requestByProfileId)->first();

                $user_detail = DB::table('users')->where('profile_id', '=', $requestvideo->requestByProfileId)->first();
                if ($user_detail != null) {
                    $deviceToken = $user_detail->device_token;
                    if (is_null($user_detail->push_notification) || $user_detail->push_notification == 1) {
                        if ($deviceToken != null) {
                            $message = 'Your video request has been rejected by ' . $artist->Name;
                            push_notification($deviceToken, $message, 2);
                        }
                    }
                }
                /* if($deviceType=='iphone' && $deviceToken!=''){
                  $passphrase = '12345';

                  if($deviceToken!=''){
                  $ctx = stream_context_create();

                  $test = stream_context_set_option($ctx, 'ssl', 'local_cert', 'VRL.pem');

                  stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

                  $fp = stream_socket_client(
                  'ssl://gateway.sandbox.push.apple.com:2195', $err,
                  $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

                  $body['aps'] = array(
                  'alert' => 'Video Request rejected successfully  by '.$artist->Name ,
                  'sound' => 'default',
                  'badge' => 1,
                  );

                  $payload = json_encode($body);

                  $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

                  $result = fwrite($fp, $msg, strlen($msg));
                  fclose($fp);
                  return redirect(url()->previous())->with('success','Rejected successfully');
                  }else{
                  $successmsg="Device token not found";
                  return redirect(url()->previous())->with('success','Rejected successfully');

                  }
                  } */
                /* if($deviceType=='android' && $deviceToken!=''){

                  $to=$deviceToken;
                  $title="Video Request";//
                  $message='Video Request rejected successfully  by '.$artist->Name;
                  define( 'API_ACCESS_KEY','AAAAUezx5KE:APA91bHdeF33VnwpVxrzlK0umno6Cb8sgTDlwmyQITcz9-3_PBBY-RXETQias398AHVqkq45-_Xu0BRopNREelz3n9YBEhI3SkKSo8myUfThTkV4dYOkGdcolMBFpdXHGSVdYnnz9SPXplFAsI7CnYcf54-G8i3bjQ');
                  $registrationIds = array($to);
                  $msg = array
                  (
                  'message' => $message,
                  'title' => $title,
                  'vibrate' => 1,
                  'sound' => 1
                  );
                  $fields = array
                  (
                  'registration_ids' => $registrationIds,
                  'data' => $msg
                  );

                  $headers = array
                  (
                  'Authorization: key='.API_ACCESS_KEY,
                  'Content-Type: application/json'
                  );

                  $ch = curl_init();
                  curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
                  curl_setopt( $ch,CURLOPT_POST, true );
                  curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                  curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                  curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                  curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
                  $result = curl_exec($ch );
                  curl_close( $ch );

                  return redirect(url()->previous())->with('success','Rejected successfully');
                  } */

                return redirect(url()->previous())->with('success', 'Rejected successfully');
            } else {
                return redirect('Dashboard')->with('error', 'Oops ! Something is wrong');
            }
        }
    }

    /* -----------------------upload requested Video------------------------------ */

    public function upload_requested_video($id) {
        /*
         * This is Get Function Return View To Artist to upload a video for specific Request ID
         * -- Has Some Unused Code I don't know Why ? [ $requested_user ] 
         * -- 
         */

        $requested_video = Requestvideo::find($id);
        //$requested_user=
        $requested_user = DB::table('requestvideos')
                ->join('profiles', 'requestvideos.requestByProfileId', '=', 'profiles.ProfileId')
                ->select('*')
                ->get();


        if ($requested_video == null) {
            return redirect(url()->previous());
        } else if ($requested_user == null) {
            return redirect('/video_requests')->with('success', 'user not exist!');
        } else {
            $user_id = $requested_video->requestByProfileId; // [Profile ID to  Who Create A request ]
            $user = Profile::find($user_id); // Profile to User who create a request 
            $profileData = Profile::where('EmailId', Auth::user()->email)->first(); // Artist
            $data['user'] = $user;
            $data['requested_video'] = $requested_video;
            $data['profileData'] = $profileData;


            return view("frontend.artistDashboard.upload_requested_video", $data);
        }
    }

    public function resend_video($user_id, $request_id)
    {

        $profile_data = DB::table('users')->where('profile_id', '=', $user_id)->first();
        //dd($profile_data);
        if (!empty($profile_data)) {
            if (Auth::check()) {
                if (Auth::user()->type == "Artist") {
                    return redirect('/');
                } elseif (Auth::user()->type == "Admin") {
                    return redirect('admin_dashboard');
                } elseif (Auth::user()->type == "User") {
                    //$profile_data = DB::table('users')->where('profile_id', '=', $user_id)->select('*')->first();
                    if ($profile_data->is_account_active == '1') {
                        $requested_user = DB::table('requested_videos')
                            ->select('*')->where('requestby', '=', $user_id)->where('id', '=', $request_id)
                            ->join('profiles', 'profiles.ProfileId', '=', 'requested_videos.requestby')
                            ->join('requestvideos', 'requestvideos.VideoReqId', '=', 'requested_videos.request_id')
                            ->first();
                       // dd($requested_user);
                        $artist = Profile::find($requested_user->requestToProfileId);

                        //dd($requested_user);
                        $data['video_name'] = $requested_user->url;
                        $data['thumbnail'] = $requested_user->thumbnail;
                        $data['video_title'] = $requested_user->title;
                        $data['video_description'] = $requested_user->description;
                        $data['requested_user'] = $requested_user;
                        $data['current_status'] = "Approved";
                        $data['video_request_id'] = $requested_user->VideoReqId;
                        $data['artistName'] = $artist->Name;
                        $data['turnaroundTime'] = $artist->timestamp;
                        $data['songName'] = $requested_user->song_name;
                        $data['identifier'] = $requested_user->requestToProfileId.'-'.$requested_user->VideoReqId;

                        if (Mail::send('emails.upload_download_email', $data, function ($message) use ($requested_user,$data) {
                            $message->from('noreply@videorequestline.com', 'Download Video');
                            $message->to($requested_user->EmailId, 'codingbrains6@gmail.com');
                            $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                            $message->subject('VRL resend request submitted ('.$data['identifier'].')');
                        })) {
                            return redirect('user_video')->with('success', "Successfully Sent ");
                        } else {
                            return redirect('user_video')->with('error', 'fails');
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

    public function artist_resend_video($user_id, $request_id) {

//dd($user_id);
        $requested_user = DB::table('requested_videos')
                ->select('*')->where('requestby', '=', $user_id)->where('id', '=', $request_id)
                ->join('profiles', 'profiles.ProfileId', '=', 'requested_videos.requestby')
                ->first();
        $artist = Profile::find(Auth::user()->profile_id);
        $request_details= DB::table('requestvideos')->select('*')->where('VideoReqId', $request_id)->first();
        $data['artistName'] = $artist->Name;
        $data['video_name'] = $requested_user->url;
        $data['thumbnail'] = $requested_user->thumbnail;
        $data['video_title'] = $requested_user->title;
        $data['video_description'] = $requested_user->description;
        $data['songName'] = $request_details->song_name;
        $data['current_status'] = "Approved";
        $data['requested_user'] = DB::table('requestvideos')->where('VideoReqId', $requested_user->request_id)->first();
        $data['identifier'] = $user_id.'-'.$request_id;
        $data['turnaroundTime'] = $artist->timestamp;
        if (Mail::send('emails.upload_download_email', $data, function ($message) use ($requested_user,$data) {
                    $message->from('noreply@videorequestline.com', 'Download Video');
                    $message->to($requested_user->EmailId);
                    $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                    $message->subject("VRL request uploaded (".$data['identifier'].')');
                })) {
            return redirect('deliver_video')->with('success', "Successfully Sent ");
        } else {
            return redirect('deliver_video')->with('error', 'fails');
        }
    }

    public function admin_resend_video($user_id, $request_id) {
        //dd('test');
        if (Auth::check()) {
            $requested_user = DB::table('requested_videos')
                    ->select('*')->where('requestby', '=', $user_id)->where('id', '=', $request_id)
                    ->join('profiles', 'profiles.ProfileId', '=', 'requested_videos.requestby')
                    ->first();
            $artist = Profile::find($requested_user->uploadedby);
            $request_details= DB::table('requestvideos')->select('*')->where('VideoReqId', $request_id)->first();
            $data['artistName'] = $artist->Name;
            $data['video_name'] = $requested_user->fileName;
            $data['thumbnail'] = $requested_user->thumbnail;
            $data['video_title'] = $requested_user->title;
            $data['video_description'] = $requested_user->description;
            $data['songName'] = $request_details->song_name;
            $data['current_status'] = "Approved";
            $data['requested_user'] = DB::table('requestvideos')->where('VideoReqId', $requested_user->request_id)->first();
            $data['video_request_id'] = $requested_user->request_id;
            $data['artist_id'] = $requested_user->uploadedby;
            $data['identifier'] = $user_id.'-'.$request_id;
            $data['turnaroundTime'] = $artist->timestamp;

            if (Mail::send('emails.upload_download_email', $data, function ($message) use ($requested_user,$data) {
                        $message->from('noreply@videorequestline.com', 'Download Video');
                        $message->to($requested_user->EmailId);
                        $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                        $message->subject("VRL request uploaded (".$data['identifier'].')');
                    })) {
                return redirect('get_video_requests')->with('success', "Successfully Sent ");
            } else {
                return redirect('get_video_requests')->with('error', 'fails');
            }
        }
    }

    public function pay_extend_storage() {
        echo "test";
    }

    public function upload_requestedVideo(Request $request) {
        try {
            $data = $request->all();
            $validator = Validator::make(
                array(
                    'video' => $request->file('video'),
                ), array(
                    'video' => 'required',
                )
            );
            if ($validator->fails()) {
                return redirect('upload_requested_video/' . $request->requested_video_id)
                    ->withErrors($validator)
                    ->withInput();
            } else {
                /*
                 * Mohamed Mamdouh
                 * 5-2-2018
                 * Calling Upload video Service Created Under the current Function Instead of Repated the whole process
                 *
                 */

                $file = $request->file('video');
                $mime = $file->getMimeType();
                if ($mime == "video/mp4" || $mime == "video/quicktime" || $mime == "video/x-msvideo"
                    || $mime == "video/x-flv" || $mime == "application/x-mpegURL" || $mime == "video/3gpp"
                    || $mime == "video/x-ms-wmv"
                ) {
                    $return = $this->upload_requested_vide_via_web_cam($request);
                } else {
                    return redirect('upload_requested_video/' . $request->requested_video_id)
                        ->with('error', 'Allowed file extensions are mp4, mov, avi, flv, wmv, m3u8, 3gp')
                        ->withInput();
                }
                if ($return['process'] == true) {
                    return redirect('/video_requests')->with('success', 'Successfully Uploaded');                 
                } else {
                    return redirect('/video_requests')->with('error', $return['message']);
                }
            }
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
            return redirect('upload_requested_video/' . $request->requested_video_id)
                ->with('error', $ex->getMessage());
        }

//        
//        
//        else {
//
//            $requested_video = new RequestedVideo();
//
//
//            $file = $request->file('video');
//            $extension = $file->getClientOriginalExtension();
//            $filename = str_replace(' ', '', $file->getClientOriginalName());
//            $filename = str_replace('-', '', $filename);
//            //$rand = rand(11111,99999).date('U');
//
//
//
//
//            $VideoURL = "https://videorequestlive.com/requested_video/";
//
//
//
//            $requested_video->description = $request->requested_video_description;
//            $requested_video->title = $request->requested_video_title;
//            $requested_video->request_id = $request->requested_video_id;
//            $requested_video->requestby = $request->requestedby;
//            $requested_video->uploadedby = $request->uploadedby;
//
//
//
//            $rand = rand(11111, 99999) . date('U');
//
//            $destination = base_path() . '/public/requested_video/';
//            $fileName = $rand . '.' . $extension;
//
//            $request->file('video')->move($destination, $fileName);
//            $destination_path = $destination . $fileName;
//
//
//            $requested_video->url = $VideoURL . $fileName;
//            $requested_video->fileName = $fileName;
//            $requested_video->is_active = 1;
//            $requested_video->Upload_date = date('Y-m-d');
//            $requested_video->purchase_date = date('Y-m-d');
//
//
//
//            $purge_data = DB::table('setting')->select('status')->where('name', '=', "purge")->first();
//            $requested_video->remain_storage_duration = $purge_data->status;
//
//            
//            /* -- Mohamed =>
//             * Created Unique token 
//             */
//            
//             $ran_token = Hash::make(rand(11111, 99999) . date('U'));
//             $requested_video->token = $ran_token;
//             $requested_video->token_used = 0;
//            
//             /* ------------------------------- */
//
//            $ffmpeg = FFMpeg\FFMpeg::create(array(
//                        'ffmpeg.binaries' => '/usr/local/bin/ffmpeg',
//                        'ffprobe.binaries' => '/usr/local/bin/ffprobe',
//                        'timeout' => 3600,
//                        'ffmpeg.threads' => 12,
//            ));
//
//            /* ------------------------ */
//
//            $orginal_video = new OriginalVideo();
//            $orginal_video->video_path = $destination_path;
//            $orginal_video->save();
//
//
//            $orginal_video_id = $orginal_video->id;
//            $orginal_video = OriginalVideo::find($orginal_video_id);
//            $uploaded_video = $ffmpeg->open($orginal_video->video_path);
//
//            /* ------------------------ */
//
//            $ffmpeg = FFMpeg\FFMpeg::create(array(
//                        'ffmpeg.binaries' => '/usr/local/bin/ffmpeg',
//                        'ffprobe.binaries' => '/usr/local/bin/ffprobe',
//                        'timeout' => 3600,
//                        'ffmpeg.threads' => 12,
//            ));
//            
//            $uploaded_video = $ffmpeg->open($destination . $fileName);
//
//            $ffprobe = FFMpeg\FFProbe::create(array(
//                        'ffmpeg.binaries' => '/usr/local/bin/ffmpeg',
//                        'ffprobe.binaries' => '/usr/local/bin/ffprobe',
//                        'timeout' => 3600,
//                        'ffmpeg.threads' => 12,
//            ));
//            /* -------------------------retrieving Video Duration---------------------------- */
//
//            $video_length = $ffprobe->streams($destination . $fileName)
//                    ->videos()
//                    ->first()
//                    ->get('duration');
//
//            if ($video_length < 15) {
//                //unlink($destination.$fileName);
//                unlink($destination . $fileName);
//                return redirect('upload_requested_video/' . $request->requested_video_id)->with('error', 'Video duration must be of 15 seconds');
//            }
//            else {
//                
//                /* ----------------------------retrieving Thumbnail------------------------------ */
//
//                $video_thumbnail_path = base_path() . '/public/images/thumbnails/' . date('U') . '.jpg';
//                $uploaded_video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(2))->save($video_thumbnail_path);
//                $requested_video->thumbnail = $video_thumbnail_path;
//
//
//                /* ----------------------------Applying Watermark---------------------------------- */
//
//                $ffmpegPath = '/usr/local/bin/ffmpeg';
//                $inputPath = $orginal_video->video_path;
//                $watermark = '/home/vrl/public_html/public/vrl_logo.png';
//                $outPath = '/home/vrl/public_html/public/requested_video/watermark/' . date('U') . '.mp4';
//                shell_exec("$ffmpegPath  -i $inputPath -i $watermark -filter_complex overlay=main_w-overlay_w-20:main_h-overlay_h-20 $outPath ");
//
//                /* 	----------------------------------Saving Video------------------------------- */
//
//
//                $watermark_video_destination = substr($outPath, 28);
//                //$pathofvideo="https://www.videorequestlive.com".$outPathvideo=substr($outPath,28);
//                $requested_video->url = $outPath;
//                //dd($pathofvideo);
//                //$video->originalVideoUrl	 = $orginal_video->video_path;
//                $ffprobe = FFMpeg\FFProbe::create(array(
//                            'ffmpeg.binaries' => '/usr/local/bin/ffmpeg',
//                            'ffprobe.binaries' => '/usr/local/bin/ffprobe',
//                            'timeout' => 3600,
//                            'ffmpeg.threads' => 12,
//                ));
//                if ($requested_video->save()) {
//
//
//                    $video_requests = Requestvideo::find($request->requested_video_id);
//
//
//                    if ($video_requests == null) {
//                        return redirect('/video_requests')->with('success', 'Request has been deleted by User');
//                    } else if ($video_requests->RequestStatus == 'Reject') {
//                        return redirect('/video_requests')->with('success', 'Request has been Rejected by You');
//                    } else {
//                        //if($video_requests!=null and $video_requests->RequestStatus!='Reject'){
//
//                        /*
//                         * 1- Update Video Status In Payment Table
//                         * 2- Update Request Status in requestvideos Table
//                         */
//                        DB::table('payments')
//                                ->where('video_request_id', $request->requested_video_id)
//                                ->update(array('video_status' => 'Completed'));
//
//                        $video_requests->RequestStatus = "Completed";
//                        $video_requests->save();
//
//
//                        $video_requests = Requestvideo::find($request->requested_video_id);
//
//
//
//                        $data['video_name'] = $fileName;
//                        $data['thumbnail'] = $video_thumbnail_path;
//                        $data['video_title'] = $request->requested_video_title;
//                        $data['video_description'] = $request->requested_video_description;
//                        $data['requested_user'] = $requested_video;
//
//                        // Send Mail To Artist
//                        Mail::send('emails.download_email', $data, function ($message) use ($request) {
//                            $message->from('noreply@videorequestline.com', 'Download Video');
//                            $message->to($request->user_email, $request->user_email);
//                            $message->subject('Please download Your requested Video');
//                        });
//
//
//                        // Send Mail To  Who will receive the gitft [ This Should Happens ]
////                                        
////     					Mail::send('emails.download_email', $data, function ($message) use ($request) {
////     						$message->from('noreply@videorequestline.com', 'Download Video');
////     						$message->to($request->requested_email, $request->requested_email);
////     						$message->subject('Please download Your requested Video');
////     					});
////                                        
//
//
//                        $artist = Profile::find($request->uploadedby);
//
//                        $user_detail = DB::table('users')->where('profile_id', '=', $request->requestedby)->first();
//
//
//                        if (count($user_detail) > 0) {
//
//                            $admin_data['user_name'] = $user_detail->user_name;
//                            $admin_data['artist_name'] = $artist->Name;
//                            $admin_data['video_price'] = $artist->VideoPrice;
//                            $admin_data['video_title'] = $request->requested_video_title;
//                            $admin_data['video_description'] = $request->requested_video_description;
//                            $admin_data['video_completion'] = $video_requests->ComplitionDate;
//                            $admin_data['thumbnail'] = $video_thumbnail_path;
//                            $admin_data['requested_user'] = $requested_video;
//
//
//                            // Send Mail To Admin That Artist Completed A request  
////                            Mail::send('emails.admin_download_email', $admin_data, function ($message) use ($request) {
////                                $message->from('noreply@videorequestline.com', 'Video Upload');
////                                $message->to('admin@videorequestline.com', 'admin@videorequestline.com');
////                                $message->subject('Artist Uploaded Video To user');
////                            });
//
//
//                            $artist_data['user_name'] = $user_detail->user_name;
//                            $artist_data['video_price'] = $artist->VideoPrice;
//                            $artist_data['video_title'] = $request->requested_video_title;
//                            $artist_data['video_description'] = $request->requested_video_description;
//                            $artist_data['video_completion'] = $video_requests->ComplitionDate;
//                            $artist_data['thumbnail'] = $video_thumbnail_path;
//                            $artist_data['requested_user'] = $requested_video;
//
//                            // Send Mail To Artist That His Video Uploaded Successfully
//
//                            Mail::send('emails.artist_download_email', $artist_data, function ($message) use ($request) {
//                                $message->from('noreply@videorequestline.com', 'Download Video');
//                                $message->to(Auth::user()->email, Auth::user()->email);
//                                $message->subject('Video Uploaded Successfully');
//                            });
//
//
//                            //$user_detail=DB::table('users')->where('profile_id','=',$requestvideo->requestByProfileId)->first();
//                            $deviceToken = $user_detail->device_token;
//                            $deviceType = $user_detail->device_type;
//                        }
//                        /* if($deviceType=='iphone' && $deviceToken!=''){
//                          $passphrase = '12345';
//
//                          if($deviceToken!=''){
//                          $ctx = stream_context_create();
//
//                          $test = stream_context_set_option($ctx, 'ssl', 'local_cert', 'VRL.pem');
//
//                          stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
//
//                          $fp = stream_socket_client(
//                          'ssl://gateway.sandbox.push.apple.com:2195', $err,
//                          $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
//
//                          $body['aps'] = array(
//                          'alert' => 'Upload Request Video successfully  by '.$artist->Name.'Please check your email to download video' ,
//                          'sound' => 'default',
//                          'badge' => 1,
//                          );
//
//                          $payload = json_encode($body);
//
//                          $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
//
//                          $result = fwrite($fp, $msg, strlen($msg));
//                          fclose($fp);
//                          return redirect('/video_requests')->with('success','Upload successfully');
//                          }else{
//                          $successmsg="Device token not found";
//                          return redirect('video_requests')->with('error','Device token not found');
//
//                          }
//                          } */
//                        /* if($deviceType=='android' && $deviceToken!=''){
//
//                          $to=$deviceToken;
//                          $title="Video Request";//
//                          $message='Upload Request video successfully  by '.$artist->Name.'Please check your email to download video';
//                          define( 'API_ACCESS_KEY','AAAAUezx5KE:APA91bHdeF33VnwpVxrzlK0umno6Cb8sgTDlwmyQITcz9-3_PBBY-RXETQias398AHVqkq45-_Xu0BRopNREelz3n9YBEhI3SkKSo8myUfThTkV4dYOkGdcolMBFpdXHGSVdYnnz9SPXplFAsI7CnYcf54-G8i3bjQ');
//                          $registrationIds = array($to);
//                          $msg = array
//                          (
//                          'message' => $message,
//                          'title' => $title,
//                          'vibrate' => 1,
//                          'sound' => 1
//                          );
//                          $fields = array
//                          (
//                          'registration_ids' => $registrationIds,
//                          'data' => $msg
//                          );
//
//                          $headers = array
//                          (
//                          'Authorization: key='.API_ACCESS_KEY,
//                          'Content-Type: application/json'
//                          );
//
//                          $ch = curl_init();
//                          curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
//                          curl_setopt( $ch,CURLOPT_POST, true );
//                          curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
//                          curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
//                          curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
//                          curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
//                          $result = curl_exec($ch );
//                          curl_close( $ch );
//
//                          return redirect('/video_requests')->with('success','Successfully Uploaded');
//                          } */
//                        return redirect('/video_requests')->with('success', 'Successfully Uploaded!');
//                        //}else{
//                        //return redirect('/video_requests')->with('success','Request has been deleted by User');
//                    }
//                }
//            }
//        }
    }

    /*
     * Mohamed Mamdouh 
     * 3-2-2018
     * Upload Video Request Via Web Camera Which needs some implementations
     * 
     */

     public function upload_requested_vide_via_web_cam(Request $request) {
          /*
         * Laravel 5.2 Dosn't Support formData in request so this function will based on $_Post and $_File 
         * 
         */

        // Get all Request Details In seperated Parameters 
        $requested_video_id = $_POST['requested_video_id'];
    
        // initilize Variable of url
        $VideoURL = url('/').'/requested_video/';
        //$VideoURL = "https://videorequestlive.com/requested_video/";

        /*
         * Make Validation For that request exist for the current authenticated user
         * Check If That Request belongs to that Artist and that request is not completed and 
         */

        $requestValidation = Requestvideo::where(['VideoReqId' => $requested_video_id,
                    'requestToProfileId' => Profile::find(Auth::user()->profile_id)->ProfileId
                ])->first();

        if (count($requestValidation) < 1) {
            return ['process' => false,
                'message' => 'This Request not exist anymore or you don not have access to it please try again later '];
        } else if ($requestValidation->RequestStatus != 'Approved') {
            return ['process' => false, 'message' => 'This Request Is Completed Or not Approved'];
        }

        /*
         * Create Request Vriable 
         */

        $requested_video_title = $requestValidation->Title;
        $requested_video_description = $requestValidation->Description;
        $requestedby = $requestValidation->requestByProfileId;
        $user_email = $requestValidation->sender_email;
        $uploadedby = Auth::user()->profile_id;

        $requested_video = new RequestedVideo();
        /* Normal Details For the video */

        $requested_video->description = $requested_video_description;
        $requested_video->title = $requested_video_title;
        $requested_video->request_id = $requested_video_id;
        $requested_video->requestby = $requestedby;
        $requested_video->uploadedby = $uploadedby;
        $path_parts = pathinfo($_FILES["video"]["name"]);
        $extension = isset($path_parts['extension'])?$path_parts['extension']:'mp4';
        /* Creating Random */
        $rand = rand(11111, 99999) . date('U');
        $destination = 'requested_video/';
        $fileName = $rand . '.'.$extension;
        $executionStartTime = microtime(true);
        $s3 = Storage::disk('s3');
        /* Moving Normal Video To the requested_video File */
        move_uploaded_file($_FILES["video"]["tmp_name"], $destination . $fileName);

        // which will be ex:   baseurl/public/requested_video/12345.mp4
        $destination_path = $destination . $fileName;
        multipartUpload($destination_path);
        //$s3->put($destination_path, file_get_contents($request->file('video')), 'public');
        // This for view sending message back
        $ran_token = Hash::make(rand(11111, 99999) . date('U'));

        // get purg time from the system

        $purge_data = DB::table('setting')->select('status')->where('name', '=', "purge")->first();
        $destination_path = $destination . $fileName;
        $requested_video->fileName = $fileName; // the file name 
        $requested_video->is_active = 1;
        $requested_video->Upload_date = date('Y-m-d');
        $requested_video->purchase_date = date('Y-m-d'); // this is wroing [ should be comming from requests table
        $requested_video->token = $ran_token; // This for view sending message back
        $requested_video->token_used = 0;    // This means not used
        $requested_video->remain_storage_duration = $purge_data->status;
        $requested_video->fileName = $fileName;
        $requested_video->is_active = 1;
        $requested_video->Upload_date = date('Y-m-d');
        $requested_video->purchase_date = date('Y-m-d');

        /*   Move out the original Video     */
        $orginal_video = new OriginalVideo();
        $orginal_video->video_path = $fileName; //  removed full path, file name saving to database
        $orginal_video->save();

        /* -------- Repated Code Again Start From Here To ---------------- */

        //dd('test');
        $ffmpeg = FFMpeg\FFMpeg::create(array(
                    'ffmpeg.binaries' => '/usr/bin/ffmpeg',
                    'ffprobe.binaries' => '/usr/bin/ffprobe',
                    'timeout' => 3600,
                    'ffmpeg.threads' => 12,
        ));
        $uploaded_video = $ffmpeg->open($destination . $fileName);

/*
        $ffprobe = FFMpeg\FFProbe::create(array(
                    'ffmpeg.binaries' => '/usr/bin/ffmpeg',
                    'ffprobe.binaries' => '/usr/bin/ffprobe',
                    'timeout' => 3600,
                    'ffmpeg.threads' => 12,
        ));*/

        /* ----------------------------retrieving Thumbnail------------------------------ */
        $thumbnailName = date('U') . '.jpg';
        $video_thumbnail_path = 'images/thumbnails/' . $thumbnailName;

        /* frame Comming For SECOND number 2 */
        $uploaded_video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(2))
            ->save($video_thumbnail_path);
        multipartUpload($video_thumbnail_path);
        //$s3->put($video_thumbnail_path, file_get_contents($video_thumbnail_path));
        unlink($video_thumbnail_path);

        $requested_video->thumbnail = $thumbnailName;

        /* ----------------------------Applying Watermark---------------------------------- */

        $ffmpegPath = '/usr/bin/ffmpeg';
        $inputPath = $destination . $fileName;
        //$watermark = '/home/vrl/public_html/public/vrl_logo.png';
        $watermark = public_path().'/vrl_logo.png';
        $waterMarkVideoName = $fileName;

        //$outPath = '/home/vrl/public_html/public/requested_video/watermark/' . $waterMarkVideoName;
        $outPath = 'requested_video/watermark/' . $waterMarkVideoName;
        $thumbnailVideo = 'requested_video/watermark/thumbnail/' . $waterMarkVideoName;
        //shell_exec("$ffmpegPath  -i $inputPath -i $watermark  -filter_complex \"[0:v]scale=640:360[bg];[bg][1:v]overlay=x=(W-w-20):y=(H-h-20)\" $thumbnailVideo ");
        $s3->put($thumbnailVideo, file_get_contents($destination_path));
        //multipartUpload($thumbnailVideo);
        //unlink($thumbnailVideo);

        //shell_exec("$ffmpegPath  -i $inputPath -i $watermark -filter_complex \"[1][0]scale2ref=(262/204)*ih/8:ih/8[wm][vid];[vid][wm]overlay=x=(W-w-20):y=(H-h-20)\" $outPath ");
        $s3->put($outPath, file_get_contents($destination_path));
        //multipartUpload($outPath);
        //unlink($outPath);
        //unlink($inputPath);
        //$executionEndTime = microtime(true);
        //Log::info('Upload time: '. ($executionEndTime - $executionStartTime));

        /* 	----------------------------------Saving Video------------------------------- */

        $watermark_video_destination = substr($outPath, 28);
        $requested_video->url = $fileName;
        if ($requested_video->save()) {
            // Change Type Of Conditions To be the first 
            $video_requests = Requestvideo::find($requested_video_id);
            $video_requests->RequestStatus = "Completed";
            $video_requests->save();
            /*
             * 1- Update Video Status In Payment Table
             * 2- Update Request Status in requestvideos Table
             */

            DB::table('payments')
                    ->where('video_request_id', $requested_video_id)
                    ->update(array('video_status' => 'Completed'));
            $user_detail = DB::table('users')->where('profile_id', '=', $uploadedby)->first();
            $artistTurnAroundTime = Profile::find($uploadedby)->timestamp;
            $artist_data = $data = $inputs = [];
            $data['video_name'] = $fileName;
            $data['requested_user'] = $requestValidation;
            $data['sender_name'] = $requestValidation->sender_name;
            $data['sender_email'] = $requestValidation->sender_email;
            $data['name'] = $requestValidation->Name;
            $data['requestor_email'] = $requestValidation->requestor_email;
            $data['Title'] = $requestValidation->Title;
            $data['Description'] = $requestValidation->Description;
            $data['complitionDate'] = $requestValidation->complitionDate;
            $data['artist_id'] = $user_detail->profile_id;
            $data['video_request_id'] = $requested_video_id;
            $data['current_status'] = "Completed";
            $data['songName'] = $requestValidation->song_name;
            $data['identifier'] = $data['artist_id'].'-'.$data['video_request_id'];
            $data['turnaroundTime'] =  $artistTurnAroundTime;
            // Send Mail To Sender
            $inputs['user_email'] = $user_email;
            $inputs['inputPath'] = $inputPath;
            $inputs['outPath'] = $outPath;
            $inputs['thumbnailVideo'] = $thumbnailVideo;
            $inputs['complitionDate'] = $video_requests->complitionDate;
            $inputs['requested_video_id'] = $request->requested_video_id;
            $inputs['email'] = \Auth::user()->email;
            $artist = Profile::find($uploadedby);
            $admin_data['video_name'] = $fileName;
            $admin_data['artist_name'] = $artist->Name;
            $admin_data['requested_user'] = $requestValidation;

            // Send Mail To Admin That Artist Completed A request   admin@videorequestline.com
//                           Mail::send('emails.admin_download_email', $admin_data, function ($message) use ($request) {
//                                $message->from('noreply@videorequestline.com', 'Video Upload');
//                                $message->to('admin@videorequestline.com', 'admin@videorequestline.com');
//                                $message->subject('Artist Uploaded Video To user');
//                            });


            $artist_data['requested_user'] = $requestValidation;
            $artist_data['video_name'] = $waterMarkVideoName;
            $artist_data['artist_id'] = $uploadedby;
            $artist_data['video_request_id'] = $requestValidation->VideoReqId;
            dispatch((new App\Jobs\uploadRequestedVideo($inputs, $data, $artist_data))->onQueue('uploadRequest'));
            $user_detail = DB::table('users')->where('profile_id', '=', $requestedby)->first();
            if (is_null($user_detail->push_notification) || $user_detail->push_notification == 1) {
                if ($user_detail->device_token != null) {
                    $message = 'Your requested video has been uploaded by ' . $artist->Name;
                    push_notification($user_detail->device_token, $message, 3);
                }
            }
            return ['process' => true];
        }

        return ['process' => false, 'message' => 'Request Does\'nt save correctly please try again later or contact the admin'];
        # $requested_video->save(); 
    }

    /* -----------------------Download Video------------------------------ */

    public function download_video($video) {


       // $filename =  base_path() . '/public/requested_video/watermark/' . $video;
        /*
         * Get the video name by the video ID 
         * Search for that video in the right place 
         * if exist download 
         * if not return with error message
         */

        $assetPath = Storage::disk('s3')->url('requested_video/watermark/'.$video);
        $mimetype =  Storage::disk('s3')->getDriver()->getMimetype('requested_video/watermark/'.$video);
        /*
          -- If This File not exist or has some errors
         * -- Return Back
         */
       /* if (!File::exists($filename)) {
            Session::flash('error', 'We Are Sorry Something Went Wrong Please Contact The Admin!');
            return redirect('/user_video');
        }
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false); // required for certain browsers 
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '";');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);

        exit;*/
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . basename($assetPath));
        header("Content-Type: " . $mimetype);

        return readfile($assetPath);
    }

    /* -----------------------Download Sample Video------------------------------ */

    public function download_sample_video($video) {
        $filename = base_path() . '/public/video/watermark/' . $video;
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false); // required for certain browsers 
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '";');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);



        exit;
    }

    /* -----------------------Download Requested Video------------------------------ */

    public function download_requ_video($video) {
        $filename = base_path() . '/public/requested_video/watermark/' . $video;
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false); // required for certain browsers 
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '";');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
        exit;
    }

    /* -----------------------view Video on artist dashboard------------------------- */

    public function view_video($video) {
        if (Auth::check()) {
            if (Auth::user()->type == "Artist") {
                $deliver_videos = DB::table('requested_videos')->select('*')
                        ->where('requested_videos.uploadedby', '=', Auth::user()->profile_id)
                        ->where('requested_videos.id', '=', $video)
                        ->paginate(15);

                $artist = Profile::find(Auth::user()->profile_id);



                $video_data['video'] = $deliver_videos;
                $video_data['artist'] = $artist;

                $requestedUser = User::find($video_data['video'][0]->requestby - 1);
                $requestInfo = Requestvideo::find($video_data['video'][0]->request_id);




                //dd($requestInfo);
                //dd($video_data);
                //return view('frontend.my_video',$video_data); [ requestby ]  = 1122 
                // [ Uploaded By [Refere To Profile ID on Profiles Table ]  UserID =   973
                // Request ID = 1200


                return view('frontend.artistDashboard.view_video', $video_data)->with(['requestInfo' => $requestInfo]);
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
        //return view("frontend.artistDashboard.view_video");
    }

    /* ------------------------------Edit video on artist---------------------- */

    public function edit_video($video, $reqId) {
        if (Auth::check()) {
            if (Auth::user()->type == "Artist") {
                $deliver_videos = DB::table('requested_videos')->select('*')
                        ->where('requested_videos.uploadedby', '=', Auth::user()->profile_id)
                        ->where('requested_videos.id', '=', $video)
                        ->paginate(15);

                $requestedVideos = DB::table('requestvideos')->select('*')
                        ->where('requestvideos.VideoReqId', '=', $deliver_videos[0]->request_id)
                        ->first();

                $artist = Profile::find(Auth::user()->profile_id);
                $video_data['video'] = $deliver_videos;
                $video_data['artist'] = $artist;
                $video_data['reqId'] = $reqId;


                //return view('frontend.my_video',$video_data);

                return view('frontend.artistDashboard.edit_video', $video_data)->with(['requestedVideos' => $requestedVideos, 'requestID' => $reqId]);
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
    }

    /* public function test_video(){
      $rand = rand(11111,99999).date('U');
      $ffmpegPath = '/usr/local/bin/ffmpeg';
      $inputPath = 'https://www.videorequestlive.com/uploads/7572356195768348.webm';
      $watermark = '/home/vrl/public_html/public/vrl_logo.png';
      $outPath = 'http://videorequestlive.com/requested_video/'.$rand.'.mp4';
      shell_exec("$ffmpegPath  -i $inputPath -i $watermark -filter_complex overlay=main_w-overlay_w-20:main_h-overlay_h-20 $outPath ");
      echo $rand;
      } */

    public function post_edit_video(Request $request)
    {
        $validator = Validator::make(
            array(
                'video_title' => $request->video_title,
                'video_description' => $request->video_description,
                //'video' =>$request->file('video'),
            ), array(
                'video_title' => 'required',
                'video_description' => 'required',
                //'video' => 'required|mimes:mp4,mpeg',
            )
        );
        if ($validator->fails()) {
            return redirect('/edit_video/' . $request->requested_video_id . '/' . $request->reqId)
                ->withErrors($validator)
                ->withInput();
        } else {
            //dd($_FILES['video']["name"]);
            if ($_FILES['video']["name"] != "") {
                $file = $request->file('video');
                $extension = $file->getClientOriginalExtension();
                $filename = str_replace(' ', '', $file->getClientOriginalName());
                $filename = str_replace('-', '', $filename);
                $VideoURL = "https://www.videorequestlive.com/video/" . date('U') . $filename;
                $ffmpeg = FFMpeg\FFMpeg::create(array(
                    'ffmpeg.binaries' => '/usr/bin/ffmpeg',
                    'ffprobe.binaries' => '/usr/bin/ffprobe',
                    'timeout' => 3600,
                    'ffmpeg.threads' => 12,
                ));
                $rand = rand(11111, 99999) . date('U');
                $destination = 'requested_video/';
                $fileName = $rand . '.' . $extension;
                $s3 = Storage::disk('s3');
                $request->file('video')->move($destination, $fileName);
                $destination_path = $destination . $fileName;
                //$s3->put($destination_path, file_get_contents($file), 'public');
                multipartUpload($destination_path);
                $orginal_video = new OriginalVideo();
                $orginal_video->video_path = $fileName;
                $orginal_video->save();
                $orginal_video_id = $orginal_video->id;
                $orginal_video = OriginalVideo::find($orginal_video_id);
                $uploaded_video = $ffmpeg->open($destination_path);

                /* ----------------------------retrieving Thumbnail------------------------------ */
                $thumbnailName = $rand . '.jpg';
                $video_thumbnail_path = 'images/thumbnails/' . $thumbnailName;
                $uploaded_video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(2))->save($video_thumbnail_path);
                //$s3->put($video_thumbnail_path, file_get_contents($video_thumbnail_path));
                multipartUpload($video_thumbnail_path);
                unlink($video_thumbnail_path);
                /* ----------------------------Applying Watermark---------------------------------- */
                $ffmpegPath = '/usr/bin/ffmpeg';
                $inputPath = 'requested_video/'.$orginal_video->video_path;
                $watermark = base_path() . '/public/vrl_logo.png';
                $outPath = 'requested_video/watermark/' . $fileName;
                $thumbnailVideo = 'requested_video/watermark/thumbnail/' . $fileName;
                shell_exec("$ffmpegPath  -i $inputPath -i $watermark -filter_complex \"[1][0]scale2ref=(262/204)*ih/12:ih/12[wm][vid];[vid][wm]overlay=x=(W-w-20):y=(H-h-20)\" $outPath ");
                //$s3->put($outPath, file_get_contents($outPath));
                multipartUpload($outPath);
                unlink($outPath);
                shell_exec("$ffmpegPath  -i $inputPath -i $watermark  -filter_complex \"[0:v]scale=640:360[bg];[bg][1:v]overlay=x=(W-w-20):y=(H-h-20)\" $thumbnailVideo ");
               // $s3->put($thumbnailVideo, file_get_contents($thumbnailVideo));
                multipartUpload($thumbnailVideo);
                unlink($thumbnailVideo);
                unlink($inputPath);
                /* 	----------------------------------Saving Video------------------------------- */
                $watermark_video_destination = substr($outPath, 28);
                //$video->VideoURL	 = $outPath;
                //$video->originalVideoUrl	 = $orginal_video->video_path;
                $ffprobe = FFMpeg\FFProbe::create(array(
                    'ffmpeg.binaries' => '/usr/bin/ffmpeg',
                    'ffprobe.binaries' => '/usr/bin/ffprobe',
                    'timeout' => 3600,
                    'ffmpeg.threads' => 12,
                ));
                if (DB::table('requested_videos')->
                where('id', $request->requested_video_id)->
                update([
                    'title' => $request->video_title,
                    'description' => $request->video_description,
                    'thumbnail' => $thumbnailName,
                    'url' => $fileName,
                    'fileName' => $fileName,
                    'desti_url' => $fileName,
                    'desti_thumbnail' => $thumbnailName,
                ])) {
                    DB::table('requestvideos')->
                    where('VideoReqId', $request->reqId)->
                    update(array(
                        'Title' => $request->video_title,
                        'Description' => $request->video_description,
                        'sender_name' => $request->request_senderName,
                        'sender_email' => $request->request_senderEmail,
                        'sender_name_pronunciation' => $request->request_sendePronunciation,
                        'requestor_email' => !empty($request->request_requestorEmail) ? $request->request_requestorEmail : $request->request_senderEmail,
                        'Name' => !empty($request->request_name) ? $request->request_name : $request->request_senderName,
                        'receipient_pronunciation' => $request->request_recPronunciation
                    ));

                    return redirect('/edit_video/' . $request->requested_video_id . '/' . $request->reqId)->with('success', 'Successfully Updated!');
                }
            } else {
                if (DB::table('requested_videos')->
                where('id', $request->requested_video_id)->
                update(array('title' => $request->video_title, 'description' => $request->video_description))) {
                    DB::table('requestvideos')->
                    where('VideoReqId', $request->reqId)->
                    update(array(
                        'Title' => $request->video_title,
                        'Description' => $request->video_description,
                        'sender_name' => $request->request_senderName,
                        'sender_email' => $request->request_senderEmail,
                        'sender_name_pronunciation' => $request->request_sendePronunciation,
                        'requestor_email' => !empty($request->request_requestorEmail) ? $request->request_requestorEmail : $request->request_senderEmail,
                        'Name' => !empty($request->request_name) ? $request->request_name : $request->request_senderName,
                        'receipient_pronunciation' => $request->request_recPronunciation
                    ));
                    return redirect('/edit_video/' . $request->requested_video_id . '/' . $request->reqId)->with('success', 'Successfully Updated!');
                }
            }
        }
    }

    /* -----------------------Artist Record Video------------------------------ */

    public function record_video() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $user = User::where('email', Auth::user()->email)->first();
                //dd($user);
                $profileData = Profile::where('EmailId', Auth::user()->email)->first();
                $artistData['profileData'] = $profileData;
                $artistData['baseurl'] = "https://videorequestlive.com/";
                $artistData['user'] = $user;
                return view("frontend.artistDashboard.record_video", $artistData);
            }
        } else {

            return redirect('/');
        }
    }

    public function record_own_video(Request $request) {
        $data = $request->all();
        $validator = Validator::make(
                        array(
                    'video_title' => $request->video_title,
                    'video_description' => $request->video_description,
                    'video' => $request->file('video'),
                        ), array(
                    'video_title' => 'required',
                    //'video_title' =>'required|unique:video,Title',
                    //'video_description' =>'required|min:80',
                    'video_description' => 'required',
                    'video' => 'required|mimes:mp4,mpeg,webm,mkv,flv,vob,ogv,ogg,drc,avi,mov,qt,wmv,yuv,asf,amv,m4p,m4v,mpg,mp2,mpe,mpv,m2v,svi,3gp,3g2,mxf,roq,nsv,flv,f4v,f4p,f4a,f4b',
                        )
        );
        if ($validator->fails()) {
            return redirect('record_video')
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $executionStartTime = microtime(true);
            $video = new Video();
            $file = $request->file('video');
            $extension = $file->getClientOriginalExtension();
            $filename = str_replace(' ', '', $file->getClientOriginalName());
            $filename = str_replace('-', '', $filename);
            $video->VideoFormat = $file->getClientOriginalExtension();
            $video->VideoSize = ($file->getSize() / 1024) . "mb";
            //$video->VideoPrice = $request->video_price;
            $video->Description = $request->video_description;
            $video->Title = $request->video_title;
            $video->VideoUploadDate = Carbon::now()->format('m-d-Y');
            $video->ProfileId = Auth::user()->profile_id;
            $video->UploadedBy = "Artist";
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
            /* --------------------------Opening Uploaded Video------------------------------ */
            $rand = rand(11111, 99999) . date('U');
            //$destination = '/home/vrl/public_html/public/video/original/';
            //$destination = '/home/vrl/public_html/public/video/original/';
            $destination = 'video/original/';
            $fileName = $rand . '.' . $extension;
            $request->file('video')->move($destination, $fileName);
            $destination_path = $destination . $fileName;
            $s3 = Storage::disk('s3');
            //$s3->put($destination_path, file_get_contents($file), 'public');
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
            /* -------------------------retrieving Video Duration---------------------------- */
            $video_length = $ffprobe->streams($destination_path)
                    ->videos()
                    ->first()
                    ->get('duration');

            if($video_length < 15){
                unlink($destination . $fileName);
                //$s3->delete($destination . $fileName);
                return redirect('record_video')->with('error', 'Video duration must be of 15 seconds');
            } else {
                $file = date('U');
                /* ----------------------------retrieving Thumbnail------------------------------ */
                $thumbnailName = $file . '.jpg';
                $video_thumbnail_path = 'images/thumbnails/' . $thumbnailName;
                $uploaded_video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(2))->save($video_thumbnail_path);
                //$s3->put($video_thumbnail_path, file_get_contents($video_thumbnail_path));
                //unlink($video_thumbnail_path);
                $video->VideoThumbnail = $thumbnailName;
                /* ----------------------------Applying Watermark---------------------------------- */
                //$ffmpegPath = '/usr/bin/ffmpeg';
                $inputPath = $destination. $orginal_video->video_path;
                //$watermark = '/home/vrl/public_html/public/vrl_logo.png';
                //$watermark = base_path() . '/public/vrl_logo.png';
                //$outPath = '/home/vrl/public_html/public/video/watermark/' . date('U') . '.mp4';
                $waterhMark = $file .'.'. $extension;
                $outPath = 'video/watermark/' . $waterhMark;
                $thumbnailVideo = 'video/watermark/thumbnail/' . $waterhMark;
                //shell_exec("$ffmpegPath  -i $inputPath -i $watermark -filter_complex \"[1][0]scale2ref=(262/204)*ih/12:ih/12[wm][vid];[vid][wm]overlay=x=(W-w-20):y=(H-h-20)\" $outPath ");
                $s3->put($outPath, file_get_contents($destination_path));
                //unlink($outPath);
                //shell_exec("$ffmpegPath  -i $inputPath -i $watermark  -filter_complex \"[0:v]scale=640:360[bg];[bg][1:v]overlay=x=(W-w-20):y=(H-h-20)\" $thumbnailVideo ");
                $s3->put($thumbnailVideo, file_get_contents($destination_path));
                //unlink($thumbnailVideo);

                /* 	----------------------------------Saving Video------------------------------- */
                $watermark_video_destination = substr($outPath, 28);
                $video->VideoURL = $waterhMark;
                $video->originalVideoUrl = $fileName;
                $ffprobe = FFMpeg\FFProbe::create(array(
                            'ffmpeg.binaries' => '/usr/bin/ffmpeg',
                            'ffprobe.binaries' => '/usr/bin/ffprobe',
                            'timeout' => 3600,
                            'ffmpeg.threads' => 12,
                ));
                /* -------------------------retrieving Video Duration---------------------------- */
                $video->VideoLength = $ffprobe->streams($destination_path)
                        ->videos()
                        ->first()
                        ->get('duration');
                $executionEndTime = microtime(true);
                Log::info('internal upload time: '. ($executionEndTime - $executionStartTime));
                $executionStartTime = microtime(true);
                multipartUpload($video_thumbnail_path);
                $executionEndTime = microtime(true);
                Log::info('s3 thumbnail image: '. ($executionEndTime - $executionStartTime));
                $executionStartTime = microtime(true);
                multipartUpload($inputPath);
                $executionEndTime = microtime(true);
                Log::info('s3 upload: '. ($executionEndTime - $executionStartTime));
                $executionStartTime = microtime(true);
                //multipartUpload($thumbnailVideo);
                $executionEndTime = microtime(true);
                Log::info('s3 watermark thumbnail upload: '. ($executionEndTime - $executionStartTime));
                $executionStartTime = microtime(true);
                //multipartUpload($outPath);
                $executionEndTime = microtime(true);

                //unlink($thumbnailVideo);
                //unlink($outPath);
                unlink($video_thumbnail_path);
                $inputs = [];
                $inputs['inputPath'] = $inputPath;
                Log::info($inputPath);
                $inputs['outPath'] = $outPath;
                $inputs['thumbnailVideo'] = $thumbnailVideo;
                dispatch((new App\Jobs\uploadSampleWatermarkVideo($inputs))->onQueue('sample'));
                //unlink($inputPath);
                // $destinationPath = base_path() . '/public/video/';
                // $request->file('video')->move($destinationPath, "video/".date('U') .$filename);
                if ($video->save()) {
                    return redirect('record_video')->with('success', 'Successfully Uploaded!');
                }
            }
        }
    }

    /* -----------------------------------Sold video List------------------------------------- */

    public function sold_videos() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $user = User::where('email', Auth::user()->email)->first();
                $sold_videos = DB::table('video')->select('video.*', 'payments.*')
                                ->join('payments', 'payments.video_id', '=', 'video.VideoId')->get();
                $sold_videos = DB::table('video')->select('video.*', 'payments.*')->where('ProfileId', '=', $user->profile_id)
                                ->join('payments', function($join) {
                                    $join->on('video.VideoId', '=', 'payments.video_id');
                                })->get();
                $image_path = DB::table('profiles')->where('EmailId', $user->email)->first();
                $artist_data['users'] = $user;
                $artist_data['sold_videos'] = $sold_videos;
                $artist_data['image_paths'] = $image_path;
                return view('frontend.artistDashboard.sold_video', $artist_data);
            }
        } else {
            return redirect('/');
        }
    }

    /* --------------------------------Artist Log Out-------------------------------- */

    public function getLogout() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                Session::flush();
                Auth::logout();
                return redirect('/admin')->with('success', 'Successfully Signout');
            } elseif (Auth::user()->type == "Admin") {
                Session::flush();
                Auth::logout();
                return redirect('/admin')->with('success', 'Successfully Signout');
            } elseif (Auth::user()->type == "User") {
                Session::flush();
                Auth::logout();
                return redirect('/');
            } 
            else {
                Session::flush();
                Auth::logout();
                return redirect(url()->previous());
            }
        } else {
            Session::flush();
            Auth::logout();
            return redirect('/');
        }
    }

    public function videoDetails(Request $request) {
        $data = DB::table('video')->where('VideoId', $request->id)->get();
        return view('frontend.VideoDetails')->with('detail', $data);
    }

//
    public function artist_search(Request $request) {
        $search_query = $request->search_query;
        if (!is_null($search_query)) {
            $search_result = User::where('user_name', 'LIKE', '%' . $search_query . '%')
                            ->where('type', '=', 'Artist')->where('is_account_active', '1')->get();
            if (!is_null($search_result)) {
                $pageData['search_result'] = $search_result;
                return view('frontend.artist_search', $pageData);
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
    }

    public function search_video(Request $request) {
        $search_query = $request->search_query;
        $search_video = Video::where('Title', 'LIKE', '%' . $search_query . '%')->get();

        $pageData['search_video'] = $search_video;
        return view('frontend.search_video', $pageData);
    }

    public function search_result(Request $request) {
        $search_query = $request->search_query;

        /*$search_result = DB::table('users')
                        ->select('users.*', 'profiles.*')
                        ->where('Name', 'LIKE', '%' . $search_query . '%')
                        ->where('users.is_account_active', '=', '1')
                        ->where('users.type', '=', 'Artist')
                        ->join('profiles', 'profiles.ProfileId', '=', 'users.profile_id')->paginate(5);*/


        $search_video = DB::table('users')
                ->select('users.*', 'video.*')
                ->where('Title', 'LIKE', '%' . $search_query . '%')
                ->where('users.is_account_active', '=', '1')
                //->where('users.type', '=','Artist')
                ->join('video', 'video.ProfileId', '=', 'users.profile_id')
                ->orderByRaw("RAND()")
                ->orderBy('ProfileId', 'desc')
                ->take(7)
                ->get();


        /*$random_artist = DB::table('users')
                ->select('users.*', 'profiles.*')
                ->where('users.is_account_active', '=', '1')
                ->where('users.type', '=', 'Artist')
                ->join('profiles', 'profiles.ProfileId', '=', 'users.profile_id')
                ->orderByRaw("RAND()")
                ->orderBy('ProfileId', 'desc')
                ->take(7)
                ->get();*/

        $random_video=DB::table('requested_videos')->where('requestby',Auth::user()->profile_id)->get();
        /*$random_video = DB::table('video')
                        ->select('video.*', 'users.*')
                        ->orderByRaw("RAND()")
                        ->orderBy('VideoId', 'desc')
                        ->join('users', 'users.profile_id', '=', 'video.ProfileId')
                        ->where('users.is_account_active', '=', '1')
                        ->take(7)->get();*/


        //$pageData['search_result'] = $search_result;
        $pageData['search_video'] = $search_video;
        $pageData['random_video'] = $random_video;
        //$pageData['random_artist'] = $random_artist;


        return view('frontend.search', $pageData);
    }

    public function get_social_link() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $user = User::where('email', Auth::user()->email)->first();

                $profileData = Profile::where('EmailId', Auth::user()->email)->first();
                $social_data = DB::table('social_media')->where('addBy_profileId', Auth::user()->profile_id)->orderBy('id', 'desc')->get();
                $artistData['user'] = $user;
                $artistData['profileData'] = $profileData;
                $artistData['social_data'] = $social_data;
                $artistData['baseurl'] = "https://videorequestlive.com/";
                return view('frontend.artistDashboard.get_social_link', $artistData);
            }
        } else {
            return redirect('/');
        }
    }

    public function add_social_link(Request $request) {
        $data = $request->all();
        //dd($data);
        $validator = Validator::make($data, array(
                    'facebook_link' => 'required',
                    'twitter_link' => 'required',
                    'instagram_link' => 'required',
                        )
        );
        if ($validator->fails()) {
            return redirect('get_social_link')
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $artist = Profile::find($request->ProfileId);
            $artist->facebook_link = $request->facebook_link;
            $artist->instagram_link = $request->instagram_link;
            $artist->twitter_link = $request->twitter_link;
            $artist->more_link = $request->twitter_link;

            if ($artist->save()) {
                return redirect('get_social_link')->with('success', 'Successfully Updated!');
            }
        }
    }

    public function get_edit_url() {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $user = User::where('email', Auth::user()->email)->first();
                $profileData = Profile::where('EmailId', Auth::user()->email)->first();
                $artistData['user'] = $user;
                $artistData['profileData'] = $profileData;
                $artistData['baseurl'] = "https://videorequestlive.com/";
                return view('frontend.artistDashboard.edit_url', $artistData);
            }
        } else {
            return redirect('/');
        }
    }

    public function update_url(Request $request) {
        if (!Auth::check()) {
            return redirect('/');
        } else {
            $data = $request->all();
            $validator = Validator::make($data, array(
                        'profile_url' => 'required|alpha_num|unique:profiles,profile_url',
                            )
            );
            if ($validator->fails()) {
                return redirect('edit_url')
                                ->withErrors($validator)
                                ->withInput();
            } else {
                $artist = Profile::find($request->ProfileId);
                $artist->profile_url = $request->profile_url;
                if ($artist->save()) {
                    return redirect('edit_url')->with('success', 'Successfully Updated!');
                }
            }
        }
    }

    /* --------------------------------Request New Video--------------------------------- */

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function requestvideo(Request $request)
    {
        $data = $request->all();
        $messages = [
            //'user_name.required' => \Lang::get('messages.required_recipient_name'),
            'user_email.required' => \Lang::get('messages.required_recipient_email'),
            'user_email.email' => \Lang::get('messages.invalid_recipient_email'),
            'person_message.required' => \Lang::get('messages.person_message'),
            'sender_email.different' => \Lang::get('messages.sender_receiver_email_error'),
            'required' => ':attribute field is required',
            'delivery_date.after' => \Lang::get('messages.invalid_delivery_date'),
            'max' => 'Maximum 50 characters allowed',
            'person_message.max' => 'Maximum 200 characters allowed',
            'song_name.max' => 'Maximum 70 characters allowed',
        ];
        $validator = Validator::make($data, array(
            'artist' => 'required',
            'user_name' => 'max:50',
            'user_email' => 'email',
            'sender_name' => 'required|max:50',
            'sender_email' => 'required|email',
            'song_name' => 'max:70',
            //'Occassion' => 'required',
            'person_message' => 'required|max:200',
            'delivery_date' => 'required|after:yesterday',
        ), $messages);

        if ($validator->fails()) {
            return ['proccess' => "false", "errors" => $validator->errors()];
        } else {
            $is_artists = User::whereProfileId($request->artist)->first();
            $is_artist_video = Profile::where('ProfileId', $request->artist)->first();
            $is_sender_artist = User::whereEmail($request->sender_email)->whereType('Artist')->first();
            $is_receipent_artist = User::whereEmail($request->user_email)->whereType('Artist')->first();
            $get_timestamp = Profile::where('ProfileId', $request->artist)->first();
            $now = new \DateTime();
            $date1 = date('Y-m-d H:i:s',strtotime($request->delivery_date));
            $date1 = new \DateTime($date1);
            $diff = date_diff($date1, $now);
            $diff_date = $diff->format("%a");
            $timesp = $get_timestamp->timestamp;
            $diff_date = $diff_date + 1;
            if ($diff_date < $timesp) {
                return ['proccess' => "false", "errors" =>
                    ['delivery_date' => \Lang::get('messages.invalid_delivery_date')]];
            } elseif ($is_artists ==null || $is_artists->is_account_active == 0) {
                return ['proccess' => "false", "errors" =>
                    ['artist' => \Lang::get('messages.artist_not_active_or_found')]];
                if ($pos !== false) {
                    return redirect('RequestNewVideo/' . $request->artist)
                        ->withErrors($validator)
                        ->withInput();
                } else {
                    return redirect()
                        ->back()
                        ->with('error', \Lang::get('messages.artist_not_active_or_found'))->withInput();
                }
            } else if ($is_sender_artist!= null) {
                return ['proccess' => "false", "errors" =>
                    ['sender_email' => \Lang::get('messages.sender_email_registered_as_artist')]];
            } else if ($is_receipent_artist!=null)   {
                return ['proccess' => "false", "errors" =>
                    ['user_email' => \Lang::get('messages.recipient_email_registered_as_artist')]];
            } else {
                $rand = rand(11111, 99999) . date('U');
                $s3 = Storage::disk('s3');
                $destination = 'usersRecords/';
                if ($request->hasFile('recipient-record')) {
                    $recipientPronunciation = $rand . 'recipient' . '.wav';
                    //$s3->put($destination.$recipientPronunciation, file_get_contents($request->file('recipient-record')));
                    $request->file('recipient-record')->move($destination, $recipientPronunciation);
                    multipartUpload($destination.$recipientPronunciation);
                    unlink($destination.$recipientPronunciation);
                    Session::put('recipient-record', $recipientPronunciation);
                }
                if ($request->hasFile('sender-record')) {
                    $senderPronunciation = $rand . 'sender' . '.wav';
                    //$s3->put($destination.$senderPronunciation, file_get_contents($request->file('sender-record')));
                    $request->file('sender-record')->move($destination, $senderPronunciation);
                    multipartUpload($destination.$senderPronunciation);
                    unlink($destination.$senderPronunciation);
                    Session::put('sender-record', $senderPronunciation);
                }
                Session::put('post_user_id', $request->user_id);
                Session::put('post_myusername', $request->user_name);
                Session::put('post_artist', $request->artist);
                Session::put('post_song_name', $request->song_name);
                Session::put('post_pronun_name', $request->pronun_name);
                Session::put('post_useremail', $request->user_email);
                Session::put('post_password', $request->password);
                Session::put('post_recei_email', $request->recei_email);
                Session::put('post_sender_name', $request->sender_name);
                Session::put('post_sender_name_pronun', $request->sender_name_pronun);
                Session::put('post_sender_email', $request->sender_email);
                Session::put('post_delivery_date', $request->delivery_date);
                Session::put('post_Occassion', $request->Occassion);
                Session::put('post_person_message', $request->person_message);
                Session::put('post_video_price', $request->video_price);
                Session::put('post_artist_id', $request->artist);
                Session::put('post_phone', $request->phone);
                Session::put('post_artist_name', $request->artist_name);
                Session::put('post_occasion_id', $request->occasion_id);
                Session::put('is_hidden', $request->is_hidden);
                return ['proccess' => "success"];
            }
        }
    }

    public function check_user_auth() {
        $email = Session::get('email');
        $password = Session::get('password');
        $user = array('email' => $email);
        $result = \DB::table('users')->where($user)->first();
        if (!is_null($result)) {
            if ((Hash::check($password, $result->password)) && ($result->is_account_active == 1)) {
                echo "present";
            } else {
                echo "false";
            }
        }
    }

    public function move_file() {
        $source = "source/";
        $destination = "destination/";
        $file = "UserVideo.blade.php";
        if (copy($source . $file, $destination . $file)) {
            $delete[] = $source . $file;
        }
        foreach ($delete as $file) {
            unlink($file);
        }
    }
    
}
