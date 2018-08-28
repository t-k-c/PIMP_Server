<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 07/05/2018
 * Time: 08:13
 */

class ItemManager {
	/*
	 * There will be the modification of this in the future to accomodate products of
	 * different prices in terms of a certain parameter (like size)
	 * */
	public static function createItem( $item_name, $item_description,$item_price, $site_id ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "INSERT INTO " . ServerData::$database . ".`items`(`item_description`, `item_name`, `item_price`, `site_id`) VALUES (?,?,?,?)" );

		if($query->execute( array( $item_description,$item_name, $item_price, $site_id ) )){
			return $handler->lastInsertId();
		}else{
			return false;
		}
	}

	public static function addInterest( $item_id, $user_id ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "INSERT INTO " . ServerData::$database . ".`interests`( `item_id`, `user_id`) VALUES (?,?)" );

		return $query->execute( array( $item_id, $user_id ) );
	}

	public static function removeInterest( $item_id, $user_id ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "DELETE FROM " . ServerData::$database . ".`interests` WHERE  `item_id` = ? AND `user_id` = ? " );

		return $query->execute( array( $item_id, $user_id ) );
	}

	/**
	 * results of the items on the main interface ;
	 */
	public static function getItemsWithQuery( $user_id, $latlng, $order_by, $order, $distance ) {
		$sqlpart = "ORDER BY item_created_at DESC";
		if ( $order_by == 'date' && $order == 'asc' ) {
			$sqlpart = "ORDER BY item_created_at ASC";
		}
		$handler = ServerData::getPDO();//" . ServerData::$database . ".
		$query   = $handler->prepare( "SELECT * FROM " . ServerData::$database . ".users," . ServerData::$database . ".sites," . ServerData::$database . ".items
		
WHERE items.site_id = sites.site_id
AND   sites.user_id = users.user_id
AND   sites.site_visibility = 1
AND   items.item_visibility = 1
AND   users.user_visibility = 1 " . $sqlpart );
		$query->execute();
		$items = $query->fetchAll( PDO::FETCH_ASSOC );
//		print_r($items);
//		echo $latlng;
		$coordinates = explode( ",", $latlng );
//				print_r($coordinates);

		$first_selection         = array();
		$dummy_array_for_sorting = array();//the dummy array to contain the indexes and the distances
//		 taking only those in range
		$i = 0;
		foreach ( $items as $item ) {
//			echo DistanceManager::distance($coordinates[0],$coordinates[1],$item['site_latitude'],$item['site_longitude'],"K");

			$dist = DistanceManager::distance( $coordinates[0], $coordinates[1], $item['site_latitude'], $item['site_longitude'], "K" );
			if ( $dist <= $distance ) {
				$item += [ 'distance' => $dist ];
				$item += [ 'interests' => count( self::getInterestForItem( $item['item_id'] ) ) ];
				$item += [ 'user_interests' => self::isInterested( $user_id, $item['item_id'] )['num'] ];
				$item += [ 'item_thumbnails' => self::getAssociatedImages( $item['item_id'] ) ];
				array_push( $first_selection, $item );
				$dummy_array_for_sorting += [ $i . '' => $dist ];
				$i ++;
			}
		}
		if ( $order_by == 'date' ) {
			return $first_selection;
		}

		//		second tests.... now sorting....
		if ( $order_by == 'site_distance' ) {

			if ( $order == 'asc' ) {
				asort( $dummy_array_for_sorting );
			} else {
				arsort( $dummy_array_for_sorting );
			}
//		echo print_r($dummy_array_for_sorting);
			$seconselection = array();
//		print_r(array_keys($dummy_array_for_sorting));
			foreach ( array_keys( $dummy_array_for_sorting ) as $dummydata ) {
//				echo $dummydata;
				array_push( $seconselection, $first_selection[ $dummydata ] );
			}

//		echo  print_r($seconselection);
			return $seconselection;
		}

		return array();
	}
	public static function getInterestForUser( $user_id , $latlng) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "SELECT * FROM " . ServerData::$database . ".users," . ServerData::$database . ".interests," . ServerData::$database . ".sites," . ServerData::$database . ".items WHERE 
		items.item_id = interests.item_id 
		AND  items.site_id = sites.site_id
		AND  sites.user_id = users.user_id
		AND users.user_id = ?
		" );
		$query->execute( array( $user_id ) );
		$items = $query->fetchAll( PDO::FETCH_ASSOC );
		$coordinates = explode( ",", $latlng );
//				print_r($coordinates);

		$first_selection         = array();
		foreach ( $items as $item ) {
//			echo DistanceManager::distance($coordinates[0],$coordinates[1],$item['site_latitude'],$item['site_longitude'],"K");

			$dist = DistanceManager::distance( $coordinates[0], $coordinates[1], $item['site_latitude'], $item['site_longitude'], "K" );

				$item += [ 'distance' => $dist ];
				$item += [ 'interests' => count( self::getInterestForItem( $item['item_id'] ) ) ];
				$item += [ 'user_interests' => self::isInterested( $user_id, $item['item_id'] )['num'] ];
				$item += [ 'item_thumbnails' => self::getAssociatedImages( $item['item_id'] ) ];
				array_push( $first_selection, $item );

		}
		return $first_selection;
	}
	public static function getInterestForItem( $item_id ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "SELECT users.* FROM " . ServerData::$database . ".users," . ServerData::$database . ".interests WHERE users.user_id = interests.user_id AND interests.item_id = ?" );
		$query->execute( array( $item_id ) );

		return $query->fetchAll( PDO::FETCH_ASSOC );

	}

	public static function isInterested( $user_id, $item_id ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "SELECT count(*) AS num FROM " . ServerData::$database . ".interests WHERE user_id = ? AND item_id = ?" );
		$query->execute( array( $user_id, $item_id ) );

		return $query->fetch( PDO::FETCH_ASSOC );
	}

	public static function getAssociatedImages( $item_id ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "SELECT item_thumbnail FROM " . ServerData::$database . ".assocs WHERE  item_id = ?" );
		$query->execute( array( $item_id ) );

		return $query->fetchAll( PDO::FETCH_ASSOC );
	}

	public static function getItemTags( $item_id ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "SELECT * FROM " . ServerData::$database . ".`tags`," . ServerData::$database . ".items 
WHERE items.item_id =  tags.item_id
AND items.item_id = ?" );
		$query->execute( array( $item_id ) );

		return $query->fetchAll( PDO::FETCH_ASSOC );
	}

	public static function deleteTag( $tag_id ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "DELETE FROM " . ServerData::$database . ".`tags` WHERE  `tag_id` = ?" );

		return $query->execute( array( $tag_id ) );
	}

	public static function tag( $item_id, $tag ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "INSERT INTO " . ServerData::$database . ".`tags`(`item_id`,`tag`) VALUES(?,?)" );

		return $query->execute( array( $item_id, $tag ) );
	}

	public static function getTag( $item ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "SELECT * FROM " . ServerData::$database . ".`tags` WHERE `item` = ? " );

		return $query->execute( array( $item ) );
	}

	public static  function search( $needle,$latlng,$user_id,$amount ) {

		$handler = ServerData::getPDO();
		if($amount==""){
			$sql     = "SELECT * FROM  " . ServerData::$database . ".`users`, " . ServerData::$database . ".`items`," . ServerData::$database . ".`sites`  WHERE  (item_description LIKE '%".$needle."%' OR items.item_name LIKE '%".$needle."%' OR item_id IN (SELECT tags.item_id FROM " . ServerData::$database . ".tags WHERE  tags.tag LIKE '%".$needle."%' )) AND sites.site_id = items.site_id AND users.user_id = sites.user_id";
		}else{
			$sql     = "SELECT * FROM  " . ServerData::$database . ".`users`, " . ServerData::$database . ".`items`," . ServerData::$database . ".`sites`  WHERE  (item_description LIKE '%".$needle."%' OR items.item_name LIKE '%".$needle."%' OR item_id IN (SELECT tags.item_id FROM " . ServerData::$database . ".tags WHERE  tags.tag LIKE '%".$needle."%' )) AND sites.site_id = items.site_id AND users.user_id = sites.user_id AND items.item_price <= ".$amount;

		}


		$query   = $handler->prepare( $sql );
//		echo $query->queryString;
		$query->execute( );

		$items = $query->fetchAll( PDO::FETCH_ASSOC );

		$coordinates = explode( ",", $latlng );
//				print_r($coordinates);

		$first_selection         = array();
		foreach ( $items as $item ) {
//			echo DistanceManager::distance($coordinates[0],$coordinates[1],$item['site_latitude'],$item['site_longitude'],"K");

			$dist = DistanceManager::distance( $coordinates[0], $coordinates[1], $item['site_latitude'], $item['site_longitude'], "K" );

			$item += [ 'distance' => $dist ];
			$item += [ 'interests' => count( self::getInterestForItem( $item['item_id'] ) ) ];
			$item += [ 'user_interests' => self::isInterested( $user_id, $item['item_id'] )['num'] ];
			$item += [ 'item_thumbnails' => self::getAssociatedImages( $item['item_id'] ) ];
			array_push( $first_selection, $item );

		}
		return $first_selection;

	}
	public function getAssociations($item_id){
		$handler = ServerData::getPDO();
		$sql = "SELECT * FROM assocs WHERE item_id = ? ";
		$query = $handler->prepare( $sql );
		$query->execute(array($item_id));
		return $query->fetchAll( PDO::FETCH_ASSOC );
	}

	public static function getAllPartnerItems($user_id,$site_id,$latlng){
		$handler = ServerData::getPDO();
		$sql = "SELECT * FROM items,assocs WHERE site_id = ? AND item_visibility = 1 and sites.site_id =  assocs.site_id ";
		$query = $handler->prepare( $sql );
		$query->execute(array($site_id));
		$items = $query->fetchAll( PDO::FETCH_ASSOC );
		$coordinates = explode( ",", $latlng );
//				print_r($coordinates);

		$first_selection         = array();
		foreach ( $items as $item ) {
//			echo DistanceManager::distance($coordinates[0],$coordinates[1],$item['site_latitude'],$item['site_longitude'],"K");

			$dist = DistanceManager::distance( $coordinates[0], $coordinates[1], $item['site_latitude'], $item['site_longitude'], "K" );

			$item += [ 'distance' => $dist ];
			$item += [ 'interests' => count( self::getInterestForItem( $item['item_id'] ) ) ];
			$item += [ 'user_interests' => self::isInterested( $user_id, $item['item_id'] )['num'] ];
			$item += [ 'item_thumbnails' => self::getAssociatedImages( $item['item_id'] ) ];
			array_push( $first_selection, $item );

		}
		return $first_selection;
	}
	public static function pp( $user_id , $latlng) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "SELECT * FROM " . ServerData::$database . ".users," . ServerData::$database . ".interests," . ServerData::$database . ".sites," . ServerData::$database . ".items," . ServerData::$database . ".transactions WHERE 
		items.item_id = interests.item_id 
		AND  items.site_id = sites.site_id
		AND  sites.user_id = users.user_id
		AND transactions.item_id = items.item_id
		AND items.item_id IN (SELECT transactions.item_id from ". ServerData::$database . ".transactions where transactions.user_id = ? AND status != 2 )
		" );
		$query->execute( array( $user_id ) );
		$items = $query->fetchAll( PDO::FETCH_ASSOC );
		$coordinates = explode( ",", $latlng );
//				print_r($coordinates);

		$first_selection         = array();
		foreach ( $items as $item ) {
//			echo DistanceManager::distance($coordinates[0],$coordinates[1],$item['site_latitude'],$item['site_longitude'],"K");

			$dist = DistanceManager::distance( $coordinates[0], $coordinates[1], $item['site_latitude'], $item['site_longitude'], "K" );

			$item += [ 'distance' => $dist ];
			$item += [ 'interests' => count( self::getInterestForItem( $item['item_id'] ) ) ];
			$item += [ 'user_interests' => self::isInterested( $user_id, $item['item_id'] )['num'] ];
			$item += [ 'item_thumbnails' => self::getAssociatedImages( $item['item_id'] ) ];
			array_push( $first_selection, $item );

		}
		return $first_selection;
	}
}