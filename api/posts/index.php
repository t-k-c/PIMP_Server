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
include_once '../../classes/PostManager.php';
include_once '../../classes/DistanceManager.php';
include_once '../../classes/FileManager.php';
/* global posts */
if(isset($_GET['type'])&&$_GET['type'] == 1){

		echo  json_encode(PostManager::viewGlobalPosts());

}

else
/* main interface */
if(isset($_GET['type'],$_GET['user_id'],$_GET['latlng'],$_GET['order_by'],$_GET['order'],$_GET['distance'])){
//	echo  json_encode(
//	echo  111;
		echo json_encode(PostManager::getPostsWithQuery($_GET['user_id'],$_GET['latlng'],$_GET['order_by'],$_GET['order'],$_GET['distance']));
//	);
}else
if(isset($_GET['user_id'],$_GET['site_id'],$_GET['latlng'])){
//	echo  111;
 echo json_encode(  PostManager::getPostsWithQuery2($_GET['user_id'],$_GET['site_id'],$_GET['latlng'])
 );
}
else if(isset($_POST['?post_content'],$_POST['post_thumbnail'],$_POST['post_title'],$_POST['post_range'],$_POST['thumbnail_ext'],$_POST['site_id'])){
	$string = str_replace(' ','-',$_POST['post_title']).'.'.$_POST['thumbnail_ext'];
	if(PostManager::createPost($_POST['?post_content'],$string,$_POST['post_range'],$_POST['post_title'],$_POST['site_id'])){
		echo FileManager::store_base_64_file($_POST['post_thumbnail'],'../../post_thumbnails/',$string);
	}else{
		echo -2;
	}
}

