<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 15/05/2018
 * Time: 05:17
 */
include_once '../../classes/ItemManager.php';
include_once '../../classes/DistanceManager.php';
include_once '../../commondata/ServerData.php';
if(isset($_GET['user_id'],$_GET['item_id'],$_GET['action'])){
	$action = $_GET['action'];
	$user_id = $_GET['user_id'];
	$item_id= $_GET['item_id'];
	if($action == 'add' && ItemManager::removeInterest($item_id,$user_id) && ItemManager::addInterest($item_id,$user_id)){
		// i delete before adding again to ensure coherency and no duplicacy of data... if not found, nothing will be deleted;
		echo '1';
	}
	elseif ($action == 'del' && ItemManager::removeInterest($item_id,$user_id)){
		echo '1';
	}else{
		echo  '0';
	}
}elseif (isset($_GET['user_id'],$_GET['latlng'])){
	echo json_encode(ItemManager::getInterestForUser($_GET['user_id'],$_GET['latlng']));
}