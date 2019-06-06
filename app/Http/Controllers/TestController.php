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
use Crypt;
class TestController extends Controller
{
	public function UpdateProfile(Request $request){

		$updateType = $request->update;

		if($updateType=="Profile"){

			if(Profile::where('ProfileId',$request->profileId)->update(array('Name' => $request->username,'EmailId' => $request->email,'DateOfBirth' => $request->dob,'MobileNo' => $request->phone,'NickName' => $request->NickName,'Address' => $request->Address,'City' => $request->City,'State' => $request->State,'country' => $request->Country,'Zip' => $request->Zip,'PaypalId'=>$request->PaypalId,'profile_description'=>$request->Description,'timestamp'=>$request->timestamp,'VideoPrice'=>$request->price))){

				$flag=1;

				if(DB::table('users')->where('profile_id', $request->profileId)->update(['user_name' => $request->username])){

					$flag=1;  
				}

				if($flag==1){

					$response = array('return_message' => "Updated Successfully ");
					return json_encode($response);
				}	

			}else{

				$response = array('return_message' => "Not Updated");
				return json_encode($response);
			}
			
		}

		if($updateType=="Picture"){
			
			if ($_FILES["media"]["error"] > 0) {

				$error = $_FILES["media"]["error"];

			}else if (($_FILES["media"]["type"] == "image/gif") || ($_FILES["media"]["type"] == "image/jpeg") || ($_FILES["media"]["type"] == "image/png") || ($_FILES["media"]["type"] == "image/pjpeg")){ 
				
				if($request->type=="User"){
					$target = "images".'/'.'User';
					$resized_file = "images/User";
					$target_file = "images/User";
				}else{
					$target = "images".'/'.'Artist';
					$resized_file = "images/Artist";
					$target_file = "images/Artist";
				}

				//$file = $request->file('media') ;
				$fileName = date('U').$request->file('media')->getClientOriginalName();
				//$fileName1 = $request->profileId.'_'.$file->getClientOriginalName() ;

				$imageTempName = $request->file('media')->getPathname();
				$imageName = $request->profileId.'_'.$request->file('media')->getClientOriginalName();
		       //$path = base_path() . '/public/uploads/consultants/images/';
				$path = $target;
				$request->file('media')->move($path , $imageName);


				// $source_url = $_FILES["media"]["tmp_name"];
				$destination_url = $target.'/'.$imageName ;
				// $quality = 100;
				
				// $info = getimagesize($source_url); 
				// if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source_url); 
				// elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source_url); 
				// elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source_url); 
				// imagejpeg($image, $destination_url, $quality);

				echo $destination_url;


				//move_uploaded_file($source_url,$target.'/'.$fileName1);
				
				
			}
			
			if(Profile::where('ProfileId',$request->profileId)->update(array('profile_path_Mobile'=>$target.'/'.$fileName,
				'profile_path'=>$destination_url))){

				$msg="Updated successfully";

			return json_encode($msg);

		}else{

			$msg='Updated not successfully';
			return json_encode($msg);
		}	


		
	}

	if($updateType=="Banner"){

		if($request->type=="User"){
			$banner_target = "banner".'/'.'User';
			$resized_file = "banner/User";
			$target_file = "banner/User";
		}else{
			$banner_target = "banner".'/'.'Artist';
			$resized_file = "banner/Artist";
			$target_file = "banner/Artist";
		}
		$file = $request->file('media') ;
		$fileName = date('U').$file->getClientOriginalName() ;
		$fileName1 = $request->profileId.'_'.$file->getClientOriginalName() ;
		$source_url = $_FILES["media"]["tmp_name"];
		$destination_url = $banner_target.'/'.$fileName ;
		$quality = 75;
		
		$info = getimagesize($source_url); 
		if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source_url); 
		elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source_url); 
		elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source_url); 
		imagejpeg($image, $destination_url, $quality);
		move_uploaded_file($source_url,$banner_target.'/'.$fileName1);
		
		
		if(Profile::where('ProfileId',$request->profileId)->update(array('BannerImg_Mobile'=>$banner_target.'/'.$fileName,
			'BannerImg'=>$banner_target.'/'.$fileName1))){
			$msg=$banner_target.'/'.$fileName;
		return json_encode($msg);
	}else{
		$msg='Updated not successfully';
		return json_encode($msg);
	}
}      
}


public function testing(){

	$artist = Profile::find(898);

	\Stripe\Stripe::setApiKey('sk_test_FLN8U4MNoHOY1dN3XSz9yJyU');
	$a  = \Stripe\Account::create(
		array(
			"country" => "US",
			"managed" => true,
			"external_account" => array(
				"object" => "bank_account",
				"country" => "US",
				"currency" => "usd",
				"routing_number" =>Crypt::decrypt($artist->routing_number),
				"account_number" => Crypt::decrypt($artist->account_number),
				),
			)
		);

	
	$strip_id = $a->id;
	/*------------------------------Update---------------------------*/
	$account = \Stripe\Account::retrieve($a->id);

	$account->legal_entity->first_name = $artist->Name;
	$account->legal_entity->last_name = " ";
	$account->legal_entity->dob->day = 18;
	$account->legal_entity->dob->month = 03;
	$account->legal_entity->dob->year = 1990;
	$account->legal_entity->type = 'individual';
	$account->tos_acceptance->date = time();
	$account->tos_acceptance->ip = $_SERVER['REMOTE_ADDR'];
	$account->legal_entity->ssn_last_4 = 1234;
				//dd($account);

	
	if($account->legal_entity->address->postal_code ==null )
	{
		if($artist->Address == ""){
			$account->legal_entity->address->line1 = null;
		}else{
			$account->legal_entity->address->line1 = $artist->Address;
		}
		if($artist->City == ""){
			$account->legal_entity->address->city = null;
		}else{
			$account->legal_entity->address->city = $artist->City;
		}

		if($artist->Zip == ""){
			$account->legal_entity->address->postal_code = null;
		}else{
			$account->legal_entity->address->postal_code = $artist->Zip;
		}
		if($artist->State == ""){
			$account->legal_entity->address->state = null;
		}else{
			$account->legal_entity->address->state = $artist->State;
		}
		//$account->legal_entity->personal_id_number=$artist->pin;
		$account->legal_entity->additional_owners =null;
		if($account->save()){
			echo 'ho gaya';
		}else{
			echo 'nahi huaa';
		}
				//dd();			
		/*------------------------------Verify-----------------------------*/
		$file = \Stripe\FileUpload::create(
			array(
				"purpose" => "identity_document",
				"file" => fopen(public_path().'/'.$artist->id_pic, 'r')
				),
			array("stripe_account" =>$a->id)
			);

		$img_id = $file->id;
		$account1 = \Stripe\Account::retrieve($a->id);
		$account1->legal_entity->verification->document = $img_id;
		if($account1->save()){	
			echo 'ho gaya';

			$artist = Profile::find(898);	
			$artist->stripe_account_id = $strip_id;
			$artist->is_bank_updated = 898;
			$artist->bank_id = $a->external_accounts->data[0]->id;
			$artist->save();

			echo 'Connected Account Created Successfully';
			
		}else{
			echo 'nahi huaa';
		}
	}else{
		echo 'nnnnnnnnnnnnnn';
	}

}

}