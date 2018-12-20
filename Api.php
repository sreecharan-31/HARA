<?php

	require_once 'DbConnect.php';

	$response = array();

	if(isset($_GET['apicall'])){

		switch($_GET['apicall']){

			case 'signup':
				if(isTheseParametersAvailable(array('name','dob','gender','ph_num','email','address','gaurdian_name','gaurdian_ph_num','gaurdian_email'))){
					$name = $_POST['name'];
					$dob = $_POST['dob'];
					$gender = $_POST['gender'];
					$ph_num=$_POST['ph_num'];
					$email = $_POST['email'];
					$address = $_POST['address'];
					$gaurdian_name = $_POST['gaurdian_name'];
					$gaurdian_ph_num = $_POST['gaurdian_ph_num'];
					$gaurdian_email = $_POST['gaurdian_email'];

					$stmt = $conn->prepare("SELECT pid FROM patient WHERE name = ? OR ph_num = ?");
					$stmt->bind_param("ss", $name, $ph_num);
					$stmt->execute();
					$stmt->store_result();

					if($stmt->num_rows > 0){
						$response['error'] = true;
						$response['message'] = 'User already registered';
						$stmt->close();
					}else{
						$stmt = $conn->prepare("INSERT INTO patient (name,dob,gender,ph_num,email,address,gaurdian_name,gaurdian_ph_num,gaurdian_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
						$stmt->bind_param("sssssssss", $name, $dob, $gender, $ph_num, $email, $address, $gaurdian_name, $gaurdian_ph_num, $gaurdian_email);

						if($stmt->execute()){
							$stmt = $conn->prepare("SELECT pid,pid,name,dob,gender,ph_num,email,address,gaurdian_name,gaurdian_ph_num,gaurdian_email FROM patient WHERE name = ? AND ph_num=?");
							$stmt->bind_param("ss",$name,$ph_num);
							$stmt->execute();
							$stmt->bind_result($userpid, $pid, $name, $dob, $gender, $ph_num, $email, $address, $gaurdian_name, $gaurdian_ph_num, $gaurdian_email);
							$stmt->fetch();

							$user = array(
								'pid'=>$pid,
								'name'=>$name,
								'dob'=>$dob,
								'gender'=>$gender,
								'ph_num'=>$ph_num,
								'email'=>$email,
								'address'=>$address,
								'gaurdian_name'=>$gaurdian_name,
								'gaurdian_ph_num'=>$gaurdian_ph_num,
								'gaurdian_email'=>$gaurdian_email
							);

							$stmt->close();

							$response['error'] = false;
							$response['message'] = 'User registered successfully';
							$response['user'] = $user;
						}
					}

				}else{
					$response['error'] = true;
					$response['message'] = 'required parameters are not available';
				}

			break;

			case 'login':

				if(isTheseParametersAvailable(array('username', 'ph_num'))){

					$username = $_POST['username'];
					$password = $_POST['ph_num'];

					$stmt = $conn->prepare("SELECT pid, name, email, gender FROM patient WHERE name = ? AND ph_num = ?");
					$stmt->bind_param("ss",$username, $ph_num);

					$stmt->execute();

					$stmt->store_result();

					if($stmt->num_rows > 0){

						$stmt->bind_result($pid, $name, $email, $gender);
						$stmt->fetch();

						$user = array(
							'pid'=>$pid,
							'name'=>$name,
							'email'=>$email,
							'gender'=>$gender
						);

						$response['error'] = false;
						$response['message'] = 'Login successfull';
						$response['user'] = $user;
					}else{
						$response['error'] = false;
						$response['message'] = 'Invalid username or password';
					}
				}
			break;

			default:
				$response['error'] = true;
				$response['message'] = 'Invalid Operation Called';
		}

	}else{
		$response['error'] = true;
		$response['message'] = 'Invalid API Call';
	}

	echo json_encode($response);

	function isTheseParametersAvailable($params){

		foreach($params as $param){
			if(!isset($_POST[$param])){
				return false;
			}
		}
		return true;
	}
?>
