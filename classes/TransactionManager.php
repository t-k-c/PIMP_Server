<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 16/06/2018
 * Time: 16:40
 */

class TransactionManager {
	public static function pay( $item_id, $user_id,$amount,$phone,$transaction_id ) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "INSERT INTO  " . ServerData::$database . ".`transactions`(`transaction_id`, `user_id`, `item_id`, `amount`, `phone`) VALUES (?,?,?,?,?)"
		);
		$query->execute( array( $transaction_id, $user_id,$item_id,$amount,$phone ));
		return sha1($transaction_id);
	}
//https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=Example
//queridoamor
	public static function verifyTransaction($transaction){
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "SELECT * FROM  " . ServerData::$database . ".items,  " . ServerData::$database . ".transactions,  " . ServerData::$database . ".users WHERE sha1(transactions.transaction_id)=? AND items.item_id = transactions.item_id AND users.user_id = transactions.user_id" );
		 $query->execute( array( $transaction));
		 $r =  $query->fetch(PDO::FETCH_ASSOC);
		 $r+=['thumbnail'=>ItemManager::getAssociatedImages($r['item_id'])[0]];
		 return $r;
	}

/*	public static function getAllPartnerTransactions() {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "INSERT INTO  " . ServerData::$database . ".`transactions`(`transaction_id`, `user_id`, `item_id`, `amount`, `phone`) VALUES (?,?,?,?,?)"
		);
//		$query->execute( array( $transaction_id, $user_id,$item_id,$amount,$phone ));
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}*/
	public static function listAllTransactions($id){
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "SELECT *  FROM " . ServerData::$database . ".`sites`," . ServerData::$database . ".`transactions`," . ServerData::$database . ".`items`," . ServerData::$database . ".`users` WHERE sites.site_id  = ? and transactions.item_id = items.item_id and sites.site_id =  items.site_id and users.user_id = transactions.user_id"
		);
		$query->execute( array( $id ));
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	public static function modify( $id,$status) {
		$handler = ServerData::getPDO();
		$query   = $handler->prepare( "UPDATE  " . ServerData::$database . ".`transactions`  SET `status`=? WHERE `transaction_id`=?"
		);
		return $query->execute( array($status,$id))?1:0;
	}

}