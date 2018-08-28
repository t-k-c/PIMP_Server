<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 23/05/2018
 * Time: 06:00
 */
include_once '../../commondata/ServerData.php';
include_once '../../classes/ItemManager.php';
if(isset($_GET['item_id'])){
 echo json_encode(ItemManager::getAssociatedImages($_GET['item_id']));
}else{
	echo 'out';
}
