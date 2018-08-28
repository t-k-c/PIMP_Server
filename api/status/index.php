<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 17/06/2018
 * Time: 05:40
 */
include_once '../../commondata/ServerData.php';
include_once  '../../classes/ItemManager.php';
include_once  '../../classes/DistanceManager.php';
if(isset($_GET['user_id'],$_GET['latlng'])){
	echo json_encode(ItemManager::pp($_GET['user_id'],$_GET['latlng']));
}