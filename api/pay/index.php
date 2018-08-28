<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 16/06/2018
 * Time: 16:38
 */
include_once '../../commondata/ServerData.php';
include_once '../../classes/TransactionManager.php';
include_once '../../classes/ItemManager.php';
if(isset($_GET['user_id'],$_GET['item_id'],$_GET['phone'],$_GET['amount'],$_GET['transaction_id'])){
	echo TransactionManager::pay($_GET['item_id'],$_GET['user_id'],$_GET['amount'],$_GET['phone'],$_GET['transaction_id']);
}
else if(isset($_GET['verification_id'])){
 echo json_encode(TransactionManager::verifyTransaction($_GET['verification_id']));
}
//phpinfo();