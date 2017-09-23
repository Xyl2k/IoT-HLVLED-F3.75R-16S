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
    include_once 'User.class.php';
    $user = new User();
    $conditions['where'] = array(
        'id' => $sessData['userID'],
    );
    $conditions['return_type'] = 'single';
    $userData = $user->getRows($conditions);
    
    $userPicture = !empty($userData['picture'])?'uploads/profile_picture/'.$userData['picture']:'images/avatar.png';
	$userName = htmlentities($userData['first_name']).' '.htmlentities($userData['last_name']);
	
	//messages count
	include 'Message.class.php';
	$msg = new Message();
	$conMsg['return_type'] = 'count';
    $msgCount = $msg->getRows($conMsg);
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
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Dashboard
          <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Dashboard</li>
        </ol>
      </section>
  
      <!-- Main content -->
      <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
			<div class="col-lg-3 col-xs-6">
				<div class="small-box bg-green">
				  <div class="inner">
					<h3><?php echo $msgCount; ?></h3>
					<p>Messages</p>
				  </div>
				  <div class="icon">
					<i class="ion ion-stats-bars"></i>
				  </div>
				  <a href="messages.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
          <!--<div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3>150</h3>
  
                <p>New Orders</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
              <div class="inner">
                <h3>53<sup style="font-size: 20px">%</sup></h3>
  
                <p>Bounce Rate</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3>44</h3>
  
                <p>User Registrations</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
              <div class="inner">
                <h3>65</h3>
  
                <p>Unique Visitors</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>-->
		  
        </div>
        <!-- /.row -->
  
      </section>
      <!-- /.content -->
    </div>
    
    <?php include_once 'elements/footer.php'; ?>

</div>
<!-- ./wrapper -->

<?php include_once 'elements/footer_scripts.php'; ?>
</body>
</html>