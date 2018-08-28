<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 30/04/2018
 * Time: 16:43
 */
class UserManager {

  /**
   * authentifies user
   * @return -4: unexpected error; -3: user blocked, -2: user not exists, -1: auth failed, userid: auth successful
   * */

   public static function login($email,$password){
	    $r1= self::getNumberOfUsersWith($email);
		if(count($r1)==0){
			//user doesnt exist;
			return -2;
		}
		else if(count($r1)>1){
			//fatal error: more than two emails
			return -4;
		}
		else{
			if($r1[0]['user_visibility'] == 0){
				//user blocked
				return -3;
			}else{
//				echo 'her//';
				return UserManager::authentify($email,$password);
			}
		}
   }
   /**
   * NB: Dont call this method directly for it is already called by the login method
    * this is for authentication only
    * @return user_id:auth successful, -1: auth failed
    */
   public static function authentify($email,$password){
	   $handler = ServerData::getPDO();
	   $sql1 = "Select user_id from ".ServerData::$database.".users where user_email = ? AND user_password = ?";
	   $query1 = $handler->prepare($sql1);
	   $query1->execute(array($email,sha1($password)));
	   $r = $query1->fetchAll(PDO::FETCH_ASSOC);
	   if(count($r)!=1){
	   	  return -1;
	   }else{
	   	   return $r[0]['user_id'];
	   }
   }
/**
* This returns null if user is blocked, to get user information still, user getUserById
 */
	public static  function  findUserById($user_id){
		$handler = ServerData::getPDO();
		$sql1 = "Select * from ".ServerData::$database.".users where user_id = ? ";
		$query1 = $handler->prepare($sql1);
		$query1->execute(array($user_id));
		$result = $query1->fetch(PDO::FETCH_ASSOC);
		return $result['user_visibility'] == 1 ? $result : null;
	}
   public static  function  getUserById($user_id){
	   $handler = ServerData::getPDO();
	   $sql1 = "Select * from ".ServerData::$database.".users where user_id = ? ";
	   $query1 = $handler->prepare($sql1);
	   $query1->execute(array($user_id));
	   return $query1->fetch(PDO::FETCH_ASSOC);
   }
   public static function getNumberOfUsersWith($email){
	   $handler = ServerData::getPDO();
	   $sql1 = "Select user_visibility from ".ServerData::$database.".`users` where user_email = ?";
	   $query1 = $handler->prepare($sql1);
	   $query1->execute(array($email));
	   return $query1->fetchAll(PDO::FETCH_ASSOC);
   }
	/**
	 * creates  user
	 * @return -3: unexpected error; -2: user already exists, -1: auth failed, userid: auth successful
	 * */
   public static  function signUp($email,$username,$name,$password,$type){
	   $r1= self::getNumberOfUsersWith($email);
	   if(count($r1)==0){
		   $handler = ServerData::getPDO();//".ServerData::$database.".
		   $sql1 = "INSERT INTO ".ServerData::$database.".`users`(`user_name`, `user_username`,  `user_password`, `user_email`, `user_category`) VALUES (?,?,?,?,?)";
		   $query1 = $handler->prepare($sql1);
		   if($query1->execute(array($name,$username,sha1($password),$email,$type))){
			   // this is just to get the user id after creation... :)... intelligence
			   $id = self::authentify($email,$password);
			    if($id==-1){
			    	// which is wierd
				    return -3;
			    }else{
			    	return $id;
			    }
		   }else{
		   	 return -3;
		   }

	   }
	   elseif (count($r1)==1){
	   	//user already exists
		   return -2;
	   }
	   else if(count($r1)>1){
		   //fatal error: more than two emails
		   return -3;
	   }

   }
}