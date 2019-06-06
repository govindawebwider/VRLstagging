<?php
/**
** http://vrl.projects-codingbrains.com/public/IACommand/vehicleDtl_api.php
**/
include('includes/db.php');
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} 
else{
	 $incident = $_GET['inc_id'];
	 $incident_type=$_GET['incident_type'];
	 $no_of_suspect=$_GET['no_of_suspects'];
	 
	 if((!empty($incident)) and (!empty($incident_type)) and (!empty($no_of_suspect))){
		$suspect_query = "INSERT INTO suspect_detail (incident,incident_type,no_of_suspect) VALUES ('$incident','$incident_type','$no_of_suspect')";
		
		if(mysqli_query($conn, $suspect_query)){
		    $last_id = mysqli_insert_id($conn);
		    $datas = json_decode($_GET['data1']);
			foreach ($datas as $data) {
			 $ethnicity = $data->ethnicity;
			 $height = $data->hei;
			 $weight = $data->weight;
			 $build = $data->build;
			 $clothing = $data->clothing;
			 $other_details = $data->otherDetails;
			 $suspect_id = $last_id;
			 $gender = $data->gender;
			 $susp_query = "INSERT INTO suspect_dtl(ethnicity,height,weight,build,clothing,other_details,suspect_id,gender) VALUES ('$ethnicity','$height','$weight','$build','$clothing','$other_details','$suspect_id','$gender')";
			 if(mysqli_query($conn, $susp_query)){
			 	$msg['success']='True';
			 }else{
				$msg['success']='False';
			 }
			}

		    
		    $msg['success']='True';
		} else{
			$msg['success']='False';
		}
	}
	echo json_encode($msg);
	mysqli_close($conn);
	
}
