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

	include 'Site.class.php';
	$site = new Site();
	$conditions['where'] = array(
		'id' => 1,
	);
	$conditions['return_type'] = 'single';
	$siteData = $site->getRows($conditions);
}else{
	header("Location: index.php");
}

//get status message from session
if(!empty($sessData['status']['msg'])){
    $statusMsg = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}

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
			Site Settings
			<small>update</small>
		  </h1>
		  <ol class="breadcrumb">
			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Site Settings</li>
		  </ol>
		</section>
	
		<!-- Main content -->
		<section class="content">
			<div class="row">
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
							<h3 class="box-title">Update Site Settings</h3>
						</div>
						<!-- form start -->
							<div class="box-body">
								<div class="form-group">
									<label for="status">Message Display</label>
									<div class="radio">
										<label>
										  <input type="radio" id="msg_display_type" name="msg_display_type" value="Dynamic" <?php echo (empty($siteData['msg_display_type']) || !empty($siteData['msg_display_type']) && $siteData['msg_display_type'] == 'Dynamic')?'checked=""':''; ?>>
										  Dynamic
										</label>
									</div>
									<div class="radio">
										<label>
										  <input type="radio" id="msg_display_type" name="msg_display_type" value="Static" <?php echo (isset($siteData['msg_display_type']) && $siteData['msg_display_type'] == 'Static')?'checked=""':''; ?>>
										  Static
										</label>
									</div>
								</div>
								<div class="form-group">
									<label for="title">Default Message</label>
									<input type="text" class="form-control" name="msg_default" placeholder="Enter message" value="<?php echo !empty($siteData['msg_default'])?$siteData['msg_default']:''; ?>">
								</div>
							</div>
							<!-- /.box-body -->
			
							<div class="box-footer">
								<input type="submit" name="siteSubmit" class="btn btn-primary" value="Submit"/>
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