<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 15/05/2018
 * Time: 05:31
 */
include_once '../../classes/PostManager.php';
include_once '../../commondata/ServerData.php';
if(isset($_GET['user_id'],$_GET['post_id'],$_GET['action'])){
	$action = $_GET['action'];
	$user_id = $_GET['user_id'];
	$post_id= $_GET['post_id'];
	if($action == 'like' && PostManager::unlike($post_id,$user_id) && PostManager::like($post_id,$user_id)){
		// i delete before adding again to ensure coherency and no duplicacy of data... if not found, nothing will be deleted;
		echo '1';
	}
	elseif ($action == 'unlike' && PostManager::unlike($post_id,$user_id)){
		echo '1';
	}else{
		echo  '0';
	}
}
