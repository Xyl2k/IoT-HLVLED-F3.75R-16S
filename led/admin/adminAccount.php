<?php
//start session
session_start();

//ini_set('display_errors', 1); 

//load and initialize user class
include 'User.class.php';
$sb = new DB();
$user = new User();
if(isset($_POST['loginSubmit'])){
	if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])):
        //your site secret key
        $secret = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';
        //get verify response data
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
        if($responseData->success):

	//check email is valid or not
	if (isset($_POST['email'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $sessData['status']['type'] = 'error';
			$sessData['status']['msg'] = ' The email that you are using is Invalid';
		 
        }
    }

//check whether login details are empty
    if(!empty($_POST['email']) && !empty($_POST['password'])){
		//get user data from user class
        $conditions['where'] = array(
            'email' => $sb->security($_POST['email']),
            'password' => hash('sha384',$_POST['password']),
            'status' => '1'
        );
        $conditions['return_type'] = 'single';
        $userData = $user->getRows($conditions);
		//set user data and status based on login credentials
        if($userData && $userData['activated'] == '0'){
			$sessData['status']['type'] = 'error';
			$sessData['status']['msg'] = 'Your account activation is pending, please check your email to verify and activate your account.';
		}elseif($userData){
			//if remember me is checked
			if ($_POST['rememberMe'] == 1) {
				setcookie('rememberUserId', $userData['id'], time() + (86400));
			}

            $sessData['userLoggedIn'] = TRUE;
            $sessData['userID'] = $userData['id'];
            $sessData['status']['type'] = 'success';
            $sessData['status']['msg'] = 'Welcome '.htmlentities($userData['first_name']).'!';
        }else{
            $sessData['status']['type'] = 'error';
            $sessData['status']['msg'] = 'Wrong email or password, please try again.'; 
        }
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = 'Enter email and password.'; 
    }

    else:
           
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = 'Robot verification failed, please try again.'; 
    endif;
    else:
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = 'Please click on the reCAPTCHA box';
    endif;


	//store login status into the session
    $_SESSION['sessData'] = $sessData;
	//redirect to the home page
    header("Location:index.php");
}elseif(isset($_POST['forgotSubmit'])){
	$frmDisplay = '';
	//check whether email is empty
    if(!empty($_POST['email'])){
		//check whether user exists in the database
		$prevCon['where'] = array('email'=>$_POST['email']);
		$prevCon['return_type'] = 'count';
		$prevUser = $user->getRows($prevCon);
		if($prevUser > 0){
			//generat unique string
			$uniqidStr = hash('sha384', uniqid(mt_rand()));
			
			//update data with forgot pass code
			$conditions = array(
				'email' => $sb->security($_POST['email'])
			);
			$data = array(
				'forgot_pass_identity' => $uniqidStr
			);
			$update = $user->update($data, $conditions);
			
			if($update){
				//get user details
				$con['where'] = array('email'=>$_POST['email']);
				$con['return_type'] = 'single';
				$userDetails = $user->getRows($con);
				
				//send reset password email
				include_once 'email_functions.php';
                @forgotPassEmail($userDetails);
				
				$sessData['status']['type'] = 'success';
				$sessData['status']['msg'] = 'Please check your e-mail, we have sent a password reset link to your registered email.';
				$frmDisplay = '?frmDis=0';
			}else{
				$sessData['status']['type'] = 'error';
				$sessData['status']['msg'] = 'Some problem occurred, please try again.';
			}
		}else{
			$sessData['status']['type'] = 'error';
			$sessData['status']['msg'] = 'Given email is not associated with any account.'; 
		}
		
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = 'Enter email to create a new password for your account.'; 
    }
	//store reset password status into the session
    $_SESSION['sessData'] = $sessData;
	//redirect to the forgot pasword page
    header("Location:forgotPassword.php".$frmDisplay);
}elseif(isset($_POST['resetSubmit'])){
	$fp_code = $_POST['fp_code'];
	if(!empty($_POST['password']) && !empty($_POST['confirm_password']) && !empty($fp_code)){
		//password and confirm password comparison
        if($_POST['password'] !== $_POST['confirm_password']){
            $sessData['status']['type'] = 'error';
            $sessData['status']['msg'] = 'Confirm password must match with the password.'; 
        }else{
			//check whether identity code exists in the database
            $prevCon['where'] = array('forgot_pass_identity' => $fp_code);
            $prevCon['return_type'] = 'single';
            $prevUser = $user->getRows($prevCon);
            if(!empty($prevUser)){
				//update data with new password
				$conditions = array(
					'forgot_pass_identity' => $fp_code
				);
				$data = array(
					'password' => hash('sha384', $_POST['password'])
				);
				$update = $user->update($data, $conditions);
				if($update){
					$sessData['status']['type'] = 'success';
                    $sessData['status']['msg'] = 'Your account password has been reset successfully. Please login with your new password.';
				}else{
					$sessData['status']['type'] = 'error';
					$sessData['status']['msg'] = 'Some problem occurred, please try again.';
				}
            }else{
                $sessData['status']['type'] = 'error';
                $sessData['status']['msg'] = 'You does not authorized to reset new password of this account.';
            }
        }
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = 'All fields are mandatory, please fill all the fields.'; 
    }
	//store reset password status into the session
    $_SESSION['sessData'] = $sessData;
    $redirectURL = ($sessData['status']['type'] == 'success')?'index.php':'resetPassword.php?fp_code='.$fp_code;
	//redirect to the login/reset pasword page
    header("Location:".$redirectURL);
}elseif(isset($_REQUEST['verifyEmail']) && $_REQUEST['verifyEmail'] == 1){
	$ac_code = $_REQUEST['ac_code'];

	//check whether activation code exists in the database
	$prevCon['where'] = array('activation_code' => $ac_code);
	$prevCon['return_type'] = 'single';
	$prevUser = $user->getRows($prevCon);
	if(!empty($prevUser)){
		//update data with new password
		$conditions = array(
			'activation_code' => $ac_code
		);
		$data = array(
			'activated' => '1'
		);
		$update = $user->update($data, $conditions);
		if($update){
			$sessData['status']['type'] = 'success';
			$sessData['status']['msg'] = 'Email verification for your account was successful. Please login to your account.';
		}else{
			$sessData['status']['type'] = 'error';
			$sessData['status']['msg'] = 'Some problem occurred, please try again.';
		}
	}else{
		$sessData['status']['type'] = 'error';
		$sessData['status']['msg'] = 'You have clicked on the wrong verification link, please check your email and try again.';
	}
	//store account activation status into the session
    $_SESSION['sessData'] = $sessData;
    $redirectURL = 'index.php';
	//redirect to the login page
    header("Location:".$redirectURL);
}elseif(isset($_POST['updateProfile']) && !empty($_SESSION['sessData']['userID'])){
	$sessData = $_SESSION['sessData'];
	$sessUserId = $sessData['userID'];

	//check whether user details are empty
    if(!empty($_POST['first_name']) && !empty($_POST['last_name']) && !empty($_POST['email'])){
		//check whether user exists in the database
		$prevCon['where'] = array('email'=>$sb->security($_POST['email']));
		$prevCon['where_not'] = array('id'=>$sessUserId);
		$prevCon['return_type'] = 'count';
		$prevUser = $user->getRows($prevCon);
		if($prevUser > 0){
			$sessData['status']['type'] = 'error';
			$sessData['status']['msg'] = 'Email already exists, please use another email.';
		}else{
			//get user information
			$conditions['where'] = array(
				'id' => $sessData['userID'],
			);
			$conditions['return_type'] = 'single';
			$userData = $user->getRows($conditions);
			$prevPicture = $userData['picture'];
			
			//prepare user data 
			$userData = array(
				'first_name' => $sb->security($_POST['first_name']),
				'last_name' => $sb->security($_POST['last_name']),
				'email' => $sb->security($_POST['email'])
			);
			
			//profile picture upload
			$fileErr = 0;
			if(isset($_FILES['picture']['name']) && $_FILES['picture']['name']!=""){
				$targetDir = 'uploads/profile_picture/';
				$fileName = time().'_'.basename($_FILES["picture"]["name"]);
				$targetFilePath = $targetDir. $fileName;
				$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
				$allowTypes = array('jpg','png','jpeg','gif','pdf');
				if(in_array($fileType, $allowTypes)){
					if(move_uploaded_file($_FILES["picture"]["tmp_name"], $targetFilePath)){
						$userData['picture'] = $fileName;
						
						//delete previous profile picture
						@unlink('uploads/profile_picture/'.$prevPicture);
					}else{
						//print_r($_FILES);die;
					}
				}else{
					$fileErr = 1;
					$sessData['status']['type'] = 'error';
					$sessData['status']['msg'] = 'Please select only gif/jpg/png file.';
				}
			}
			
			if($fileErr == 0){
				//update user data in the database
				$conditions = array(
					'id' => $sessUserId
				);
				$update = $user->update($userData, $conditions);
				
				//set status based on data insert
				if($update){
					$sessData['status']['type'] = 'success';
					$sessData['status']['msg'] = 'Your profile information has been updated successfully.';
				}else{
					$sessData['status']['type'] = 'error';
					$sessData['status']['msg'] = 'Some problem occurred, please try again.';
				}
			}
		}
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = 'All fields are mandatory, please fill all the fields.'; 
    }
	
	$sessData['tabActive'] = 'settings';
	
	//store signup status into the session
    $_SESSION['sessData'] = $sessData;

    $redirectURL = 'profile.php';
	//redirect to the profile page
    header("Location:".$redirectURL);
}elseif(isset($_POST['updatePassword']) && !empty($_SESSION['sessData']['userID'])){
	$sessData = $_SESSION['sessData'];
	$sessUserId = $sessData['userID'];
	if(!empty($_POST['password']) && !empty($_POST['confirm_password'])){
		//password and confirm password comparison
        if($_POST['password'] !== $_POST['confirm_password']){
            $sessData['status']['type'] = 'error';
            $sessData['status']['msg'] = 'Confirm password must match with the password.'; 
        }else{
			//check whether identity code exists in the database
            $prevCon['where'] = array('id' => $sessUserId, 'password' => hash('sha384', $_POST['old_password']));
            $prevCon['return_type'] = 'count';
            $prevUser = $user->getRows($prevCon);
            if($prevUser > 0){
				//update data with new password
				$conditions = array(
					'id' => $sessUserId
				);
				$data = array(
					'password' => hash('sha384', $_POST['password'])
				);
				$update = $user->update($data, $conditions);
				if($update){
					$sessData['status']['type'] = 'success';
                    $sessData['status']['msg'] = 'Your profile password has been updated successfully.';
				}else{
					$sessData['status']['type'] = 'error';
					$sessData['status']['msg'] = 'Some problem occurred, please try again.';
				}
            }else{
                $sessData['status']['type'] = 'error';
                $sessData['status']['msg'] = 'The given old password does not match with your account password.';
            }
        }
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = 'All fields are mandatory, please fill all the fields.'; 
    }
	
	$sessData['tabActive'] = 'settings';
	
	//store reset password status into the session
    $_SESSION['sessData'] = $sessData;
    $redirectURL = 'profile.php';
	//redirect to the pasword settings page
    header("Location:".$redirectURL);
}elseif(!empty($_REQUEST['logoutSubmit'])){
	//remove cookie data
	setcookie("rememberUserId", "", time() - 3600);
	
	//remove session data
    unset($_SESSION['sessData']);
    session_destroy();
	//store logout status into the ession
    $sessData['status']['type'] = 'success';
    $sessData['status']['msg'] = 'You have logout successfully from your account.';
    $_SESSION['sessData'] = $sessData;
	//redirect to the home page
    header("Location:index.php");
}else{
	//redirect to the home page
    header("Location:index.php");
}

/*
function secure($input) {
  $input = trim($input);
  $input = stripslashes($input);
  $input = htmlspecialchars($input);
  return $input;
}
*/