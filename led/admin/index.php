<?php
//start session
session_start();

//get session data
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';

//check whether user ID is available in cookie


if(!empty($rememberUserId)){
	$rememberUserId = $_COOKIE['rememberUserId'];
	$_SESSION['sessData']['userLoggedIn'] = TRUE;
	$_SESSION['sessData']['userId'] = $rememberUserId;
}

if(!empty($sessData['userLoggedIn']) && !empty($sessData['userID'])){
	header("Location: dashboard.php");
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
    <a href="index.php"><b>HLVLED-F3.75R-16S</b> Admin</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to Administrative Panel</p>
	<?php echo !empty($statusMsg)?'<p class="statusMsg '.$statusMsgType.'">'.$statusMsg.'</p>':''; ?>
    <form action="adminAccount.php" method="post">
		<div class="form-group has-feedback">
			<input type="email" name="email" class="form-control" placeholder="Email" required="">
			<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
		</div>
		<div class="form-group has-feedback">
			<input type="password" name="password" class="form-control" placeholder="Password" required="">
			<span class="glyphicon glyphicon-lock form-control-feedback"></span>
		</div>
		<center><div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div></center>
		<div class="row">
			<div class="col-xs-8">
				<div class="checkbox icheck">
					<label><input type="checkbox" name="rememberMe" value="1"> Remember Me</label>
				</div>
			</div>

			<div class="col-xs-4">
				<input type="submit" name="loginSubmit" class="btn btn-primary btn-block btn-flat" value="Sign In">
			</div>
		</div>
    </form>
    <a href="forgotPassword.php">Forgot Password?</a>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

	<?php include_once 'elements/footer_scripts.php'; ?>
</body>
</html>