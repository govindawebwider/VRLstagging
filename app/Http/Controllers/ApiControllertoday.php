<?php
namespace App\Http\Controllers;

/**
 * @package App/Http/Controllers
 * added some functions in existing code by Azim Khan
 * @class ApiController
 *
 * @author Azim Khan <azim@surmountsoft.com>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

use Illuminate\Support\Facades\Storage;
use Log;
use App\FooterContent;
use App\ArtistCategory;
use App\Category;
use App\Payment;
use App\ReactionVideo;
use App\Review;
use App\SocialMedia;
use App\Testimonial;
use Auth;
use App\RequestedVideo;
use App\User;
use App\Video;
use App\OriginalVideo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Input;
use DB;
use App\Http\Requests;
use Hash;
use ReCaptcha\RequestMethod\Post;
use Validator;
use Mail;
use App\Profile;
use App\Occasion;
use App\Requestvideo;
use FFMpeg;
use Session;
use Crypt;
use Carbon\Carbon;
use App\helper;
use Exception;
use Snipe\BanBuilder\CensorWords;
use Intervention\Image\ImageManager;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Config;

class ApiController extends Controller
{
    public function changePassword(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
            'new_password.regex' => ' Use at least one letter, one numeral & one special character',
            'new_password.min' => ' New password should be at least 8 characters ',
            'confirm_password.min' => ' Confirm password should be at least 8 characters',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'profile_id' => 'required',
            'new_password' => 'required|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/',
            'confirm_password' => 'required|min:8|same:new_password'
        ], $messages
        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::whereProfileId($request->profile_id)
            ->whereAccessToken($request->access_token)
            ->first();
        if (is_null($user)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        $user = User::find($user->user_id);
        if (Hash::check($request->old_password, $user->getAuthPassword())) {
            $user->password = Hash::make($request->new_password);
            $user->mobile_login_count = 0;
            $user->access_token = null;
            if ($user->save()) {
                return response()->json(null, 200);
            }
        } else {
            return response()->json(\Lang::get('messages.invalid_password'), 400);
        }
    }

    public function test()
    {
        $videoDetails = DB::table('video')->select('*')
            ->join('profiles', 'profiles.ProfileId', '=', 'video.ProfileId')
            ->get();
        if ($videoDetails != null) {
            return json_encode($videoDetails);
        } else {
            $response = array('return_message' => "No video found");
            return json_encode($response);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ahptText(Request $request)
    {
        if (!isset($request->access_token)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        } else {
            $user = User::whereAccessToken($request->access_token)->first();
            if (is_null($user)) {
                return response()->json(\Lang::get('messages.unauthorized_access'), 401);
            }
        }
        $apt = FooterContent::whereType($request->type)->first();
        if (!is_null($apt)) {
            $apt = str_replace('&#39;', "'",
                str_replace('&nbsp;', ' ', trim($apt->content)));
        }
        return response()->json(['ahpt' => $apt], 200);
    }

    /*---------------------All video api----------------------*/
    public function view_allVideo()
    {
        $videoDetails = DB::table('video')->select('*')
            ->join('profiles', 'profiles.ProfileId', '=', 'video.ProfileId')
            ->join('users', function ($join) {
                $join->on('users.profile_id', '=', 'profiles.ProfileId')
                    ->where('users.is_account_active', '=', '1');
            })
            ->orderBy('VideoId', 'desc')
            ->get();
        if ($videoDetails != null) {
            return json_encode($videoDetails);
        } else {
            $response = array('return_message' => "No video found");
            return json_encode($response);
        }
    }

    /*
    * API for showing all Chart Toppers
    */

    public function list_chart_toppers(Request $request)
    {
        if (!isset($request->access_token)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        } else {
            $user = User::whereAccessToken($request->access_token)->first();
            if (is_null($user)) {
                return response()->json(\Lang::get('messages.unauthorized_access'), 401);
            }
        }

        $artists = DB::table('profiles')->select('profiles.*','users.*')
            ->join('users',function($join){
             $join->on('profiles.ProfileId', '=', 'users.profile_id')
             ->where('users.type', '=','Artist')
             ->where('users.is_chart_topper', '=','1')
             ->where('users.is_account_active', '=','1');
        })->get();
        $s3 = Storage::disk('s3');
        if (!is_null($artists)) {
            foreach ($artists as $key => $artist) {
                if (!empty($artist->profile_path)) {
                    $artist->profile_path = $s3->url('images/Artist/'. $artist->profile_path);
                } else {
                    $artist->profile_path = null;
                }
            }
            return response()->json(['artists' => $artists], 200);
        } else {
            return response()->json(null, 404);
        }
    }

    /*
    * API for showing all latest video
    */

    public function view_latestVideo()
    {
        $videoDetails = DB::table('video')->select('*')
            ->join('profiles', 'profiles.ProfileId', '=', 'video.ProfileId')
            ->join('users', function ($join) {
                $join->on('users.profile_id', '=', 'profiles.ProfileId')
                    ->where('users.is_account_active', '=', '1');
            })
            ->orderBy('VideoId', 'desc')
            ->get();
        if ($videoDetails != null) {
            return json_encode($videoDetails);
        } else {
            $response = array('return_message' => "No video found");
            return json_encode($response);
        }
    }

    /*
    * API for insert featured video of artist
    */
    public function ins_featuredVideo(Request $request)
    {

        $video = new Video();
        $file = $request->file('video');
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace(' ', '', $file->getClientOriginalName());
        $filename = str_replace('-', '', $filename);
        //$VideoURL = "https://www.videorequestline.com/video/".date('U') .$filename ;
        $video->VideoFormat = $file->getClientOriginalExtension();
        $video->VideoSize = ($file->getSize() / 1024) . "mb";
        $video->Description = $request->video_description;
        $video->Title = $request->video_title;
        $video->VideoUploadDate = Carbon::now();
        $video->ProfileId = $request->profile_id;
        $video->UploadedBy = "Artist";
        $ffmpeg = FFMpeg\FFMpeg::create(array(
            'ffmpeg.binaries' => '/usr/local/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/local/bin/ffprobe',
            'timeout' => 3600,
            'ffmpeg.threads' => 12,
        ));
        /*--------------------------Opening Uploaded Video------------------------------*/
        $rand = rand(11111, 99999) . date('U');
        $destination =  'video/original/';
        $fileName = $rand . '.' . $extension;
        $request->file('video')->move($destination, $fileName);

        $destination_path = $destination . $fileName;
        $s3 = Storage::disk('s3');
        //$s3->put($destination_path, file_get_contents($file), 'public');
        multipartUpload($destination_path);
        $orginal_video = new OriginalVideo();
        $orginal_video->video_path = $destination_path;
        $orginal_video->save();
        $orginal_video_id = $orginal_video->id;
        $orginal_video = OriginalVideo::find($orginal_video_id);
        $uploaded_video = $ffmpeg->open('video/original/'.$orginal_video->video_path);
        $ffprobe = FFMpeg\FFProbe::create(array(
            'ffmpeg.binaries' => '/usr/local/ffmpeg',
            'ffprobe.binaries' => '/usr/local/bin/ffprobe',
            'timeout' => 3600,

            'ffmpeg.threads' => 12,
        ));
        /*--------------------retrieving Video Duration--------------------*/
        $video_length = $ffprobe->streams($destination . $fileName)
            ->videos()
            ->first()
            ->get('duration');

        if ($video_length < 15) {
            $message = "Video duration must be above 15 seconds";
            $response = array('return_message' => $message);
            return json_encode($response);
        } else {
            /*----------------------------retrieving Thumbnail------------------------------*/
            $video_thumbnail_path = 'images/thumbnails/' . date('U') . '.jpg';
            $uploaded_video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(2))->save($video_thumbnail_path);
            //$s3->put($video_thumbnail_path, file_get_contents($video_thumbnail_path));
            multipartUpload($video_thumbnail_path);
            unlink($video_thumbnail_path);
            $video->VideoThumbnail = $video_thumbnail_path;
            /*----------------------------Applying Watermark----------------------------------*/
            $ffmpegPath = '/usr/bin/ffmpeg';
            $inputPath = $destination . $fileName;
            $watermark = base_path() . '/public/vrl_logo.png';
            $outPath = 'video/watermark/' . date('U') . '.mp4';
            $thumbnailVideo = 'video/watermark/thumbnail/' . date('U') . '.mp4';
            shell_exec("$ffmpegPath  -i $inputPath -i $watermark -filter_complex \"[1][0]scale2ref=(262/204)*ih/12:ih/12[wm][vid];[vid][wm]overlay=x=(W-w-20):y=(H-h-20)\" $outPath ");
            //$s3->put($outPath, file_get_contents($outPath));
            multipartUpload($outPath);
            unlink($outPath);
            shell_exec("$ffmpegPath  -i $inputPath -i $watermark  -filter_complex \"[0:v]scale=640:360[bg];[bg][1:v]overlay=x=(W-w-20):y=(H-h-20)\" $thumbnailVideo ");
            //$s3->put($thumbnailVideo, file_get_contents($thumbnailVideo));
            multipartUpload($thumbnailVideo);
            unlink($thumbnailVideo);
            unlink($inputPath);

            /*  ----------------------------------Saving Video-------------------------------*/
            $watermark_video_destination = substr($outPath, 28);
            $video->VideoURL = $outPath;
            $video->originalVideoUrl = $orginal_video->video_path;
            $ffprobe = FFMpeg\FFProbe::create(array(
                'ffmpeg.binaries' => '/usr/bin/ffmpeg',
                'ffprobe.binaries' => '/usr/bin/ffprobe',
                'timeout' => 3600,
                'ffmpeg.threads' => 12,
            ));
            /*-------------------------retrieving Video Duration----------------------------*/
            $video->VideoLength = $ffprobe->streams($s3->url('video/original/'.$orginal_video->video_path))
                ->videos()
                ->first()
                ->get('duration');

            if ($video->save()) {

                $response = array('return_message' => "Submitted Successfully");
                return json_encode($response);
            }
        }

    }

    /*
    * API for show testimonial by profile id
    */
    public function show_testimonial(Request $request)
    {
        /*$testimonial_data = DB::select("SELECT testimonials.*,profiles.Name FROM `testimonials` left JOIN profiles on profiles.ProfileId=testimonials.to_profile_id  WHERE `to_profile_id`=$request->profileId  and AdminApproval= 1 ");
        return json_encode($testimonial_data);*/

        $testimonial_data = DB::table('testimonials')
            ->join('profiles', 'profiles.ProfileId', '=', 'testimonials.to_profile_id')
            ->select('testimonials.*', 'profiles.Name')
            ->where('testimonials.to_profile_id', '=', $request->profileId)
            ->get();
        return json_encode($testimonial_data);
    }

    /*
    * API for show testimonial by profile id
    */
    public function insert_testimonial(Request $request)
    {
        $censor = new CensorWords;
        $string = $censor->censorString($request->message);
        $user = \App\Profile::find($request->by_profile_id);
        $values = array('user_name' => $user->Name, 'to_profile_id' => $request->to_profile_id, 'Message' => $string['clean'], 'Email' => $user->EmailId, 'AdminApproval' => 0);
        if (DB::table('testimonials')->insert($values)) {
            $response = array('return_message' => "Submitted Successfully");
            return json_encode($response);
        } else {
            $response = array('return_message' => "Not Submitted Successfully");
            return json_encode($response);
        }
    }

    /*
    * API for upload address proof id picture
    */
    public function upload_bank_id_pic(Request $request)
    {
        $imageName = $request->randomFilename;
        $updateType = $request->update;
        $fileExtension = $request->extension;
        $target = "images" . '/' . 'Artist' . '/' . 'address_proof_pic' . '/' . str_replace(" ", "", $_POST['randomFilename']);
        if (move_uploaded_file($_FILES['media']['tmp_name'], $target)) {
            $filename = $target;
            list($current_width, $current_height) = getimagesize($filename);
            $left = 0;
            $top = 0;
            $crop_width = $current_width;
            $crop_height = $current_height;
            $canvas = imagecreatetruecolor($crop_width, $crop_height);
            $current_image = imagecreatefromjpeg($filename);
            imagecopy($canvas, $current_image, 0, 0, $left, $top, $current_width, $current_height);
            imagejpeg($canvas, $filename, 100);
            $msg = $target;
            $profilePath = $msg;
            if (Profile::where('ProfileId', $request->profileId)->update(array('id_pic' => $profilePath))) {
                $response = array('return_message' => "Updated successfully");
                return json_encode($response);
            } else {
                $response = array('return_message' => "Not Updated successfully");
                return json_encode($response);
            }
        }
    }

    /*
    * API for delete request
    */
    public function delete_request(Request $request)
    {
        if (DB::table('requestvideos')->where('VideoReqId', '=', $request->VideoReqId)->delete()) {
            $response = array('return_message' => "Deleted Successfully");
            return json_encode($response);
        } else {
            $response = array('return_message' => "Not Deleted Successfully");
            return json_encode($response);
        }
    }

    /*
    * API for Search by name and type
    */
    public function Add_BankDtl(Request $request)
    {
        if (Profile::where('ProfileId', $request->profileId)->update(array('account_number' => $request->account_number, 'routing_number' => $request->routing_number, 'ssn_number' => $request->ssn_number, 'id_pic' => $request->id_pic, 'pin' => $request->pin, "is_bank_updated" => 1))) {
            $response = array('return_message' => "Added Successfully");
            return json_encode($response);
        } else {
            $response = array('return_message' => "Not Added Successfully");
            return json_encode($response);
        }

    }

    /**
     * API for Search artists by Name from profiles table
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'offset' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::whereAccessToken($request->access_token)->first();
        if (is_null($user)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        $artists = DB::table('users as u')
            ->select('p.ProfileId', 'p.Name', 'p.profile_path', 'p.NickName', 'p.VideoPrice', 'p.timestamp')
            ->where('p.Type', '=', 'Artist')
            ->where('p.Name', 'LIKE', "%{$request->Name}%")
            ->where('u.is_account_active', '=', '1')
            ->join('profiles as p', 'p.ProfileId', '=', 'u.profile_id')
            ->offset($request->offset)
            ->limit(10)
            ->get();
        if (count($artists) > 0) {
            $s3 = Storage::disk('s3');
            foreach ($artists as $key => $artist) {
                $category = ArtistCategory::whereProfileId($artist->ProfileId)
                    ->whereMainCategory(1)->first();
                $artist->category = null;
                if (!is_null($category)) {
                    $mainCategory = Category::whereId($category->category_id)->first();
                    if (!is_null($category)) {
                        $artist->category = !is_null($mainCategory) ? $mainCategory->title : null;
                    }
                }
                $artist->profile_path =
                    !empty($artist->profile_path) ? $s3->url('images/Artist/'.$artist->profile_path) :
                        $artist->profile_path = null;
            }

            return response()->json(['artist' => $artists], 200);
        } else {
            return response()->json(null, 404);
        }
    }

    /**
     * API, returns active artist list
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ArtistList(Request $request)
    {
        if (!isset($request->access_token)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        } else {
            $user = User::whereAccessToken($request->access_token)->first();
            if (is_null($user)) {
                return response()->json(\Lang::get('messages.unauthorized_access'), 401);
            }
        }
        if (!isset($request->category_id)) {
            $artists = DB::table('users as u')->select('p.ProfileId', 'p.Name', 'p.profile_path', 'p.NickName',
                'p.VideoPrice')
                ->join('profiles as p', function ($join) {
                    $join->on('p.ProfileId', '=', 'u.profile_id');
                })
                ->where('u.is_account_active', '=', 1)
                ->where('p.Type', '=', 'Artist')
                ->orderBy('p.Name', 'ASC')
                ->offset($request->offset)
                ->limit(10)->get();
        } else {
            $artists = DB::table('category')->select('p.ProfileId', 'p.Name', 'p.profile_path', 'p.NickName',
                'p.VideoPrice')
                ->distinct('ac.profile_id')
                ->where('category.id', '=', $request->category_id)
                ->where('category.status', 1)
                ->join('artist_category as ac', function ($join) {
                    $join->on('ac.category_id', '=', 'category.id');
                })
                ->join('profiles as p', function ($join) {
                    $join->on('p.ProfileId', '=', 'ac.profile_id');
                })
                ->join('users', function ($join) {
                    $join->on('users.profile_id', '=', 'ac.profile_id')
                        ->where('users.type', '=', 'Artist')
                        ->where('users.is_account_active', '=', 1);
                })
                ->offset($request->offset)
                ->orderBy('p.Name', 'ASC')
                ->limit(10)->get();
        }
        $s3 = Storage::disk('s3');
        foreach ($artists as $key => $artist) {
            $category = ArtistCategory::whereProfileId($artist->ProfileId)
                ->whereMainCategory(1)->with('category')->first();
            if (!is_null($category)) {
                $artist->category = !is_null($category->category) ? $category->category->title : null;
            } else {
                $artist->category = null;
            }
            $artist->profile_path =
                !empty($artist->profile_path) ? $s3->url('images/Artist/'.$artist->profile_path) : null;
        }
        if (count($artists) > 0) {
            return response()->json(['artist' => $artists], 200);
        } else {
            return response()->json(null, 404);
        }
    }

    /*
    * API for Profile Details
    */
    public function ProfileList(Request $request)
    {
        $users = DB::table('profiles')->where('ProfileId', $request->ProfileId)->select('ProfileId', 'Name', 'EmailId', 'Address', 'City', 'State', 'PaypalId', 'RegisterDate', 'LastLogin', 'Zip', 'profile_path', 'profile_path_Mobile', 'BannerImg', 'BannerImg_Mobile', 'NickName', 'Gender', 'DateOfBirth', 'MobileNo', 'profile_description', 'Type', 'country', 'created_at', 'VideoPrice', 'timestamp')->get();
        if (empty($users)) {
            $response = array('return_message' => "Not Found");
            return json_encode($response);

        } else {
            return json_encode($users);
        }

    }

    /*
    * API for Video List According to video id
    */
    public function VideoDetails(Request $request)
    {
        $videoDetails = DB::table('video')->where('VideoId', $request->videoId)->select('VideoId', 'VideoFormat', 'VideoLength', 'VideoSize', 'VideoURL', 'VideoThumbnail', 'UploadedBy', 'ProfileId', 'VideoPrice', 'VideoUploadDate', 'Description', 'Title', 'comments', 'review')->get();
        if (empty($videoDetails)) {
            $response = array('return_message' => "Not Found");
            return json_encode($response);

        } else {
            return json_encode($videoDetails);
        }

    }

    /*
    * API for push notification
    */
    public function push(Request $request)
    {
        $passphrase = 'vrl12345';
        $deviceToken = "f9b0381007fc2ab2bf55500ee4f4d18bae641d12da12b9d78f8054890349bafa";

        $ctx = stream_context_create();
        $test = stream_context_set_option($ctx, 'ssl', 'local_cert', 'Certificatesvrl.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        // Open a connection to the APNS server
        $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        // Create the payload body
        $body['aps'] = array(
            'alert' => 'Testing notification',
            'sound' => 'default',
            'badge' => 1,
            // 'type' =>  'Newsroom',
        );
        // Encode the payload as JSON
        $payload = json_encode($body);
        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));
        if (!$result) {
            return '0';
        } else {
            return '1';
        }
        // Close the connection to the server
        fclose($fp);
    }

    /*
    * API for Video Request
    */
    public function unlogin_user_RequestVideo(Request $request)
    {
        return json_encode($request);
        die;
        $user_favorites = DB::table('users')
            ->where('email', '=', $request->email)
            ->first();
        if (!is_null($user_favorites)) {
            if ($user_favorites->type == 'Artist') {
                $response = array('return_message' => "You can not send request by this email");
                return json_encode($response);
            } elseif ($user_favorites->type == 'User') {
                $artist = DB::table('profiles')
                    ->where('ProfileId', '=', $request->requestToProfileId)
                    ->first();
                $timestamp = date('m-d-Y', strtotime(Carbon::now()->format('m-d-Y')));
                $complitionDate = date('m-d-Y', strtotime($timestamp . ' + ' . $artist->timestamp . 'days'));
                $Requestvideo = new Requestvideo();
                $Requestvideo->Name = $request->Name;
                $Requestvideo->requestToProfileId = $request->requestToProfileId;
                $Requestvideo->RequestStatus = "Pending";
                $Requestvideo->Description = $request->Description;
                $Requestvideo->ReqVideoPrice = $artist->VideoPrice;
                $Requestvideo->Title = $request->Title;
                $Requestvideo->requestByProfileId = $user_favorites->profile_id;
                $Requestvideo->requestor_email = $request->email;
                $mydate = date('d-m-Y');
                $daystosum = $artist->timestamp;
                $datesum = date('d-m-Y', strtotime($mydate . ' +' . $daystosum . ' days'));
                $Requestvideo->complitionDate = $datesum;
                $Requestvideo->request_date = Carbon::now();
                $response = array('return_message' => "Your Request have been Submitted Successfully.Please check you Email to see details");
                return json_encode($response);
            }
        } else {
            $artist = DB::table('profiles')
                ->where('ProfileId', '=', $request->requestToProfileId)
                ->first();
            $Requestvideo = new Requestvideo();
            $Requestvideo->Name = $request->Name;
            $Requestvideo->requestToProfileId = $request->requestToProfileId;
            $Requestvideo->RequestStatus = "Pending";
            $Requestvideo->Description = $request->Description;
            $Requestvideo->ReqVideoPrice = $artist->VideoPrice;
            $Requestvideo->Title = $request->Title;
            $Requestvideo->requestor_email = $request->email;
            $mydate = date('d-m-Y');
            $daystosum = $artist->timestamp;
            $datesum = date('d-m-Y', strtotime($mydate . ' +' . $daystosum . ' days'));
            $Requestvideo->complitionDate = $datesum;
            //$Requestvideo->complitionDate=$request->RequestDate;
            $Requestvideo->request_date = Carbon::now();
            $Requestvideo->save();
            $Profile = new Profile();
            $users = new User();
            $users->user_name = $request->Name;
            $users->email = $request->email;
            $password = str_random(10);
            $users->password = Hash::make($password);
            $users->type = 'User';
            $users->is_account_active = '1';
            $users->is_email_active = '1';
            $users->phone_no = $request->phone;
            $Profile->EmailId = $request->email;
            $Profile->MobileNo = $request->phone;
            $Profile->Address = $request->city;
            $Profile->Type = "User";
            $Profile->Name = $request->Name;
            $Profile->City = $request->city;
            $Profile->Zip = $request->zip;
            $profile_path = "images/Artist/default-artist.png";
            $Profile->profile_path = $profile_path;
            $Profile->State = $request->state;
            $Profile->country = $request->country;
            $user_id = DB::table('profiles')->where('EmailId', $request->email)->first();
            $profId = $user_id->ProfileId;
            $artist = Profile::where('ProfileId', $request->requestToProfileId)->first();
            $mydate = date('d-m-Y');
            $daystosum = $artist->timestamp;
            $datesum = date('d-m-Y', strtotime($mydate . ' +' . $daystosum . ' days'));
            $response = array('return_message' => "Your Request have been Submitted Successfully.Please check you Email to see details");
            return json_encode($response);
        }
    }

    /*
    * API for Video Request
    */
    public function requestVideo(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
            'delivery_date.after' => \Lang::get('messages.invalid_delivery_date'),
            'max' => 'Maximum 50 characters allowed',
            'message.max' => 'Maximum 200 characters allowed',
            'song_name.max' => 'Maximum 70 characters allowed',
        ];
        $validator = Validator::make($data, [
            'delivery_date' => 'required|after:yesterday',
            'artist_profile_id' => 'required',
            'user_profile_id' => 'required',
            'recipient_name' => 'max:50',
            'recipient_email' => 'email',
            'sender_name' => 'max:50',
            'stripe_token' => 'required',
            'message' => 'required|max:200',
           // 'occassion' => 'required',
            'song_name' => 'max:70',
            'phone_no' => 'min:10|max:15',
            'video_price' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'access_token' => 'required',
            '_token' => 'required',
        ], $messages
        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        /*check for token*/
        if (!isset($request->access_token)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        } else {
            $user = User::whereAccessToken($request->access_token)->first();
            if (is_null($user)) {
                return response()->json(\Lang::get('messages.unauthorized_access'), 401);
            }
        }
        //begin new code by Azim Khan
        #1. check for is artist active or not
        //return $request->requestToProfileId;
        $artist = User::whereProfileId($request->artist_profile_id)->whereType('Artist')->with('profile')->first();
        $requester = User::whereProfileId($request->user_profile_id)->with('profile')->whereType('User')->first();
        if (is_null($artist) || $artist->is_account_active == 0) {
            return response()->json(\Lang::get('messages.artist_not_active_or_found'), 404);
        }
        #2. process Payment
        /*
         * payment to be completed before video request
         * */

        if (App::environment('local') || App::environment('testing')) {
            // The environment is local or testing
            \Stripe\Stripe::setApiKey(Config::get('constants.STRIPE_KEYS.test.secret_key'));
        } else {
            // The environment is production
            \Stripe\Stripe::setApiKey(Config::get('constants.STRIPE_KEYS.live.secret_key'));
        }
        \Stripe\Stripe::setApiVersion(Config::get('constants.STRIPE_VERSION'));

        /*create token for payment process*/
        /*$stripeToken = \Stripe\Token::create(array(
            "card" => array(
                "number" => '4242424242424242',
                "exp_month" => 12,
                "exp_year" => 2019,
                "cvc" => 123
            )
        ));*/
        try {
            $customer = \Stripe\Customer::create(array(
                'email' => $request->sender_email,
                'source' => $request->stripe_token
            ));
            $charge = \Stripe\Charge::create(array(
                'customer' => $customer->id,
                'amount' => $request->video_price * 100,
                'currency' => Config::get('constants.STRIPE_CURRENCY')
            ));
        } catch (\Stripe\Error\Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
            $body = $e->getJsonBody();
            $err = $body['error'];
            return response()->json($err['code'], $e->getHttpStatus());
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            $body = $e->getJsonBody();
            $err = $body['error'];
            return response()->json($err['code'], $e->getHttpStatus());
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            $body = $e->getJsonBody();
            $err = $body['error'];
            return response()->json($err['code'], $e->getHttpStatus());
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            $body = $e->getJsonBody();
            $err = $body['error'];
            return response()->json($err['code'], $e->getHttpStatus());
        } catch (\Stripe\Error\ApiConnection $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return response()->json($err['code'], $e->getHttpStatus());
        } catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            return response()->json($err['code'], $e->getHttpStatus());
        }
        \DB::beginTransaction();
        if ($charge['status'] == "succeeded") {
            $recipientPronunciation = $senderPronunciation = null;
            $destination =  'usersRecords/';
            #3 upload pronunciation if available in request
            $rand = rand(11111, 99999) . date('U');
            $s3 = Storage::disk('s3');
            if ($request->hasFile('receipient_pronunciation')) {
                $file = $request->file('receipient_pronunciation');
                $extension = $file->getClientOriginalExtension();
//                $extension = $file->extension();
                $recipientPronunciation = $rand.'recipient' . '.'.$extension;
                //$s3->put($destination.$recipientPronunciation, file_get_contents($file));
                $request->file('receipient_pronunciation')->move($destination, $recipientPronunciation);
                multipartUpload($destination.$recipientPronunciation);
                unlink($destination.$recipientPronunciation);
            }

            if ($request->hasFile('sender_name_pronunciation')) {
                $file = $request->file('sender_name_pronunciation');
                $extension = $file->getClientOriginalExtension();
//                $extension = $file->extension();
                $senderPronunciation = $rand.'sender' . '.'.$extension;
                //$s3->put($destination.$senderPronunciation, file_get_contents($file));
                $request->file('sender_name_pronunciation')->move($destination, $senderPronunciation);
                multipartUpload($destination.$senderPronunciation);
                unlink($destination.$senderPronunciation);
            }

            #4. if Payment added to database, create video request
            /* calculating date interval in days*/
            $deliveryDate = Carbon::createFromFormat('d/m/Y', $request->delivery_date);
            $interval = $deliveryDate->diffInDays(Carbon::now());
            $requested = RequestVideo::create([
                'Name' => !empty($request->recipient_name) ? $request->recipient_name : $requester->profile->Name,
                'requestToProfileId' => $artist->profile_id,
                'VideoId' => 0,
                'RequestStatus' => 'Pending',
                'DateInterval' => $interval,
                'Description' => $request->message,
                'Title' => $request->occassion,
                'requestByProfileId' => $requester->profile_id,
                'requestor_email' => !empty($request->recipient_email) ? $request->recipient_email : $requester->email,
                'paid' => 'Paid',
                'is_active' => 1,
                'complitionDate' => date('m/d/Y', strtotime($request->delivery_date)),
                'song_name' => $request->song_name,
                'request_date' => Carbon::now()->format('m/d/Y'),
                'sender_name' => $requester->profile->Name,
                'sender_name_pronunciation' => $senderPronunciation,
                'receipient_pronunciation' => $recipientPronunciation,
                'sender_record' =>  $senderPronunciation,
                'recipient_record'  =>  $recipientPronunciation,
                'sender_email' => $requester->email,
                'sender_voice_pronunciation' => $senderPronunciation,
                'receipient_voice_pronunciation' => $recipientPronunciation,
                'is_hidden' => $request->is_hidden,
                'ReqVideoPrice' => $request->video_price,
                'device_type' => $requester->device_type,
                'is_delete' => 'false',
            ]);
            Payment::create([
                'profile_id' => $requester->profile_id,
                'stripeToken' => $request->stripe_token,
                'stripeTokenType' => 'card',
                'customerEmail' => $requester->email,
                'status' => 'succeeded',
                'is_refunded' => 0,
                'videoPrice' => $request->video_price,
                'video_status' => 'Pending',
                'charge_id' => $charge->id,
                'payment_id' => 1,
                'token' => $request->_token,
                'payer_id' => $artist->profile_id,
                'payment_date' => Carbon::now()->format('m-d-Y'),
                'payment_type' => 'Purchase',
                'video_request_id' => $requested->VideoReqId,
            ]);

            #5. if video request added, then send an email to user with video request detail
            
            $request_video_email['artist_id'] = $artist->profile_id;
            $request_video_email['artistName'] = $artist->Name;
            $request_video_email['video_request_id'] = $requested->VideoReqId;
            $request_video_email['recipient_name'] = !empty($request->recipient_name) ? $request->recipient_name : $requester->profile->Name;
            $request_video_email['video_title'] = $request->occassion;
            $request_video_email['requester_name'] = $requester->profile->Name;
            $request_video_email['requester_email'] = $requester->email;
            $request_video_email['video_description'] = $request->message;
            $request_video_email['current_status'] = "Pending";
            $request_video_email['price'] = $artist->VideoPrice;
            $request_video_email['delivery_date'] = $request->delivery_date;
            $request_video_email['songName'] = $request->song_name;
            $request_video_email['identifier'] = $artist->profile_id.'-'.$requested->VideoReqId;
            Mail::send('emails.video-request', $request_video_email, function ($message) use ( $artist, $request_video_email) {
                $message->from('noreply@videorequestline.com', 'New Video Request');
                $message->to($artist->email);
                $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                $message->subject('VRL Video Request Received ('.$request_video_email['identifier'].')');
            });
            
            $confirmation_code['user_email'] = $requester->email;
            $confirmation_code['username'] = $requester->profile->Name;

            $confirmation_code['recipient_name'] = !empty($request->recipient_name) ? $request->recipient_name : $requester->profile->Name;
            $confirmation_code['recipient_email'] = !empty($request->recipient_email) ? $request->recipient_email : $requester->email;
            $confirmation_code['delivary_date'] = $request->delivery_date;
            $confirmation_code['video_title'] = $request->occassion;
            $confirmation_code['video_description'] = $request->message;
            $confirmation_code['current_status'] = "Pending";
            $confirmation_code['songName'] = $request->song_name;
            $confirmation_code['video_delivery_time'] = $artist->profile->timestamp;
            $confirmation_code['artist_name'] = $artist->profile->Name;
            $confirmation_code['artist_email'] = $artist->email;
            $confirmation_code['artist_id'] = $artist->profile_id;
            $confirmation_code['video_request_id'] = $requested->VideoReqId;
            $confirmation_code['request_date']= $request->request_date;
            $confirmation_code['identifier'] = $confirmation_code['artist_id'].'-'.$confirmation_code['video_request_id'];

            Mail::send('emails.exist_User_RequestNewVideo', $confirmation_code, function ($message) use ($requester,$confirmation_code) {
                $message->from('noreply@videorequestline.com', 'Confirmation for Video Request');
                $message->to($requester->email);
                $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                $message->subject('VRL Video Request Confirmation ('.$confirmation_code['identifier'].')');
            });
            \DB::commit();
            if (is_null($artist->push_notification) || $artist->push_notification == 1) {
                $deviceToken = $artist->device_token;
                if ($deviceToken != null) {
                    $message = 'You have received a new video request';
                    push_notification($deviceToken, $message, 4);
                }
            }
            return response()->json($requested, 201);
        }
        //end new code by Azim Khan
    }

    /*
    * API for Video List According to Profile id
    */
    public function VideoList(Request $request)
    {
        $videoDetails = DB::table('video')->where('ProfileId', $request->profileId)->select('*')->orderBy('VideoId', 'desc')
            ->get();
        $profiles = DB::table('profiles')->where('ProfileId', $request->profileId)->select('*')
            ->get();
        $data['profile'] = $profiles;
        $data['video'] = $videoDetails;
        return json_encode($data);
    }

    /*
    * API for request List
    */
    public function requestList(Request $request)
    {
        $requestList = DB::table('requestvideos')
            ->join('profiles', 'profiles.ProfileId', '=', 'requestvideos.requestToProfileId')
            ->where('requestvideos.sender_email', $request->email)
            ->select('requestvideos.*', 'profiles.Name AS artist_name')->orderBy('VideoReqId', 'desc')->get();
        if (empty($requestList)) {
            $response = array('return_message' => "Not Found");
            return json_encode($response);

        } else {
            return json_encode($requestList);
        }
        //return json_encode($requestList);
    }

    /*
    * API for request List by requesttoProfileId
    */
    public function requestToList(Request $request)
    {
        $requestList = DB::table('requestvideos')
            ->join('profiles', 'profiles.ProfileId', '=', 'requestvideos.requestToProfileId')
            ->select('profiles.*', 'requestvideos.*')
            ->where('requestvideos.requestToProfileId', '=', $request->requestToProfileId)
            ->orderBy('VideoReqId', 'desc')
            ->get();
        if (empty($requestList)) {
            $response = array('return_message' => "Not Found");
            return json_encode($response);

        } else {
            return json_encode($requestList);
        }
        //return json_encode($requestList);
    }

    /*-------------API for Accepting Video Request-------------------*/
    public function accept_video_request($id, $artist_id, $user_id)
    {
        $requestvideo = Requestvideo::find($id);
        $artist = Profile::find($artist_id);
        $data['recipient_email'] = $requestvideo->requestor_email;
        $data['current_status'] = "Approved";
        $data['video_description'] = $requestvideo->Description;
        $data['artist'] = $artist->Name;
        $data['user'] = $requestvideo->Name;
        $data['video_title'] = $requestvideo->Occassion;
        $data['price'] = $artist->VideoPrice;
        $data['complitionDate'] = $requestvideo->complitionDate;
        $data['artist_id'] = $artist->ProfileId;
        $data['video_request_id'] = $requestvideo->VideoReqId;
        $data['songName'] = $requestvideo->song_name;
        $data['identifier'] = $artist->ProfileId.'-'.$requestvideo->VideoReqId;
        $requestvideo->RequestStatus = "Approved";
        $requestvideo->ReqVideoPrice = $artist->VideoPrice;
        $user_email = $requestvideo->requestor_email;
        if ($requestvideo->save()) {
            $user = $user_email;
            try {
                Mail::send('emails.video_response', $data, function ($message) use ($requestvideo,$data) {
                    $message->from('noreply@videorequestline.com', 'VRL');
                    $message->to($requestvideo->requestor_email, 'User');
                    $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                    $message->subject('Video Request Approved! ('.$data['identifier'].')');
                });
            } catch (\Exception $e) {
            }
            if ($requestvideo->sender_email != "") {
                try {
                    Mail::send('emails.video_response', $data, function ($message) use ($requestvideo,$data) {
                        $message->from('noreply@videorequestline.com', 'VRL');
                        $message->to($requestvideo->sender_email, 'User');
                        $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                        $message->subject('Video Request Approved! ('.$data['identifier'].')');
                    });
                } catch (\Exception $e) {
                }
            }
            $user_detail = DB::table('users')->where('profile_id', '=', $user_id)->first();
            $deviceToken = $user_detail->device_token;
            $deviceType = $user_detail->device_type;
            if (is_null($user_detail->push_notification) || $user_detail->push_notification == 1) {
                if ($deviceToken != null) {
                    Log::info('deviceToken not null  - API Line:892');
                    $message = 'Your video request has been approved by ' . $artist->Name;
                    push_notification($deviceToken, $message, 1);
                }
            }
        } else {
            $response = array('return_message' => " not Approved");
            return json_encode($response);
        }
    }

    /*-------------API for Rejecting Video Request-------------------*/
    public function reject_video_request($id, $artist_id, $user_id)
    {
        $requestvideo = Requestvideo::find($id);
        $current_status = $requestvideo->RequestStatus;
        $artist = Profile::find($artist_id);
        $user = Profile::find($user_id);

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

        if ($requestvideo->rejected_reason != '' || $requestvideo->rejected_comment != '') {
            $rejected_reason = $requestvideo->rejected_reason;
            if(!empty($rejected_reason) && array_key_exists($rejected_reason, Config::get('constants.REJECTED_REASONS')))
            {
                $rejected_reason = Config::get('constants.REJECTED_REASONS')[$rejected_reason];
            }
            $rejected_comment_string  = ($requestvideo->rejected_comment != '') ?  ' - ' . $requestvideo->rejected_comment : '';
            $data['rejected_reason'] = $rejected_reason.$rejected_comment_string;
        } else {
            $data['rejected_reason'] = 'No reason provided';
        }

        if ($requestvideo->paid == "Paid" && $requestvideo->RequestStatus != "Pending") {
            $requestvideo->RequestStatus = \Lang::get('views.refund');
        } else {
            $requestvideo->RequestStatus = \Lang::get('views.reject');
        }

        $requestvideo->ReqVideoPrice = $artist->VideoPrice;
        $user_email = $requestvideo->requestor_email;
        if ($requestvideo->save()) {

            $user = $user_email;
            $senderuser = $requestvideo->sender_email;
            if ($requestvideo->RequestStatus == "Refund") {

                try {
                     $payment = \DB::table('payments')->where('payer_id', $artist_id)->where('video_request_id', $id)->first();
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
                        //$re = $ch->refund();
                        $refund_details['status'] = 'refunded';
                        $refund_details['request_detail'] = $requestvideo;
                        $refund_details['artist'] = $artist->Name;
                        $refund_details['complitionDate'] = $requestvideo->complitionDate;

                        $refund_details['artist_id'] = $artist->ProfileId;
                        $refund_details['video_request_id'] = $requestvideo->VideoReqId;
                        $refund_details['identifier'] = $artist->ProfileId.'-'.$requestvideo->VideoReqId;
                        $refund_details['turnaroundTime'] = $artist->timestamp;
                        $refund_details['rejected_reason'] = $rejected_reason = $requestvideo->rejected_reason;

                        if ($requestvideo->rejected_reason != '' || $requestvideo->rejected_comment != '') {
                            $rejected_reason = $requestvideo->rejected_reason;
                            if(!empty($rejected_reason) && array_key_exists($rejected_reason, Config::get('constants.REJECTED_REASONS')))
                            {
                                $rejected_reason = Config::get('constants.REJECTED_REASONS')[$rejected_reason];
                            }
                            $rejected_comment_string  = ($requestvideo->rejected_comment != '') ?  ' - ' . $requestvideo->rejected_comment : '';
                            $refund_details['rejected_reason_detail'] = $rejected_reason.$rejected_comment_string;
                        } else {
                            $refund_details['rejected_reason_detail'] = 'No reason provided';
                        }

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

                        //$refund_details['request_detail'] = $request;
                        try {

                            Mail::send('emails.refund_success', $refund_details, function ($message) use ($requestvideo,$refund_details) {
                                $message->from('noreply@videorequestline.com', 'VRL');

                                $message->to($requestvideo->requestor_email, $requestvideo->requestor_email);
                                $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                                $message->subject('Payment Refunded Successfully ('.$refund_details['identifier'].')');
                            });
                        } catch (\Exception $e) {

                            Log::error("API: emails.refund_success".$e->getMessage());

                        }
                        try {
                            Mail::send('emails.refund_success', $refund_details, function ($message) use ($requestvideo,$refund_details) {
                                $message->from('noreply@videorequestline.com', 'VRL');

                                $message->to($requestvideo->sender_email, $requestvideo->sender_email);
                                $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                                $message->subject('Payment Refunded Successfully ('.$refund_details['identifier'].')');
                            });
                        } catch (\Exception $e) {
                            Log::error("API: emails.refund_success".$e->getMessage());

                        }
                    }
                } catch (\Exception $ref) {
                    $requestvideo->RequestStatus = $current_status;
                    $requestvideo->save();
                    Log::error("API: STRIPE REFUND ".$ref->getMessage());
                    $response = array('return_message' => " not Rejected");
                    return json_encode($response);
                }
            } else {


                try {
                    Mail::send('emails.video_request_reject', $data, function ($message) use ($user, $data) {

                        $message->from('noreply@videorequestline.com', 'VRL');
                        $message->to($user, 'User');
                        $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                        $message->subject('Sorry we cannot fulfill your request ! ('.$data['identifier'].')');
                    });
                } catch (\Exception $e) {
                    //echo $e->getMessage();
                    Log::error("API: emails.video_request_reject".$e->getMessage());
                }
                try {

                    Mail::send('emails.video_request_reject', $data, function ($message) use ($senderuser, $data) {
                        $message->from('noreply@videorequestline.com', 'VRL');
                        $message->to($senderuser, 'User');
                        $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                        $message->subject('Sorry we cannot fulfill your request ! ('.$data['identifier'].')');
                    });

                } catch (\Exception $e) {
                    //echo $e->getMessage();
                    Log::error("API: emails.video_request_reject".$e->getMessage());
                }
            }

            $user_detail = DB::table('users')->where('profile_id', '=', $user_id)->first();
            $deviceToken = $user_detail->device_token;
            $deviceType = $user_detail->device_type;
            if (is_null($user_detail->push_notification) || $user_detail->push_notification == 1) {
                if ($deviceToken != null) {
                    $message = 'Your video request has been rejected by ' . $artist->Name;
                    push_notification($deviceToken, $message, 2);
                }
            }
        } else {
            $response = array('return_message' => " not Rejected");
            return json_encode($response);
        }
    }

    public function UpdateProfile(Request $request)
    {
        $updateType = $request->update;
        if ($updateType == "Profile") {
            if (Profile::where('ProfileId', $request->profileId)->update(array('Name' => $request->username, 'EmailId' => $request->email, 'DateOfBirth' => $request->dob, 'MobileNo' => $request->phone, 'NickName' => $request->NickName, 'Address' => $request->Address, 'City' => $request->City, 'State' => $request->State, 'country' => $request->Country, 'Zip' => $request->Zip, 'PaypalId' => $request->PaypalId, 'profile_description' => $request->Description, 'timestamp' => $request->timestamp, 'VideoPrice' => $request->price))) {
                $flag = 1;
                if (DB::table('users')->where('profile_id', $request->profileId)->update(['user_name' => $request->username])) {
                    $flag = 1;
                }
                if ($flag == 1) {
                    $response = array('return_message' => "Updated Successfully ");
                    return json_encode($response);
                }
            } else {
                $response = array('return_message' => "Not Updated");
                return json_encode($response);
            }
        }
        $s3 = Storage::disk('s3');
        if ($updateType == "Picture") {

            if ($_FILES["media"]["error"] > 0) {

                $error = $_FILES["media"]["error"];

            } else if (($_FILES["media"]["type"] == "image/gif") || ($_FILES["media"]["type"] == "image/jpeg") || ($_FILES["media"]["type"] == "image/png") || ($_FILES["media"]["type"] == "image/pjpeg")) {
                if ($request->type == "User") {
                    $target = "images" . '/' . 'User';
                    $resized_file = "images/User";
                    $target_file = "images/User";
                } else {
                    $target = "images" . '/' . 'Artist';
                    $resized_file = "images/Artist";
                    $target_file = "images/Artist";
                }
                $file = $request->file('media') ;
                $fileName = date('U') . $request->file('media')->getClientOriginalName();
                //$fileName1 = $request->profileId.'_'.$file->getClientOriginalName() ;

                $imageTempName = $request->file('media')->getPathname();
                $imageName = $request->profileId . '_' . $request->file('media')->getClientOriginalName();
                //$path = base_path() . '/public/uploads/consultants/images/';
                $path = $target;
                $request->file('media')->move($path, $imageName);


                // $source_url = $_FILES["media"]["tmp_name"];
                $destination_url = $target . '/' . $imageName;
                //$s3->put($destination_url, file_get_contents($file));
                multipartUpload($destination_url);
                unlink($destination_url);

                // $quality = 100;

                // $info = getimagesize($source_url);
                // if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source_url);
                // elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source_url);
                // elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source_url);
                // imagejpeg($image, $destination_url, $quality);

                echo $destination_url;


                //move_uploaded_file($source_url,$target.'/'.$fileName1);


            }

            if (Profile::where('ProfileId', $request->profileId)->update(array('profile_path_Mobile' => $target . '/' . $fileName,
                'profile_path' => $destination_url))) {

                $msg = "Updated successfully";

                return json_encode($msg);

            } else {

                $msg = 'Updated not successfully';
                return json_encode($msg);
            }


        }

        if ($updateType == "Banner") {

            if ($request->type == "User") {
                $banner_target = "banner" . '/' . 'User';
                $resized_file = "banner/User";
                $target_file = "banner/User";
            } else {
                $banner_target = "banner" . '/' . 'Artist';
                $resized_file = "banner/Artist";
                $target_file = "banner/Artist";
            }
            $file = $request->file('media');
            $fileName = date('U') . $file->getClientOriginalName();
            $fileName1 = $request->profileId . '_' . $file->getClientOriginalName();
            $source_url = $_FILES["media"]["tmp_name"];
            $destination_url = $banner_target . '/' . $fileName;
            //$s3->put($destination_url, file_get_contents($file));
            multipartUpload($destination_url);
            $quality = 75;

            $info = getimagesize($source_url);
            if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source_url);
            elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source_url);
            elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source_url);
            imagejpeg($image, $destination_url, $quality);
            move_uploaded_file($source_url, $banner_target . '/' . $fileName1);


            if (Profile::where('ProfileId', $request->profileId)->update(array('BannerImg_Mobile' => $banner_target . '/' . $fileName,
                'BannerImg' => $banner_target . '/' . $fileName1))) {
                $msg = $banner_target . '/' . $fileName;
                return json_encode($msg);
            } else {
                $msg = 'Updated not successfully';
                return json_encode($msg);
            }
        }
    }

    /*
    * API for login
    */
    public function artistlogin()
    {
        return view('frontend.test');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logins(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (empty($request->device_token) || empty($request->device_type)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = \Auth::user();
            /*if ($user->mobile_login_count > 18) {
                return response()->json(\Lang::get('messages.max_login_exceeded'), 503);
            }*/
            $userData = [];
            if ($user->is_account_active == 1) {
                if (is_null($user->mobile_login_count) || $user->mobile_login_count == 0) {
                    $user->whereUserId(Auth::user()->user_id)
                        ->update([
                            'device_token' => "'".$request->device_token."'",
                            'device_type' => $request->device_type,
                            'access_token' => $this->accessToken(),
                            'mobile_login_count' => 1
                        ]);
                } else {
                    $user->increment('mobile_login_count');
                    $trimmed = trim($user->device_token, "'");
                    $tokens = "'";
                    $tokens .= $trimmed. ','. $request->device_token;
                    $tokens .= "'";
                    $user->device_token = $tokens;
                    $user->save();
                }
                $user = User::where('user_id', \Auth::user()->user_id)->with('profile')->first();
                $userData['user'] = $user;
                if (App::environment('local') || App::environment('testing')) {
                    // The environment is local or testing
                    $userData['stripe_publishable_key'] = Config::get('constants.STRIPE_KEYS.test.publishable_key');
                } else {
                    // The environment is production
                    $userData['stripe_publishable_key'] = Config::get('constants.STRIPE_KEYS.live.publishable_key');
                }
                return response()->json($userData, 200);
            } else {
                return response()->json(\Lang::get('messages.inactive_account'), 403);
            }
        } else {
            return response()->json(\Lang::get('messages.invalid_credentials'), 401);
        }
    }

    /**
     * return access token for mobile session
     * @return string
     */
    function accessToken()
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet);
        for ($i = 0; $i < 80; $i++) {
            $token .= $codeAlphabet[random_int(0, $max - 1)];
        }
        return Hash::make($token);
    }

    /*
    * API Code for User Registration
    */
    public function userRegistration(Request $request)
    {
        $input = $request->all();
        \DB::beginTransaction();
        $user = new User();
        $validator = $user->validator($input);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if ($request->hasfile('image')) {
            $image = $request->file('image');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $destination = base_path() . '/public/images/Artist/';
            $image->move($destination, $fileName);
        } else {
            $fileName = 'default-artist.png';
        }

        $profile = Profile::create([
            'Name' => $input['full_name'],
            'EmailId' => $input['email'],
            'Type' => 'User',
            'gender' => isset($input['gender']) ? $input['gender'] : null,
            'DateOfBirth' => isset($request->date_of_birth) ? $request->date_of_birth : null,
            'profile_url' => strtolower($input['user_name']),
            'profile_path' => $fileName,
            'country' => $input['country'],
            'BannerImg' => "vrl_bg.jpg",
            'header_image' => "default_header.jpg",
            'video_background' => "vrl_bg.jpg",
            'MobileNo' => $input['phone_no'],
        ]);

        $user = $user->create([
            'user_name' => $input['user_name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'is_account_active' => 1,
            'is_email_active' => 1,
            'phone_no' => $input['phone_no'],
            'Gender' => isset($input['gender']) ? $input['gender'] : null,
            'date_of_birth' => isset($request->date_of_birth) ? $request->date_of_birth : null,
            'type' => 'User',
            'device_token' => $input['device_token'],
            'device_type' => $input['device_type'],
            'access_token' => $this->accessToken(),
            'profile_id' => $profile->ProfileId,
            'mobile_login_count' => 1
        ]);
        \DB::commit();
        $user = User::with('profile')->findOrFail($user->user_id);
        $confirmation_code = array();
        $confirmation_code['email'] = $input['email'];
        $confirmation_code['password'] = $input['password'];
        /*if ($input['device_token'] != null && $input['device_type'] != null && $input['type'] != null) {
            $message = "You have Successfully registered as " . $input['type'];
            android_push($input['device_type'], $input['device_token'], $message);

        }*/
        Mail::send('emails.api_signup', [], function ($message) use ($request) {
            $message->from('noreply@videorequestline.com', 'Welcome to VRL');
            $message->to($request->email);
            $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
            $message->subject('Welcome to VRL');
        });
        if (App::environment('local') || App::environment('testing')) {
            // The environment is local or testing
            $user->stripe_publishable_key = Config::get('constants.STRIPE_KEYS.test.publishable_key');
        } else {
            // The environment is production
            $user->stripe_publishable_key = Config::get('constants.STRIPE_KEYS.live.publishable_key');
        }

        return response()->json($user, 201);
    }

    /*
    * API code formCheck Email Exists
    */
    public function CheckEmailExists(Request $request)
    {
        $email = User::where('email', $request->email)->get();
        if (count($email) == 0) {
            $response = array('return_message' => "NO Email Found");
            return json_encode($response);
        } else {
            $response = array('return_message' => "An Email Founded");
            return json_encode($response);
        }
    }

    /**
     * API to send email of password reset
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => 'required|email'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $email = Profile::where('EmailId', $request->email)->first();
        $auth_pass = str_random(15);
        if (!is_null($email)) {
            User::whereEmail($request->email)->update([
                'auth_reset_pass' => $auth_pass,
            ]);
            $confirmation_code['confirmation_code'] = encrypt($auth_pass);
            Mail::send('emails.forget_reminder', $confirmation_code, function ($message) use ($request) {
                $message->from('noreply@videorequestline.com', \Lang::get('views.reset_password'));
                $message->to($request->email, \Lang::get('views.user'));
                $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                $message->subject('Reset Password');
            });
            return response()->json(null, 200);
        } else {
            return response()->json(\Lang::get('messages.email_does_not_exist'), 404);
        }
    }

    /*
    * API for Video List According to profile id and paid=yes
    */
    public function VideoListByProfileId(Request $request)
    {
        $videoDetails = DB::table('requestvideos')
            ->join('requested_videos', 'requested_videos.request_id', '=', 'requestvideos.VideoReqId')
            ->select('*')
            ->where('requestvideos.requestByProfileId', '=', $request->profileId)
            ->where('requestvideos.RequestStatus', '=', 'Completed')
            ->get();
        //$videoDetails="test";
        if (empty($videoDetails)) {
            $response = array('return_message' => "Not Found");
            return json_encode($response);

        } else {
            return json_encode($videoDetails);
        }
        //return json_encode($videoDetails);
    }

    /*
    * API For Sale details
    */
    public function ArtistSaleDetails(Request $request)
    {
        /*$videoDetails = DB::table('video')
        ->join('requestvideos', 'video.VideoId', '=', 'requestvideos.VideoId')
        ->select('VideoThumbnail')
        ->where('requestvideos.requestByProfileId','=', $request->profileId)
        ->where('requestvideos.paid', '=' ,'yes')
        ->get();*/
        $videoDetails = "test";
        return json_encode($videoDetails);
    }

    /*---------------------------Artist sales details-------------------------*/
    public function sales($artist_id)
    {

        $noOfRequests = Requestvideo::where('requestToProfileId', $artist_id)->get();
        $noOfCompleteRequests = Requestvideo::where('requestToProfileId', $artist_id)->where('RequestStatus', 'Completed')->get();
        $amounts = Requestvideo::where('requestToProfileId', $artist_id)->where('RequestStatus', 'Completed')->where('paid', 'Paid')->get();
        $total_amount = 0;
        foreach ($amounts as $amount) {
            $total_amount = $total_amount + $amount->ReqVideoPrice;
        }

        $amountsPaidToArtist = DB::table('admin_artist_payments')->where('payment_to', $artist_id)->where('status', 'paid')->get();

        $total_amount_paid = 0;
        foreach ($amountsPaidToArtist as $amountPaidToArtist) {
            $total_amount_paid = $total_amount_paid + $amountPaidToArtist->paid_amount;
        }

        $artist_Accout_data = DB::table('profiles')->select('ssn_number', 'routing_number', 'account_number', 'pin')->where('ProfileId', $artist_id)->get();
        $message['noOfRequests'] = count($noOfRequests);
        $message['noOfCompleteRequests'] = count($noOfCompleteRequests);
        $message['total_amount_gathered'] = $total_amount;
        $message['total_amount_paid'] = $total_amount_paid;
        $message['artist_Accout_data'] = $artist_Accout_data;
        $response = array('return_message' => $message);
        return json_encode($response);


    }

    /**
     * @param Request $request
     * @return string
     */
    public function upload_videos(Request $request)
    {
        if (!isset($request->access_token)) {
            return response()->json(null, 401);
        } else {
            $user = User::whereAccessToken($request->access_token)->first();
            if (is_null($user)) {
                return response()->json(null, 401);
            }
        }

        $data = $request->all();
        $validator = Validator::make($data, [
            'artist_profile_id' => 'required',
            'VideoReqId' => 'required',
            'access_token' => 'required',
            'video' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $requestVideo = Requestvideo::where('VideoReqId', $request->VideoReqId)
            ->where('requestToProfileId', $request->artist_profile_id)
            ->first();

        if (is_null($requestVideo)) {
            return response()->json(null, 404);
        }

        if (is_null($requestVideo) || $request->artist_profile_id != $requestVideo->requestToProfileId) {
            return response()->json(null, 401);
        }

        $artist = User::whereProfileId($request->artist_profile_id)->whereIsAccountActive(1)
            ->whereType('Artist')->with('profile')->first();
        $user = User::whereProfileId($requestVideo->requestByProfileId)->whereIsAccountActive(1)
            ->whereType('User')->with('profile')->first();

        $purgeData = DB::table('setting')->select('status')->where('name', '=', "purge")->first();
        $requested_video = new RequestedVideo();
        $requested_video->description = $requestVideo->Description;
        $requested_video->title = $requestVideo->Title;
        $requested_video->request_id = $requestVideo->VideoReqId;
        $requested_video->requestby = $requestVideo->requestByProfileId;
        $requested_video->uploadedby = $request->artist_profile_id;
        $executionStartTime = microtime(true);
        $rand = rand(11111, 99999) . date('U');
        $destination ='requested_video/';
        $extension = $request->file('video')->extension();
        $fileName = $rand . '.' . $extension;
        $request->file('video')->move($destination, $fileName);
        $s3 = Storage::disk('s3');
        //$s3->put($destination.$fileName, file_get_contents($request->file('video')), 'public');
        multipartUpload($destination.$fileName);
        $requested_video->url = $fileName;
        $requested_video->fileName = $fileName; // the file name
        $requested_video->Upload_date = date('Y-m-d');
        $requested_video->remain_storage_duration = $purgeData->status;
        $requested_video->is_active = 1;
        $requested_video->purchase_date = date('Y-m-d');
        $requested_video->token_used = 0;    // This means not used
        /*   Move out the original Video     */
        $orginal_video = new OriginalVideo();
        $orginal_video->video_path = $fileName; //  removed full path, file name saving to database
        $orginal_video->save();
        $ffmpeg = FFMpeg\FFMpeg::create([
            'ffmpeg.binaries' => '/usr/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/bin/ffprobe',
            'timeout' => 3600,
            'ffmpeg.threads' => 12,
        ]);
        $uploaded_video = $ffmpeg->open($destination . $fileName);
        $ffprobe = FFMpeg\FFProbe::create([
            'ffmpeg.binaries' => '/usr/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/bin/ffprobe',
            'timeout' => 3600,
            'ffmpeg.threads' => 12,
        ]);

        /*--------------------retrieving Video Duration--------------------*/
        $video_length = $ffprobe->streams($destination . $fileName)
            ->videos()->first()->get('duration');
        if ($video_length < 15) {
            return response()->json(\Lang::get('messages.video_duration_error'), 422);
        }
        /*-----------------------retrieving Thumbnail----------------------*/
        $thumbnailName = $rand . '.jpg';
        $video_thumbnail_path = 'images/thumbnails/' . $thumbnailName;
        $uploaded_video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(2))->save($video_thumbnail_path);
        //$s3->put($video_thumbnail_path, file_get_contents($video_thumbnail_path));
        multipartUpload($video_thumbnail_path);
        unlink($video_thumbnail_path);
        $requested_video->thumbnail = $thumbnailName;
        /* ----------------------------Applying Watermark---------------------------------- */

        $ffmpegPath = '/usr/bin/ffmpeg';
        $inputPath =  'requested_video/'.$orginal_video->video_path;
        //$watermark = '/home/vrl/public_html/public/vrl_logo.png';
        $watermark = public_path().'/vrl_logo.png';
        $waterMarkVideoName = $fileName;

        //$outPath = '/home/vrl/public_html/public/requested_video/watermark/' . $waterMarkVideoName;
        $outPath = 'requested_video/watermark/' . $waterMarkVideoName;
        $thumbnailVideo = 'requested_video/watermark/thumbnail/' . $waterMarkVideoName;
        shell_exec("$ffmpegPath  -i $inputPath -i $watermark -filter_complex \"[1][0]scale2ref=(262/204)*ih/12:ih/12[wm][vid];[vid][wm]overlay=x=(W-w-20):y=(H-h-20)\" $outPath ");
        //$s3->put($outPath, file_get_contents($outPath));
        multipartUpload($outPath);
        unlink($outPath);
        shell_exec("$ffmpegPath  -i $inputPath -i $watermark  -filter_complex \"[0:v]scale=640:360[bg];[bg][1:v]overlay=x=(W-w-20):y=(H-h-20)\" $thumbnailVideo ");
        //$s3->put($thumbnailVideo, file_get_contents($thumbnailVideo));
        multipartUpload($thumbnailVideo);
        unlink($thumbnailVideo);
        unlink($inputPath);
        $executionEndTime = microtime(true);
        Log::info('Upload time via API: '. ($executionEndTime - $executionStartTime));

        /*  ----------------------------------Saving Video------------------------------- */

        $watermark_video_destination = substr($outPath, 28);
        \DB::beginTransaction();
        if ($requested_video->save()) {
            Payment::whereVideoRequestId($request->VideoReqId)
                ->update([
                    'video_status' => 'Completed'
                ]);
            Requestvideo::where('VideoReqId', $request->VideoReqId)->update([
                'RequestStatus' =>  'Completed'
            ]);
            \DB::commit();
            $request->email = $user->email;
            $data['complitionDate'] = $requestVideo->complitionDate;
            $data['name'] = $requestVideo->Name;
            $data['requestor_email'] = $requestVideo->requestor_email;
            $data['complitionDate'] = $requestVideo->delivery_date;
            $data['recipient_name'] = $requestVideo->Name;
            $data['receipient_pronunciation'] = $requestVideo->receipient_pronunciation;
            $data['sender_name'] = $requestVideo->sender_name;
            $data['sender_email'] = $requestVideo->sender_email;
            $data['sender_name_pronunciation'] = $requestVideo->sender_name_pronunciation;
            $data['Title'] = $requestVideo->Title;
            $data['Description'] = $requestVideo->Description;
            $data['video_name'] = $fileName;
            $data['current_status'] = "Approved";
            $data['thumbnail'] = $video_thumbnail_path;
            $data['video_title'] = $requestVideo->Title;
            $data['turnaroundTime'] = $artist->timestamp;
            $data['songName'] = $requestVideo->song_name;
            $data['identifier'] = $requestVideo->requestToProfileId.'-'.$requestVideo->VideoReqId;
            $data['video_description'] = $requestVideo->Description;
            Mail::send('emails.download_email', $data, function ($message) use ($request, $data) {
                $message->from('noreply@videorequestline.com', \Lang::get('views.download_video'));
                $message->to([$request->email]);
                $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                $message->subject('VRL request uploaded ('.$data['identifier'].')');
            });
            /*end sending download video email to requestor*/

            /*begin sending email to admin with download link and video detail*/
            $admin_data['artist_name'] = $artist->profile->Name;
            $admin_data['VideoReqId'] = $requestVideo->VideoReqId;
            $admin_data['complitionDate'] = $requestVideo->complitionDate;
            $admin_data['Name'] = $requestVideo->Name;
            $admin_data['requestor_email'] = $requestVideo->requestor_email;
            $admin_data['receipient_pronunciation'] = $requestVideo->receipient_pronunciation;
            $admin_data['sender_name'] = $requestVideo->sender_name;
            $admin_data['sender_email'] = $requestVideo->sender_email;
            $admin_data['sender_name_pronunciation'] = $requestVideo->sender_name_pronunciation;
            $admin_data['Description'] = $requestVideo->Description;
            $admin_data['Title'] = $requestVideo->Title;
            $admin_data['video_name'] = $fileName;
            Mail::send('emails.admin_download_email', $admin_data, function ($message) use ($request) {
                $message->from('noreply@videorequestline.com', \Lang::get('views.video_upload'));
                $message->to([config('constants.BCC_MAIL_CONFIG.admin_email')]);
                $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                $message->subject(\Lang::get('messages.artist_uploaded_video'));
            });
            /*end sending email to admin with download link and video detail*/

            /*begin sending email to artist with download link and video detail*/
            $artist_data['complitionDate'] = $requestVideo->complitionDate;
            $artist_data['Name'] = $requestVideo->Name;
            $artist_data['requestor_email'] = $requestVideo->requestor_email;
            $artist_data['receipient_pronunciation'] = $requestVideo->receipient_pronunciation;
            $artist_data['sender_name'] = $requestVideo->sender_name;
            $artist_data['sender_email'] = $requestVideo->sender_email;
            $artist_data['sender_name_pronunciation'] = $requestVideo->sender_name_pronunciation;
            $artist_data['Title'] = $requestVideo->Title;
            $artist_data['Description'] = $requestVideo->Description;
            $artist_data['video_name'] = $fileName;
            $request->artist_email = $artist->email;
//            Mail::send('emails.artist_download_email', $artist_data, function ($message) use ($request) {
//                $message->from('noreply@videorequestline.com', \Lang::get('views.download_video'));
//                $message->to([$request->artist_email]);
//                $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
//                $message->subject('Video Submitted Successfully');
//            });
            $user_detail = DB::table('users')->where('profile_id', '=', $requestVideo->requestByProfileId)->first();
            $deviceToken = $user_detail->device_token;
            $deviceType = $user_detail->device_type;
            if (is_null($user_detail->push_notification) || $user_detail->push_notification == 1) {
                if ($deviceToken != null) {
                    Log::info('deviceToken not null  - API Line:892');
                    $message = 'Your requested video has been uploaded by ' . $artist->profile->Name;
                    push_notification($deviceToken, $message, 3);
                }
            }

            /**
             * begin recepient email for same delivery date
             * by Abhishek Tandon
             */

            if(strtotime($requestVideo->complitionDate) == strtotime(date('Y-m-d'))) {
                $m  =  DB::table('requested_videos')
                    ->where('VideoReqId', $requestVideo->VideoReqId)  
                    ->join('requestvideos', 'requestvideos.VideoReqId', '=', 'requested_videos.request_id')
                    ->join('profiles', 'profiles.ProfileId', '=', 'requested_videos.uploadedby')
                   ->select([
                       "requestvideos.complitionDate as deliveryDate ",
                       "requestvideos.VideoReqId",
                       "requestvideos.requestor_email as reciever_email",
                       "requestvideos.Name as reciever_name",
                       "requestvideos.sender_email",
                       "requestvideos.sender_name" ,
                       "requestvideos.Description as message",
                       "requestvideos.song_name",
                       "requestvideos.Title as occassion",
                       "requested_videos.id as ID", 
                       "requested_videos.url as videoUrl",
                       "requested_videos.thumbnail as videoThumbnail",
                       "requested_videos.token as request_token",
                       "profiles.Name as artistName",
                       "profiles.ProfileId"
                       ])
                       ->first();

                //$m->videoUrl = $s3->url('requested_video/watermark/'.$m->videoUrl);
                $m->videoThumbnail = $s3->url('images/thumbnails/'.$m->videoThumbnail);
                $m->artist_id =  $m->ProfileId;
                $m->video_id = $m->VideoReqId;

                if ($m->reciever_email != $m->sender_email) {
                    Mail::send('emails.cronDeliveryRecipientEmail', ['data' => $m], function ($message) use ($m) {
                        $message->from('noreply@videorequestline.com', 'Gift videorequestline');
                        $message->to($m->reciever_email, $m->reciever_name);
                        $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                        $message->subject('You have received new gift from : ' . $m->sender_name);
                    });
                }                                      

                DB::table('requested_videos')->where('id',$m->ID)
                        ->update(['is_email_sent' => 1]);
            }

             /**
              * end recepient email for same delivery date
              */
            return response()->json(null, 200);
        }
    }

    public function sliders()
    {
        $slider_data = DB::table('sliders')->select('sliders.slider_title', 'sliders.mob_slider_desc', 'sliders.mob_slider_path', 'profiles.profile_url')
            ->join('profiles', function ($join) {
                $join->on('profiles.ProfileId', '=', 'sliders.artist_id')
                    //->where('profiles.Type', '=','Artist')
                    ->where('sliders.slider_visibility', '=', '1');
            })
            ->join('users', function ($join) {
                $join->on('users.profile_id', '=', 'sliders.artist_id')
                    ->where('users.is_account_active', '=', '1');
            })->get();

        if (!is_null($slider_data)) {
            $slider_data = $slider_data;
            $msg = array('response' => 200, 'data' => $slider_data, 'default_slider_path' => '');
            return json_encode($msg);
        } else {
            $msg = array('response' => 200, 'data' => 'no slider found', 'default_slider_path' => 'images/artist_default.jpg');
            return json_encode($msg);
        }

    }

    /**
     * return artist's data when user request for the artist's profile
     * @param Request $request
     * @return string
     */
    public function artistProfile(Request $request)
    {
        if (empty($request->access_token)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        $user = User::whereProfileId($request->id)->whereType('Artist')->with('profile')->first();
        if (!is_null($user)) {
            $socialLink = DB::table('social_media')->where('addBy_profileId', $user->profile_id)
                ->whereIsActive('Enable')->get();
            $video = Video::where('ProfileId', $user->profile_id)->get();
            $artistData['user_profile'] = $user;
            $artistData['video'] = count($video) > 0 ? $socialLink : null;
            $artistData['social_link'] = count($socialLink) > 0 ? $socialLink : null;
            $artistData['related_artist'] = null;
            return response()->json($artistData, 200);
        } else {
            return response()->json(null, 404);
        }
    }

    /**
     * returns the profile details of artist for him/her self
     * @param Request $request
     * @return string
     */
    public function getArtistProfile(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'artist_profile_id' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::whereAccessToken($request->access_token)->first();
        if (is_null($user)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        #1. check for artist, exists or not
        $artist = User::whereProfileId($request->artist_profile_id)
            ->whereIsAccountActive(1)
            ->whereType('Artist')
            ->with('profile')
            ->first();
        $s3 = Storage::disk('s3');
        if (!is_null($artist)) {
            $artist->profile->profile_path = !is_null($artist->profile->profile_path) ?
                $s3->url('images/Artist/'.$artist->profile->profile_path) : null;
            #2. get user profile detail of artist
            $artistData['user_profile'] = $artist;

            #3. get 5 latest videos of artist for slider
            $latestVideos = RequestedVideo::select('request_id', 'title', 'requestBy', 'thumbnail', 'fileName')
                ->where('uploadedby', $artist->profile_id)
                ->where('Upload_date', '<=', date('Y-m-d'))
                ->where('is_email_sent', 1)
                ->orderBy('Upload_date', 'DESC')
                ->with(['requestVideo' => function ($query) {
                    $query->select('VideoReqId', 'Name');
                }])->whereHas('requestVideo', function ($query) {
                    $query->where('requestvideos.is_hidden', 0);
                })->limit(5)->get();
            foreach ($latestVideos as $key => $video) {
                if (!is_null($video->fileName)) {
                    if (!empty($video->fileName)) {
                        $video->fileName = $s3->url('requested_video/watermark/'.$video->fileName);
                    } else {
                        $video->fileName = null;
                    }
                    if (!empty($video->thumbnail)) {
                        $video->thumbnail = $s3->url('images/thumbnails/'.$video->thumbnail);
                    } else {
                        $video->thumbnails = null;
                    }
                }
            }
            $artistData['video'] = count($latestVideos) > 0 ? $latestVideos : null;

            #4. and get 5 sample videos of artist for slider
            $sampleVideos = Video::select('Title as title', 'VideoThumbnail as thumbnail', 'originalVideoUrl as fileName')
                ->where('UploadedBy', 'Artist')
                ->where('ProfileId', $artist->profile_id)
                ->where('VideoUploadDate', '<=', date('Y-m-d'))
                ->orderBy('VideoUploadDate', 'DESC')
                ->limit(5)->get();

            foreach ($sampleVideos as $key => $sample) {
                if (!is_null($sample->fileName)) {
                    if (!empty($sample->fileName)) {
                        $sample->fileName = $s3->url('video/original/'.$sample->fileName);
                    } else {
                        $sample->fileName = null;
                    }
                    if (!empty($sample->thumbnail)) {
                        $sample->thumbnail = $s3->url('images/thumbnails/'.$sample->thumbnail);
                    } else {
                        $sample->thumbnail = null;
                    }
                }
            }
            $artistData['sample'] = count($sampleVideos) > 0 ? $sampleVideos : null;

            #5. get artist reviews and rating details
            $reviews = Testimonial::select('Message', 'rate')
                ->whereToProfileId($artist->profile_id)
                ->whereShowArtist(1)
                ->where('AdminApproval', 1)
                ->orderByRaw("RAND()")
                ->limit(3)
                ->get();
            $reviewsCount = Testimonial::whereToProfileId($artist->profile_id)
                ->where('AdminApproval', 1)
                ->whereShowArtist(1)
                ->count();
            $artistData['reviews'] = count($reviews) > 0 ? $reviews : null;
            $artistData['review_count'] = count($reviews) > 0 ? $reviewsCount : null;

            #6. get categories, artist belongs to
            $categoriesId = ArtistCategory::whereProfileId($artist->profile_id)->pluck('category_id')->toArray();
            $categories = null;
            if (count($categoriesId) > 0) {
                $categories = Category::select('id', 'title')->whereStatus(1)->whereIn('id', $categoriesId)->get();
            }
            $artistData['categories'] = count($categories) > 0 ? $categories : null;

            /*
             * if user is requesting for artist profile then we will add related artists too
             * */
            #7. get related artist belong to same category
            if (isset($request->type) && $request->type == 'User') {
                $artistData['related_artist'] = null;
                $artistCategory = ArtistCategory::leftJoin('category', function ($join) {
                    $join->on('artist_category.category_id', '=', 'category.id');
                })->where('artist_category.profile_id', $artist->profile_id)->get();
                $categoryId = $artistCategory->pluck('category_id');
                $relatedArtistId = ArtistCategory::whereIn('category_id', $categoryId)
                    ->where('profile_id', '!=', $artist->profile_id)->pluck('profile_id');
                $relatedArtist = Profile::select('ProfileId', 'Name', 'profile_path')
                    ->whereIn('ProfileId', $relatedArtistId)
                    ->limit(10)
                    ->get();
                if (count($relatedArtist) > 0) {
                    foreach ($relatedArtist as $key => $artist) {
                        if (!empty($artist->profile_path)) {
                            $artist->profile_path = $s3->url('images/Artist/' . $artist->profile_path);
                        } else {
                            $artist->profile_path = null;
                        }
                    }
                    $artistData['related_artist'] = $relatedArtist;
                }
            }
            return response()->json(['artist' => $artistData], 200);
        } else {
            return response()->json(null, 404);
        }
    }

    public function user_status($user_id)
    {
        $user = \App\User::where('profile_id', $user_id)->first();
        if (is_null($user)) {
            return 'false';
        } else if ($user->is_account_active == '0') {
            return 'deactivated';
        } else {
            return 'true';
        }
    }

    /**
     * returns list of pending video requests
     * @param int $artist
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPendingVideos($artist)
    {
        $requestedVideos = Requestvideo::where('RequestToProfileId', $artist)
            ->where('RequestStatus', 'Pending')->with('user')->get();
        return response()->json($requestedVideos, 200);
    }

    /**
     * returns user or artist profile data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile(Request $request)
    {
        if (!$request->has('profile_id') || !$request->has('type') || !$request->has('token')) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        } else {
            $user = User::whereProfileId($request->input('profile_id'))
                ->whereType($request->input('type'))
                ->whereAccessToken($request->input('token'))
                ->with('profile')
                ->first();
        }
        $s3 = Storage::disk('s3');
        if (is_null($user)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        $user->profile->profile_path = $s3->url('images/Artist/'.$user->profile->profile_path);
        return response()->json($user, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'device_token' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::whereAccessToken($request->access_token)->first();
        if (!is_null($user)) {
            $user->decrement('mobile_login_count');
            if ($user->mobile_login_count == 0) {
                $user->update(['access_token' => null, 'device_token' => '']);
            } else {
                $existingTokens = explode(',', $user->device_token);
                if (in_array($request->device_token, $existingTokens)) {
                    unset($existingTokens[array_search($request->device_token, $existingTokens)]);
                }
                $tokensToBeUpdated = implode(',', $existingTokens);
                $user->device_token = $tokensToBeUpdated;
                $user->save();
            }
            return response()->json(null, 200);
        } else {
            return response()->json(null, 401);
        }
    }

    /**
     * retrieve and returns the bookings having status pending & approved
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBookings(Request $request)
    {
        if (!isset($request->access_token)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        } else {
            $user = User::whereAccessToken($request->access_token)->first();
            if (is_null($user)) {
                return response()->json(\Lang::get('messages.unauthorized_access'), 401);
            }
        }
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'profile_id' => 'required',
            'offset' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $videos = Requestvideo::where('requestToProfileId', $request->profile_id)
            ->whereIn('RequestStatus', ['Pending', 'Approved'])
            ->with('requestByUser', 'requestByProfile', 'requestToUser', 'requestToProfile')
            ->offset($request->offset)
            ->orderBy('VideoReqId', 'DESC')
            ->limit(10)
            ->get();
        $s3 = Storage::disk('s3');
        if (count($videos) > 0) {
            $path = $s3->url('usersRecords/');
            foreach ($videos as $video) {
                if (!is_null($video->recipient_record)) {
                    $video->receipient_pronunciation = $path . $video->recipient_record;
                } elseif (!is_null($video->receipient_pronunciation)) {
                    $video->receipient_pronunciation = $path . $video->receipient_pronunciation;
                } else {
                    $video->receipient_pronunciation = null;
                }
                if (!is_null($video->sender_record)) {
                    $video->sender_name_pronunciation = $path . $video->sender_record;
                } elseif (!is_null($video->sender_name_pronunciation)) {
                    $video->sender_name_pronunciation = $path . $video->sender_name_pronunciation;
                } else {
                    $video->sender_name_pronunciation = null;
                }
                $video->complitionDate = date('m/d/Y', strtotime($video->complitionDate));
            }
            return response()->json(['bookings' => $videos], 200);
        } else {
            return response()->json(null, 404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editProfile(Request $request)
    {
        if (!isset($request->access_token)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        } else {
            $user = User::whereAccessToken($request->access_token)->first();
            if (is_null($user)) {
                return response()->json(\Lang::get('messages.unauthorized_access'), 401);
            }
        }
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'full_name' => 'required|min:2|max:30',
            'phone_no' => 'required|min:10|max:15',
            'country' => 'required',
            'zip' => 'min:5|max:12',
            'profile_id' => 'required',
            'image' => 'mimes:jpeg,jpg,png',
            'access_token' => 'required',
        ], $messages
        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $fileName = 'default-artist.png';
        $s3 = Storage::disk('s3');
        if ($request->hasfile('image')) {
            $image = $request->file('image');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            //$s3->put('images/Artist/'.$fileName, file_get_contents($image));
            $image->move('images/Artist/', $fileName);
            multipartUpload('images/Artist/'.$fileName);
            unlink('images/Artist/'.$fileName);
        }
        \DB::beginTransaction();
        $profile = Profile::where('ProfileId', $request->profile_id)->where('Type', 'User')->first();
        $profile->Name = $request->full_name;
        $profile->MobileNo = $request->phone_no;
        $profile->Address = $request->address;
        $profile->City = $request->city;
        $profile->State = $request->state;
        $profile->country = $request->country;
        $profile->profile_path = $fileName;
        $profile->Zip = $request->zip;
        $profile->save();
        \DB::commit();
        $profile = Profile::where('ProfileId', $request->profile_id)->with('user')->first();
        return response()->json($profile, 200);
    }

    /**
     * upload reaction videos
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reactionVideoUpload(Request $request){ 
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'requested_video_id' => 'required',
            'file' => 'required',
            'access_token' => 'required',
            'user_id' => 'required',   // it is profile id          
        ], $messages
        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::whereAccessToken($request->access_token)->first();
        if (is_null($user)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        $s3 = Storage::disk('s3');
        $file = $request->file('file');
        $mime = $file->getMimeType();
        $filesize = $request->file('file')->getSize();
        if ($mime == "video/mp4" || $mime == "video/quicktime" || $mime == "video/x-msvideo"
            || $mime == "video/x-flv" || $mime == "application/x-mpegURL" || $mime == "video/3gpp"
            || $mime == "video/x-ms-wmv") {
                $filename = $file->getClientOriginalName();
                $extention = $request->file('file')->getClientOriginalExtension();
                $rand = rand(11111, 99999) . date('U');
                $newfilename = $rand . '.' . $extention;
                $path = 'uploads/reaction-videos/';
                $move = $file->move($path, $newfilename);
                multipartUpload($path.$newfilename);
                $ffmpeg = FFMpeg\FFMpeg::create([
                    'ffmpeg.binaries' => '/usr/bin/ffmpeg',
                    'ffprobe.binaries' => '/usr/bin/ffprobe',
                    'timeout' => 3600,
                    'ffmpeg.threads' => 12,
                ]);
                $uploaded_video = $ffmpeg->open($path. $newfilename);
                $ffprobe = FFMpeg\FFProbe::create([
                    'ffmpeg.binaries' => '/usr/bin/ffmpeg',
                    'ffprobe.binaries' => '/usr/bin/ffprobe',
                    'timeout' => 3600,
                    'ffmpeg.threads' => 12,
                ]);
                $thumbnailName = $rand. '.jpg';
                $video_thumbnail_path = 'public/images/thumbnails/' . $thumbnailName;
                $uploaded_video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(2))->save($video_thumbnail_path);
                //$s3->put($video_thumbnail_path, file_get_contents($video_thumbnail_path));
                multipartUpload($video_thumbnail_path);
                unlink($video_thumbnail_path);
                unlink($path. $newfilename);
                
                if ($move) {
                    $postData = [
                        'UserId' => $request->user_id,
                        'VideoName' => $newfilename,
                        'VideoFormat' => $extention,
                        'VideoURL' => $newfilename,
                        'status' => 0,
                        'VideoUploadDate' => date('Y-m-d H:i:s'),
                        'thumbnail' => $thumbnailName,
                        'requested_video_id' => $request->requested_video_id,
                    ];
                    $id = DB::table('reactionvideos')->insert($postData);
                    return response()->json(['videoList' => $postData], 200);
                } else {
                    return response()->json(\Lang::get('messages.error_uploading_file'), 500);
                }
            } else {
                return response()->json(\Lang::get('messages.mp4_allowed'), 422);
            }        
    }

    /**
     * user report against artist
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user_report_against_artist(Request $request)
    { 
        if (empty($request->access_token)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);     
        }else{
            $user = User::whereAccessToken($request->access_token)->first();
            if (is_null($user)) {
                return response()->json(\Lang::get('messages.unauthorized_access'), 401);
            }
        }
        $data = $request->all();
        $validator = Validator::make($data, [
            'user_id' => 'required',
            'artist_id' => 'required',
            'report_type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            $repordata = [
                'by' => $request->user_id,
                'for' => $request->artist_id,
                'comment' => (!empty($request->comment))?$request->comment:"",
                'report_type' => $request->report_type,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $notifiction = [
                'send_from' => $request->user_id,
                'send_to' => $request->artist_id,
                'message' => (!empty($request->comment))?$request->comment:"",
                'type' => 'notification',
                'date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $id = DB::table('artist_report')->insert($repordata);
            $notification = DB::table('notification')->insert($notifiction);
                if ($id === true && $notification === true) {
                    return response()->json(\Lang::get('messages.report_success'), 200);
                } else {
                    return response()->json(\Lang::get('messages.report_error'), 500);
                }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArtistLatestVideos(Request $request)
    {
        if (empty($request->access_token)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        } else {
            $user = User::whereAccessToken($request->access_token)->first();
            if (is_null($user)) {
                return response()->json(\Lang::get('messages.unauthorized_access'), 401);
            }
        }
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'profile_id' => 'required',
            'offset' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $latestVideo = [];
        $s3 = Storage::disk('s3');

        /*
         * Abhishek
         * */
        $latest_video = DB::select('select
       requestvideos.title as Title,
       requestvideos.Name as Name,
       requested_videos.thumbnail as thumbnail,
       requested_videos.fileName as url,
       user.name user_name
       from requestvideos
       join requested_videos on requestvideos.VideoReqId = requested_videos.request_id
       inner join profiles artist on artist.ProfileId = requested_videos.uploadedby
       inner join profiles user on user.ProfileId = requested_videos.requestby
       inner join users on artist.ProfileId = users.profile_id and users.type = "Artist" and users.is_account_active = 1
       where requested_videos.id IN (select MAX(rv1.id) from requested_videos rv1 inner join requestvideos rv2 ON rv1.request_id=rv2.VideoReqId where rv2.is_hidden=0 AND rv1.is_email_sent=1 group by rv1.uploadedby)
        and requestvideos.is_hidden = 0
        ORDER BY requested_videos.id DESC
        limit 10');
        if (count($user) > 0) {
            $requestIds = [];
            if (count($latest_video) > 0) {
                foreach ($latest_video as $key => $latest) {
                    $latestVideo[$key]['name'] = $latest->Name;
                    $latestVideo[$key]['title'] = $latest->Title;
                    $latestVideo[$key]['thumbnail'] = $s3->url('images/thumbnails/'.$latest->thumbnail);
                    $latestVideo[$key]['video'] = $s3->url('requested_video/watermark/'.$latest->url);
                }
                if (count($latestVideo) > 0) {
                    $userHome['latest_video'] = count($latestVideo) > 0 ? $latestVideo : null;
                } else {
                    $userHome['latest_video'] = null;
                }
            } else {
                $userHome['latest_video'] = null;
            }
        } else {
            $userHome['latest_video'] = null;
        }
        #3. getting reaction videos
        $videos = ReactionVideo::whereStatus(1)->limit(10)->get();
        if (count($videos) > 0) {
            foreach ($videos as $key => $video) {
                $video->VideoURL = ($video->type == 1 || $video->type == 2) ? $video->VideoURL : $s3->url('uploads/reaction-videos/'.$video->VideoURL);
                $video->thumbnail = ($video->type == 1 || $video->type == 2) ? $video->thumbnail : $s3->url('images/thumbnails/'.$video->thumbnail);
            }
        }
        $userHome['reaction_videos'] = count($videos) > 0 ? $videos : null;
        if (count($userHome) > 0) {
            return response()->json($userHome, 200);
        } else {
            return response()->json(null, 404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArtistWallet(Request $request)
    {
        if (empty($request->access_token)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        } else {
            $artist = User::whereProfileId($request->profile_id)
                ->whereAccessToken($request->access_token)
                ->first();
            if (is_null($artist)) {
                return response()->json(\Lang::get('messages.unauthorized_access'), 401);
            }
        }
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'profile_id' => 'required',
            'offset' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        
        $walletObj = DB::table('payments as p')
            ->select('p.videoPrice as amount', 'profiles.Name as paid_by', 'p.payment_date as date')
            ->where('p.payer_id', $request->profile_id)
            ->where('p.is_refunded', 0)->where('p.video_status', 'Completed')
            ->join('requestvideos as rv', 'rv.VideoReqId', '=', 'p.video_request_id')
            ->join('profiles', 'profiles.ProfileId', '=', 'p.profile_id')
            ->orderBy('id', 'desc')
            ->offset($request->offset)
            ->limit(10)
            ->get();
        if (count($walletObj) > 0) {
            $walletTotal = DB::table('payments as p')
                ->select(DB::raw("SUM(p.videoPrice) as amount"))
                ->where('p.payer_id', $request->profile_id)
                ->where('p.is_refunded', 0)->where('p.video_status', 'Completed')
                ->join('requestvideos as rv', 'rv.VideoReqId', '=', 'p.video_request_id')
                ->get();
            $data = [];
            foreach ($walletObj as $key => $wallet) {
                //$wallet->amount = $wallet->amount * 70 / 100;
                $wallet->amount = $wallet->artist_share;
                $data[$key] = $wallet;
            }

            $receivedamount = DB::table('payments as p')
                ->select(DB::raw("SUM(p.artist_share) as artist_amount"))
                ->where('p.payer_id', $request->profile_id)
                ->where('p.is_refunded', 0)->where('p.video_status', 'Completed')
                ->join('requestvideos as rv', 'rv.VideoReqId', '=', 'p.video_request_id')
                ->get();

                $dueamount = DB::table('payments as p')
                ->select(DB::raw("SUM(p.artist_share) as artist_amount"))
                ->where('p.payer_id', $request->profile_id)
                ->where('p.is_refunded', 0)->where('p.video_status', 'Pending')
                ->join('requestvideos as rv', 'rv.VideoReqId', '=', 'p.video_request_id')
                ->get();

                $totalamount = $receivedamount + $dueamount;



            return response()->json(['wallet' => $data,
                'totalamount' => $totalamount,'receivedamount' => $receivedamount,'dueamount' => $dueamount, 200);
        } else {
            return response()->json(null, 404);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rejectVideoRequest(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
            'rejected_reason.in' => 'The selected reject reason is invalid.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'artist_profile_id' => 'required',
            'request_id' => 'required',
            'rejected_reason' => 'in:'.implode(',', array_keys(Config::get('constants.REJECTED_REASONS'))),
        ], $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $artist = User::whereProfileId($request->artist_profile_id)
            ->whereAccessToken($request->access_token)
            ->whereType('Artist')
            ->first();
        if (is_null($artist)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        $requestVideo = Requestvideo::where('VideoReqId', $request->request_id)->first();
        $artist = Profile::where('ProfileId', $request->artist_profile_id)->where('Type', 'Artist')->first();
        if (is_null($requestVideo) || $request->artist_profile_id != $requestVideo->requestToProfileId) {
            //Something went wrong please contact the admin
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }

        $data['sender_name'] = $requestVideo->sender_name;
        $data['recipient_name'] = $requestVideo->Name; // This is receptient
        $data['sender_email'] = $requestVideo->sender_email;
        $data['recipient_email'] = $requestVideo->requestor_email; // This is receptient
        $data['complitionDate'] = $requestVideo->complitionDate;
        $data['songName'] = $requestVideo->song_name;
        $data['occasion'] = $requestVideo->Title;
        $data['personalizedMessage'] = $requestVideo->Description;
        $data['artist'] = $artist->Name;
        $data['price'] = $requestVideo->ReqVideoPrice;
        $data['artist_id'] = $artist->ProfileId;
        $data['video_request_id'] = $requestVideo->VideoReqId;

        $data['identifier'] = $artist->ProfileId.'-'.$requestVideo->VideoReqId;
        $data['turnaroundTime'] = $artist->timestamp;

        if ($request->rejected_reason != '' || $request->rejected_comment != '') {
            $rejected_reason = $request->rejected_reason;
            if(!empty($rejected_reason) && array_key_exists($rejected_reason, Config::get('constants.REJECTED_REASONS')))
            {
                $rejected_reason = Config::get('constants.REJECTED_REASONS')[$rejected_reason];
            }
            $rejected_comment_string  = ($request->rejected_comment != '') ?  ' - ' . $request->rejected_comment : '';
            $data['rejected_reason'] = $rejected_reason.$rejected_comment_string;
        } else {
            $data['rejected_reason'] = 'No reason provided';
        }

        $requestVideo->RequestStatus = \Lang::get('views.reject');
        $requestVideo->is_refunded = 1;
        $requestVideo->is_active = 0;
        $requestVideo->rejected_reason = !empty($request->rejected_reason) ? $request->rejected_reason : null;
        $requestVideo->rejected_comment = !empty($request->rejected_comment) ? $request->rejected_comment : null;
        //$user_email = $requestVideo->requestor_email; // Who woll receive the Video as a gift
        $sender_email = $requestVideo->sender_email;   // who will send the request

        if ($requestVideo->is_active == 1) {
            //Request has been approved previously
            return response()->json(null, 200);
        }
        if ($requestVideo->save()) {
            $payment = Payment::whereVideoRequestId($request->request_id)->whereStatus('succeeded')->first();
            if (!is_null($payment)) {
                try {
                    if (App::environment('local') || App::environment('testing')) {
                        // The environment is local or testing
                        \Stripe\Stripe::setApiKey(Config::get('constants.STRIPE_KEYS.test.secret_key'));
                    } else {
                        // The environment is production
                        \Stripe\Stripe::setApiKey(Config::get('constants.STRIPE_KEYS.live.secret_key'));
                    }
                    $ch = \Stripe\Charge::retrieve($payment->charge_id);
                    if (!$ch->refunded) {
                        $ch->refund();
                        $payment->is_refunded = 1;
                        $payment->save();

                        $refund_details['status'] = 'refunded';
                        $refund_details['request_detail'] = $requestVideo;
                        $refund_details['artist'] = $artist->Name;
                        $refund_details['complitionDate'] = $requestVideo->complitionDate;
                        $refund_details['artist_id'] = $artist->ProfileId;
                        $refund_details['video_request_id'] = $requestVideo->VideoReqId;
                        $refund_details['identifier'] = $artist->ProfileId.'-'.$requestVideo->VideoReqId;
                        $refund_details['turnaroundTime'] = $artist->timestamp;
                        $refund_details['recipient_name'] = $requestVideo->Name;
                        $refund_details['recipient_email'] = $requestVideo->requestor_email;
                        $refund_details['artistName'] = $artist->Name;
                        $refund_details['video_title'] = $requestVideo->Occassion;
                        $refund_details['requester_name'] = $requestVideo->sender_name;
                        $refund_details['requester_email'] = $requestVideo->sender_email;
                        $refund_details['video_description'] = $requestVideo->person_message;
                        $refund_details['current_status'] = "Rejected";
                        $refund_details['delivery_date'] = $requestVideo->delivery_date;
                        $refund_details['songName'] = $requestVideo->song_name;
                        $refund_details['price'] = $requestVideo->artist_vidoe_price;
                        $refund_details['identifier'] = $artist->ProfileId.'-'.$requestVideo->VideoReqId;
                        $refund_details['rejected_reason'] = $rejected_reason = $request->rejected_reason;

                        if ($request->rejected_reason != '' || $request->rejected_comment != '') {
                            $rejected_reason = $request->rejected_reason;
                            if(!empty($rejected_reason) && array_key_exists($rejected_reason, Config::get('constants.REJECTED_REASONS')))
                            {
                                $rejected_reason = Config::get('constants.REJECTED_REASONS')[$rejected_reason];
                            }
                            $rejected_comment_string  = ($request->rejected_comment != '') ?  ' - ' . $request->rejected_comment : '';
                            $refund_details['rejected_reason_detail'] = $rejected_reason.$rejected_comment_string;
                        } else {
                            $refund_details['rejected_reason_detail'] = 'No reason provided';
                        }

                        $sender = $sender_email;
                        Mail::send('emails.refund_success', $refund_details, function ($message) use ($sender, $refund_details) {
                            $message->from('noreply@videorequestline.com', 'VRL');
                            $message->to($sender, 'Sender');
                            $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                            $message->subject('Payment Refunded Successfully ('.$refund_details['identifier'].')');
                        });
                    }
                } catch (\Stripe\Error\Card $e) {
                    // Since it's a decline, \Stripe\Error\Card will be caught
                    $body = $e->getJsonBody();
                    $err = $body['error'];
                    return response()->json($err['type'], $err['code']);
                } catch (\Stripe\Error\InvalidRequest $e) {
                    // Invalid parameters were supplied to Stripe's API
                    $body = $e->getJsonBody();
                    $err = $body['error'];
                    return response()->json($err['type'], $err['code']);
                } catch (\Stripe\Error\Authentication $e) {
                    // Authentication with Stripe's API failed
                    // (maybe you changed API keys recently)
                    $body = $e->getJsonBody();
                    $err = $body['error'];
                    return response()->json($err['type'], $err['code']);
                } catch (\Stripe\Error\ApiConnection $e) {
                    // Network communication with Stripe failed
                    $body = $e->getJsonBody();
                    $err = $body['error'];
                    return response()->json($err['type'], $err['code']);
                } catch (\Stripe\Error\Base $e) {
                    $body = $e->getJsonBody();
                    // Display a very generic error to the user, and maybe send
                    // yourself an email
                    $err = $body['error'];
                    return response()->json($err['type'], $err['code']);
                } catch (Exception $e) {
                    // Something else happened, completely unrelated to Stripe
                    return response()->json($e->getMessage(), 500);
                }
                $sender = $sender_email;
                Mail::send('emails.video_request_reject', $data, function ($message) use ($sender,$data) {
                    $message->from('noreply@videorequestline.com', \Lang::get('views.vrl'));
                    $message->to($sender, \Lang::get('views.user'));
                    $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                    $message->subject('Sorry we cannot fulfill your request ! ('.$data['identifier'].')');
                });
                //Rejected successfully
                $rejectedRequest = Requestvideo::where('VideoReqId', $requestVideo->VideoReqId)
                    ->with('requestToUser', 'requestToProfile')->first();
                $user_detail = DB::table('users')->where('profile_id', '=', $requestVideo->requestByProfileId)->first();
                $deviceToken = $user_detail->device_token;
                $deviceType = $user_detail->device_type;
                if (is_null($user_detail->push_notification) || $user_detail->push_notification == 1) {
                    if ($deviceToken != null) {
                        $message = 'Your video request has been rejected by ' . $artist->Name;
                        push_notification($deviceToken, $message, 2);
                    }
                }
                return response()->json($rejectedRequest, 200);
            }
        } else {
            return response()->json(null, 500);
        }
    }

    /**
     * API for Accepting Video Request
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function approveVideoRequest(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'artist_profile_id' => 'required',
            'request_id'    =>  'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $artist = User::whereProfileId($request->artist_profile_id)
            ->whereAccessToken($request->access_token)
            ->whereType('Artist')
            ->first();
        if (is_null($artist)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        $requestVideo = Requestvideo::where('VideoReqId', $request->request_id)->first();
        $artist = Profile::where('ProfileId', $request->artist_profile_id)->where('Type', 'Artist')->first();
        $data['artist'] = $artist->Name;
        $data['artist_id'] = $artist->ProfileId;
        $data['video_request_id'] = $request->request_id;
        $data['user'] = $requestVideo->Name;
        $data['price'] = $requestVideo->ReqVideoPrice;
        $data['complitionDate'] = $requestVideo->complitionDate;
        $data['video_title'] = $requestVideo->Occassion;
        $data['recipient_email'] = $requestVideo->requestor_email;
        $data['current_status'] = "Approved";
        $data['songName'] = $requestVideo->song_name;
        $data['identifier'] = $artist->ProfileId.'-'.$requestVideo->VideoReqId;
        $data['video_description'] = $requestVideo->Description;

        $requestVideo->RequestStatus = \Lang::get('views.approved');
        //$requestVideo->ReqVideoPrice = $artist->VideoPrice;
        if ($requestVideo->is_active == 0) {
            //Request has been rejected previously
            return response()->json(null, 404);
        }
        if ($requestVideo->save()) {
            Payment::whereVideoRequestId($requestVideo->VideoReqId)->update([
                'video_status'  =>  \Lang::get('views.approved')
            ]);
            if ($requestVideo->sender_email != "") {
                Mail::send('emails.video_response', $data, function ($message) use ($requestVideo, $data) {
                    $message->from('noreply@videorequestline.com', \Lang::get('views.vrl'));
                    $message->to($requestVideo->sender_email, \Lang::get('views.user'));
                    $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                    $message->subject('Video Request Approved! ('.$data['identifier'].')');
                });
            }

            $approvedRequest = Requestvideo::where('VideoReqId', $requestVideo->VideoReqId)
                ->with('requestToUser', 'requestToProfile', 'requestByUser', 'requestByProfile')->first();
            $user_detail = DB::table('users')->where('profile_id', '=', $requestVideo->requestByProfileId)->first();
            $deviceToken = $user_detail->device_token;
            $deviceType = $user_detail->device_type;
            Log::info('deviceToken: - API Line:892 :'.$deviceToken);
            if (is_null($user_detail->push_notification) || $user_detail->push_notification == 1) {
                if ($deviceToken != null) {
                    Log::info('deviceToken not null  - API Line:892');
                    $message = 'Congratulations, your video request has been approved by ' . $artist->Name;
                    push_notification($deviceToken, $message, 1);
                }
            }
            return response()->json($approvedRequest, 202);
        }
        
        return response()->json(null, 500);
    }
    /**
     * reaction videos list
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reactionVideoList(Request $request)
    {
        if (empty($request->access_token)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        } else {
            $user = User::whereAccessToken($request->access_token)->first();
            if (is_null($user)) {
                return response()->json(\Lang::get('messages.unauthorized_access'), 401);
            }
        }
        $s3 = Storage::disk('s3');
        $videos = ReactionVideo::whereStatus(1)->get();
        if (count($videos) > 0) {
            foreach ($videos as $key => $video) {
                $video->VideoURL = ($video->type == 1 || $video->type == 2) ? $video->VideoURL : $s3->url('uploads/reaction-videos/'.$video->VideoURL);
                $video->thumbnail = ($video->type == 1 || $video->type == 2) ? $video->thumbnail : $s3->url('images/thumbnails/'. $video->thumbnail);
            }
            return response()->json(['videoList' => $videos], 200);
        } else {
            return response()->json(null, 404);
        }
    }

    /**
     * we are getting these reviews from testimonial table
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArtistReviews(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'artist_profile_id' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::whereAccessToken($request->access_token)->first();
        if (is_null($user)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        $artist = User::whereProfileId($request->artist_profile_id)
            ->whereType('Artist')
            ->whereIsAccountActive(1)
            ->first();
        if (is_null($artist)) {
            return response()->json(\Lang::get('messages.artist_not_active_or_found'), 404);
        }
        $reviews = Testimonial::select('by_profile_id', 'Message', 'rate', 'created_at')
            ->whereToProfileId($artist->profile_id)
            ->whereShowArtist(1)
            ->where('AdminApproval', 1)
            ->with(['reviewedBy' => function ($query) {
                $query->select('Name', 'ProfileId', 'profile_path');
            }])
            ->offset($request->offset)
            ->limit(10)->get();
        $s3 = Storage::disk('s3');
        if (count($reviews) > 0) {
            $reviewData = [];
            $count = 0;
            foreach ($reviews as $key => $review) {
                $date = $review->created_at->format('d-m-Y');
                $reviewData[$count] = [
                    'by_profile_id' => $review->by_profile_id,
                    'Message' => $review->Message,
                    'rate' => $review->rate,
                    'reviewed_at' => $date,
                    'reviewed_by' => (!is_null($review->reviewedBy)) ? [
                        'Name' => $review->reviewedBy->Name,
                    'ProfileId' => $review->reviewedBy->ProfileId,
                    ] : null,
                ];
                if (!is_null($review->reviewedBy)) {
                    if (!empty($review->reviewedBy->profile_path)) {
                        $reviewData[$count]['reviewed_by']['profile_path'] = $s3->url('images/Artist/'.$review->reviewedBy->profile_path);
                    }
                }
                $count++;
            }
            return response()->json(['reviews' => $reviewData], 200);
        } else {
            return response()->json(null, 404);
        }
    }

    /**
     * get this artist's latest videos
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getThisArtistLatestVideo(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'artist_profile_id' => 'required',
            'offset' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::whereAccessToken($request->access_token)->first();
        if (is_null($user)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        $artist = User::whereProfileId($request->artist_profile_id)
            ->whereType('Artist')
            ->whereIsAccountActive(1)
            ->first();
        if (is_null($artist)) {
            return response()->json(\Lang::get('messages.artist_not_active_or_found'), 404);
        }
        $latestVideos = RequestedVideo::select('request_id', 'title', 'description',
            'requestBy', 'thumbnail', 'fileName')
            ->where('uploadedby', $artist->profile_id)
            ->where('is_email_sent', 1)
            ->orderBy('Upload_date', 'DESC')
            ->with(['requestVideo' => function ($query) {
                $query->select('VideoReqId', 'Name');
            }])->whereHas('requestVideo', function ($query) {
                $query->where('requestvideos.is_hidden', 0);
            })
            ->offset($request->offset)
            ->limit(10)->get();
        $s3 = Storage::disk('s3');
        foreach ($latestVideos as $key => $video) {
            if (!is_null($video->fileName)) {
                if (!empty($video->fileName)) {
                    $video->fileName = $s3->url('requested_video/watermark/'.$video->fileName);
                } else {
                    $video->fileName = null;
                }
                if (!empty($video->thumbnail)) {
                    $video->thumbnail = $s3->url('images/thumbnails/'.$video->thumbnail);
                } else {
                    $video->thumbnail = null;
                }
            }
        }
        if (count($latestVideos) > 0) {
            return response()->json(['latest_videos' => $latestVideos], 200);
        } else {
            return response()->json(null, 404);
        }
    }

    /**
     * get related artist of this artist
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRelatedArtist(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'artist_profile_id' => 'required',
            'offset' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::whereAccessToken($request->access_token)->first();
        if (is_null($user)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        $artist = User::whereProfileId($request->artist_profile_id)
            ->whereType('Artist')
            ->whereIsAccountActive(1)
            ->with('profile')
            ->first();
        if (is_null($artist)) {
            return response()->json(\Lang::get('messages.artist_not_active_or_found'), 404);
        }

        $artistCategory = ArtistCategory::leftJoin('category', function ($join) {
            $join->on('artist_category.category_id', '=', 'category.id');
        })->where('artist_category.profile_id', $artist->profile_id)->get();
        $categoryId = $artistCategory->pluck('category_id');
        $relatedArtistId = ArtistCategory::whereIn('category_id', $categoryId)
            ->where('profile_id', '!=', $artist->profile_id)->pluck('profile_id');
        $relatedArtist = Profile::select('ProfileId', 'Name', 'profile_path')
            ->whereIn('ProfileId', $relatedArtistId)
            ->offset($request->offset)
            ->limit(10)->get();
        $s3 = Storage::disk('s3');
        if (count($relatedArtist) > 0) {
            foreach ($relatedArtist as $key => $artist) {
                $artist->profile_path = !empty($artist->profile_path) ?
                    $s3->url('images/Artist/'.$artist->profile_path) : null;
            }
        }
        if (count($relatedArtist) > 0) {
            return response()->json(['artist' => $relatedArtist], 200);
        } else {
            return response()->json(null, 404);
        }
    }

    /**
     * get Pending videos list of user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserApprovedPendingVideos(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'user_profile_id' => 'required',
            'offset' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::whereAccessToken($request->access_token)->first();
        if (is_null($user)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        $pendingList = Requestvideo::select('description', 'request_date', 'complitionDate', 'requestToProfileId',
            'RequestStatus as status')
            ->with(['requestToProfile' => function ($query) {
                $query->select('ProfileId', 'Name');
            }])
            ->where('requestByProfileId', $request->user_profile_id)
            ->whereIn('RequestStatus', ['Pending', 'Approved'])
            ->offset($request->offset)
            ->orderBy('VideoReqId', 'DESC')
            ->limit(10)->get();
        if (count($pendingList) > 0) {
            return response()->json(['pending' => $pendingList], 200);
        } else {
            return response()->json(null, 404);
        }
    }

    /**
     * get Completed videos list of user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserCompletedVideos(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'user_profile_id' => 'required',
            'offset' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::whereAccessToken($request->access_token)->first();
        if (is_null($user)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        $completedList = Requestvideo::select('description', 'request_date', 'complitionDate', 'requestToProfileId',
            'VideoReqId', 'requestByProfileId')
            ->with([
                'requestToProfile' => function ($query) {
                    $query->select('ProfileId', 'Name');
                },
                'requestedVideo' => function ($query) {
                    $query->select('request_id', 'thumbnail', 'fileName');
                }])
            ->where('requestByProfileId', $request->user_profile_id)
            ->where('RequestStatus', 'Completed')
            ->offset($request->offset)
            ->orderBy('VideoReqId', 'DESC')
            ->limit(10)->get();
        $s3 = Storage::disk('s3');
        if (count($completedList) > 0) {
            foreach ($completedList as $key => $completed) {
                $testimonial = Testimonial::whereByProfileId($completed->requestByProfileId)
                    ->whereVideoId($completed->VideoReqId)
                    ->first();
                if (!is_null($completed->requestToProfile)) {
                    $completed->artist_name = $completed->requestToProfile->Name;
                } else {
                    $completed->artist_name = null;
                }
                unset($completed->requestToProfile);
                $completed->review_status = !is_null($testimonial) ? 1 : 0;
                if (!empty($completed->requestedVideo->thumbnail)) {
                    $completed->requestedVideo->thumbnail =
                        $s3->url('images/thumbnails/'.$completed->requestedVideo->thumbnail);
                }
                if (!is_null($completed->requestedVideo)) {
                    $completed->requestedVideo->fileName =
                        $s3->url('requested_video/watermark/'.$completed->requestedVideo->fileName);
                }
                $completed->complitionDate = date('m/d/Y', strtotime($completed->complitionDate));
            }
            return response()->json(['completed' => $completedList], 200);
        } else {
            return response()->json(null, 404);
        }
    }

    /**
     * add video review by user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addVideoReview(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'user_profile_id' => 'required',
            'video_id' => 'required',
            'message' => 'required',
            'rate' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::whereAccessToken($request->access_token)->first();
        if (is_null($user)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        $videoRequest = RequestVideo::where('VideoReqId', $request->video_id)->first();
        if (is_null($videoRequest)) {
            return response()->json(null, 404);
        }
        $profile = Profile::where('ProfileId', $request->user_profile_id)->first();
        $isReviewed = Testimonial::whereByProfileId($profile->ProfileId)
            ->whereVideoId($videoRequest->VideoReqId)
            ->first();
        if (is_null($isReviewed)) {
            $censor = new CensorWords;
            $string = $censor->censorString($request->message);
            Testimonial::create([
                'by_profile_id'  =>  $profile->ProfileId,
                'to_profile_id'  =>  $videoRequest->requestToProfileId,
                'video_id'  =>  $videoRequest->VideoReqId,
                'AdminApproval' =>  0,
                'rate'  =>  $request->rate,
                'Message'  =>  $string['clean'],
                'is_active'  =>  0,
                'user_name' =>  $profile->Name,
                'Email' =>  $profile->EmailId
            ]);
            return response()->json(null, 201);
        }
        return response()->json(null, 409);
    }

    /**
     * get user home screen
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserHomeScreen(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'user_profile_id' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::whereAccessToken($request->access_token)->first();
        if (is_null($user)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        $userHome = [];
        #1. getting chart toppers
        $artists = DB::table('profiles')->select('profiles.*', 'users.*')
            ->join('users', function ($join) {
                $join->on('profiles.ProfileId', '=', 'users.profile_id')
                    ->where('users.type', '=', 'Artist')
                    ->where('users.is_chart_topper', '=', '1')
                    ->where('users.is_account_active', '=', '1');
            })->limit(10)->get();
        $s3 = Storage::disk('s3');
        if (count($artists) > 0) {
            foreach ($artists as $key => $artist) {
                $artist->profile_path =
                    !empty($artist->profile_path) ? $s3->url('images/Artist/'.$artist->profile_path) : null;
            }
            $userHome['chart_toppers'] = $artists;
        } else {
            $userHome['chart_toppers'] = null;
        }
        #2. getting latest videos
        $latestVideo = [];


        /*
         * Abhishek
         * */
        $latest_video = DB::select('select
       requestvideos.title as Title,
       requestvideos.Name as Name,
       requested_videos.thumbnail as thumbnail,
       requested_videos.fileName as url,
       user.name user_name
       from requestvideos
       join requested_videos on requestvideos.VideoReqId = requested_videos.request_id
       inner join profiles artist on artist.ProfileId = requested_videos.uploadedby
       inner join profiles user on user.ProfileId = requested_videos.requestby
       inner join users on artist.ProfileId = users.profile_id and users.type = "Artist" and users.is_account_active = 1
       where requested_videos.id IN (select MAX(rv1.id) from requested_videos rv1 inner join requestvideos rv2 ON rv1.request_id=rv2.VideoReqId where rv2.is_hidden=0 AND rv1.is_email_sent=1 group by rv1.uploadedby)
        and requestvideos.is_hidden = 0
        ORDER BY requested_videos.id DESC
        limit 10');
        if (count($user) > 0) {
            $requestIds = [];
            if (count($latest_video) > 0) {
                foreach ($latest_video as $key => $latest) {
                    $latestVideo[$key]['name'] = $latest->Name;
                    $latestVideo[$key]['title'] = $latest->Title;
                    $latestVideo[$key]['thumbnail'] = $s3->url('images/thumbnails/'.$latest->thumbnail);
                    $latestVideo[$key]['video'] = $s3->url('requested_video/watermark/'.$latest->url);
                }
                if (count($latestVideo) > 0) {
                    $userHome['latest_videos'] = count($latestVideo) > 0 ? $latestVideo : null;
                } else {
                    $userHome['latest_videos'] = null;
                }
            } else {
                $userHome['latest_videos'] = null;
            }
        } else {
            $userHome['latest_videos'] = null;
        }
        #3. getting reaction videos
        $videos = ReactionVideo::whereStatus(1)->limit(10)->get();
        if (count($videos) > 0) {
            foreach ($videos as $key => $video) {
                $video->VideoURL = ($video->type == 1 || $video->type == 2) ? $video->VideoURL : $s3->url('uploads/reaction-videos/'.$video->VideoURL);
                $video->thumbnail = ($video->type == 1 || $video->type == 2) ? $video->thumbnail : $s3->url('images/thumbnails/'.$video->thumbnail);
            }
        }
        $userHome['reaction_videos'] = count($videos) > 0 ? $videos : null;
        if (count($userHome) > 0) {
            return response()->json($userHome, 200);
        } else {
            return response()->json(null, 404);
        }
        /*#3. getting reaction videos
        $videos = ReactionVideo::whereStatus(1)->limit(10)->get();
        if (count($videos) > 0) {
            foreach ($videos as $key => $video) {
                $video->VideoURL = ($video->type == 1 || $video->type == 2) ? $video->VideoURL : url('uploads/reaction-videos/') . '/' . $video->VideoURL;
                $video->thumbnail = ($video->type == 1 || $video->type == 2) ? $video->thumbnail : url('images/thumbnails/') . '/' . $video->thumbnail;
            }
        }
        $userHome['reaction_videos'] = count($videos) > 0 ? $videos : null;
        if (count($userHome) > 0) {
            return response()->json($userHome, 200);
        } else {
            return response()->json(null, 404);
        }*/
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editArtist(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'artist_profile_id' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $artist = User::select('profile_id')
            ->whereProfileId($request->artist_profile_id)
            ->whereIsAccountActive(1)
            ->whereType('Artist')
            ->with(['profile' => function ($query) {
                $query->select('ProfileId', 'Name as artist_name', 'Address as address', 'City as city',
                    'State as state', 'country', 'Zip', 'profile_path', 'NickName as nick_name', 'Gender as gender',
                    'DateOfBirth as date_of_birth', 'MobileNo as phone_no', 'profile_description as description');
            }])->first();

        //if artist not found or deactivated then return 404
        if(empty($artist))
        {
            return response()->json(\Lang::get('messages.artist_not_active_or_found'), 404);
        }

        $main_category_id = '';
        $artistCategories = ArtistCategory::whereProfileId($artist->profile_id);
        foreach ($artistCategories->get() as $artistCategory) {
            if ($artistCategory->main_category == 1 && $main_category_id == '') {
                $mainCategory = Category::whereStatus(1)->whereId($artistCategory->category_id)->select('title', 'id')->get();
                $artist->linked_main_category = $mainCategory;
            }
        }
        $s3 = Storage::disk('s3');
        $categoryId = $artistCategories->pluck('category_id');
        $categories = Category::whereStatus(1);
        if (!is_null($artist)) {
            if (!is_null($artist->profile->profile_path)) {
                $artist->profile->profile_path = $s3->url('images/Artist/'.$artist->profile->profile_path);
            } else {
                $artist->profile->profile_path = url('/images/Artist/') . '/default-artist.png';
            }
            $artist->categories = $categories->select('title', 'id')->get();
            $artist->linked_categories = $categories->whereIn('id', $categoryId)->select('title', 'id')->get();
        } else {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        return response()->json($artist, 200);
    }

    /**
     * update artist profile
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editArtistProfile(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
            'artist_name.regex' => ' :attribute should not contain number or special character',
            'phone.regex' => ' Phone should contain numbers only',
            'password.regex' => ' Use at least one letter, one numeral & one special character',
            'image.dimensions' => 'Image size must be 400 x 400 pixels',
            'date_of_birth.date_format' => 'Date format must be in m-d-Y (eg. 05-20-2018)',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'artist_profile_id' => 'required',
            'artist_name' => 'required|min:2|max:30',
            'date_of_birth' => 'required|date_format:"m-d-Y"',
            'phone_no' => 'required|min:10|max:15',
            'category_id' => 'required',
            'main_category_id' => 'required',
            'nick_name' => 'min:2|max:30|regex:/^[\pL\s\-]+$/u',
            'zip' => 'required|min:5|max:12',
            'image' => 'mimes:jpeg,jpg,png',
            'gender' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $artist = User::whereProfileId($request->artist_profile_id)
            ->whereAccessToken($request->access_token)
            ->whereIsAccountActive(1)
            ->whereType('Artist')
            ->first();
        if (is_null($artist)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        $dob = $request->date_of_birth;
        $birthday = \DateTime::createFromFormat('m-d-Y', $dob);
        $diff = $birthday->diff(new \DateTime());
        if ($diff->y < 18) {
            return response()->json(\Lang::get('messages.age_must_be_18'), 422);
        }
        try {
            $fileName = 'default-artist.png';
            $s3 = Storage::disk('s3');
            if ($request->hasFile('image')) {
                $rand = rand(11111, 99999) . date('U');
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $fileName = $rand . '.' . $extension;
                $destination = 'images/Artist/';
                //$s3->put($destination.$fileName, file_get_contents($file));
                $request->file('image')->move($destination, $fileName);
                multipartUpload($destination.$fileName);
                unlink($destination.$fileName);
            }
            \DB::beginTransaction();
            Profile::where('ProfileId', $artist->profile_id)->update([
                'Name' => $request->artist_name,
                'NickName' => $request->nick_name,
                'Gender' => $request->gender,
                'DateOfBirth' => date('m-d-Y', strtotime($request->date_of_birth)),
                'MobileNo' => $request->phone_no,
                'profile_description' => $request->description,
                'Address' => $request->address,
                'City' => $request->city,
                'State' => $request->state,
                'country' => $request->country,
                'Zip' => $request->zip,
                'profile_path' => $fileName,
            ]);
            ArtistCategory::whereProfileId($artist->profile_id)->delete();
            $categoryIds = (array)$request->category_id;
            foreach ($categoryIds as $categoryId) {
                ArtistCategory::create([
                    'profile_id' => $artist->profile_id,
                    'category_id' => $categoryId,
                ]);
            }
            $mainCategoryId = $request->main_category_id;
            if (in_array($mainCategoryId, $categoryIds)) {
                ArtistCategory::whereProfileId($artist->profile_id)
                    ->whereCategoryId($mainCategoryId)
                    ->update([
                        'main_category' => 1
                    ]);
            } else {
                ArtistCategory::create([
                    'profile_id' => $request->artist_profile_id,
                    'category_id' => $mainCategoryId,
                    'main_category' => 1
                ]);
            }
            $artist->date_of_birth = date('m-d-Y', strtotime($request->date_of_birth));
            $artist->save();
            \DB::commit();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(null, 500);
        }
        return response()->json(null, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArtistOccasions(Request $request) {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'artist_profile_id' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::whereAccessToken($request->access_token)->first();
        if (is_null($user)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        $occasions = Occasion::whereArtistProfileId($request->artist_profile_id)->get()->toArray();

        return response()->json(['occasion' => $occasions], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function getOccasionPrice(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'artist_profile_id' => 'required',
            'occasion_id' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::whereAccessToken($request->access_token)->first();
        if (is_null($user)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        $price = null;
        if ($request->artist_profile_id == 0) {
            $artist = Profile::find($request->artist_profile_id);
            $price = $artist->VideoPrice;
        } else {
            $occasion = Occasion::whereId($request->occasion_id)
                ->whereArtistProfileId($request->artist_profile_id)
                ->first();
            if (!is_null($occasion)) {
                $price = $occasion->price;
            }
        }
        return response()->json(['price' => $price], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pushNotification(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'profile_id' => 'required',
            'push_notification' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::whereProfileId($request->profile_id)->first();
        if (!is_null($user)) {
            $user->push_notification = $request->push_notification;
            $user->save();
        }
        return response()->json(null, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBankDetails(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'profile_id' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $artist = User::whereProfileId($request->profile_id)->whereAccessToken($request->access_token)
            ->whereType('Artist')
            ->with('profile')
            ->first();
        if (is_null($artist)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        } else {
            try {
                $artist->profile->routing_number = Crypt::decrypt($artist->profile->routing_number);
            } catch (DecryptException $e) {
                $artist->profile->routing_number = "";
            }
            try {
                $artist->profile->account_number = Crypt::decrypt($artist->profile->account_number);
            } catch (DecryptException $e) {
                $artist->profile->account_number = "";
            }
            try {
                $ssn_number = Crypt::decrypt(substr($artist->profile->ssn_number, -4));
                $artist->profile->ssn_number = $ssn_number;
                $artist->profile->confirm_ssn_number = $ssn_number;
            } catch (DecryptException $e) {
                $artist->profile->ssn_number = "";
                $artist->profile->confirm_ssn_number = "";
            }
            try {
                $artist->profile->pin = Crypt::decrypt($artist->profile->pin);
            } catch (DecryptException $e) {
                $artist->profile->pin= "";
            }
            return response()->json($artist, 200);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBankDetails(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
            'confirm_ssn_number.same' => 'The confirmation SSN number should be same as SSN number',
            'confirm_ssn_number.min' => 'The Confirmation SSN number must be at least 4 characters.',
            'confirm_ssn_number.max' => 'The Confirmation SSN number must be at least 4 characters.',
        ];
        $validator = Validator::make($data, [
            'access_token' => 'required',
            'profile_id' => 'required',
            'country' => 'required',
            'currency' => 'required',
            'routing_number' => 'required|numeric|digits:9',
            'account_number' => 'required|digits_between:9,12',
            'confirm_account_number' => 'required|same:account_number',
            'ssn_number' => 'required|numeric|digits:4',
            'confirm_ssn_number' => 'required|same:ssn_number',
        ], $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $artist = User::whereProfileId($request->profile_id)->whereAccessToken($request->access_token)
            ->with('profile')->first();
        if (is_null($artist)) {
            return response()->json(\Lang::get('messages.unauthorized_access'), 401);
        }
        $profile = Profile::where('ProfileId', $request->profile_id)->first();
        if ($profile->is_bank_updated == 2 && !is_null($profile->stripe_account_id)) {
            return response()->json(\Lang::get('messages.can_not_update_bank_detail'), 422);
        }
        if ($profile->Zip == '') {
            return response()->json(\Lang::get('messages.goto_edit_profile'), 422);
        }
        $updateData = [
            'routing_number' => Crypt::encrypt($request->routing_number),
            'account_number' => Crypt::encrypt($request->account_number),
            'ssn_number' => Crypt::encrypt($request->ssn_number),
            'pin' => Crypt::encrypt($request->ssn_number),
        ];
        if (is_null($profile->account_number) || empty($profile->account_number)) {
            $isBankUpdated = $profile->is_bank_updated == 0 ? 1 : 2;
            $updateData = array_add($updateData, 'is_bank_updated', $isBankUpdated);
        }
        Profile::where('ProfileId', $request->profile_id)->update($updateData);
        $data['artist_id'] = $artist->profile_id;
        $data['artist_name'] = $profile->Name;
        Mail::send('emails.Artist_id', $data, function ($message) {
            $message->to(config('constants.BCC_MAIL_CONFIG.admin_email'));
            $message->subject('Artist Driver License');
            $message->from('noreply@videorequestline.com');
        });
        return response()->json(null, 200);
    }
}//End Class
