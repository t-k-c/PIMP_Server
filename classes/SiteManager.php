<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 11/05/2018
 * Time: 12:01
 */

class SiteManager {
	public static function viewSite( $site_id ) {
		$site       = self::findSiteById( $site_id );
		$restofdata = UserManager::findUserById( $site['user_id'] );
		if ( $restofdata != null && $site['site_visibility'] != 0 ) {
			array_push( $site, $restofdata );

			return $site;
		} else {
			return null;
		}
	}

	public static function findSiteById( $site_id ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "SELECT * FROM " . ServerData::$database . ".`sites` WHERE site_id = ?" );
		$query->execute( array( $site_id ) );

		return $query->fetch( PDO::FETCH_ASSOC );
	}

	public static function getSitesFromQuery( $user_id, $latlng, $order_by, $order ) {

	}

	public static function follow( $site_id, $user_id ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "INSERT INTO " . ServerData::$database . ".`subscriptions`( `site_id`, `user_id`) VALUES (?,?)" );

		return $query->execute( array( $site_id, $user_id ) );
	}

	public static function unfollow( $site_id, $user_id ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "DELETE FROM " . ServerData::$database . ".`subscriptions` WHERE  `site_id` = ? AND `user_id` = ? " );

		return $query->execute( array( $site_id, $user_id ) );
	}

	public static function getFollowedSites( $user_id,$latlng ) {
		$handler = ServerData::getPDO();
		$sql     = "SELECT sites.*,users.* FROM " . ServerData::$database . ".`users`," . ServerData::$database . ".`sites`," . ServerData::$database . ".`subscriptions` WHERE sites.site_visibility = 1 AND subscriptions.user_id = users.user_id AND subscriptions.site_id = sites.site_id
		AND users.user_id = ? ";
		$query   = $handler->prepare( $sql );
		$query->execute( array( $user_id ) );
		$coordinates = explode( ",", $latlng );
		$r =  $query->fetchAll( PDO::FETCH_ASSOC );
		$final = array();
		foreach($r as $result){
			$dist = DistanceManager::distance( $coordinates[0], $coordinates[1], $result['site_latitude'], $result['site_longitude'], "K" );
			$result+=['distance'=>$dist];
			$result+=['user_follows'=>self::userfollows($user_id,$result['site_id'])];
			$result+=['site_follows'=>self::numberoffollows($result['site_id'])];
			array_push($final,$result);
		}
		return $final;
	}
	public static function getSites( $user_id, $latlng,$distance ) {
		$handler = ServerData::getPDO();
		$coordinates = explode( ",", $latlng );
		$sql     = "SELECT sites.*,users.* FROM " . ServerData::$database . ".`users`," . ServerData::$database . ".`sites`
		WHERE  sites.user_id = users.user_id";
		$query   = $handler->prepare( $sql );
		$query->execute(  );

		$r = $query->fetchAll( PDO::FETCH_ASSOC );
		$final = array();
		foreach($r as $result){
			$dist = DistanceManager::distance( $coordinates[0], $coordinates[1], $result['site_latitude'], $result['site_longitude'], "K" );
			if($dist <= $distance){
				$result+=['distance'=>$dist];
				$result+=['user_follows'=>self::userfollows($user_id,$result['site_id'])];
				$result+=['site_follows'=>self::numberoffollows($result['site_id'])];
				array_push($final,$result);
			}
		}
		return $final;
	}
	public static function getMySites( $user_id, $latlng ) {
		$handler = ServerData::getPDO();
		$coordinates = explode( ",", $latlng );
		$sql     = "SELECT sites.*,users.* FROM " . ServerData::$database . ".`users`," . ServerData::$database . ".`sites`
		WHERE users.user_id = ? AND sites.user_id = users.user_id";
		$query   = $handler->prepare( $sql );
		$query->execute( array( $user_id ) );

		$r = $query->fetchAll( PDO::FETCH_ASSOC );
		$final = array();
		foreach($r as $result){
			$dist = DistanceManager::distance( $coordinates[0], $coordinates[1], $result['site_latitude'], $result['site_longitude'], "K" );
			$result+=['distance'=>$dist];
			$result+=['user_follows'=>self::userfollows($user_id,$result['site_id'])];
			$result+=['site_follows'=>self::numberoffollows($result['site_id'])];
			array_push($final,$result);
		}
		return $final;
	}
	public static function userfollows($userid,$siteid){
		$handler = ServerData::getPDO();
		$sql     = "SELECT count(*) as num FROM " . ServerData::$database . ".`subscriptions`
		WHERE user_id = ? AND site_id = ?";
		$query   = $handler->prepare( $sql );
		$query->execute( array( $userid,$siteid ) );

		$r =  $query->fetch( PDO::FETCH_ASSOC );
		return $r['num'];
	}
	public static function numberoffollows($siteid){
		$handler = ServerData::getPDO();
		$sql     = "SELECT count(*) as num FROM " . ServerData::$database . ".`subscriptions`
		WHERE  site_id = ?";
		$query   = $handler->prepare( $sql );
		$query->execute( array( $siteid ) );

		$r =  $query->fetch( PDO::FETCH_ASSOC );
		return $r['num'];
	}
	public static function viewSites() {
		$handler = ServerData::getPDO();
		$sql = "SELECT";
	}
	public static function shortname($shortname){
		$handler = ServerData::getPDO();
		$sql     = "SELECT count(*) as num FROM " . ServerData::$database . ".`sites`
		WHERE  site_short_name = ?";
		$query   = $handler->prepare( $sql );
		$query->execute( array( $shortname ) );

		$r =  $query->fetch( PDO::FETCH_ASSOC );
		return $r['num'];
	}
	public static  function createSite($latlng,$user_id,$site_name,$site_short_name,$site_thumbnail,$site_description,$site_contact,
		$site_working_period,$site_location_description){
		$handler = ServerData::getPDO();
		$coordinates = explode( ",", $latlng );
		$sql = "INSERT INTO " . ServerData::$database . ".`sites`( `site_longitude`, `site_latitude`, `site_description`, `site_contact`, `site_thumbnail`, `site_created_at`, `site_working_period`, `user_id`, `site_name`, `site_short_name`,site_location_description) VALUES (?,?,?,?,?,NOW(),?,?,?,?,?)";
		$query = $handler->prepare( $sql );
		return $query->execute(array($coordinates[1],$coordinates[0],$site_description,$site_contact,$site_thumbnail,$site_working_period,$user_id,$site_name,$site_short_name,$site_location_description));
	}
}