<?php
/**
 * Created by PhpStorm.
 * User: codename-tkc
 * Date: 15/05/2018
 * Time: 03:59
 */

class AIManager {
	public static function compare( $image1, $image2 ) {
	 $curl = curl_init('https://api.deepai.org/api/image-similarity');
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'api-key: faeb20ec-30b6-4cff-b7d4-3a72df75656c'
		));
		if (function_exists('curl_file_create')) { // php 5.5+
			$cFile1 = curl_file_create($image1);
			$cFile2 = curl_file_create($image2);
		} else { //
			$cFile1 = '@' . realpath($image1);
			$cFile2 = '@' . realpath($image2);
		}
		$post = array('image1' => $cFile1,'image2'=> $cFile2);
		curl_setopt($curl, CURLOPT_POST,1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
		$result=curl_exec ($curl);
		curl_close ($curl);
		echo $result;
	}
}
AIManager::compare('../post_thumbnails/test.jpg', '../post_thumbnails/utufb.jpg');
/*import requests
r = requests.post(
		"https://api.deepai.org/api/image-similarity",
		data={
        'image1': 'YOUR_IMAGE_URL',
        'image2': 'YOUR_IMAGE_URL',
    },
    headers={'api-key': 'YOUR_API_KEY'}
)
print(r.json())*/