<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 17/05/2018
 * Time: 16:55
 */

class FileManager {
 public static function store_base_64_file($base64_file,$path,$imgname){
 	$imgsrc = base64_decode($base64_file,true);
	if($imgsrc!==false){
		if(file_exists($path.$imgname)){
			if(!unlink(realpath($path.$imgname))){
				return 0;
			}
		}
		$fp = fopen($path.$imgname, 'w');

		fwrite($fp, $imgsrc);
		if(fclose($fp)){
			return 1;
		}else{
			return 0;
		}
	}
	return 0;
 }
}