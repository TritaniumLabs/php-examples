<?php
	//==========================================================================
	// Tritanium Labs USA LLC
	//
	// PURPOSE:  Post data or document metadata and digital signature to the 
	//           blockchain without uploading or storing a document.
	//
	// The /anchor endpoint is used.
	// 
	// API Parameters:
	//     auth_key:  Blockchain address and secret key.
	//     gtin or asset_type:  Type of document.
	//     lot or asset_code:   Identifier of the document.
	//     signature:  sha-256 signature of the file.
	//     signature_type:  Type of signature, defaults to 'sha-256'.
	//     data:  Optional data about document json object.
	//
	//==========================================================================

	//==========================================================================
	// PHP Example 
	// 
	// Prerequisites:
	//		1) Create a key.json file for your secret key.
	//	    2) Get the sha-256 value for the dataset.
	//==========================================================================	

//-- Sample Post 
$_POST['asset_type']="IMAGE";
$_POST['asset_code']="9982A1234";
$_POST['signature']="019141530366394442c5d6977a5763b187908b7995194b9f36e7224262686221";
$_POST['signature_type']="sha-256";

$url = 'https://traceabilityapi.com/anchor/';   //-- Microsoft Azure


//-- The Blockchain address of the location where the asset is located.
//-- Replace with your blockchain address.
$blockchain_address="31a151d30363396042c3d1977a5763b18b90cb7f95192b9f06e7824c626862c1";

//-- Read the auth_key from the key file.	
$key=json_decode(file_get_contents('key.json'),true);
$secret_key=$key['secret_key'];

$fields=array();

//-- Authentication Key to access API.
$fields['auth_key']=$blockchain_address . ":" . $secret_key;

$fields['asset_type']=$_POST['asset_type'];
$fields['asset_code']=$_POST['asset_code'];
$fields['signature']=$_POST['signature'];
$fields['signature_type']=$_POST['signature_type'];

//-- Create optional fields for the document.
$meta_data=array();
$meta_data['title']="Big Boat";
$meta_data['description']="Black and white description";
$fields['data']=urlencode(json_encode($meta_data));

//-- Store the actual file on the Traceability Blockchain Cloud
$fields['store']='N';

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

$result = curl_exec($ch);

curl_close($ch);
echo $result;



?>