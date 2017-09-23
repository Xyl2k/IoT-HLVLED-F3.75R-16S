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
}else{
	header("Location: index.php");
}

//get status message from session
if(!empty($sessData['status']['msg'])){
    $statusMsg = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}

$infoTab = 'active';
$settingsTab = '';
if(!empty($sessData['tabActive']) && ($sessData['tabActive'] == 'settings')){
	$infoTab = '';
	$settingsTab = 'active';
	unset($_SESSION['sessData']['tabActive']);
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
			Profile
			<small>Management</small>
		  </h1>
		  <ol class="breadcrumb">
			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Profile</li>
		  </ol>
		</section>
	
		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-md-3">
					<!-- Profile Image -->
					<div class="box box-primary">
						<div class="box-body box-profile">
							<img class="profile-user-img img-responsive img-circle" src="<?php echo $userPicture; ?>" alt="User profile picture">
							<h3 class="profile-username text-center"><?php echo htmlentities($userData['first_name']).' '.htmlentities($userData['last_name']); ?></h3>
							<p class="text-muted text-center">Administrator</p>
							<ul class="list-group list-group-unbordered">
								<li class="list-group-item">
									<b><?php echo $userData['email']; ?></b>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- /.col -->
				<div class="col-md-9">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li class="<?php echo $infoTab; ?>"><a href="#timeline" data-toggle="tab">Info</a></li>
							<li class="<?php echo $settingsTab; ?>"><a href="#settings" data-toggle="tab">Settings</a></li>
						</ul>	
						<div class="tab-content">
							<!-- /.tab-pane -->
							<div class="tab-pane <?php echo $infoTab; ?>" id="timeline">
								<!-- The timeline -->
								<form class="form-horizontal">
								<div class="form-group">
									<label for="inputName" class="col-sm-2 control-label">Name</label>
									<div class="col-sm-10">
										<p><?php echo htmlentities($userData['first_name']).' '.htmlentities($userData['last_name']); ?></p>
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail" class="col-sm-2 control-label">Email</label>
									<div class="col-sm-10">
										<p><?php echo $userData['email']; ?></p>
									</div>
								</div>
								</form>
							</div>
							<!-- /.tab-pane -->
			
							<div class="tab-pane <?php echo $settingsTab; ?>" id="settings">
								<?php 
									if(!empty($statusMsg) && ($statusMsgType == 'success')){
										echo '<div class="alert alert-success">'.$statusMsg.'</div>';
									}elseif(!empty($statusMsg) && ($statusMsgType == 'error')){
										echo '<div class="alert alert-danger">'.$statusMsg.'</div>';
									}
								?>
								<h2 class="page-header">
									<i class="fa fa-globe"></i> Update Profile Information
								</h2>
								<form action="adminAccount.php" class="form-horizontal" method="post" enctype="multipart/form-data">
								<div class="form-group">
									<label for="inputName" class="col-sm-2 control-label">Profile Picture</label>
									<div class="col-sm-10">
										<input type="file" class="form-control" name="picture">
									</div>
								</div>
								<div class="form-group">
									<label for="inputName" class="col-sm-2 control-label">First Name</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="first_name" placeholder="First Name" value="<?php echo !empty(htmlentities($userData['first_name']))?htmlentities($userData['first_name']):''; ?>" required="">
									</div>
								</div>
								<div class="form-group">
									<label for="inputName" class="col-sm-2 control-label">Last Name</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="last_name" placeholder="Last Name" value="<?php echo !empty(htmlentities($userData['last_name']))?htmlentities($userData['last_name']):''; ?>" required="">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail" class="col-sm-2 control-label">Email</label>
									<div class="col-sm-10">
										<input id="email" class="form-control" type="email" name="email" placeholder="Email Address" value="<?php echo !empty($userData['email'])?$userData['email']:''; ?>" required="">
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<input type="submit" name="updateProfile" class="btn btn-danger" value="Submit"/>
									</div>
								</div>
								</form>
								
								<h2 class="page-header">
									<i class="fa fa-globe"></i> Update Password
								</h2>
								<form action="adminAccount.php" class="form-horizontal" method="post">
								<div class="form-group">
									<label for="old_password" class="col-sm-2 control-label">Old Password</label>
									<div class="col-sm-10">
										<input type="password" class="form-control" name="old_password" placeholder="Old Password" value="" required="">
									</div>
								</div>
								<div class="form-group">
									<label for="password" class="col-sm-2 control-label">New password</label>
									<div class="col-sm-10">
										<input id="password" class="form-control" type="password" name="password" placeholder="New password" value="" required="">
									</div>
								</div>
								<div class="form-group">
									<label for="confirm_password" class="col-sm-2 control-label">Confirm password</label>
									<div class="col-sm-10">
										<input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Repeat password" value="" required="">
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<input type="submit" name="updatePassword" class="btn btn-danger" value="Submit"/>
									</div>
								</div>
								</form>
								
							</div>
							<!-- /.tab-pane -->
						</div>
						<!-- /.tab-content -->
					</div>
					<!-- /.nav-tabs-custom -->
				</div>
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