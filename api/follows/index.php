<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 15/05/2018
 * Time: 05:39
 */

include_once '../../classes/SiteManager.php';
include_once '../../classes/DistanceManager.php';
include_once '../../commondata/ServerData.php';
if(isset($_GET['user_id'],$_GET['site_id'],$_GET['action'])){
	$action = $_GET['action'];
	$user_id = $_GET['user_id'];
	$site_id= $_GET['site_id'];
	if($action == 'follow' && SiteManager::unfollow($site_id,$user_id) && SiteManager::follow($site_id,$user_id)){
		// i delete before adding again to ensure coherency and no duplicacy of data... if not found, nothing will be deleted;
		echo '1';
	}
	elseif ($action == 'unfollow' && SiteManager::unfollow($site_id,$user_id)){
		echo '1';
	}else{
		echo  '0';
	}
}else if(isset($_GET['user_id'],$_GET['latlng'])){
 echo json_encode(SiteManager::getFollowedSites($_GET['user_id'],$_GET['latlng']));
}