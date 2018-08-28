<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 15/05/2018
 * Time: 10:07
 */
include_once '../../commondata/ServerData.php';
include_once '../../classes/PostManager.php';
// create / modify a comment;
if(isset($_GET['user_id'],$_GET['post_id'],$_GET['comment'],$_GET['action'])){
	$action = $_GET['action'];
	$user_id = $_GET['user_id'];
	$post_id = $_GET['post_id'];
	$comment = $_GET['comment'];
	if($action == 'push' && PostManager::comment($post_id,$user_id,$comment)){
		echo 1;
	}else if($action == 'edit'  && isset($_GET['comment_id']) && PostManager::modifyComment($post_id,$user_id,$comment,$_GET['comment_id'])){
		echo 1;
	}else{
		echo 0;
	}
}
// delete a comment
elseif (isset($_GET['comment_id'],$_GET['action'])){
	$action = $_GET['action'];
	if($action == 'del' && PostManager::uncomment($_GET['comment_id'])){
    echo 1;
	}else{
		echo 0;
	}
}
//wants the list of comments for a post
elseif(isset($_GET['post_id'])){
  echo json_encode(PostManager::viewComments($_GET['post_id']));
}