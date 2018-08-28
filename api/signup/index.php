<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 11/05/2018
 * Time: 10:33
 */
include_once '../../commondata/ServerData.php';
include_once '../../classes/UserManager.php';
if(isset($_GET['email'],$_GET['username'],$_GET['name'],$_GET['password'],$_GET['type'])){
  echo UserManager::signUp($_GET['email'],$_GET['username'],$_GET['name'],$_GET['password'],$_GET['type']);
}