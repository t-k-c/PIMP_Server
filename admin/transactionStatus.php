<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 17/06/2018
 * Time: 04:38
 */
include_once '../classes/TransactionManager.php';
include_once '../commondata/ServerData.php';
if(isset($_POST['id'],$_POST['status'])){
 echo TransactionManager::modify($_POST['id'],$_POST['status']);
}