<?php
namespace App\Http\Controllers;

use App\ReactionVideo;
use Illuminate\Http\Request;

use App\Http\Requests;

use Twitter;

use File;

use DB;

class TwitterController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function twitterUserTimeLine()
    {
        $Tag = env("TWITTER_TAG", "");
        $InstaTag = env("INSTAGRAM_TAG", "");
        $InstAccess = env("INSTAGRAM_ACCESS_TOKEN", "");
        $Data = Twitter::getSearch(['q' =>'#'.$Tag ,'filter'=>'native_video', 'count' => 100, 'format' => 'array']);
        $VideoURL = '';
        $twitterVideoData = [];
        $socialReaction = ReactionVideo::whereIn('type', [1, 2])->pluck('VideoURL')->toArray();
        $uniqueIds = [];
        foreach($socialReaction as $key => $reaction) {
            $socialStr = substr($reaction, strrpos($reaction, '/') + 1);
            if(strstr($reaction,'?')) {
                $socialVideoID = substr($socialStr, 0, strpos($socialStr, '?'));
            } else{
                $socialVideoID = substr($socialStr, strpos($socialStr, '?'));
            }
            array_push($uniqueIds, $socialVideoID);
        }
        foreach($Data['statuses'] as $k=>$v){
            if(isset($v['extended_entities']) && $v['extended_entities']['media'][0]['type']=='video') {
                foreach ($v['extended_entities']['media'][0]['video_info']['variants'] as $s) {
                    if ($s['content_type'] == 'video/mp4') {
                        $VideoURL = $s['url'];
                    }
                }
                $twitter = substr($VideoURL, strrpos($VideoURL, '/') + 1);
                $twitterID = substr($twitter, 0, strpos($twitter, '?'));
                if (!in_array($twitterID, $uniqueIds)) {
                    $twitterVideoData[] = [
                        'VideoName' => $v['text'],
                        'VideoURL' => $VideoURL,
                        'status' => 0,
                        'VideoUploadDate' => date('Y-m-d H:i:s', strtotime($v['created_at'])),
                        'thumbnail' => $v['extended_entities']['media'][0]['media_url_https'],
                        'type_id' => $v['id'],
                        'type' => '1'
                    ];
                }
            }
        }
        $Client = new \GuzzleHttp\Client;
        $Url = sprintf('https://api.instagram.com/v1/tags/'.$InstaTag.'/media/recent?access_token='.$InstAccess);
        $Response = $Client->get($Url);
        $Items = json_decode((string) $Response->getBody(), true);
        $instaVideoData = [];
        foreach($Items['data'] as $keyData => $InstaData){
            if($InstaData['type'] == 'video'){
                $VideoUrl = $InstaData['videos']['standard_resolution']['url'];
                $insta = substr($VideoURL, strrpos($VideoURL, '/') + 1);
                $instaID = substr($insta, 0, strpos($insta, '?'));
                if ($InstaData['caption']['text']) {
                    if (!in_array($instaID, $uniqueIds)) {
                        $instaVideoData[] = [
                            'VideoName' => $InstaData['caption']['text'],
                            'VideoURL' => $InstaData['videos']['standard_resolution']['url'],
                            'status' => 0,
                            'VideoUploadDate' => date('Y-m-d H:i:s', strtotime($InstaData['created_time'])),
                            'thumbnail' => $InstaData['images']['thumbnail']['url'],
                            'type_id' => $InstaData['id'],
                            'type' => '2'
                        ];                    
                    } 
                }
            }
        }

        DB::beginTransaction();
        try {
            //$Deletedata =  DB::table('reactionvideos')->where('type','1')->orWhere('type','2')->delete();
            $Twitterdata = DB::table('reactionvideos')->insert($twitterVideoData); 
            $Instadata = DB::table('reactionvideos')->insert($instaVideoData);
            DB::commit();
        }catch (\Exception $e) {
            DB::rollback();
        }
       
        return redirect('/SocialMediaVideos');
    }
   
}
