<?php
//==========================================================================
// Tritanium Labs USA LLC
//
// PURPOSE:  Package an item to create a new item from a single input. 
//
// The /package endpoint is used to package a single input.
// 
// API Parameters:
//     gtin:  Global Trade Identification Number of the item created.
//     lot:   Lot Number of the Item created.
//     input_gtin:  Global Trade Identification Number of the item input.
//     input_lot:   Lot Number of the Item input.
//     data:  Optional json object containing optional data.
//
//==========================================================================

//==========================================================================
// PHP Example 
// 
// Prerequisites:
//		1) Create a key.json file for your secret key.
//==========================================================================

//-- Sample Post 
$_POST['input_gtin']="00001300872567";
$_POST['input_lot']="AM09982A";
$_POST['gtin']="00001300800000";
$_POST['lot']="AM09982A";

$_POST['product_name']="16oz Package";

$url = 'https://traceabilityapi.net/package';   //-- Microsoft Azure
$url = 'https://traceabilityapi.com/package/';   //-- Microsoft Azure - Fast API

//-- The Blockchain address of the location where the asset is located.
//-- Replace with your blockchain address.
$blockchain_address="31a151d30363396042c3d1977a5763b18b90cb7f95192b9f06e7824c626862c1";

//-- Read the auth_key from the key file.	
$key=json_decode(file_get_contents('key.json'),true);
$secret_key=$key['secret_key'];

$fields=array();

//-- Authentication Key to access API.
$fields['auth_key']=$blockchain_address . ":" . $secret_key;

//-- GTIN and LOT number of the item being packaged.
$fields['input_gtin']=$_POST['input_gtin'];
$fields['input_lot']=$_POST['input_lot'];

//-- GTIN and LOT number of the item of the completed package.
$fields['gtin']=$_POST['gtin'];
$fields['lot']=$_POST['lot'];

//-- Optional data releted to the transaction.  Each element of the array is optional.
$item_data=array();
$item_data['product_name']=$_POST['product_name'];

$fields['data']=urlencode(json_encode($item_data));

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

$result = curl_exec($ch);

//close connection
curl_close($ch);

echo $result;

?>