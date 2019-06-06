<?php
namespace App\Http\Controllers;
use Auth;
use App\RequestedVideo;
use App\User;
use App\Video;
use App\OriginalVideo;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Input;
use DB;
use App\Http\Requests;
use Hash;
use Validator;
use Mail;
use App\Profile;
use App\Requestvideo;
use FFMpeg;
use Session;
use Carbon\Carbon;
use App\helper;
use Snipe\BanBuilder\CensorWords;
use Intervention\Image\ImageManager;
class VideoRecodeController extends Controller
{
	public function webcame(){
		return view('fileupload');
	}
	public function sample_webcame(){
		//dd(Auth::user()->email);

		if(Auth::check()){
			if(Auth::user()->type=="Admin"){
				return redirect('admin_dashboard');
			}
			elseif (Auth::user()->type=="User") {
				return redirect('/');
			}
			else{
				$artist =  Profile::where('EmailId',Auth::user()->email)->first();
				$artist_data['artist'] = $artist;

				return view('frontend.artistDashboard.sample_fileupload',$artist_data);
			}
		}else{
			return redirect('/');
		}

	}
	public function sample_video_upload(Request $request){
		if (!isset($_POST['audiofilename']) && !isset($_POST['videofilename'])) {
			echo 'PermissionDeniedError 1';
			return;
		}

		$fileName = '';
		$tempName = '';

		if (isset($_POST['audiofilename'])) {
			$fileName = $_POST['audiofilename'];
			$tempName = $_FILES['audioblob']['tmp_name'];
		} else {
			$fileName = $_POST['videofilename'];
			$tempName = $_FILES['videoblob']['tmp_name'];
		}

		if (empty($fileName) || empty($tempName)) {
			echo 'PermissionDeniedError 2';
			return;
		}
		$rand = rand(11111,99999).date('U');
		$filename = str_replace(' ', '', $fileName);
		$filename = str_replace('-', '', $filename);
		//$path = $_FILES['audiofilename']['name'];
		//$ext = pathinfo($path, PATHINFO_EXTENSION);
		//$fileName = $rand.'.'.$ext;
		$fileName = $rand.'.'.'mp4';

		$filePath = 'video/original/' . $fileName;

    // make sure that one can upload only allowed audio/video files
		$allowed = array(
			'webm',
			'wav',
			'mp4',
			'mp3',
			'ogg'
			);
		$extension = pathinfo($filePath, PATHINFO_EXTENSION);
		if (!$extension || empty($extension) || !in_array($extension, $allowed)) {
			echo 'PermissionDeniedError 3';
			// continue;
		}

		if (!move_uploaded_file($tempName, $filePath)) {
			echo ('Problem saving file.');
			return;
		}else{

			$ffmpeg = FFMpeg\FFMpeg::create(array(
				'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
				'ffprobe.binaries' => '/usr/local/bin/ffprobe',
				'timeout'          => 3600,
				'ffmpeg.threads'   => 12,
				));


			//$rand = rand(11111,99999).date('U');
			$ffmpegPath = '/usr/local/bin/ffmpeg';
			$inputPath = '/home/vrl/public_html/public/'.$filePath;
			$watermark = '/home/vrl/public_html/public/vrl_logo.png';
			$outPath = '/home/vrl/public_html/public/video/original/'.$rand.'.mp4';
			shell_exec("$ffmpegPath  -i $inputPath -i $watermark -filter_complex \"[1][0]scale2ref=(262/204)*ih/12:ih/12[wm][vid];[vid][wm]overlay=x=(W-w-20):y=(H-h-20)\" $outPath ");
				//shell_exec("ffmpeg -i $inputPath -i $watermark 
					//-filter_complex overlay='if(gte(t,1), -w+(t-1)*200, NAN)':(main_h-overlay_h)/2 $outPath");


			$video = new Video();
			$uploaded_video = $ffmpeg->open($inputPath);
			$ffprobe = FFMpeg\FFProbe::create(array(
				'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
				'ffprobe.binaries' => '/usr/local/bin/ffprobe',
				'timeout'          => 3600,
				'ffmpeg.threads'   => 12,
				));
			$video_thumbnail_path = base_path() . '/public/images/thumbnails/'.date('U').'.jpg';
			$uploaded_video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(2))->save($video_thumbnail_path);
			$video->VideoThumbnail	 = $video_thumbnail_path;
			//$video->VideoPrice = $request->video_price;
			$video->Description = $_POST['discription'];
			$video->Title = $_POST['title'];
			$video->VideoUploadDate = Carbon::now()->format('m-d-Y');
			$video->ProfileId	 = Auth::user()->profile_id;
			$video->UploadedBy	 = "Artist";
			$video->download_status	 = $_POST['download_status'];
			$video->originalVideoUrl	 = '/home/vrl/public_html/public/'.$filePath;

			//$video->home_auto_play_status = $_POST['autoPlay_status'];
			$video->profile_auto_play_status = $_POST['profile_autoPlay_status'];
			$video->VideoURL = $outPath;
			if($video->save())
			{

				echo'Successfully Uploaded!';
				return;
			}
		}
	}

        //webcam requested video code
	public function video_upload(Request $request){
		if (!isset($_POST['audiofilename']) && !isset($_POST['videofilename'])) {
			echo 'PermissionDeniedError 1';
			return;
		}
		$fileName = '';
		$tempName = '';
		if (isset($_POST['audiofilename'])) {
			$fileName = $_POST['audiofilename'];
			$tempName = $_FILES['audioblob']['tmp_name'];
		} else {
			$fileName = $_POST['videofilename'];
			$tempName = $_FILES['videoblob']['tmp_name'];
		}
		if (empty($fileName) || empty($tempName)) {
			echo 'PermissionDeniedError 2';
			return;
		}
		$rand = rand(11111,99999).date('U');
		$filename = str_replace(' ', '', $fileName);
		$filename = str_replace('-', '', $filename);
		//$path = $_FILES['audiofilename']['name'];
		//$ext = pathinfo($path, PATHINFO_EXTENSION);
		//$fileName = $rand.'.'.$ext;
		$fileName = $rand.'.'.'mp4';


		/*$ffmpegPath = '/usr/local/bin/ffmpeg';
		$inputPath = '/home/vrl/public_html/public/'.$fileName;
		$outPath = '/home/vrl/public_html/public/uploads/'.'rajesh'.'.mp4';
		shell_exec("$ffmpegPath -i $tempName -vcodec h264 -acodec aac -strict -2 $outPath");*/
		

		$filePath = 'requested_video/' . $fileName;
		$allowed = array(
			'mp4',
			'avi',
			'webm'
			);
		$extension = pathinfo($filePath, PATHINFO_EXTENSION);
		if (!$extension || empty($extension) || !in_array($extension, $allowed)) {
			echo 'PermissionDeniedError 3';
			// continue;
		}

		if (!move_uploaded_file($tempName, $filePath)) {
			echo ('Problem saving file.');
			return;
		}else{
			//$videoSource ='/home/vrl/public_html/public/requested_video/892941494403428.mp4';
			//$videoSource = '/home/vrl/public_html/public/uploads/'.'rajesh'.'.mp4';
			

			$ffmpegPath = '/usr/local/bin/ffmpeg';
			$inputPath = '/home/vrl/public_html/public/'.$filePath;
			$outPath = '/home/vrl/public_html/public/requested_video/'.$rand.'.mp4';
			$watermark = '/home/vrl/public_html/public/vrl_logo.png';
			
//shell_exec("$ ffmpeg -i $inputPath -i $watermark -filter_complex overlay=main_w-overlay_w-20:main_h-overlay_h-20  -codec:v libx264 -crf 18 -preset slow -pix_fmt yuv420p -c:a aac -strict -2 $outPath");


//ffmpeg -i input.mp4 -i watermark.png -filter_complex "overlay=1500:1000" output.mp4


			/*ffmpeg -y -i input.mp4 -i watermark.png -filter_complex "[1]lut=a=val*0.3[a];[0][a]overlay=0:0" -c:v libx264 -an output.mp4*/

//ffmpeg -i birds.mp4 -i watermark2.png -filter_complex "pad=height=ih+80:width=iw+80:x=40:y=40:color=violet" birds5.mp4

//ffmpeg -i video.mp4 -i logo.png -filter_complex "[0:v][1:v]overlay" \ -codec:a copy out.mp4

			shell_exec("$ffmpegPath  -i $inputPath -i $watermark -filter_complex overlay=main_w-overlay_w-20:main_h-overlay_h-20  -codec:v libx264 -crf 18 -preset slow -pix_fmt yuv420p -c:a aac -strict -2 $outPath");
			
			$requested_video = new RequestedVideo();
			$ffmpeg = FFMpeg\FFMpeg::create(array(
				'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
				'ffprobe.binaries' => '/usr/local/bin/ffprobe',
				'timeout'          => 3600,
				'ffmpeg.threads'   => 12,
				));
			$uploaded_video = $ffmpeg->open($inputPath);
			$ffprobe = FFMpeg\FFProbe::create(array(
				'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
				'ffprobe.binaries' => '/usr/local/bin/ffprobe',
				'timeout'          => 3600,
				'ffmpeg.threads'   => 12,
				));
			$video_thumbnail_path = base_path() . '/public/images/thumbnails/'.date('U').'.jpg';

			$uploaded_video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(2))->save($video_thumbnail_path);
			$requested_video->thumbnail	 = $video_thumbnail_path;
			
			//$VideoURL = "https://www.videorequestline.com/requested_video/";
			$VideoURL = base_path()."/requested_video/";
			$requested_video->description = $_POST['requested_video_description'];
			$requested_video->title = $_POST['requested_video_title'];
			$requested_video->request_id = $_POST['requested_video_id'];
			$requested_video->requestby	 = $_POST['requestedby'];
			$requested_video->uploadedby	 =$_POST['uploadedby'];
			$requested_video->url	 =$outPath;
			$requested_video->fileName	 =$rand.'.mp4';
			$requested_video->is_active	 =1;
			$requested_video->Upload_date=date('Y-m-d');
			$requested_video->purchase_date=date('Y-m-d');
			$purge_data = DB::table('setting')->select('status')->where('name','=',"purge")->first();
			$requested_video->remain_storage_duration=$purge_data->status;
			if($requested_video->save())
			{
				$video_requests = Requestvideo::find($_POST['requested_video_id']);
				if($video_requests==null){
					echo ('Request has been deleted by User');
					return;		
				}else if($video_requests->RequestStatus=='Reject'){
					echo ('Request has been Rejected by You');
					return;	
				}else{
					$video_requests->RequestStatus = "Completed";
					DB::table('payments')
					->where('video_request_id',$_POST['requested_video_id'])
					->update(array('video_status' => 'Completed' ));
					$video_requests->save();
					$video_requests = Requestvideo::find($_POST['requested_video_id']);
                    $artist = Profile::where('ProfileId', Session::get('post_artist_id'))->first();
                    $Requestvideo = new Requestvideo();
					$data['video_name'] =$rand.'.mp4';;
					$data['thumbnail'] ='video_thumbnail_path';
					$data['video_title'] =$_POST['requested_video_title'];
					$data['video_description'] = $_POST['requested_video_description'];
                    $data['songName'] = $video_requests->song_name;
//                    dd($video_requests->song_name);
                    $data['current_status'] = "Approved";
                    $data['turnaroundTime'] = $artist->timestamp;
                    $data['artist_id'] = $artist->ProfileId;
                    $data['video_request_id'] = $Requestvideo->VideoReqId;
                    $data['identifier'] = $artist->ProfileId.'-'.$Requestvideo->VideoReqId;
					Mail::send('emails.download_email', $data, function ($message) use ($request,$data) {
						$message->from('noreply@videorequestline.com', 'Download Video');
						$message->to($_POST['user_email'], "User");
						$message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
						$message->subject('VRL request uploaded ('.$data['identifier'].')');
					});

					$artist = Profile::find($_POST['uploadedby']);
					$user_detail=DB::table('users')->where('profile_id','=',$_POST['requestedby'])->first();
					$admin_data['user_name'] = $user_detail->user_name;
					$admin_data['artist_name'] = $artist->Name;
					$admin_data['video_price'] = $artist->VideoPrice;
					$admin_data['video_title'] = $_POST['requested_video_title'];
					$admin_data['video_description'] = $_POST['requested_video_description'];
					$admin_data['video_completion'] = $video_requests->ComplitionDate;
					$admin_data['thumbnail'] ='video_thumbnail_path';
					Mail::send('emails.admin_download_email', $admin_data, function ($message) use ($request) {
						$message->from('noreply@videorequestline.com', 'Video Upload');
						$message->to('admin@videorequestline.com', 'admin@videorequestline.com');
						$message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
						$message->subject('Artist Uploaded Video To user');
					});
					$artist_data['user_name'] = $user_detail->user_name;
					$artist_data['video_price'] = $artist->VideoPrice;
					$artist_data['video_title'] = $_POST['requested_video_title'];
					$artist_data['video_description'] = $_POST['requested_video_description'];
					$artist_data['video_completion'] = $video_requests->ComplitionDate;
					$artist_data['thumbnail'] ='video_thumbnail_path';
					Mail::send('emails.artist_download_email', $artist_data, function ($message) use ($request) {
						$message->from('noreply@videorequestline.com', 'Download Video');
						$message->to(Auth::user()->email,Auth::user()->email);
						$message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
						$message->subject('Video Uploaded Successfully');
					});
					echo ('Successfully Uploaded!');
					return;
				}
			}
			
			
		}

		

	}

	public function water_mark(Request $request){ 
		$ffmpegPath = '/usr/local/bin/ffmpeg';
		$ffmpeg = FFMpeg\FFMpeg::create(array(
			'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
			'ffprobe.binaries' => '/usr/local/bin/ffprobe',
			'timeout'          => 3600,
			'ffmpeg.threads'   => 12,
			));
		/*$videoSource ='/home/vrl/public_html/public/requested_video/892941494403428.mp4';*/
		
		/*$watermark = '/home/vrl/public_html/public/vrl_logo.png';*/
		
		//$outPath = '/home/vrl/public_html/public/requested_video/'.$rand.'.mp4';
		/*$outPath = '/home/vrl/public_html/public/requested_video/test14.webm';*/
		
		//shell_exec("$ffmpegPath -i $videoSource -ar 22050 -ab 32 -f flv -s 320x240 $outPath");
		
		/*shell_exec("$ffmpegPath -i $videoSource -s 1280x720 -vpre libvpx-720p -b 993900k -pass 1 \
		-an -f webm -y $outPath");*/
		





		//shell_exec('$ffmpegPath -i $videoSource -i $watermark -filter_complex "overlay=10:10" $outPath');
		
		//dd();

		//ffmpeg -i $IN -f webm -vcodec libvpx -acodec libvorbis -ab 128000 -crf 22 -s 640x360 $OUT.webm
		//ffmpeg -i input.mp4 -c:v libvpx -minrate 1M -maxrate 1M -b:v 1M -c:a libvorbis output.webm
		
		//$videoSource ='/home/vrl/public_html/public/uploads/227063179783342.mp4';
		$videoSource ='/home/vrl/public_html/public/requested_video/892941494403428.mp4';
		
		$reqExtension = "mp4";
		$watermark = '/home/vrl/public_html/public/vrl_logo.png';
		


		$video = $ffmpeg->open($videoSource);

		$format = new FFMpeg\Format\Video\X264('libmp3lame', 'libx264');

		if (!empty($watermark))
		{
			$video  ->filters()
			->watermark($watermark, array(
				'position' => 'relative',
				'top' => 25,
				'right' => 50,
				));
		}

		$format
		-> setKiloBitrate(1000)
		-> setAudioChannels(2)
		-> setAudioKiloBitrate(256);

		$randomFileName = rand().".$reqExtension";
		$saveLocation = '/home/vrl/public_html/public/requested_video/originalwatermark.mp4';
		$video->save($format, $saveLocation);

		if (file_exists($saveLocation))
			return "http://google.com";
		else
			return "http://yahoo.com";
		
	}
        
        /* ------------------------------ MoMonkey Code -------------------------------------------------------- */
        

    
        
        
        
}