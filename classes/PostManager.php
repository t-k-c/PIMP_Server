<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 07/05/2018
 * Time: 06:27
 */

class PostManager {
	public static function createPost(
		 $post_content,
		$post_thumbnail,  $post_range, $post_title,$site_id
	) {
		/*
		*is the post latitude and longitude really necessary given the fact that the lat and
		*long have to be gottent from the siteid. but the post range is actually necessary
		* remember visibility is by default 1
		*/
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "INSERT INTO " . ServerData::$database . ".`posts`(  `post_content`, `post_thumbnail`, 
 	`post_priority`, `post_range`, `post_title`,`site_id`) 
 	VALUES (?,?,?,?,?,?)" );
		return $query->execute( array(
			$post_content,
			$post_thumbnail,
			10,
			$post_range,
			$post_title,
			$site_id
		) );
	}

	public static function deletePost( $post_id ) {
		/*
		 * a post is never removed directly from the database.
		 * The visibility is only set to zero
		 * for marketing strategies lol
		 * */
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "UPDATE `posts` SET `post_visibility`= 0 WHERE `post_id` = ?" );

		return $query->execute( array( $post_id ) );
	}

	public static function modifyPost(
		$post_id, $post_content,
		$post_thumbnail, $post_priority, $post_range
	) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "UPDATE `posts` SET `post_content`= ? ,`post_thumbnail`= ? ,`post_priority`= ?,`post_range`= ?  WHERE `post_id` = ?" );

		return $query->execute( array(
			$post_content,
			$post_thumbnail,
			$post_priority,
			$post_range,
			$post_id
		) );
	}

	public static function viewGlobalPosts() {
		$posts   = self::getGlobalPosts();
		$results = array();
		foreach ( $posts as $post ) {
			$restofdata = SiteManager::viewSite( $post['post_id'] );
			if ( $restofdata != null && $post['post_visibility'] == 1 ) {

				$likes                 = self::getPostLikes( $post['post_id'] );
				$comments              = self::getPostComments( $post['post_id'] );
				$likesandcommentscount = array(
					"post_like_number"    => count( $likes ),
					"post_comment_number" => count( $comments )
				);
				array_push( $post, $restofdata );
				array_push( $post, $likesandcommentscount );
				array_push( $results, $post );
			}
		}

		return $results;
	}

	public static function getGlobalPosts() {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "SELECT * FROM " . ServerData::$database . ".`posts` WHERE post_type = 1" );
		$query->execute();

		return $query->fetchAll( PDO::FETCH_ASSOC );
	}

	public static function getPostLikes( $post_id ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "SELECT * FROM " . ServerData::$database . ".`likes` WHERE post_id= ?" );
		$query->execute( array( $post_id ) );

		return $query->fetchAll( PDO::FETCH_ASSOC );
	}
	public static function userLikes( $user_id,$post_id ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "SELECT count(*) as num FROM " . ServerData::$database . ".`likes` WHERE user_id =?  AND post_id= ? " );
		$query->execute( array( $user_id, $post_id ) );

		return $query->fetch( PDO::FETCH_ASSOC );
	}

	public static function getPostComments( $post_id ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "SELECT * FROM " . ServerData::$database . ".`comments` WHERE post_id= ?" );
		$query->execute( array( $post_id ) );

		return $query->fetchAll( PDO::FETCH_ASSOC );
	}

	public static function getPosts() {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "SELECT * FROM " . ServerData::$database . ".`posts`" );
		$query->execute();

		return $query->fetchAll( PDO::FETCH_ASSOC );
	}

	/**
	 * results of the posts on the main interface (recent items);
	 */
	public static function getPostsWithQuery( $user_id, $latlng, $order_by, $order, $distance ) {
		$sqlpart = "ORDER BY post_date DESC";
		if ( $order_by == 'post_date' && $order == 'asc' ) {
			$sqlpart = "ORDER BY post_date ASC";
		}
		$handler = ServerData::getPDO();//" . ServerData::$database . ".
		$query   = $handler->prepare( "SELECT * FROM " . ServerData::$database . ".users," . ServerData::$database . ".sites," . ServerData::$database . ".posts WHERE posts.post_type = 1   
		 AND   posts.site_id = sites.site_id
AND   sites.user_id = users.user_id
AND   sites.site_visibility = 1
AND   posts.post_visibility = 1
AND   users.user_visibility = 1 
AND   post_type = 1 " . $sqlpart );
		$query->execute();
		$posts = $query->fetchAll( PDO::FETCH_ASSOC );
//		print_r($posts);
//		echo $latlng;
		$coordinates = explode( ",", $latlng );
//				print_r($coordinates);

		$first_selection = array();
		$dummy_array_for_sorting = array();//the dummy array to contain the indexes and the distances
//		 taking only those in range
$i=0;
		foreach ( $posts as $post ) {
//			echo DistanceManager::distance($coordinates[0],$coordinates[1],$post['site_latitude'],$post['site_longitude'],"K");

			$dist = DistanceManager::distance( $coordinates[0], $coordinates[1], $post['site_latitude'], $post['site_longitude'], "K" );
			if ( $dist <= $distance && $dist<= $post['post_range'] ) {
				$post+=['distance'=>$dist];

				$likes                 = self::getPostLikes( $post['post_id'] );
				$comments              = self::getPostComments( $post['post_id'] );
				$post+=[
					"post_like_number"    => count( $likes ),
					"post_comment_number" => count( $comments ),
					"user_interested" => self::userLikes($user_id,$post['post_id'])['num']
				];
				array_push( $first_selection, $post );
				$dummy_array_for_sorting+=[$i.''=>$dist];
				$i++;
			}
		}
		if ( $order_by == 'post_date' ) {
			return $first_selection;
		}

		//		second tests.... now sorting....
		if ( $order_by == 'site_distance') {

		if($order == 'asc' ){
			asort($dummy_array_for_sorting);
		}else{
			arsort($dummy_array_for_sorting);
		}
//		echo print_r($dummy_array_for_sorting);
		$seconselection = array();
//		print_r(array_keys($dummy_array_for_sorting));
		foreach (array_keys($dummy_array_for_sorting) as $dummydata){
//			echo $dummydata;
			array_push($seconselection,$first_selection[$dummydata]);
		}
//		echo  print_r($seconselection);
		return $seconselection;
	}
		return array();
	}
	public static function like( $post_id, $user_id ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "INSERT INTO " . ServerData::$database . ".`likes`( `post_id`, `user_id`) VALUES (?,?)" );
		return $query->execute( array( $post_id, $user_id ) );
	}

	public static function unlike( $post_id, $user_id ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "DELETE FROM " . ServerData::$database . ".`likes` WHERE  `post_id` = ? AND `user_id` = ? " );
		return $query->execute( array( $post_id, $user_id ) );
	}
	public static function uncomment( $comment_id ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "DELETE FROM " . ServerData::$database . ".`comments` WHERE  `comment_id` = ?  " );
		return $query->execute( array( $comment_id) );
	}

	public static function viewComments( $post_id ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "SELECT * FROM " . ServerData::$database . ".users, " . ServerData::$database . ".comments 
WHERE comments.user_id = users.user_id
AND users.user_visibility = 1
AND comments.post_id = ?" );
		 $query->execute( array( $post_id) );
		 return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function comment($post_id,$user_id,$comment){
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "INSERT INTO " . ServerData::$database . ".`comments`( `post_id`, `user_id`, `comment`) VALUES (?,?,?)" );
		return $query->execute( array( $post_id, $user_id,$comment ) );
	}

	public static function modifyComment($post_id,$user_id,$comment,$commentid){
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "UPDATE " . ServerData::$database . ".`comments` SET `post_id` = ?,  `user_id` = ?,  `comment` = ? WHERE `comment_id` = ? " );
		return $query->execute( array( $post_id, $user_id,$comment,$commentid ) );
	}

	/**
	 * results of the posts on the main interface (recent items);
	 */
	public static function getPostsWithQuery2( $userid, $site_id, $latlng ) {
		$handler = ServerData::getPDO();
		$sql = "SELECT posts.*,sites.site_latitude,sites.site_longitude FROM " . ServerData::$database . ".`posts`," . ServerData::$database . ".`sites` WHERE  post_visibility = 1 AND posts.site_id = ? 
		AND posts.site_id = sites.site_id order by post_date DESC";
		$query = $handler->prepare($sql);
		$query->execute(array($site_id));
		$posts = $query->fetchAll(PDO::FETCH_ASSOC);
		$coordinates = explode( ",", $latlng );
		$final= array();
		foreach ($posts as $post){
			$dist = DistanceManager::distance( $coordinates[0], $coordinates[1], $post['site_latitude'], $post['site_longitude'], "K" );
			if($dist <= $post['post_range']){
				$likes                 = self::getPostLikes( $post['post_id'] );
				$comments              = self::getPostComments( $post['post_id'] );
				$post+=[
					"post_like_number"    => count( $likes ),
					"post_comment_number" => count( $comments ),
					"user_interested" => self::userLikes($userid,$post['post_id'])['num']
				];
			  array_push($final,$post);
			}
		}
		return $final;
	}
}