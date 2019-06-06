<?php

function artist_payment($artist_id)
{
    $all_payments=\App\Payment::where('payer_id',$artist_id)->where('is_refunded','=','0')
        ->where('video_status','Completed')->get();
    if(!is_null($all_payments)){
        $count=0;
        foreach ($all_payments as $payment) {
            $count=$count+$payment->videoPrice;
        }
        return $count;
    }else{
        return "NO payments";
    }
}

function admin_payment($artist_id)
{
    $all_payments=\App\AdminArtistPayment::where('payment_to',$artist_id)->get();
    if(!is_null($all_payments)){
        $count=0;
        foreach ($all_payments as $payment) {
            $count=$count+$payment->paid_amount;
        }
        return $count;
    }else{
        return "NO payments";
    }
}

/**
* testing push notifictionTest
*/
    function push_notification($device_token , $body_message, $request_type) {

    $api_key = env("FIREBASE_ACCESS_KEY", "");
    $client_id = env("FIREBASE_OAUTH_CLIENT_ID", "");
    $current_key = env("FIREBASE_CURRENT_ID", "");
    $message = [
        'body' => $body_message,
        'title' => 'VRL',
        'sound' => 'mySound',
        'click_action' => $request_type
    ];
    $trimmed = trim($device_token, "'");
    $tokens = explode(',', $trimmed);

    foreach ($tokens as $token) {
        $fields = ['to' => $token, 'priority' => 'high', 'notification' => $message];
        $headers = array(
            'Authorization:key=' . $api_key,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === false) {
            Log::info('Failed :'. $token);
            //return response()->json(curl_error($ch));
        } else {
            Log::info('Success :'. $token);
            //return response()->json($result, 200);
        }
        curl_close( $ch );
    }


}
/*if (! function_exists('android_push')) {
    function android_push($deviceType,$deviceToken,$msgs)
    {
        if($deviceType=='android' && $deviceToken!=''){
            $to=$deviceToken;
            $title="Video Request";//
            $message=$msgs;
            if (!defined('API_ACCESS_KEY'))
                define( 'API_ACCESS_KEY','AAAAUezx5KE:APA91bHdeF33VnwpVxrzlK0umno6Cb8sgTDlwmyQITcz9-3_PBBY-RXETQias398AHVqkq45-_Xu0BRopNREelz3n$
            $registrationIds = array($to);
            $msg = array
            (
              'message' => $message,'title' => $title,'vibrate' => 1,'sound' => 1
              );
            $fields = array
            (
              'registration_ids' => $registrationIds,'data' => $msg
              );

            $headers = array
            (
              'Authorization: key='.API_ACCESS_KEY,'Content-Type: application/json'
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
        }


    }
}*/
function breakWords ($text, $maxWords) {
    $split = explode(' ', $text);
    $array=[];
    for ($i = 0; $i < count($split); $i ++) {
        if($i % $maxWords == 0){
            $newArray = array_slice($split, $i, $maxWords);
            $array[] =  implode(' ', $newArray);
        }
    }
    return $array ;
}

function multipartUpload($completePath) {
    $disk = \Illuminate\Support\Facades\Storage::disk('s3');
    $disk->put($completePath, file_get_contents($completePath), 'public');
    /*$uploader = new \Aws\S3\MultipartUploader($disk->getDriver()->getAdapter()->getClient(), $completePath, [
        'bucket' => \Config::get('filesystems.disks.s3.bucket'),
        'key'    => $keyName,
    ]);
    try {
        $result = $uploader->upload();
        Log::info($result);
    } catch (\Aws\Exception\MultipartUploadException $e) {
        Log::info($e->getMessage());
    }*/
    //return $disk->put($completePath, file_get_contents($completePath), 'public');
}