<?php
//start session
session_start();

//get session data
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';

//redirect to homepage if user logged in
if(!empty($sessData['userLoggedIn']) && $sessData['userLoggedIn'] == true){
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
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="index.php"><b>HLVLED-F3.75R-16S</b>Admin</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
		<h2>Reset Admin Account Password</h2>
		<?php 
			if(!empty($statusMsg) && ($statusMsgType == 'success')){
				echo '<p class="statusMsg success">'.$statusMsg.'</p>';
			}elseif(!empty($statusMsg) && ($statusMsgType == 'error')){
				echo '<p class="statusMsg error">'.$statusMsg.'</p>';
			}
		?>
		<form action="adminAccount.php" method="post">
			<div class="form-group has-feedback">
				<input type="password" class="form-control" name="password" placeholder="Password" required="">
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="password" class="form-control" name="confirm_password" placeholder="Retype password" required="">
				<span class="glyphicon glyphicon-log-in form-control-feedback"></span>
			</div>
			<div class="row">
				<div class="col-xs-8">
				
				</div>
				<!-- /.col -->
				<div class="col-xs-4">
					<input type="hidden" name="fp_code" value="<?php echo $_REQUEST['fp_code']; ?>"/>
					<input type="submit" name="resetSubmit" value="Update" class="btn btn-primary btn-block btn-flat">
				</div>
				<!-- /.col -->
			</div>
		</form>
		<div class="social-auth-links text-center">
			<p>- Don't Want to Reset -</p>
			<a href="index.php" class="hvr-shutter-in-horizontal">Back to login</a>
		</div>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

	<?php include_once 'elements/footer_scripts.php'; ?>
</body>
</html>