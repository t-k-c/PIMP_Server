<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 11/05/2018
 * Time: 12:46
 */
include_once '../../commondata/ServerData.php';
include_once '../../classes/UserManager.php';
include_once '../../classes/SiteManager.php';
include_once '../../classes/ItemManager.php';
include_once '../../classes/DistanceManager.php';
/* global posts */

if ( isset( $_GET['user_id'], $_GET['latlng'], $_GET['order_by'], $_GET['order'], $_GET['distance'] ) ) {
//	echo  json_encode(
	echo json_encode( ItemManager::getItemsWithQuery( $_GET['user_id'], $_GET['latlng'], $_GET['order_by'], $_GET['order'], $_GET['distance'] ) );
//	);
}

else if(isset($_POST['action'],$_POST['item_name'], $_POST['item_price'], $_POST['site_id'], $_POST['item_description'],$_POST['i1']
,$_POST['i2'],$_POST['i3'],$_POST['i4'],$_POST['thumnail_ext1'],$_POST['thumnail_ext2'],$_POST['thumnail_ext3'],$_POST['thumnail_ext4'])){
 if($_POST['action'] == 'create'){
		if(($v = ItemManager::createItem($_POST['item_name'],$_POST['item_description'],$_POST['item_price'],$_POST['site_id']))!==false){
			FileManager::store_base_64_file($_POST['i1'],'../item_thumbnails/','item_'.$v.'-1.'.$_POST['thumnail_ext1']);
			FileManager::store_base_64_file($_POST['i2'],'../item_thumbnails/','item_'.$v.'-2.'.$_POST['thumnail_ext2']);
			FileManager::store_base_64_file($_POST['i3'],'../item_thumbnails/','item_'.$v.'-3.'.$_POST['thumnail_ext3']);
			FileManager::store_base_64_file($_POST['i4'],'../item_thumbnails/','item_'.$v.'-4.'.$_POST['thumnail_ext4']);
		}else{
			echo -1;
		}
 }
 //we could also have modify here
}else if ( isset( $_GET['user_id'], $_GET['site_id'], $_GET['latlng'])){
	echo json_encode(ItemManager::getAllPartnerItems($_POST['user_id'],$_POST['site_id'],$_POST['latlng']));

}
else if(isset($_GET['user_id'],$_GET['latlng'])){
	echo json_encode(ItemManager::getInterestForUser($_GET['user_id'],$_GET['latlng']));
}