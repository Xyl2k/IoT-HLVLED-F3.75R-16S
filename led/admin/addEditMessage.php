<?php
//start session
session_start();

//get session data
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';

//check whether user ID is available in cookie
$rememberUserId = $_COOKIE['rememberUserId'];
if(!empty($rememberUserId)){
	$_SESSION['sessData']['userLoggedIn'] = TRUE;
	$_SESSION['sessData']['userId'] = $rememberUserId;
}

if(!empty($sessData['userLoggedIn']) && !empty($sessData['userID'])){
    include_once 'User.class.php';
    $user = new User();
    $conditions['where'] = array(
        'id' => $sessData['userID'],
    );
    $conditions['return_type'] = 'single';
    $userData = $user->getRows($conditions);
    
    $userPicture = !empty($userData['picture'])?'uploads/profile_picture/'.$userData['picture']:'images/avatar.png';
	$userName = htmlentities($userData['first_name']).' '.htmlentities($userData['last_name']);
	
	if(!empty($_GET['id'])){
		include 'Message.class.php';
		$msg = new Message();
		$conditions['where'] = array(
			'id' => $sb->security($_GET['id']),
		);
		$conditions['return_type'] = 'single';
		$msgData = $msg->getRows($conditions);
	}
}else{
	header("Location: index.php");
}

//get status message from session
if(!empty($sessData['status']['msg'])){
    $statusMsg = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}

$actionLabel = !empty($_GET['id'])?'Edit':'Add';
?>
<!DOCTYPE html>
<html>
<head>
	<?php include_once 'elements/head.php'; ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include_once 'elements/header.php'; ?>
    <?php include_once 'elements/left_navigation.php'; ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
		<section class="content-header">
		  <h1>
			Message
			<small><?php echo $actionLabel; ?></small>
		  </h1>
		  <ol class="breadcrumb">
			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="messages.php"><i class="fa fa-dashboard"></i> Message</a></li>
			<li class="active"><?php echo $actionLabel; ?></li>
		  </ol>
		</section>
	
		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-xs-12">
					<a href="messages.php" class="btn btn-success pull-right"><i class="fa fa-arrow-left"></i> Back</a>
				</div>
				<?php if(!empty($statusMsg) && ($statusMsgType == 'success')){ ?>
				<div class="col-xs-12">
					<div class="alert alert-success"><?php echo $statusMsg; ?></div>
				</div>
				<?php }elseif(!empty($statusMsg) && ($statusMsgType == 'error')){ ?>
				<div class="col-xs-12">
					<div class="alert alert-danger"><?php echo $statusMsg; ?></div>
				</div>
				<?php } ?>
				<form role="form" method="post" action="action.php">
				<div class="col-md-9">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><?php echo $action; ?> Message Content</h3>
						</div>
						<!-- form start -->
							<div class="box-body">
								<div class="form-group">
									<label for="title">Message</label>
									<input type="text" class="form-control" name="content" maxlength="59" placeholder="Enter content" value="<?php echo !empty($msgData['content'])?$msgData['content']:''; ?>" required="">
								</div>
								<div class="form-group">
									<label for="status">Status</label>
									<div class="radio">
										<label>
										  <input type="radio" id="status" name="status" value="1" <?php echo (empty($msgData['status']) || !empty($msgData['status']) && $msgData['status'] == '1')?'checked=""':''; ?>>
										  Active
										</label>
									</div>
									<div class="radio">
										<label>
										  <input type="radio" id="status" name="status" value="0" <?php echo (isset($msgData['status']) && $msgData['status'] == '0')?'checked=""':''; ?>>
										  Inactive
										</label>
									</div>
								</div>
							</div>
							<!-- /.box-body -->
			
							<div class="box-footer">
								<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
								<input type="submit" name="msgSubmit" class="btn btn-primary" value="Submit"/>
							</div>
					</div>
				</div>
				</form>
			</div>
		</section>
		<!-- /.content -->
    </div>
    
    <?php include_once 'elements/footer.php'; ?>

</div>
<!-- ./wrapper -->

<?php include_once 'elements/footer_scripts.php'; ?>
</body>
</html>