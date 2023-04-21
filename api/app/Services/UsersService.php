<?php

namespace App\Services;
use Illuminate\Http\Request;
use App\Traits\Utillity;
use Carbon\Carbon;
use DB;
use Config;
use Illuminate\Support\Facades\Http;
class UsersService
{
	use Utillity;

	public function getUsersListData()
	{	
		// get token from iformbuilder using client key and secret
		$userList = $this->generateToken();
		if($userList['status'] == 200){
			$accessToken = $userList['data']['access_token'];
			//call to get value from iformbuilder
			$userDetail = self::getUserDetails($accessToken);
			$userDetail['access_token'] = $accessToken;
		}else{
			$userDetail['status'] = $userList['status'];
		}

		return $userDetail;
	}


	function base64url_encode($data) { 
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
	} 

	function base64url_decode($data) { 
		return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
	} 

	//to generate a token and get an access token
	private function generateToken(){
		$post_Header = base64_encode(json_encode(array("alg" => "HS256","typ" => "JWT")));
		$header = [
			'typ' => 'JWT',
			'alg' => 'HS256'
        ];
		// Returns the JSON representation of the header
		$header = json_encode($header);
		//encodes the $header with base64.	
		$header = base64_encode($header);
		$CLIENT_KEY = \config('iformbuilder.CLIENT_KEY');
		$baseURL = \config('iformbuilder.BASE_URL');
		$apiURL = $baseURL.'oauth/token';
		$CLIENT_SECRET = \config('iformbuilder.CLIENT_SECRET');
		$nowtime = time();
		$exptime = $nowtime + 599;
		$payload = "{
			\"iss\": \"$CLIENT_KEY\",
		   \"aud\": \"$apiURL\",
		  \"exp\": $exptime,
		  \"iat\": $nowtime}";	
		$payload = $this->base64url_encode($payload);
		
		// create signature
		$signature = $this->base64url_encode(hash_hmac('sha256',"$header.$payload",$CLIENT_SECRET, true));
		$assertionValue = "$header.$payload.$signature";
		
		$grant_type = "urn:ietf:params:oauth:grant-type:jwt-bearer";
		$grant_type = urlencode($grant_type);

		//parameter for get access token
		$postField= "grant_type=".$grant_type."&assertion=".$assertionValue;
		$responseArray = self::curlRequest($apiURL,"POST","", $postField);
		return $responseArray;

	}

	//get user details from iformbuilder using access token
	function getUserDetails($accessToken){
		
		$baseURL = \config('iformbuilder.BASE_URL');
		$PAGE_ID = \config('iformbuilder.PAGE_ID');
		$PROFILE_ID = \config('iformbuilder.PROFILE_ID');
		$url = $baseURL.'v60/profiles/'.$PROFILE_ID.'/pages/'.$PAGE_ID.'/records?fields=first_name,last_name,email,phone,designation,zip_code,date_of_birth,experience,subscribe,comments&subform_order=desc';
// UPDATE_URL="https://loadapp.iformbuilder.com/exzact/api/v60/profiles/${PROFILE_ID}/pages/${PAGE_ID}/records"
		$responseArray = self::curlRequest($url,"GET",$accessToken, []);
		return $responseArray;
	}

	function create($param){
		// get token from iformbuilder using client key and secret
		$accessDetails = $this->generateToken();
		if($accessDetails['status'] == 200){
			$accessToken = $accessDetails['data']['access_token'];
			//call to store value in iformbuilder
			$user = self::createUser($accessToken,$param);
			$user['access_token'] = $accessToken;
		}else{
			$user['status'] = $accessDetails['status'];
		}

		return $user;
	}

	//create user method
	function createUser($accessToken,$param){

			$baseURL = \config('iformbuilder.BASE_URL');
			$PAGE_ID = \config('iformbuilder.PAGE_ID');
			$PROFILE_ID = \config('iformbuilder.PROFILE_ID');
			$apiURL = $baseURL.'v60/profiles/'.$PROFILE_ID.'/pages/'.$PAGE_ID.'/records';
			// //Initiate curl and send the data through API by using generateToken function
			$collection = array();
			foreach($param as $key => $val) {
				$jsonArray['element_name'] = $key;
				$jsonArray['value'] = $val;
				array_push($collection,$jsonArray);
			}

			$jsonPostFields['fields'] = $collection;
			$jsonPostFieldsEncode = '['.json_encode($jsonPostFields).']';

			$responseArray = self::curlRequest($apiURL,"POST",$accessToken, $jsonPostFieldsEncode);
			return $responseArray;
	}

	//common curl function
	function curlRequest($apiURL,$method,$accessToken, $jsonPostFields){

		$ch1 = curl_init();
		curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch1, CURLOPT_URL, $apiURL);
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch1, CURLOPT_HEADER, false);

		if($method=="POST"){
			curl_setopt($ch1, CURLOPT_POST, TRUE);
			curl_setopt($ch1, CURLOPT_POSTFIELDS, $jsonPostFields);
		}else{
			curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");
		}

		if($accessToken!=""){
			curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
				"Content-Type: application/json",
				"cache-control: no-cache",
				"Authorization: Bearer $accessToken"
			));
		}else{
			curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
				"Content-Type: application/json",
				"cache-control: no-cache",
			));
		}

		$response = curl_exec($ch1);
		$headerSize = curl_getinfo($ch1, CURLINFO_HEADER_SIZE);
		$httpStatus = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
		curl_close($ch1);
		$responseArray['status'] = $httpStatus;
		$responseArray['data'] = json_decode($response,true);

		return $responseArray;
	}

}
