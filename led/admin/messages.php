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
	
	include 'Message.class.php';
	$msg = new Message();
    $messages = $msg->getRows();
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
          Manage Messages
          <small>message content management</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Messages</li>
        </ol>
      </section>
  
      <!-- Main content -->
      <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
			<div class="col-xs-12">
				<a href="addEditMessage.php" class="btn btn-success pull-right"><i class="fa fa-user-plus"></i> Add New</a>
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
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">All Messages</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body table-responsive no-padding">
						<table class="table table-hover">
							<tr>
								<th width="10%">ID</th>
								<th width="60%">Message</th>
								<th width="20%">Created</th>
								<th width="10%">Status</th>
								<!--<th width="10%">Action</th>-->
							</tr>
							<?php if(!empty($messages)){ foreach($messages as $row){?>
							<?php
							$stcl_arr = array('1'=>'label-success','0'=>'label-danger');
							$stcls = $stcl_arr[$row['status']];
							?>
							<tr>
								<td><?php echo '#'.$row['id']; ?></td>
								<td><?php echo $row['content']; ?></td>
								<td><?php echo date("M d, Y", strtotime($row['created'])); ?></td>
								<td><span class="label <?php echo $stcls; ?>"><?php echo ($row['status'] == '1')?'Active':'Deactive'; ?></span></td>
							</tr>
							<?php } }else{ ?>
							 <tr><td colspan="4">Message(s) not found....</td></tr>    
							<?php } ?>
						</table>
					</div>
					<!-- /.box-body -->
					<div class="box-footer clearfix">
						<ul class="pagination pagination-sm no-margin pull-right">
							
						</ul>
					</div>
				</div>
				<!-- /.box -->
			</div>
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