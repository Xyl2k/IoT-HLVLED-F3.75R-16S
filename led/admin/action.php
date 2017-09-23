<?php
//start session
session_start();

//load and initialize site settings class
include 'Site.class.php';
$sb = new DB();
$site = new Site();

//load and initialize message class
include 'Message.class.php';
$msg = new Message();

$sessData = $_SESSION['sessData'];
if(isset($_POST['msgSubmit']) && !empty($sessData['userID'])){
	$sessUserId = $sessData['userID'];
	if(!empty($_POST['content'])){
        //insert data
        $data = array(
            'content' => $sb->security($_POST['content']),
            'status' => $sb->security($_POST['status'])
        );
        $insert = $msg->insert($data);
        if($insert){
            $sessData['status']['type'] = 'success';
            $sessData['status']['msg'] = 'Message has been added successfully.';
        }else{
            $sessData['status']['type'] = 'error';
            $sessData['status']['msg'] = 'Some problem occurred, please try again.';
        }
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = 'All fields are mandatory, please fill all the fields.'; 
    }
	
	//store status into the session
    $_SESSION['sessData'] = $sessData;
    $redirectURL = 'messages.php';
	//redirect to the pasword settings page
    header("Location:".$redirectURL);
}elseif(isset($_POST['siteSubmit']) && !empty($sessData['userID'])){
    //update data
    $conditions = array(
        'id' => 1
    );
    $data = array(
        'msg_display_type' => $sb->security($_POST['msg_display_type']),
        'msg_default' => $sb->security($_POST['msg_default'])
    );
    $update = $site->update($data, $conditions);
    if($update){
        $sessData['status']['type'] = 'success';
        $sessData['status']['msg'] = 'Site Settings has been updated successfully.';
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = 'Some problem occurred, please try again.';
    }
	
	//store status into the session
    $_SESSION['sessData'] = $sessData;
    $redirectURL = 'settings.php';
	//redirect to the pasword settings page
    header("Location:".$redirectURL);
}elseif(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type']) && !empty($sessData['userID'])){
    if(($_REQUEST['action_type'] == 'blockMessage') && !empty($_GET['id'])){
        //update data
        $conditions = array(
            'id' => $sb->security($_GET['id'])
        );
        $data = array(
            'status' => '0'
        );
        $update = $msg->update($data, $conditions);
        if($update){
            $sessData['status']['type'] = 'success';
            $sessData['status']['msg'] = 'Message has been blocked successfully.';
        }else{
            $sessData['status']['type'] = 'error';
            $sessData['status']['msg'] = 'Some problem occurred, please try again.';
        }
        
        //store status into the session
        $_SESSION['sessData'] = $sessData;
        $redirectURL = 'messages.php';
        //redirect to the pasword settings page
        header("Location:".$redirectURL);
    }elseif(($_REQUEST['action_type'] == 'unblockMessage') && !empty($_GET['id'])){
        //update data
        $conditions = array(
            'id' => $sb->security($_GET['id'])
        );
        $data = array(
            'status' => '1'
        );
        $update = $msg->update($data, $conditions);
        if($update){
            $sessData['status']['type'] = 'success';
            $sessData['status']['msg'] = 'Message has been unblocked successfully.';
        }else{
            $sessData['status']['type'] = 'error';
            $sessData['status']['msg'] = 'Some problem occurred, please try again.';
        }
        
        //store status into the session
        $_SESSION['sessData'] = $sessData;
        $redirectURL = 'messages.php';
        //redirect to the pasword settings page
        header("Location:".$redirectURL);
    }elseif(($_REQUEST['action_type'] == 'deleteMessage') && !empty($_GET['id'])){
        //update data
        $conditions = array(
            'id' => $sb->security($_GET['id'])
        );
        $delete = $msg->delete($conditions);
        if($delete){
            $sessData['status']['type'] = 'success';
            $sessData['status']['msg'] = 'Message has been deleted successfully.';
        }else{
            $sessData['status']['type'] = 'error';
            $sessData['status']['msg'] = 'Some problem occurred, please try again.';
        }
        
        //store status into the session
        $_SESSION['sessData'] = $sessData;
        $redirectURL = 'messages.php';
        //redirect to the pasword settings page
        header("Location:".$redirectURL);
    }
}