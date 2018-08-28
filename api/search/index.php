<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 15/05/2018
 * Time: 20:25
 */
include_once '../../commondata/ServerData.php';
include_once '../../classes/ItemManager.php';
include_once '../../classes/DistanceManager.php';
if(isset($_GET['needle'],$_GET['user_id'],$_GET['latlng'],$_GET['amount'])){
	echo json_encode(ItemManager::search($_GET['needle'],$_GET['latlng'],$_GET['user_id'],$_GET['amount']));
}
elseif
(isset($_GET['image'],$_GET['action']))
{
//	$image= ""
	$action = $_GET['action'];
	$imagedir = '../../../';
	$imagemime = 'image/*';

	if($action == 'search'){
//		move_uploaded_file()
	}

}else echo print_r($_GET);