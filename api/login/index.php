<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 30/04/2018
 * Time: 16:36
 */
if(isset($_GET['email'],$_GET['password'])){
	include_once '../../commondata/ServerData.php';
	include_once '../../classes/UserManager.php';
	echo UserManager::login($_GET['email'],$_GET['password']);
}