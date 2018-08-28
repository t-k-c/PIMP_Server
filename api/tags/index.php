<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 15/05/2018
 * Time: 14:05
 */
include_once '../../commondata/ServerData.php';
include_once '../../classes/ItemManager.php';

if(isset($_GET['item_id'],$_GET['tag'])){

}elseif(isset($_GET['tag_id'])){

}
elseif( isset($_GET['ticket_id']) ) {
	echo json_encode( ItemManager::getItemTags( $_GET['ticket_id'] ) );
}
