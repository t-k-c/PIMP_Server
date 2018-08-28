<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 16/05/2018
 * Time: 15:15
 */
include_once '../../commondata/ServerData.php';
include_once '../../classes/UserManager.php';
if(isset($_GET['user_id'])){
	echo json_encode(UserManager::getUserById($_GET['user_id']));
}