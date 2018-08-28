<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 30/04/2018
 * Time: 16:40
 */

class ServerData {

	public static $host = "127.0.0.1";
	public static $url = "http://127.0.0.1/";
	public static $database = "pimp";
	public static $user = "root";
	public static $password = "";

	public static function getPDO() {
		try {
			$pdo = new PDO( 'mysql:host:' . ServerData::$host . ';dbname=' . ServerData::$database, '' . ServerData::$user, '' . ServerData::$password );
			$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			return $pdo;
		} catch ( Exception $exception ) {
			die( 'exception caught' . $exception->getMessage() );
		}
	}
}