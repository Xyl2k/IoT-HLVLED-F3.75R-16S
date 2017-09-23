<?php
include 'tmp/admin/Site.class.php';
$site = new Site();
$conditions['where'] = array(
	'id' => 1,
);
$conditions['return_type'] = 'single';
$siteData = $site->getRows($conditions);

include 'tmp/admin/Message.class.php';
$msg = new Message();
$msgConditions['where'] = array(
	'status' => '1',
);
$msgConditions['return_type'] = 'single';
$msgData = $msg->getRows($msgConditions);

if(($siteData['msg_display_type'] == 'Static') && !empty($siteData['msg_default'])){
	$messageContent = $siteData['msg_default'];
}else{
	$messageContent = !empty($msgData['content'])?$msgData['content']:'';
}
echo "<MESSAGE>$messageContent</MESSAGE>";
?>