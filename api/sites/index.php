<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 13/05/2018
 * Time: 18:51
 */
include_once '../../commondata/ServerData.php';
include_once '../../classes/DistanceManager.php';
include_once '../../classes/SiteManager.php';
include_once '../../classes/FileManager.php';
if(isset($_GET['user_id'],$_GET['latlng'],$_GET['order_by'],$_GET['order'],$_GET['distance'])){
 echo json_encode(SiteManager::getSites($_GET['user_id'],$_GET['latlng'],$_GET['distance']));
}elseif(isset($_GET['user_id'],$_GET['latlng'])){
	echo json_encode(SiteManager::getMySites($_GET['user_id'],$_GET['latlng']));
}
elseif (isset($_POST['?user_id'],$_POST['latlng'],$_POST['site_name'],$_POST['site_short_name'],$_POST['site_description'],$_POST['site_contact'],
$_POST['site_working_period'],$_POST['site_location_description'],$_POST['site_thumbnail'],$_POST['thumbnail_ext'])){
if(SiteManager::shortname($_POST['site_short_name'])>0){
	echo -2;
}
else{
	if(SiteManager::createSite($_POST['latlng'],$_POST['?user_id'],$_POST['site_name'],$_POST['site_short_name'],$_POST['site_short_name'].'.'.$_POST['thumbnail_ext'],$_POST['site_description'],$_POST['site_contact'],$_POST['site_working_period'],$_POST['site_location_description'])){
		echo FileManager::store_base_64_file($_POST['site_thumbnail'],'../../site_thumbnails/',$_POST['site_short_name'].'.'.$_POST['thumbnail_ext']);
	}else{
		echo -1;
	}

}
}