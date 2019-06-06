<?php
namespace App\Http\Controllers;

use App\Share;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
//use Validator;
use App\Http\Requests;
use Paypalpayment;
use App\Video;
use App\Profile;
use App\User;
use App\Requestvideo;
use App\Payment;
use Carbon\Carbon;
use Session;
use Redirect;
use DB;
use Hash;
use Mail;
use Auth;
use App\AdminPayment;
use App\AdminArtistPayment;

class PaymentController extends Controller {
    /*
     * 10 - 02 - 2018 
     * Extend Storage Function 
     * Params ( Request ID , Number Of Months , Stripe Token )
     * Return Succfully Extended Or False
     */


    /* --------------------------------Repayment---------------------------------------- */

    public function RePayment(Request $request) {

        // Validate the Request 
        $this->validate($request, [
            'requestExtendMonth' => 'required',
            'requestID' => 'required',
            'stripeToken' => 'required'
        ]);


        // Make sure that request belongs to that Auth User 

        $uploadedRequest = \App\RequestedVideo::where('id', $request->requestID)->first();
        $videoRequest = Requestvideo::where('VideoReqId', $uploadedRequest->request_id)->first();
        if (count($videoRequest) > 0 && count($uploadedRequest) > 0) {
            try {
                $stripe = array(
                    /* Test key */
                    "secret_key" => "sk_test_CtVU3fFCOkPs7AbQDLLJmU1n",
                    "publishable_key" => "pk_test_u2EpaiGskW20KXn5Nw7MMJta"

                        /* Live key */
                        //	"secret_key"      => "sk_live_AuLjanpEXm7L1Iq22XhDBzyR",
                        //	"publishable_key" => "pk_live_ibbVEpwDbfWJAboByQ6Kvygy"
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
                $price = $request->requestExtendMonth * 5;
                $totalAmount = $price * 100;

                $customer = \Stripe\Customer::create(array(
                            'email' => $videoRequest->sender_email,
                            'source' => $token
                ));



                $charge = \Stripe\Charge::create(array(
                            'customer' => $customer->id,
                            'amount' => $totalAmount,
                            'currency' => 'usd'
                ));



                if ($charge->status == "succeeded") {

                    $payment = new Payment();

                    $payment->video_request_id = $videoRequest->VideoReqId;
                    $payment->stripeTokenType = $charge->source->object;




                    $payment->token = $token;

                    $payment->customerEmail = $videoRequest->sender_email;

                    $payment->videoPrice = $price;

                    $payment->video_status = 'Extend Succeded';

                    $payment->status = 'succeeded';
                    $payment->payment_id = 1; // payment id
                    $payment->charge_id = $charge->id;





                    // The Previous Developer Who Developed the database forced me to do that with his structure sorry for ambigous names :) Mohamed Mamouh (m_mamdouh@outlook.com)
                    $payment->profile_id = $videoRequest->requestByProfileId;
                    $payment->payer_id = $videoRequest->requestToProfileId;

                    $payment->payment_type = 'Extent Storage';
                    $payment->payment_date = Carbon::now()->format('m-d-Y');

                    $payment->save();

                    // Update the request 
                    $date = date('Y-m-d');

                    $uploadedRequest->purchase_date = $date;


                    if ($uploadedRequest->remain_storage_duration == 0) {

                        $uploadedRequest->url = $uploadedRequest->desti_url;
                        $uploadedRequest->desti_url = 'removed';

                        $uploadedRequest->thumbnail = $uploadedRequest->desti_thumbnail;
                        $uploadedRequest->desti_thumbnail = 'removed';
                    }


                    $uploadedRequest->remain_storage_duration += $request->requestExtendMonth * 30;  // Number of months * 30 days | ex 2 * 30 = 60 days


                    $uploadedRequest->save();

                    Session::flash('success', 'Video Extended Successfully');
                    return redirect('/user_video');
                } else {
                    Session::flash('error', 'This Request is not Right please try again later');
                    return redirect()->back();
                }
            } catch (\Stripe\Error\Card $e) {
                dd("1");
                // Since it's a decline, \Stripe\Error\Card will be caught
                $err_msg = $e->getMessage();
                return redirect('/user_video')->with('error', $err_msg)->withInput();
            } catch (\Stripe\Error\InvalidRequest $e) {
                dd($e->getMessage());
                $err_msg = $e->getMessage();
                return redirect('/user_video')->with('error', $err_msg)->withInput();
                // Invalid parameters were supplied to Stripe's API
            } catch (\Stripe\Error\Authentication $e) {
                dd("3");
                $err_msg = $e->getMessage();
                return redirect('/user_video')->with('error', $err_msg)->withInput();
                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
            } catch (\Stripe\Error\ApiConnection $e) {
                dd("4");
                $err_msg = $e->getMessage();
                return redirect('/user_video')->with('error', $err_msg)->withInput();
                // Network communication with Stripe failed
            } catch (\Stripe\Error\Base $e) {
                dd("5");
                $err_msg = $e->getMessage();
                return redirect('/user_video')->with('error', $err_msg)->withInput();
                // Display a very generic error to the user, and maybe send
                // yourself an email
            } catch (Exception $e) {
                dd("6");
                $err_msg = $e->getMessage();
                return redirect('/user_video')->with('error', $err_msg)->withInput();
                // Something else happened, completely unrelated to Stripe
            }
        } else {
            Session::flash('error', 'This Request is not Right please try again later');
            return redirect()->back();
        }
    }

    /* ------------------------------Strip User payment API ----------------------------- */

    public function payapp(Request $request) {
        dd($request->all());
        //echo '<pre>';print_r($_REQUEST);die;
        //return view('payment.payapp',$request);
    }

    public function AppPayment(Request $request) {

        //dd($request->all());
        try {
            $stripe = array(
                //Test key
                // "secret_key"      => "sk_test_CtVU3fFCOkPs7AbQDLLJmU1n",
                // "publishable_key" => "pk_test_u2EpaiGskW20KXn5Nw7MMJta"

                /* Live key */
                "secret_key" => "sk_live_AuLjanpEXm7L1Iq22XhDBzyR",
                "publishable_key" => "pk_live_ibbVEpwDbfWJAboByQ6Kvygy"
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
            $price = ($request->artist_vidoe_price) * 100;
            $customer = \Stripe\Customer::create(array(
                        'email' => $request->sender_email,
                        'source' => $token
            ));
            $charge = \Stripe\Charge::create(array(
                        'customer' => $customer->id,
                        'amount' => $price,
                        'currency' => 'usd'
            ));

            if ($charge['status'] == "succeeded") {
                //dd($request->all());
                $is_found_user = User::where('email', $request->sender_email)->where('type', 'User')->first();
                $last_pro_id;
                if (count($is_found_user) == 1) {

                    $Status = "Pending";
                    $Requestvideo = new Requestvideo();
                    $Requestvideo->requestToProfileId = $request->ArtistProfileId;
                    $Requestvideo->song_name = $request->song_name;
                    $Requestvideo->Name = $request->user_name;
                    $Requestvideo->receipient_pronunciation = $request->pronun_name;
                    $Requestvideo->requestor_email = $request->user_email;
                    $Requestvideo->sender_name = $request->sender_name;
                    $Requestvideo->sender_name_pronunciation = $request->sender_name_pronun;
                    $Requestvideo->sender_email = $request->sender_email;
                    $Requestvideo->Title = $request->Occassion;
                    $Requestvideo->ReqVideoPrice = $request->artist_vidoe_price;
                    $Requestvideo->complitionDate = $request->delivery_date;
                    $Requestvideo->paid = 'Paid';
                    $Requestvideo->Description = $request->person_message;
                    $Requestvideo->RequestStatus = $Status;
                    $Requestvideo->is_active = 1;
                    $Requestvideo->request_date = Carbon::now()->format('m/d/Y');
                    $Requestvideo->requestByProfileId = $request->requestByProfileId;
                    if ($Requestvideo->save()) {

                        $payment = new Payment();
                        $status = 'succeeded';
                        $stripeTokenType = 'card';
                        $payment->profile_id = $is_found_user->profile_id;
                        $payment->video_status = 'Pending';
                        $payment->video_request_id = $request->VideoReqId;
                        $payment->stripeTokenType = 'card';
                        $payment->stripeToken = $request->stripeToken;
                        $payment->customerEmail = $request->sender_email;
                        $payment->status = $status;
                        $payment->videoPrice = $request->artist_vidoe_price;
                        $payment->payer_id = $request->ArtistProfileId;
                        $payment->payment_date = Carbon::now()->format('m-d-Y');
                        //$payment->token = $request->_token;
                        $payment->charge_id = $charge->id;
                        $payment->payment_type = 'Purchase';
                        $payment->save();
                        //
                        $confirmation_code['user_email'] = $request->sender_email;
                        $confirmation_code['video_title'] = $request->Occassion;
                        $confirmation_code['video_description'] = $request->person_message;
                        $confirmation_code['current_status'] = "Pending";
                        $artist = Profile::where('ProfileId', $request->ArtistProfileId)->first();
                        if (isset($artist) && !empty($artist)) {
                            $confirmation_code['video_delivery_time'] = $artist->timestamp;
                            $confirmation_code['artist_name'] = $artist->Name;
                            $confirmation_code['artist_email'] = $artist->EmailId;
                            $confirmation_code['username'] = $request->sender_name;
                        } else {
                            $confirmation_code['video_delivery_time'] = '';
                            $confirmation_code['artist_name'] = '';
                            $confirmation_code['artist_email'] = '';
                            $confirmation_code['username'] = '';
                            $confirmation_code['password'] = '';
                        }

//                        if(isset($request->user_email) && !empty($request->user_email)){
//						Mail::send('emails.exist_User_RequestNewVideo', $confirmation_code, function ($message) use ($request) {
//							$message->from('noreply@videorequestline.com', 'Confirmation for Video Request');
//							$message->to($request->user_email, $request->user_email);
//							$message->subject('Successfully requested video');
//						});
//                        }
//                        
                        if (isset($request->sender_email) && !empty($request->sender_email)) {
                            Mail::send('emails.exist_User_RequestNewVideo', $confirmation_code, function ($message) use ($request) {
                                $message->from('noreply@videorequestline.com', 'Confirmation for Video Request');
                                $message->to($request->sender_email, $request->sender_email);
                                $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                                $message->subject('Successfully requested video');
                            });
                        }

                        if (isset($confirmation_code['artist_email']) && !empty($confirmation_code['artist_email'])) {
                            Mail::send('emails.admin_RequestNewVideo', $confirmation_code, function ($message) use ($request) {
                                $artist = Profile::where('ProfileId', $request->ArtistProfileId)->first();
                                if (isset($artist) && !empty($artist)) {
                                    $message->from('noreply@videorequestline.com', 'Confirmation for Video Request');
                                    $message->to($artist->EmailId, 'Artist');
                                    $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                                    $message->subject('Requested New video');
                                }
                            });
                        }
                    }
                } else {

                    $type = "User";
                    $password = str_random(8);
                    $Profile = new Profile();
                    $Profile->EmailId = $request->sender_email;
                    $Profile->Type = $type;
                    $Profile->Name = $request->sender_name;
                    $Profile->save();
                    $last_pro_id = $Profile->ProfileId;
                    $users = new User();
                    $users->user_name = $request->sender_name;
                    $users->email = $request->sender_email;
                    $users->password = Hash::make($password);
                    $users->type = 'User';
                    $users->profile_id = $Profile->ProfileId;
                    $users->is_account_active = '1';
                    $users->is_email_active = '1';
                    $users->save();
                    $Status = "Pending";
                    $Requestvideo = new Requestvideo();
                    $Requestvideo->requestToProfileId = $request->ArtistProfileId;
                    $Requestvideo->song_name = $request->song_name;
                    $Requestvideo->Name = $request->user_name;
                    $Requestvideo->receipient_pronunciation = $request->pronun_name;
                    $Requestvideo->requestor_email = $request->user_email;
                    $Requestvideo->sender_name = $request->sender_name;
                    $Requestvideo->sender_name_pronunciation = $request->sender_name_pronun;
                    $Requestvideo->sender_email = $request->sender_email;
                    $Requestvideo->Title = $request->Occassion;
                    $Requestvideo->ReqVideoPrice = $request->artist_vidoe_price;
                    $Requestvideo->complitionDate = $request->delivery_date;
                    $Requestvideo->paid = 'Paid';
                    $Requestvideo->Description = $request->person_message;
                    $Requestvideo->RequestStatus = $Status;
                    $Requestvideo->is_active = 1;
                    $Requestvideo->request_date = Carbon::now()->format('m/d/Y');
                    $Requestvideo->requestByProfileId = $Profile->ProfileId;
                    if ($Requestvideo->save()) {
                        //
                        $payment = new Payment();
                        $status = 'succeeded';
                        $stripeTokenType = 'card';
                        $payment->profile_id = $Profile->ProfileId;
                        $payment->video_status = 'Pending';
                        $payment->video_request_id = $request->VideoReqId;
                        $payment->stripeTokenType = 'card';
                        $payment->stripeToken = $request->stripeToken;
                        $payment->customerEmail = $request->sender_email;
                        $payment->status = $status;
                        $payment->videoPrice = $request->artist_vidoe_price;
                        $payment->payer_id = $request->ArtistProfileId;
                        $payment->payment_date = Carbon::now()->format('m-d-Y');
                        //$payment->token = $request->_token;
                        $payment->charge_id = $charge->id;
                        $payment->payment_type = 'Purchase';
                        $payment->save();

                        $confirmation_code['user_email'] = $request->sender_email;
                        $confirmation_code['video_title'] = $request->Occassion;
                        $confirmation_code['video_description'] = $request->person_message;
                        $confirmation_code['current_status'] = "Pending";
                        $artist_id = $request->profile_id;
                        $requestVideoId = $request->VideoReqId;
                        $confirmation_code['identifier'] = $artist_id.'-'.$requestVideoId;
                        //dd($request->all);
                        $artist = Profile::where('ProfileId', $request->ArtistProfileId)->first();
                        if (isset($artist) && !empty($artist)) {
                            $confirmation_code['video_delivery_time'] = $artist->timestamp;
                            $confirmation_code['artist_name'] = $artist->Name;
                            $confirmation_code['artist_email'] = $artist->EmailId;
                            $confirmation_code['username'] = $request->sender_name;
                            $confirmation_code['password'] = $password;
                        } else {
                            $confirmation_code['video_delivery_time'] = '';
                            $confirmation_code['artist_name'] = '';
                            $confirmation_code['artist_email'] = '';
                            $confirmation_code['username'] = '';
                            $confirmation_code['password'] = '';
                        }
                        //dd($request->all());
                        /* Stop Sending Message to reciptionist -- Mohamed M */
//                        if(isset($request->user_email) && !empty($request->user_email)){
//                            Mail::send('emails.exist_User_RequestNewVideo', $confirmation_code, function ($message) use ($request) {
//                                       $message->from('noreply@videorequestline.com', 'Confirmation for Video Request');
//                                       $message->to($request->user_email, $request->user_email);
//                                       $message->subject('Successfully requested video');
//                                       });
//                        }

                        if (isset($request->sender_email) && !empty($request->sender_email)) {
                            Mail::send('emails.User_RequestNewVideo', $confirmation_code, function ($message) use ($request,$confirmation_code) {
                                $message->from('noreply@videorequestline.com', 'Confirmation for Video Request');
                                $message->to($request->sender_email, $request->sender_email);
                                $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                                $message->subject('VRL Video Request Confirmation ('.$confirmation_code['identifier'].')');
                            });
                        }
                        if (isset($confirmation_code['artist_email']) && !empty($confirmation_code['artist_email'])) {
                            Mail::send('emails.admin_RequestNewVideo', $confirmation_code, function ($message) use ($request,$confirmation_code ) {
                                $artist = Profile::where('ProfileId', $request->ArtistProfileId)->first();
                                if (isset($artist)) {
                                    $message->from('noreply@videorequestline.com', 'Confirmation for Video Request');
                                    $message->to($artist->EmailId, 'Artist');
                                    $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                                    $message->subject('VRL Video Request Confirmation ('.$confirmation_code['identifier'].')');
                                }
                            });
                        }
                    }
                }
                //dd('hi');
                if ($request->device_type == 'iphone') {
                    if (isset($_FILES['sender_name_pronun']["name"])) {
                        $target = "pron_name" . '/' . $_FILES['sender_name_pronun']['name'];
                        if (move_uploaded_file($_FILES["sender_name_pronun"]["tmp_name"], $target)) {
                            $Requestvideo->sender_voice_pronunciation = $target;
                        } else {
                            $response = array('response_code' => "500", 'return_message' => "File not Updated ! Please Try Again With different file");
                            return json_encode($response);
                        }
                    }
                    if (isset($_FILES['pronun_name']["name"])) {
                        $target1 = "pron_name" . '/' . $_FILES['pronun_name']['name'];
                        if (move_uploaded_file($_FILES["pronun_name"]["tmp_name"], $target1)) {
                            $Requestvideo->receipient_voice_pronunciation = $target1;
                        } else {
                            $response = array('response_code' => "500", 'return_message' => "File not Updated ! Please Try Again With different file");
                            return json_encode($response);
                        }
                    }
                }
                if ($request->device_type == 'android') {

                    if (isset($_FILES['sender_name_pronun']["name"])) {
                        $target = "pron_name" . '/' . $_FILES['sender_name_pronun']['name'];
                        if (move_uploaded_file($_FILES["sender_name_pronun"]["tmp_name"], $target)) {
                            $Requestvideo->sender_voice_pronunciation = $target;
                        } else {
                            $response = array('response_code' => "500", 'return_message' => "File not Updated ! Please Try Again With different file");
                            return json_encode($response);
                        }
                    }
                    if (isset($_FILES['pronun_name']["name"])) {
                        $target1 = "pron_name" . '/' . $_FILES['pronun_name']['name'];
                        if (move_uploaded_file($_FILES["pronun_name"]["tmp_name"], $target1)) {
                            $Requestvideo->receipient_voice_pronunciation = $target1;
                        } else {
                            $response = array('response_code' => "500", 'return_message' => "File not Updated ! Please Try Again With different file");
                            return json_encode($response);
                        }
                    }
                }
                $Requestvideo->save();
            }

            $VideoReqId = $request->VideoReqId;

            DB::table('requestvideos')
                    ->where('VideoReqId', $VideoReqId)
                    ->update(array('paid' => 'Paid'));
            $response = array('return_message' => "Payment succesfully");
            //print_r($response);die;
            $passphrase = '12345';
            $artist_detail = DB::table('users')->where('profile_id', '=', $request->requestToProfileId)->first();
            //print_r($artist_detail);die;
            if ($artist_detail && isset($artist_detail->device_token)) {

                $deviceToken = $artist_detail->device_token;
                $deviceType = $artist_detail->device_type;
                if ($deviceToken != null && $deviceToken != null) {
                    $message = 'Payment succesfully done!';
                    android_push($deviceType, $deviceToken, $message);
                }
            }



            dd($response['return_message']);
            return json_encode($response);
        } catch (\Stripe\Error\Card $e) {
            $err_msg = $e->getMessage();
            $response = array('return_message' => $err_msg);
            //$string = "The card has been declined.";
            dd($response['return_message']);
            //return json_encode($response);
        }
    }

    /* -----------------------------------Strip User payment ------------------------------------- */

    public function payment(Request $request) {
        //dd($request->all());
        $profile_data = DB::table('users')->where('profile_id', '=', $_POST['requestByProfileId'])->select('profile_id')->first();

        if (count($profile_data) > 0) {
            try {
                $stripe = array(
                    /* Test key */
                    //"secret_key"      => "sk_test_CtVU3fFCOkPs7AbQDLLJmU1n",
                    //"publishable_key" => "pk_test_u2EpaiGskW20KXn5Nw7MMJta"

                    /* Live key */
                    "secret_key" => "sk_live_AuLjanpEXm7L1Iq22XhDBzyR",
                    "publishable_key" => "pk_live_ibbVEpwDbfWJAboByQ6Kvygy"
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
                $token = $_POST['stripeToken'];

                $customer = \Stripe\Customer::create(array(
                            'email' => $request->stripeEmail,
                            'source' => $token
                ));
                $charge = \Stripe\Charge::create(array(
                            'customer' => $customer->id,
                            'amount' => $_POST['amount'] * 100,
                            'currency' => 'usd'
                ));

                $payment = new Payment();
                $payment->profile_id = $_POST['requestByProfileId'];
                $payment->video_request_id = $_POST['VideoReqId'];
                $payment->stripeTokenType = $request->stripeTokenType;
                $payment->token = $request->stripeToken;
                $payment->customerEmail = $request->stripeEmail;
                $payment->status = $charge['status'];
                $payment->videoPrice = $_POST['amount'];
                $payment->charge_id = $charge->id;
                $payment->token = $request->_token;
                $payment->video_status = 'Pending';
                $payment->payer_id = $_POST['requestToProfileId'];
                $payment->stripeToken = $token;
                $payment->payment_date = Carbon::now()->format('m-d-Y');
                $payment->payment_type = 'Purchase';
                $payment->save();
                $date_in_future = date('d-m-Y', strtotime("+15 days"));
                DB::table('requestvideos')
                        ->where('VideoReqId', $_POST['VideoReqId'])
                        ->update(array('paid' => 'Paid', 'refund_date' => $date_in_future));
///
                $passphrase = '12345';
                $artist_detail = DB::table('users')->where('profile_id', '=', $_POST['requestToProfileId'])->first();
                $deviceToken = $artist_detail->device_token;
                $deviceType = $artist_detail->device_type;
                /* if($deviceType=='iphone'){
                  $ctx = stream_context_create();
                  $test = stream_context_set_option($ctx, 'ssl', 'local_cert', 'VRL.pem');
                  stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
                  $fp = stream_socket_client(
                  'ssl://gateway.sandbox.push.apple.com:2195', $err,
                  $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
                  $body['aps'] = array(
                  'alert' => 'Payment has been completed for Video request',
                  'sound' => 'default',
                  'badge' => 1,
                  );
                  $payload = json_encode($body);
                  $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
                  $result = fwrite($fp, $msg, strlen($msg));
                  //if (!$result) {
                  // return '0';
                  //}
                  //else {
                  //  return '1';
                  //}
                  fclose($fp);
                  } */
                /* if($deviceType=='android'){
                  $to=$deviceToken;
                  $title="Payment successfully";//
                  $message='Payment has been done for Video request';
                  //sendPush($to,$title,$message);

                  //function sendPush($to,$title,$message)
                  //{
                  // API access key from Google API's Console
                  // replace API
                  define( 'API_ACCESS_KEY','AAAAUezx5KE:APA91bHdeF33VnwpVxrzlK0umno6Cb8sgTDlwmyQITcz9-3_PBBY-RXETQias398AHVqkq45-_Xu0BRopNREelz3n9YBEhI3SkKSo8myUfThTkV4dYOkGdcolMBFpdXHGSVdYnnz9SPXplFAsI7CnYcf54-G8i3bjQ');
                  $registrationIds = array($to);
                  $msg = array
                  (
                  'message' => $message,
                  'title' => $title,
                  'vibrate' => 1,
                  'sound' => 1

                  // you can also add images, additionalData
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
                  } */
///
                return redirect('/payment_success');
            } catch (\Stripe\Error\Card $e) {
                $err_msg = $e->getMessage();
                //return redirect('/stripe_payment')->with('error',$err_msg)->withInput();
                //$string = "The card has been declined.";
                echo json_encode($err_msg);
            }
        } else {
            return redirect('/');
//echo "remove user";
        }
    }

    /* -------------------------payment success redirect---------------- */

    public function payment_success() {
		
        if(Auth::check() && Auth::user()->type=="User"){   
                return view('frontend.success_payment');  
        }else{
                return redirect('/');
           } 
    }

    public function payment_success_register() {
        return view('frontend.payment_success_register');
    }

    /**
     * @param $artistId
     * @param $price
     * @return mixed
     */
    public function pay_to_artist($artistId, $price)
    {
        $artist = Profile::find($artistId);
        $is_artists = User::where('profile_id', $artistId)->first();
        if ($artist->is_bank_updated == 2) {
            if (count($is_artists) != 1) {
                return redirect("artist_payments/")
                    ->with('error', "Artist not found. ");
            } elseif ($is_artists->is_account_active == 0) {
                return redirect("artist_payments/")
                    ->with('error', "Artist Deactivated. ");
            } else {
                if ($artist->stripe_account_id == '') {
                    return redirect('artist_payments')->with('error', "Artist don't have Stripe Account");
                } else {
                    try {
                        //\Stripe\Stripe::setApiKey('sk_test_ok0Vuv6ldys5bU6MwIhikULS');
                        //	\Stripe\Stripe::setApiKey('sk_live_AuLjanpEXm7L1Iq22XhDBzyR');
                        if (App::environment('local') || App::environment('testing')) {
                            // The environment is local or testing
                            \Stripe\Stripe::setApiKey(Config::get('constants.STRIPE_KEYS.test.secret_key'));
                        } else {
                            // The environment is production
                            \Stripe\Stripe::setApiKey(Config::get('constants.STRIPE_KEYS.live.secret_key'));
                        }
                        \Stripe\Stripe::setApiVersion(Config::get('constants.STRIPE_VERSION'));

                        $a = \Stripe\Transfer::create(array(
                            "amount" => ($price) * 100,
                            "currency" => "usd",
                            "destination" => $artist->stripe_account_id,
                            "description" => "Transfer for Accepting Video Request From User"
                        ));
                        $admin_artist_payment = new AdminArtistPayment();
                        $admin_artist_payment->transaction_id = $a->id;
                        $admin_artist_payment->status = 'paid';
                        $admin_artist_payment->payment_to = $artistId;
                        $admin_artist_payment->artist_name = $artist->Name;
                        $admin_artist_payment->paid_amount = $a->amount / 100;
                        if ($admin_artist_payment->save()) {
                            $data['artist_name'] = $artist->Name;
                            $data['amount'] = $a->amount / 100;;

                            Mail::send('emails.Artist_payment', $data, function ($message) use ($artist) {
                                $message->from('noreply@videorequestline.com', 'VRL');
                                $message->to($artist->EmailId, 'User');
                                $message->cc('noreply@videorequestline.com', 'Super Administrator');
                                $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                                $message->subject('Your have got Money for requested video');
                                //dd($artist->EmailId);
                            });
                            return redirect('artist_payments')->with('success', 'successfully Payment transfered to artist account');
                        } else {
                            return redirect('artist_payments')->with('error', 'fail Payment transfered to artist account');
                        }
                    } catch (\Stripe\Error\Card $e) {
                        // Since it's a decline, \Stripe\Error\Card will be caught
                        $err_msg = $e->getMessage();
                        return redirect('artist_payments')->with('error', $err_msg)->withInput();
                    } catch (\Stripe\Error\InvalidRequest $e) {
                        $err_msg = $e->getMessage();
                        return redirect('artist_payments')->with('error', $err_msg)->withInput();
                        // Invalid parameters were supplied to Stripe's API
                    } catch (\Stripe\Error\Authentication $e) {
                        $err_msg = $e->getMessage();
                        return redirect('artist_payments')->with('error', $err_msg)->withInput();
                        // Authentication with Stripe's API failed
                        // (maybe you changed API keys recently)
                    } catch (\Stripe\Error\ApiConnection $e) {
                        $err_msg = $e->getMessage();
                        return redirect('artist_payments')->with('error', $err_msg)->withInput();
                        // Network communication with Stripe failed
                    } catch (\Stripe\Error\Base $e) {
                        $err_msg = $e->getMessage();
                        return redirect('artist_payments')->with('error', $err_msg)->withInput();
                        // Display a very generic error to the user, and maybe send
                        // yourself an email
                    } catch (Exception $e) {
                        $err_msg = $e->getMessage();
                        return redirect('artist_payments')->with('error', $err_msg)->withInput();
                        // Something else happened, completely unrelated to Stripe
                    }
                }
            }
        } else {
            return redirect('artist_payments')->with('error', 'Artist Stripe Account is not created by admin');
        }
//
        /* if(!is_null($artist))
          {

          }else{
          return redirect('artist_payments')->with('error','Artist Not found');
          } */

        /* Live key */
//\Stripe\Stripe::setApiKey('sk_live_AuLjanpEXm7L1Iq22XhDBzyR');
        // $a = \Stripe\Transfer::create(array(
        // 	"amount" => ($price)*100,
        // 	"currency" => "usd",
        // 	"destination" => $artist->stripe_account_id,
        // 	"description" => "Transfer for Accepting Video Request From User"
        // 	));
        // $admin_payment = new AdminPayment();
        // $admin_payment->transaction_id = $a->id;
        // $admin_payment->status = $a->status;
        // $admin_payment->payment_to = $artistId;
        // $admin_payment->video_request_id = $videoId;
        // $admin_payment->paid_amount = $a->amount/100;
        // $admin_payment->date = $a->date;
        // if($admin_payment->save()){
        // 	return redirect('artist_payments')->with('success','Payment Done Successfully');
        // }
    }

    public function refund_payments() {
        try {
            //\Stripe\Stripe::setApiKey("pk_test_6GQiJcLMm9lxe4cd3TT6OCRl");
            //\Stripe\Stripe::setApiKey("sk_live_AuLjanpEXm7L1Iq22XhDBzyR");
            if (App::environment('local') || App::environment('testing')) {
                // The environment is local or testing
                \Stripe\Stripe::setApiKey(Config::get('constants.STRIPE_KEYS.test.secret_key'));
            } else {
                // The environment is production
                \Stripe\Stripe::setApiKey(Config::get('constants.STRIPE_KEYS.live.secret_key'));
            }
            \Stripe\Stripe::setApiVersion(Config::get('constants.STRIPE_VERSION'));

            $sql = DB::table('requestvideos')
                    ->join('payments', 'requestvideos.VideoReqId', '=', 'payments.video_request_id')
                    ->select('payments.*', 'requestvideos.*')
                    ->where('payments.status', 'succeeded')
                    ->where('requestvideos.RequestStatus', 'Approved')
                    ->orWhere('requestvideos.RequestStatus', 'Pending')
                    ->get();

            foreach ($sql as $request) {
                $date_1 = $request->created_at;
                $date_2 = date('Y-m-d H:i:s');
                $datetime1 = date_create($date_1);
                $datetime2 = date_create($date_2);
                $interval = date_diff($datetime2, $datetime1);
                $day = $interval->format('%a');

                if ($day > 15) {
                    $ch = \Stripe\Charge::retrieve($request->charge_id);

                    if (!$ch->refunded) {
                        $re = $ch->refund();
                        $video_request = \App\Requestvideo::find($request->video_request_id);
                        $video_request->RequestStatus = 'Time elapsed';
                        $video_request->save();
                        $refund_details['status'] = 'refunded';
                        $refund_details['request_detail'] = $request;
                        Mail::send('emails.refund_success_cron', $refund_details, function ($message) use ($request) {
                            $message->from('noreply@videorequestline.com', 'VRL');
                            $message->to($request->requestor_email, $request->requestor_email);
                            $message->bcc(config('constants.BCC_MAIL_CONFIG.admin_email'), config('constants.BCC_MAIL_CONFIG.admin_email_display_name'));
                            $message->subject('Payment refund');
                        });
                    }
                }
            }
        } catch (\Stripe\Error\Card $e) {
            //$response = array('return_message' => "The card has been declined.");
            $err_msg = $e->getMessage();
            return redirect('/stripe_payment')->with('error', $err_msg)->withInput();
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSharePercentage()
    {
        $share = Share::first();
        return view('admin.percentage-update', compact('share'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updateSharePercentage(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
                'admin_share' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
                'artist_share' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            ]
        );
        $share = Share::first();
        if ($validator->fails()) {
            $errors = $validator->messages();
            return view('admin.percentage-update', compact('errors', 'share'));
        }
        $percentage = $request->admin_share + $request->artist_share;
        if ($percentage > 100 || $percentage < 100) {
            $error = ['error' => 'Percentage sum must not be less than or greater than 100'];
            return view('admin.percentage-update', compact('error', 'share'));
        }
        if (!is_null($share)) {
            DB::table('shares')->where('id', 1)->update([
                'admin_share' => $request->admin_share,
                'artist_share' => $request->artist_share,
            ]);
            $success = ['success' => 'Successfully updated'];
            $share = Share::first();
            return view('admin.percentage-update', compact('success', 'share'));
        } else {
            Share::create([
                'admin_share' => $request->admin_share,
                'artist_share' => $request->artist_share,
            ]);
            $success = ['success' => 'Successfully added'];
            $share = Share::first();
            return view('admin.percentage-update', compact('success', 'share'));
        }
    }
}
